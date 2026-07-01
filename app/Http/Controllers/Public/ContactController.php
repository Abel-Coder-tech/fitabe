<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('public.contact.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'sujet' => 'required|string|max:200',
            'message' => 'required|string',
        ], [
            'nom.required' => 'Votre nom est requis.',
            'email.required' => 'Votre email est requis.',
            'email.email' => 'Veuillez entrer un email valide.',
            'sujet.required' => 'Le sujet est requis.',
            'sujet.max' => 'Le sujet ne doit pas dépasser :max caractères.',
            'message.required' => 'Le message est requis.',
        ]);

        Contact::create($validated);

        return back()->with('success', 'Votre message a été envoyé avec succès.');
    }
}
