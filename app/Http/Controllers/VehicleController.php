<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{

    public function index()
    {

        $vehicles = Vehicle::with(['make', 'model', 'client'])->latest()->get();

        return view('vehicles.index', compact('vehicles'));
    }

    // Formularul de asignare
    public function sell($id)
    {
        $vehicle = Vehicle::findOrFail($id);

        //  daca mașina e deja vanduta, nu lasam consilierul sa intre pe pagină
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
            'condition' => 'rulat', // O trecem automat ca rulată
            // 'sold_at' => now(), // Dacă aveai coloana asta, aici o actualizam
        ]);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehiculul a fost asignat clientului cu succes!');
    }
}
