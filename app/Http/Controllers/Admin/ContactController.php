<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactReply;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::orderByDesc('created_at')->paginate(15);
        return view('admin.contacts.index', compact('contacts'));
    }

    public function show(Contact $contact)
    {
        if (!$contact->lu) {
            $contact->update(['lu' => true]);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    public function reply(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'reponse' => 'required|string|max:5000',
        ], [
            'reponse.required' => 'Veuillez écrire votre réponse.',
        ]);

        try {
            Mail::to($contact->email)->send(new ContactReply($contact, $validated['reponse']));
        } catch (\Throwable $e) {
            Log::error('Erreur envoi réponse contact: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'envoi de la réponse. Veuillez réessayer.');
        }

        return to_route('admin.contacts.index')->with('success', 'Réponse envoyée avec succès à ' . $contact->email);
    }

    public function destroy(Contact $contact)
    {
        $contact->forcedelete();
        return to_route('admin.contacts.index')->with('success', 'Message supprimé avec succès.');
    }
}
