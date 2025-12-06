<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Lead;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(Request $request, Client $client)
    {
        $validated = $request->validate([
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|time',
            'method' => 'required|string',
            'objective' => 'required|string',
            'notes' => 'required|string',
        ]);

        $fullDate = $validated['appointment_date'] . ' ' . $validated['appointment_time'];

        Lead::create([
            'client_id' => $client->id,
            'user_id' => auth()->id(),
            'appointment_date' => $fullDate,
            'method' => $validated['method'],
            'objective' => $validated['objective'],
            'notes' => $validated['notes'],
            'is_completed' => false,
        ]);


        return back()->with('succes');
    }
}
