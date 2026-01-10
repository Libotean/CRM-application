<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Client; // <--- Asigura-te ca ai linia asta
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        // 1. Pregatim lista de masini
        $query = Vehicle::with(['make', 'model', 'client']);

        // 2. Logica de Cautare
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('vin', 'LIKE', "%{$search}%")
                    ->orWhereHas('make', function($subQuery) use ($search) {
                        $subQuery->where('name', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('model', function($subQuery) use ($search) {
                        $subQuery->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        $vehicles = $query->latest()->get();

        // =========================================================
        // AICI ESTE CHEIA PROBLEMEI TALE!
        // Fara liniile de mai jos, butonul "Inapoi" nu stie cine e clientul.
        // =========================================================
        $client = null;
        if ($request->has('client_id')) {
            $client = Client::find($request->client_id);
        }

        // Trimitem variabila $client catre pagina (View)
        return view('vehicles.index', compact('vehicles', 'client'));
    }

    // Pagina de confirmare vanzare
    public function sell(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        // 1. Luam TOTI clientii din baza de date pentru lista (sortati alfabetic)
        $clients = Client::orderBy('lastname', 'asc')->get();

        // 2. (Optional) Daca  un ID, il preselectam, dar nu e obligatoriu
        $selectedClient = null;
        if ($request->has('client_id')) {
            $selectedClient = Client::find($request->client_id);
        }

        return view('vehicles.sell', compact('vehicle', 'clients', 'selectedClient'));
    }

    // Procesarea vanzarii
    public function processSale(Request $request, $id)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
        ]);

        $vehicle = Vehicle::findOrFail($id);

        $vehicle->update([
            'client_id' => $request->client_id,
        ]);

        // Daca vanzarea a reusit, ne intoarcem la clientul respectiv
        return redirect()->route('consilier.clients.show', $request->client_id)
            ->with('success', 'Vehiculul a fost asignat cu succes!');
    }
}
