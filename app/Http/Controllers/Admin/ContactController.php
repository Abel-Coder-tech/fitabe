<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactResponseMail;

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

    public function respond(Request $request, Contact $contact)
    {
        $request->validate([
            'reponse' => 'required|string',
        ]);

        Mail::to($contact->email)->send(new ContactResponseMail(
            email: $contact->email,
            name: $contact->nom,
            replyMessage: $request->reponse,
            originalSubject: $contact->sujet,
        ));

        return back()->with('success', 'Réponse envoyée avec succès à ' . $contact->email);
    }

    public function destroy(Contact $contact)
    {
        $contact->forcedelete();
        return to_route('admin.contacts.index')->with('success', 'Message supprimé avec succès.');
    }
}
