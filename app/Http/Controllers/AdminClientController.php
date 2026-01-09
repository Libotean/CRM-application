<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;

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
}
