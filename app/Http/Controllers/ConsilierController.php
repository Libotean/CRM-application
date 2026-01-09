<?php

namespace App\Http\Controllers;
use App\Models\Client;
use App\Models\Lead;
use Illuminate\Http\Request;

class ConsilierController extends Controller
{
    /**
     * Afisare lista de clienti cu filtre.
     */
    public function index(Request $request)
    {
        $query = Client::where('user_id', auth()->id());

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                    ->orWhere('lastname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $clients = $query->orderBy('lastname')->get();

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
            'lastname'  => 'required|string|max:255',
            'type'      => 'required|string',
            'cnp'       => ['nullable','string','max:13','required_if:type,persoana_fizica','unique:clients,cnp'],
            'cui'       => ['nullable','string','max:12','required_if:type,persoana_juridica','unique:clients,cui'],
            'tva_payer' => 'required|boolean',
            'email'     => 'required|email|unique:clients,email',
            'phone'     => 'nullable|string|digits_between:10,15',
            'country'   => 'nullable|string|max:255',
            'county'    => 'nullable|string|max:255',
            'locality'  => 'nullable|string|max:255',
            'address'   => 'nullable|string|max:255',
        ]);

        Client::create([
            'user_id'  => auth()->id(),
            'firstname'=> $validated['firstname'],
            'lastname' => $validated['lastname'],
            'type'     => $validated['type'],
            'cnp'      => $validated['cnp'] ?? null,
            'cui'      => $validated['cui'] ?? null,
            'tva_payer'=> $validated['tva_payer'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'],
            'country'  => $validated['country'],
            'county'   => $validated['county'],
            'locality' => $validated['locality'],
            'address'  => $validated['address'],
            'status'   => 'activ',
        ]);

        return redirect()->route('consilier.clients.index')
            ->with('success', 'Clientul a fost creat cu succes');
    }


    public function show(Client $client)
    {
        // 1. Verificam ca apartine userului
        $client = Client::where('id', $client->id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // 2. Incarcam relatiile: Lead-uri (sortate) SI Vehicule
        $client->load([
            'leads' => function ($query) {
                $query->orderBy('appointment_date', 'desc');
            },
            'vehicles'
        ]);

        return view('consilier.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('consilier.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'type'      => 'required|in:PF,PJ',
            'cnp'       => ['nullable','string','max:13','required_if:type,PF','unique:clients,cnp,' . $client->id],
            'cui'       => ['nullable','string','max:12','required_if:type,PJ','unique:clients,cui,' . $client->id],
            'type'      => 'required|string',
            'tva_payer' => 'required|in:0,1',
            'email'     => 'required|email|unique:clients,email,' . $client->id,
            'phone'     => 'required|string|max:15',
            'country'   => 'required|string|max:255',
            'county'    => 'required|string|max:255',
            'locality'  => 'required|string|max:255',
            'address'   => 'required|string|max:500',
            'status'    => 'required|in:activ,inactiv',
        ]);

        $client->update([
            'firstname' => $validated['firstname'],
            'lastname'  => $validated['lastname'],
            'type'      => $validated['type'],
            'cnp'       => $validated['cnp'] ?? null,
            'cui'       => $validated['cui'] ?? null,
            'tva_payer' => $validated['tva_payer'],
            'email'     => $validated['email'],
            'phone'     => $validated['phone'],
            'country'   => $validated['country'],
            'county'    => $validated['county'],
            'locality'  => $validated['locality'],
            'address'   => $validated['address'],
            'status'    => $validated['status'],
        ]);

        return redirect()->route('consilier.clients.index')
            ->with('success', 'Client actualizat cu succes');
    }
}
