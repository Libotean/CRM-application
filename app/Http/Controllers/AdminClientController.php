<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;


class AdminClientController extends Controller
{
    /**
     * Affisare lista clienti cu optiuni de filtrare si paginare,
     *
     * Filtre disponibile:
     * - search: cautare dupa firstname, lastname, email, CUI sau CNP
     * - type: filtrare dupa tipul clientului (PF/PJ)
     * - user_id: filtrare dupa consilierul asignat
     *
     * Returneaza o lista paginata de clienti si lista consilierilor pentru filtrare.
     *
     * @param \Illuminate\Http\Request $request Request-ul care contine parametrii de filtrare.
     * @return \Illuminate\View\View Pagina cu lista de clienti si filtrele disponibile.
     */
    public function index(Request $request)
    {
        // Construim query-ul de baza, incarcam si relatia cu utilizatorul
        $querry = Client::query()->with('user');

        // Filtrare dupa text (firstname, lastname, email, CUI, CNP)
        if ($request->filled('search')) {
            $search = $request->input('search');

            $querry->where(function ($q) use ($search) {
                $q->where('firstname', 'like', "%$search%")
                  ->orWhere('lastname', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('cui', 'like', "%$search%")
                  ->orWhere('cnp', 'like', "%$search%");
            });
        }

        // Filtrare dupa tipul clientului (PF sau PJ)
        if ($request->filled('type')) {
            $querry->where('type', $request->input('type'));
        }

        // Filtrare dupa consilierul asignat (user_id)
        if ($request->filled('user_id')) {
            $querry->where('user_id', $request->input('user_id'));
        }

        // Preluam lista de clienti paginata (15 pe pagina)
        $clients = $querry->latest()->paginate(15)->withQueryString();

        // Preluam lista consilierilor pentru filtrul user_id
        $consilieri = User::where('role', 'user')->orderBy('lastname')->get();

        return view('admin.clients.index', compact('clients', 'consilieri'));
    }

    public function show(Client $client)
    {
        $client->load(['leads' => function ($query) {
            $query->orderBy('appointment_date', 'desc');
        }]);

        return view('admin.clients.show', compact('client'));
    }

    /**
     * Stergere client din baza de date.
     *
     * @param \App\Models\Client $client Clientul care urmeaza sa fie sters.
     * @return \Illuminate\Http\RedirectResponse Redirect cu mesaj de succes.
     */
    public function destroy(Client $client)
    {
        // Stergere client
        $client->delete();

        // Revenire cu mesaj
        return back()->with('success', 'Client sters cu succes.');
    }

    public function adminIndex(Request $request)
    {
        // Preluam perioada pentru filtrare (implicit ultimele 12 luni)
        $startDate = $request->input('start_date', now()->subYear()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $consilierId = $request->input('consilier_id');

        // Query de baza pentru vehicule vandute (cu client_id)
        $vehiclesQuery = Vehicle::whereNotNull('client_id')
            ->whereBetween('vehicles.updated_at', [$startDate, $endDate]);

        // Filtrare dupa consilier daca este selectat
        if ($consilierId) {
            $vehiclesQuery->whereHas('client', function ($query) use ($consilierId) {
                $query->where('user_id', $consilierId);
            });
        }

        // 1. STATISTICI GENERALE
        $totalVehiclesVandute = (clone $vehiclesQuery)->count();
        $totalValoareVanzari = (clone $vehiclesQuery)->sum('price_eur');
        $pretMediuVanzare = (clone $vehiclesQuery)->avg('price_eur');
        $clientiUnici = (clone $vehiclesQuery)->distinct('client_id')->count('client_id');
        
        // Discount mediu (diferenta intre old_price si price)
        $vehiculesWithDiscount = (clone $vehiclesQuery)->whereNotNull('old_price_eur')->get();
        $discountMediu = $vehiculesWithDiscount->isNotEmpty() 
            ? $vehiculesWithDiscount->avg(function($v) { return $v->old_price_eur - $v->price_eur; })
            : 0;

        // 2. VANZARI PE CONSILIER
        $vanzariPeConsilier = Vehicle::whereNotNull('client_id')
            ->whereBetween('vehicles.updated_at', [$startDate, $endDate])
            ->with(['client.user'])
            ->get()
            ->filter(function($vehicle) {
                return $vehicle->client && $vehicle->client->user;
            })
            ->groupBy('client.user.id')
            ->map(function ($vehicles) {
                $user = $vehicles->first()->client->user;
                return [
                    'consilier' => $user->firstname . ' ' . $user->lastname,
                    'nr_vanzari' => $vehicles->count(),
                    'valoare_totala' => $vehicles->sum('price_eur'),
                    'valoare_medie' => round($vehicles->avg('price_eur'), 2),
                ];
            })
            ->sortByDesc('valoare_totala')
            ->values();

        // 3. VANZARI PE TIP CLIENT (PF/PJ)
        $vanzariPeTipClient = Vehicle::whereNotNull('client_id')
            ->whereBetween('vehicles.updated_at', [$startDate, $endDate])
            ->with('client')
            ->get()
            ->filter(function($vehicle) {
                return $vehicle->client;
            })
            ->groupBy('client.type')
            ->map(function ($vehicles, $type) {
                return [
                    'tip' => $type == 'PF' ? 'Persoana Fizica' : 'Persoana Juridica',
                    'nr_vanzari' => $vehicles->count(),
                    'valoare_totala' => $vehicles->sum('price_eur'),
                    'procent' => 0,
                ];
            });

        // Calculam procentele pentru tipuri clienti
        $totalVanzari = $vanzariPeTipClient->sum('nr_vanzari');
        if ($totalVanzari > 0) {
            $vanzariPeTipClient = $vanzariPeTipClient->map(function ($item) use ($totalVanzari) {
                $item['procent'] = round(($item['nr_vanzari'] / $totalVanzari) * 100, 2);
                return $item;
            });
        }

        // 4. VANZARI PE MARCA (Top 10)
        $vanzariPeMarca = Vehicle::whereNotNull('vehicles.client_id')
            ->whereBetween('vehicles.updated_at', [$startDate, $endDate])
            ->join('vehicle_makes', 'vehicles.vehicle_make_id', '=', 'vehicle_makes.id')
            ->select('vehicle_makes.name as marca', 
                     DB::raw('count(*) as nr_vanzari'), 
                     DB::raw('sum(vehicles.price_eur) as valoare_totala'),
                     DB::raw('avg(vehicles.price_eur) as pret_mediu'))
            ->groupBy('vehicle_makes.name')
            ->orderByDesc('nr_vanzari')
            ->limit(10)
            ->get();

        // 5. VANZARI PE MODEL (Top 15)
        $vanzariPeModel = Vehicle::whereNotNull('vehicles.client_id')
            ->whereBetween('vehicles.updated_at', [$startDate, $endDate])
            ->join('vehicle_models', 'vehicles.vehicle_model_id', '=', 'vehicle_models.id')
            ->join('vehicle_makes', 'vehicles.vehicle_make_id', '=', 'vehicle_makes.id')
            ->select(
                DB::raw('CONCAT(vehicle_makes.name, " ", vehicle_models.name) as model_complet'),
                DB::raw('count(*) as nr_vanzari'), 
                DB::raw('sum(vehicles.price_eur) as valoare_totala'),
                DB::raw('avg(vehicles.price_eur) as pret_mediu')
            )
            ->groupBy('model_complet')
            ->orderByDesc('nr_vanzari')
            ->limit(15)
            ->get();

        // 6. VANZARI PE CATEGORIE
        $vanzariPeCategorie = Vehicle::whereNotNull('vehicles.client_id')
            ->whereBetween('vehicles.updated_at', [$startDate, $endDate])
            ->join('vehicle_models', 'vehicles.vehicle_model_id', '=', 'vehicle_models.id')
            ->join('vehicle_categories', 'vehicle_models.vehicle_category_id', '=', 'vehicle_categories.id')
            ->select('vehicle_categories.name as categorie', 
                     DB::raw('count(*) as nr_vanzari'), 
                     DB::raw('sum(vehicles.price_eur) as valoare_totala'))
            ->groupBy('vehicle_categories.name')
            ->orderByDesc('nr_vanzari')
            ->get();

        // 7. VANZARI PE TIP COMBUSTIBIL
        $vanzariPeCombustibil = Vehicle::whereNotNull('client_id')
            ->whereBetween('vehicles.updated_at', [$startDate, $endDate])
            ->select('fuel_type', 
                     DB::raw('count(*) as nr_vanzari'), 
                     DB::raw('sum(price_eur) as valoare_totala'),
                     DB::raw('avg(price_eur) as pret_mediu'))
            ->groupBy('fuel_type')
            ->orderByDesc('nr_vanzari')
            ->get();

        // 8. VANZARI PE TIP TRANSMISIE
        $vanzariPeTransmisie = Vehicle::whereNotNull('client_id')
            ->whereBetween('vehicles.updated_at', [$startDate, $endDate])
            ->select('transmission', 
                     DB::raw('count(*) as nr_vanzari'), 
                     DB::raw('sum(price_eur) as valoare_totala'),
                     DB::raw('avg(price_eur) as pret_mediu'))
            ->groupBy('transmission')
            ->orderByDesc('nr_vanzari')
            ->get();

        // 9. VANZARI PE TRACTIUNE
        $vanzariPeTractiune = Vehicle::whereNotNull('client_id')
            ->whereBetween('vehicles.updated_at', [$startDate, $endDate])
            ->select('traction', 
                     DB::raw('count(*) as nr_vanzari'), 
                     DB::raw('sum(price_eur) as valoare_totala'))
            ->groupBy('traction')
            ->orderByDesc('nr_vanzari')
            ->get();

        // 10. VANZARI PE AN FABRICATIE
        $vanzariPeAnFabricatie = Vehicle::whereNotNull('client_id')
            ->whereBetween('vehicles.updated_at', [$startDate, $endDate])
            ->select('manufacturing_year', 
                     DB::raw('count(*) as nr_vanzari'), 
                     DB::raw('avg(price_eur) as pret_mediu'),
                     DB::raw('avg(mileage) as km_mediu'))
            ->groupBy('manufacturing_year')
            ->orderByDesc('manufacturing_year')
            ->get();

        // 11. ANALIZA GEOGRAFICA - Vanzari pe Judet
        $vanzariPeJudet = Vehicle::whereNotNull('vehicles.client_id')
            ->whereBetween('vehicles.updated_at', [$startDate, $endDate])
            ->join('clients', 'vehicles.client_id', '=', 'clients.id')
            ->select('clients.county as judet', 
                     DB::raw('count(*) as nr_vanzari'), 
                     DB::raw('sum(vehicles.price_eur) as valoare_totala'))
            ->groupBy('clients.county')
            ->orderByDesc('nr_vanzari')
            ->limit(15)
            ->get();

        // 12. TOP 10 CLIENTI (cei care au cumparat cele mai multe vehicule)
        $topClienti = Client::whereHas('vehicles', function($query) use ($startDate, $endDate) {
                $query->whereBetween('vehicles.updated_at', [$startDate, $endDate]);
            })
            ->withCount(['vehicles' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('vehicles.updated_at', [$startDate, $endDate]);
            }])
            ->with(['vehicles' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('vehicles.updated_at', [$startDate, $endDate]);
            }])
            ->orderByDesc('vehicles_count')
            ->take(10)
            ->get()
            ->map(function ($client) {
                return [
                    'nume' => $client->firstname . ' ' . $client->lastname,
                    'tip' => $client->type,
                    'judet' => $client->county,
                    'nr_achizitii' => $client->vehicles_count,
                    'valoare_totala' => $client->vehicles->sum('price_eur'),
                ];
            });

        // 13. EVOLUTIE VANZARI PE LUNI
        $evolutieVanzari = Vehicle::whereNotNull('client_id')
            ->whereBetween('vehicles.updated_at', [$startDate, $endDate])
            ->select(
                DB::raw("strftime('%Y-%m', vehicles.updated_at) as luna"),
                DB::raw('count(*) as nr_vanzari'),
                DB::raw('sum(price_eur) as valoare_totala')
            )
            ->groupBy('luna')
            ->orderBy('luna')
            ->get();

        // 14. ANALIZA STOC CURENT
        $stocCurent = Vehicle::whereNull('client_id')->count();
        $valoareStoc = Vehicle::whereNull('client_id')->sum('price_eur');
        $stocPeMarca = Vehicle::whereNull('vehicles.client_id')
            ->join('vehicle_makes', 'vehicles.vehicle_make_id', '=', 'vehicle_makes.id')
            ->select('vehicle_makes.name as marca', DB::raw('count(*) as nr_vehicule'))
            ->groupBy('vehicle_makes.name')
            ->orderByDesc('nr_vehicule')
            ->get();

        // 15. VEHICULE CU VAT DEDUCTIBIL
        $vehiculeVATDeductibil = Vehicle::whereNotNull('client_id')
            ->whereBetween('vehicles.updated_at', [$startDate, $endDate])
            ->where('vat_deductible', 1)
            ->count();

        // Lista consilieri pentru filtru
        $consilieri = User::where('role', 'user')->orderBy('lastname')->get();

        // Returnam view-ul cu toate datele
        return view('admin.clients.rapoarte', compact(
            'totalVehiclesVandute',
            'totalValoareVanzari',
            'pretMediuVanzare',
            'clientiUnici',
            'discountMediu',
            'vanzariPeConsilier',
            'vanzariPeTipClient',
            'vanzariPeMarca',
            'vanzariPeModel',
            'vanzariPeCategorie',
            'vanzariPeCombustibil',
            'vanzariPeTransmisie',
            'vanzariPeTractiune',
            'vanzariPeAnFabricatie',
            'vanzariPeJudet',
            'topClienti',
            'evolutieVanzari',
            'stocCurent',
            'valoareStoc',
            'stocPeMarca',
            'vehiculeVATDeductibil',
            'consilieri',
            'startDate',
            'endDate',
            'consilierId'
        ));
    }
}
