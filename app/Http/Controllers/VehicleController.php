<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{

    public function index(Request $request)
    {
        // 1. Începem interogarea (fără să aducem datele încă)
        $query = Vehicle::with(['make', 'model', 'client']);

        // 2. Verificăm dacă utilizatorul a scris ceva în bara de căutare
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                // Caută în coloana VIN
                $q->where('vin', 'LIKE', "%{$search}%")
                    // SAU caută în tabelul Mărci (relația 'make')
                    ->orWhereHas('make', function($subQuery) use ($search) {
                        $subQuery->where('name', 'LIKE', "%{$search}%");
                    })
                    // SAU caută în tabelul Modele (relația 'model')
                    ->orWhereHas('model', function($subQuery) use ($search) {
                        $subQuery->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        // 3. Executăm interogarea și luăm rezultatele
        $vehicles = $query->latest()->get();

        return view('vehicles.index', compact('vehicles'));
    }

    // Formularul de asignare
    public function sell($id)
    {
        $vehicle = Vehicle::findOrFail($id);

        //  daca mașina e deja vanduta, nu lasam consilierul sa intre pe pagina
        if ($vehicle->client_id) {
            return redirect()->route('vehicles.index')
                ->with('error', 'Această mașină a fost deja vândută!');
        }

        //ordoneaza
        $clients = Client::orderBy('lastname', 'asc')->get();

        return view('vehicles.sell', compact('vehicle', 'clients'));
    }

    public function processSale(Request $request, $id)
    {

        $request->validate([
            'client_id' => 'required|exists:clients,id',
        ]);

        $vehicle = Vehicle::findOrFail($id);


        $vehicle->update([
            'client_id' => $request->client_id, // Aici se face legătura (Cheia Străină)
            // 'sold_at' => now(), // Dacă aveai coloana asta, aici o actualizam
        ]);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehiculul a fost asignat clientului cu succes!');
    }
}
