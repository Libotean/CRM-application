<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\TestDrive;
use Illuminate\Http\Request;

class TestDriveController extends Controller
{
    public function store(Request $request, Client $client)
    {
        // 1. Validare date
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required',
            'start_km' => 'required|numeric|min:0',
        ]);

        // 2. Salvare în baza de date
        TestDrive::create([
            'client_id' => $client->id,
            'vehicle_id' => $validated['vehicle_id'],
            'scheduled_date' => $validated['scheduled_date'],
            'scheduled_time' => $validated['scheduled_time'],
            'start_km' => $validated['start_km'],
            'is_completed' => false,
        ]);

        // 3. Mesaj succes și refresh
        return back()->with('success', 'Test Drive programat cu succes!');
    }
}
