<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Mail\ContactConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

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

        $token = $request->input('recaptcha_token');
        $secret = config('services.recaptcha.secret_key');

        if ($secret) {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $token,
                'remoteip' => $request->ip(),
            ]);

            $body = $response->json();

            if (!($body['success'] ?? false) || ($body['score'] ?? 0) < 0.5) {
                return back()->withErrors(['recaptcha' => 'La vérification anti-spam a échoué. Veuillez réessayer.']);
            }
        }

        $contact = Contact::create($validated);

        try {
            Mail::to($contact->email)->send(new ContactConfirmation($contact));
        } catch (\Throwable $e) {
            Log::error('Erreur envoi email confirmation contact: ' . $e->getMessage());
        }

        return back()->with('success', 'Votre message a été envoyé avec succès. Un email de confirmation vous a été adressé.');
    }
}
