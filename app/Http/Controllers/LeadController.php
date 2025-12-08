<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Lead;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactClientMail;

class LeadController extends Controller
{
    public function store(Request $request, Client $client)
    {
        $validated = $request->validate([
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'method' => 'required|string',
            'objective' => 'required|string',
            'notes' => 'nullable|string',
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

    public function toggleStatus(Lead $lead)
    {
        if($lead->user_id != auth()->id()){
            abort(403);
        }

        $lead->update([
            'is_completed' => !$lead->is_completed
        ]);

        return back()->with('success');
    }

    public function sendEmail(Request $request, Client $client)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $emailData = [
            'subiect' => $validated['subject'],
            'mesaj' => $validated['message'],
            'nume_client' => $client->firstname . ' ' . $client->lastname,
            'nume_consilier' => auth()->user()->firstname . ' ' . auth()->user()->lastname,
            'email_consilier' => auth()->user()->email,
        ];

        try {
            Mail::to($client->email)->send(new ContactClientMail($emailData));
        } catch(\Exception $e) {
            return back()->with('error', 'Eroare la trimitere' . $e->getMessage());
        }

        Lead::create([
            'client_id' => $client->id,
            'user_id' => auth()->id(),
            'appointment_date' => now(),
            'method' => 'Email',
            'objective' => 'Discutie Generala',
            'notes' => "Email Trimis: " . $validated['subject'] . "\n\n" . $validated['message'],
            'is_completed' => true,
        ]);

        return back()->with('succes', 'Email trimis');
    }
}
