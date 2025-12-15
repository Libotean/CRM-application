<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
    * Afiseaza o lista cu toti utilizatorii.
    * Include logica de filtrare dupa nume, email, rol si status.
    * Actualizeaza automat statusul conturilor expirate inainte de afisare.
    * @param \Illuminate\Http\Request $request Request-ul ce contine eventualele filtre
    * @return \Illuminate\View\View Returneaza view-ul cu lista utilizatorilor si filtrele active
    */
    public function index(Request $request) 
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('firstname', 'like', "%$search%")
                ->orWhere('lastname', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->filled('status')) {
            $isActive = $request->input('status') == 'active';
            $query->where('is_active', $isActive);
        }

        User::updateExpiredStatus();

        $users = $query->orderBy('lastname')->get();

        return view('admin.users.index', [
            'users' => $users,
            'filters' => $request->all() 
        ]);
    }
    
    /**
    * Afiseaza formularul de creare a unui utilizator nou.
    * @return \Illuminate\View\View
    */
    public function create()
    {
        return view('admin.users.create');
    }
    
    /**
    * Salveaza un utilizator nou in baza de date.
    * Valideaza datele, hash-uieste parola si seteaza datele de valabilitate ale contului.
    * @param \Illuminate\Http\Request $request Datele din formularul de adaugare
    * @return \Illuminate\Http\RedirectResponse Redirect catre lista de utilizatori
    */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'country' => 'required|string|max:255',
            'county' => 'required|string|max:255',
            'locality' => 'required|string|max:255',
            'role' => ['required', Rule::in(['admin', 'user'])],
            'date_start' => 'nullable|date',
            'date_end' => 'nullable|date|after_or_equal:date_start',
        ]);

        User::create([
           'firstname' => $validated['firstname'],
           'lastname' => $validated['lastname'],
           'country' => $validated['country'],
           'county' => $validated['county'],
           'locality' => $validated['locality'],
           'email' => $validated['email'],
           'password' => Hash::make($validated['password']),
           'role' => $validated['role'],
           'date_start' => $validated['date_start'],
           'date_end' => $validated['date_end'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }
    
    /**
    * Afiseaza detaliile complete ale unui utilizator.
    * Incarca lista de clienti asociati acestui utilizator.
    * @param \App\Models\User $user Utilizatorul selectat
    * @return \Illuminate\View\View
    */
    public function show(User $user)
    {
        $user->load('clients');
        return view('admin.users.show', compact('user'));
    }

    /**
    * Afiseaza formularul de editare pentru un utilizator existent.
    * @param \App\Models\User $user Utilizatorul selectat
    * @return \Illuminate\View\View
    */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
    * Actualizeaza datele unui utilizator in baza de date.
    * Permite schimbarea parolei doar daca este completata.
    * Verifica automat daca contul devine inactiv pe baza datei de final.
    * @param \Illuminate\Http\Request $request Noile date din formular
    * @param \App\Models\User $user Utilizatorul care se actualizeaza
    * @return \Illuminate\Http\RedirectResponse Redirect inapoi la lista
    */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'country' => 'required|string|max:255',
            'county' => 'required|string|max:255',
            'locality' => 'required|string|max:255',
            'role' => ['required', Rule::in(['admin', 'user'])],
            'date_start' => 'nullable|date',
            'date_end' => 'nullable|date|after_or_equal:date_start',
        ]);

        $dataToUpdate =[
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'country' => $validated['country'],
            'county' => $validated['county'],
            'locality' => $validated['locality'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'date_start' => $validated['date_start'],
            'date_end' => $validated['date_end'],
        ];

        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($validated['password']);
        }

  
        if(isset($validated['date_end'])) {
            $endDateTime = \Carbon\Carbon::parse($validated['date_end'])->endOfDay();
            
            if ($endDateTime->isPast()) {
                $dataToUpdate['is_active'] = false;
            } else {
                $dataToUpdate['is_active'] = true;
            }
        }

        $user->update($dataToUpdate);
        return redirect()->route('admin.users.index')->with('success', 'User actualizat cu succes');
    }
	
    /**
    * Sterge un utilizator din sistem.
    * Include o masura de siguranta pentru a preveni stergerea propriului cont.
    * @param \App\Models\User $user Utilizatorul de sters
    * @return \Illuminate\Http\RedirectResponse
    */
    public function destroy(User $user)
    {
        if (Auth::id() == $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Nu poti sterge propriul cont!');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User sters cu succes');
    }
}
