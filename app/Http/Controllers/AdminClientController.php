<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\User;

class AdminClientController extends Controller
{
    public function index(Request $request)
    {
        $querry = Client::query()->with('user');

        if($request->filled('search')) {
            $search = $request->input('search');
            $querry->where(function($q) use ($search) {
                $q->where('firstname', 'like', "%$search%")
                  ->orWhere('lastname', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('cui', 'like', "%$search%")
                  ->orWhere('cnp', 'like', "%$search%");
            });
        }

        if($request->filled('type')) {
            $querry->where('type', $request->input('type'));
        }

        if ($request->filled('user_id')) {
            $querry->where('user_id', $request->input('user_id'));
        }

        $clients = $querry->latest()->paginate(15)->withQueryString();
        return view('admin.clients.index', compact('clients'));
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return back()->with('success', 'Client sters cu succes.');
    }
}
