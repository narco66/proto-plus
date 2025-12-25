<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Si l'utilisateur est authentifié, rediriger vers le dashboard
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        return view('home');
    }

    public function documentation()
    {
        return view('documentation');
    }

    public function faq()
    {
        return view('faq');
    }
}
