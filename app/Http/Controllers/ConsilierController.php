<?php

namespace App\Http\Controllers;
use App\Models\Client;
use Illuminate\Http\Request;

class ConsilierController extends Controller
{
    public function index()
    {
        $client = client::orderBy('lastname')->get();
        return view('consilier.index', compact('client'));
    }
}
