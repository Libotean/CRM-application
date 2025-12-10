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
    /** 
     * Functie pentru stocarea lead-urilor
     * 
     * Valideaza datele primite prin request, combina data si ora intr-un singur camp
     * si creaaza un lead asociat clientului si utilizatorului autentificat.
     * 
     * @param \Illuminate\Http\Request $request Request-ul care contine datele lead-ului
     * @param \App\Models\Client $client Instanta clientului pentru care se creeaza lead-ul
     * @return Redirectare inapoi cu un mesaj de succes
    */
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


        return back()->with('success', 'Lead creat cu succes.');
    }

    /**
    * Schimbare stats lead existent.
    * Verifica daca lead-ul apartine utilizatorului autentificat.
    * Daca verificarea trece, inverseaza valoarea campului 'is_completed'.
    * @param \App\Models\Lead $lead Instanta lead-ului care trebuie modificat
    * @return \Illuminate\Http\RedirectResponse Redirectare inapoi cu mesaj de succes
    */
    public function toggleStatus(Lead $lead)
    {
        if($lead->user_id != auth()->id()){
            abort(403);
        }

        $lead->update([
            'is_completed' => !$lead->is_completed
        ]);

        return back()->with('success', 'Status actualizat cu succes');
    }

    /**
    * Trimite un email catre client si inregistreaza actiunea in istoricul lead-urilor.
    * Valideaza subiectul si mesajul, pregateste datele,
    * trimite emailul efectiv folosind Mailable-ul si creeaza automat
    * un lead marcat ca 'finalizat' ce contine detaliile emailului trimis.
    * @param \Illuminate\Http\Request $request Datele din formularul de email (subiect, mesaj)
    * @param \App\Models\Client $client Clientul caruia i se trimite emailul
    * @return \Illuminate\Http\RedirectResponse Redirectare cu mesaj de succes sau eroare in caz de esec
    */
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

        return back()->with('success', 'Email trimis');
    }
}
