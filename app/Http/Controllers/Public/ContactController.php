<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Mail\ContactConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function index()
    {
        return view('public.contact.index');
    }

    public function store(Request $request)
    {
        if ($request->filled('_hp_name')) {
            return back()->with('success', 'Votre message a été envoyé avec succès.');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'sujet' => 'required|string|max:200',
            'message' => 'required|string|max:5000',
        ], [
            'nom.required' => 'Votre nom est requis.',
            'email.required' => 'Votre email est requis.',
            'email.email' => 'Veuillez entrer un email valide.',
            'sujet.required' => 'Le sujet est requis.',
            'sujet.max' => 'Le sujet ne doit pas dépasser :max caractères.',
            'message.required' => 'Le message est requis.',
            'message.max' => 'Le message ne doit pas dépasser :max caractères.',
        ]);

        $contact = Contact::create($validated);

        try {
            Mail::to($contact->email)->send(new ContactConfirmation($contact));
        } catch (\Throwable $e) {
            Log::error('Erreur envoi email confirmation contact: ' . $e->getMessage());
        }

        return back()->with('success', 'Votre message a été envoyé avec succès. Un email de confirmation vous a été adressé.');
    }
}
