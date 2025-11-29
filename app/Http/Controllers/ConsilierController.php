<?php

namespace App\Http\Controllers;
use App\Models\Client;
use Illuminate\Http\Request;

class ConsilierController extends Controller
{
    public function index()
    {
        $client = Client::orderBy('lastname')->get();
        return view('consilier.index', compact('client'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'type'=> 'required|string',
            'cnp' => 'required|string|max:13',
            'cui'=> 'required|string|max:12',
            'tva_payer'=> 'required|boolean',
            'email'=> 'required|email|unique:client,email',
            'phone'=> 'string|digits_between:10,15',
            'country'=> 'string|max:255',
            'county'=> 'string|max:255',
            'locality'=> 'string|max:255',
            'address'=> 'string|max:255',
            'status' => 'required|string|in:activ,inactiv',
        ]);

        Client::create([
           'firstname'=> $validated['firstname'],
           'lastname'=> $validated['lastname'],
           'type'=> $validated['type'],
           'cnp'=> $validated['cnp'],
           'cui'=> $validated['cui'],
           'tva_payer'=> $validated['tva_payer'],
           'email'=> $validated['email'],
           'phone'=> $validated['phone'],
           'country'=> $validated['country'],
           'county'=> $validated['county'],
           'locality'=> $validated['locality'],
           'address'=> $validated['address'],
           'status'=> $validated['status'],
        ]);

        return redirect()->route('consilier.index')->with('success', 'Clinetul a fost creat cu success');
    }
}


