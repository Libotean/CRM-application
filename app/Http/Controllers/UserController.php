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
    // metoda pentru a afisa toti utilizatorii
    public function index()
    {
        User::updateExpiredStatus();
        $users = User::orderBy('lastname')->get();
        return view('admin.users.index', compact('users'));
    }
    // metoda pentru a afisa formularul de adaugare
    public function create()
    {
        return view('admin.users.create');
    }

    // metoda pentru a adauga un utilizator nou
    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|email:rfc,dns|unique:users,email',
            'password' => 'required|string|min:8',
            'country' => 'required|string|max:255',
            'county' => 'required|string|max:255',
            'locality' => 'required|string|max:255',
            'role' => ['required', Rule::in(['admin', 'user'])], // rolul trebuie sa fie admin sau user
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

    public function show(User $user)
    {
        $user->load('clients');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {

        return view('admin.users.edit', compact('user'));
    }

    // metoda pentru a actualiza un utilizator
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => ['required', 'email:rfc,dns', 'email', Rule::unique('users')->ignore($user->id)],
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
            $dataToUopdate['password'] = Hash::make($validated['[password']);
        }

        $user->update($dataToUpdate);
        return redirect()->route('admin.users.index')->with('success', 'User actualizat cu succes');

    }

    public function destroy(User $user)
    {
        if (Auth::id() == $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Nu poti sterge propriul cont!');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User sters cu succes');
    }
}
