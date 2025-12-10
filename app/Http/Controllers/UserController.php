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

    public function create()
    {
        return view('admin.users.create');
    }

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

    public function show(User $user)
    {
        $user->load('clients');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
    * Actualizeaza datele unui utilizator.
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

        // CORECTIA ESTE AICI:
        // Folosim isset() pentru a evita erori daca date_end lipseste
        // Folosim endOfDay() pentru a compara corect data
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

    public function destroy(User $user)
    {
        if (Auth::id() == $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Nu poti sterge propriul cont!');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User sters cu succes');
    }
}