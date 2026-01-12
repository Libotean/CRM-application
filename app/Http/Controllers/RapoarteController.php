<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class RapoarteController extends Controller
{
    public function index(Request $request)
    {
        // 1. FILTRE SI SETARI INITIALE
        $startDate = $request->input('start_date', Carbon::now()->subMonths(3)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $consilierId = $request->input('consilier_id');
        $perioadaLead = $request->input('perioada_lead', 'luna_curenta');

        $consilieri = DB::table('users')
            ->where('role', 'consilier')
            ->select('id', 'firstname', 'lastname')
            ->get();

        // 2. STATISTICI GENERALE (Vanzari = Vehicule cu client_id)
        $vanzariQuery = DB::table('vehicles')
            ->join('clients', 'vehicles.client_id', '=', 'clients.id')
            ->whereNotNull('vehicles.client_id')
            ->whereBetween('vehicles.updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        
        if ($consilierId) {
            $vanzariQuery->where('clients.user_id', $consilierId);
        }

        $totalVehiclesVandute = (clone $vanzariQuery)->count();
        $totalValoareVanzari = (clone $vanzariQuery)->sum('vehicles.price_eur');
        $pretMediuVanzare = $totalVehiclesVandute > 0 ? $totalValoareVanzari / $totalVehiclesVandute : 0;
        $clientiUnici = (clone $vanzariQuery)->distinct('vehicles.client_id')->count('vehicles.client_id');

        // 3. PERFORMANTĂ PE CONSILIER
        $vanzariPeConsilier = DB::table('vehicles')
            ->join('clients', 'vehicles.client_id', '=', 'clients.id')
            ->join('users', 'clients.user_id', '=', 'users.id')
            ->whereNotNull('vehicles.client_id')
            ->whereBetween('vehicles.updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->when($consilierId, fn($q) => $q->where('clients.user_id', $consilierId))
            ->select(
                DB::raw("users.firstname || ' ' || users.lastname as consilier"),
                DB::raw('COUNT(vehicles.id) as nr_vanzari'),
                DB::raw('SUM(vehicles.price_eur) as valoare_totala'),
                DB::raw('AVG(vehicles.price_eur) as valoare_medie')
            )
            ->groupBy('users.id', 'users.firstname', 'users.lastname')
            ->orderBy('valoare_totala', 'DESC')
            ->get()->map(fn($item) => (array)$item);

        // 4. TOP MARCI SI MODELE (FR-20 context)
        $vanzariPeMarca = DB::table('vehicles')
            ->join('vehicle_makes', 'vehicles.vehicle_make_id', '=', 'vehicle_makes.id')
            ->join('clients', 'vehicles.client_id', '=', 'clients.id')
            ->whereNotNull('vehicles.client_id')
            ->whereBetween('vehicles.updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->when($consilierId, fn($q) => $q->where('clients.user_id', $consilierId))
            ->select('vehicle_makes.name as marca', DB::raw('COUNT(*) as nr_vanzari'), DB::raw('SUM(vehicles.price_eur) as valoare_totala'))
            ->groupBy('vehicle_makes.id', 'vehicle_makes.name')
            ->orderBy('nr_vanzari', 'DESC')->limit(10)->get();

        $vanzariPeModel = DB::table('vehicles')
            ->join('vehicle_makes', 'vehicles.vehicle_make_id', '=', 'vehicle_makes.id')
            ->join('vehicle_models', 'vehicles.vehicle_model_id', '=', 'vehicle_models.id')
            ->join('clients', 'vehicles.client_id', '=', 'clients.id')
            ->whereNotNull('vehicles.client_id')
            ->whereBetween('vehicles.updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->when($consilierId, fn($q) => $q->where('clients.user_id', $consilierId))
            ->select(
                DB::raw("vehicle_makes.name || ' ' || vehicle_models.name as model_complet"),
                DB::raw('COUNT(*) as nr_vanzari'),
                DB::raw('SUM(vehicles.price_eur) as valoare_totala'),
                DB::raw('AVG(vehicles.price_eur) as pret_mediu')
            )
            ->groupBy('vehicle_makes.name', 'vehicle_models.name')
            ->orderBy('nr_vanzari', 'DESC')->limit(15)->get();

        // 5. FR-18 & FR-19: RAPORT VECHIME ÎN STOC
        $vehiculeVechime = DB::table('vehicles')
            ->join('vehicle_makes', 'vehicles.vehicle_make_id', '=', 'vehicle_makes.id')
            ->join('vehicle_models', 'vehicles.vehicle_model_id', '=', 'vehicle_models.id')
            ->whereNull('vehicles.client_id')
            ->select(
                'vehicles.id', 'vehicle_makes.name as marca', 'vehicle_models.name as model',
                'vehicles.stock_entry_date as data_intrare',
                DB::raw("CAST(julianday('now') - julianday(vehicles.stock_entry_date) AS INTEGER) as vechime_zile"),
                'vehicles.price_eur as pret',
                DB::raw("'disponibil' as status")
            )
            ->orderBy('vechime_zile', 'DESC')->get();

        // 6. FR-20: RAPORT INTERES CLIENȚI PER VEHICUL
        $interesVehicule = collect();
        if (Schema::hasTable('interactions') || Schema::hasTable('test_drives')) {
            $interesVehicule = DB::table('vehicles')
                ->join('vehicle_makes', 'vehicles.vehicle_make_id', '=', 'vehicle_makes.id')
                ->join('vehicle_models', 'vehicles.vehicle_model_id', '=', 'vehicle_models.id')
                ->leftJoin('interactions', 'vehicles.id', '=', 'interactions.vehicle_id')
                ->leftJoin('test_drives', 'vehicles.id', '=', 'test_drives.vehicle_id')
                ->whereNull('vehicles.client_id')
                ->select(
                    'vehicles.id', 'vehicle_makes.name as marca', 'vehicle_models.name as model',
                    DB::raw("CAST(julianday('now') - julianday(vehicles.stock_entry_date) AS INTEGER) as vechime_zile"),
                    DB::raw('COUNT(DISTINCT interactions.client_id) as nr_clienti'),
                    DB::raw('COUNT(DISTINCT test_drives.id) as nr_test_drives')
                )
                ->groupBy('vehicles.id', 'vehicle_makes.name', 'vehicle_models.name', 'vehicles.stock_entry_date')
                ->orderBy('nr_clienti', 'DESC')->get();
        }

        // 7. FR-21: RAPORT ACTIVITATE CONSILIERI
        $activitateConsilieri = DB::table('users')
            ->where('role', 'consilier')
            ->when($consilierId, fn($q) => $q->where('id', $consilierId))
            ->get()->map(function($user) use ($startDate, $endDate) {
                return [
                    'nume_consilier' => $user->firstname . ' ' . $user->lastname,
                    'clienti_noi' => DB::table('clients')->where('user_id', $user->id)->whereBetween('created_at', [$startDate, $endDate])->count(),
                    'interactiuni' => Schema::hasTable('interactions') ? DB::table('interactions')->where('user_id', $user->id)->whereBetween('created_at', [$startDate, $endDate])->count() : 0,
                    'test_drives_programate' => Schema::hasTable('test_drives') ? DB::table('test_drives')->where('user_id', $user->id)->whereBetween('scheduled_date', [$startDate, $endDate])->count() : 0,
                    'test_drives_efectuate' => Schema::hasTable('test_drives') ? DB::table('test_drives')->where('user_id', $user->id)->where('status', 'completed')->whereBetween('scheduled_date', [$startDate, $endDate])->count() : 0,
                    'clienti_activi' => DB::table('clients')->where('user_id', $user->id)->where('status', '!=', 'pierdut')->count(),
                ];
            });

        // 8. FR-22: LEAD-URI ȘI CONVERSII
        $dataStartLead = match($perioadaLead) {
            '3_luni' => Carbon::now()->subMonths(3)->format('Y-m-d'),
            '6_luni' => Carbon::now()->subMonths(6)->format('Y-m-d'),
            'an' => Carbon::now()->subYear()->format('Y-m-d'),
            default => Carbon::now()->startOfMonth()->format('Y-m-d')
        };

        $distributieLead = DB::table('clients')
            ->whereBetween('created_at', [$dataStartLead . ' 00:00:00', Carbon::now()])
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')->get()
            ->map(function($item) {
                $totalGlobal = DB::table('clients')->count();
                $item->procent = $totalGlobal > 0 ? round(($item->total / $totalGlobal) * 100, 1) : 0;
                return $item;
            });

        $totalLeaduri = DB::table('clients')->whereBetween('created_at', [$dataStartLead . ' 00:00:00', Carbon::now()])->count();
        $leaduriCastigate = DB::table('clients')->where('status', 'castigat')->whereBetween('created_at', [$dataStartLead . ' 00:00:00', Carbon::now()])->count();
        $rataConversie = $totalLeaduri > 0 ? round(($leaduriCastigate / $totalLeaduri) * 100, 1) : 0;

        // Comparatie vnzari curenta vs anterioara
        $lunaCurenta = Carbon::now()->format('Y-m');
        $lunaAnterioara = Carbon::now()->subMonth()->format('Y-m');
        $vanzariLunaCurenta = DB::table('vehicles')->whereNotNull('client_id')->whereRaw("strftime('%Y-%m', updated_at) = ?", [$lunaCurenta])->count();
        $vanzariLunaAnterioara = DB::table('vehicles')->whereNotNull('client_id')->whereRaw("strftime('%Y-%m', updated_at) = ?", [$lunaAnterioara])->count();
        $diferentaVanzari = $vanzariLunaCurenta - $vanzariLunaAnterioara;
        $procentDiferenta = $vanzariLunaAnterioara > 0 ? round(($diferentaVanzari / $vanzariLunaAnterioara) * 100, 1) : 0;

        return view('admin.users.rapoarte', compact(
            'startDate', 'endDate', 'consilierId', 'consilieri', 'totalVehiclesVandute',
            'totalValoareVanzari', 'pretMediuVanzare', 'clientiUnici', 'vanzariPeConsilier',
            'vanzariPeMarca', 'vanzariPeModel', 'vehiculeVechime', 'interesVehicule',
            'activitateConsilieri', 'perioadaLead', 'distributieLead', 'totalLeaduri',
            'leaduriCastigate', 'rataConversie', 'vanzariLunaCurenta', 'vanzariLunaAnterioara',
            'diferentaVanzari', 'procentDiferenta'
        ) + [
            'evolutieVanzari' => (clone $vanzariQuery)
                ->select(DB::raw("strftime('%Y-%m', vehicles.updated_at) as luna"), DB::raw('COUNT(*) as nr_vanzari'), DB::raw('SUM(vehicles.price_eur) as valoare_totala'))
                ->groupBy('luna')->orderBy('luna', 'ASC')->get(),
            'topClienti' => (clone $vanzariQuery)
                ->select('clients.firstname', 'clients.lastname', DB::raw('COUNT(*) as nr_achizitii'), DB::raw('SUM(vehicles.price_eur) as valoare_totala'))
                ->groupBy('clients.id')->orderBy('valoare_totala', 'DESC')->limit(10)->get(),
            'vanzariPeTipClient' => (clone $vanzariQuery)
                ->select('clients.type as tip', DB::raw('COUNT(*) as nr_vanzari'))
                ->groupBy('clients.type')->get()
        ]);
    }
}