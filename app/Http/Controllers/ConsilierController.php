<?php

namespace App\Http\Controllers;
use App\Models\Client;
use App\Models\Lead;
use Illuminate\Http\Request;


class ConsilierController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('lastname')->get();
        return view('consilier.index', compact('clients'));

    }

    public function create()
    {
        return view('consilier.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'type'=> 'required|string',
            'cnp' => [
                'nullable',
                'string',
                'max:13',
                'required_if:type,persoana_fizica', 
                'unique:clients,cnp',
            ],

            'cui'=> [
                'nullable',
                'string',
                'max:12',
                'required_if:type,persoana_juridica',
                'unique:clients,cui',
            ],
            'tva_payer'=> 'required|boolean',
            'email'=> 'required|email|unique:clients,email',
            'phone'=> 'nullable|string|digits_between:10,15',
            'country'=> 'nullable|string|max:255',
            'county'=> 'nullable|string|max:255',
            'locality'=> 'nullable|string|max:255',
            'address'=> 'nullable|string|max:255',
        ]);

        Client::create([
           'user_id' => auth()->id(),
           'firstname'=> $validated['firstname'],
           'lastname'=> $validated['lastname'],
           'type'=> $validated['type'],
           'cnp'=> $validated['cnp'] ?? null,
           'cui'=> $validated['cui'] ?? null,
           'tva_payer'=> $validated['tva_payer'],
           'email'=> $validated['email'],
           'phone'=> $validated['phone'],
           'country'=> $validated['country'],
           'county'=> $validated['county'],
           'locality'=> $validated['locality'],
           'address'=> $validated['address'],
           'status'=> 'activ',
        ]);

        return redirect()->route('consilier.clients.index')->with('success', 'Clinetul a fost creat cu success');
    }

    public function show(Client $client)
    {
        // verificam daca clientul apartine consilierului logat -- 403 = forbidden
        if ($client->user_id != auth()->id()){
            abort(403);
        }

        $client->load(['leads' => function ($query) {
            $query->orderBy('appointment_date', 'desc');
        }]);

        return view('consilier.show', compact('client'));
    }

    
}


