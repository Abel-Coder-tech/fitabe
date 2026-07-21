<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ], [
            'email.required' => 'Veuillez entrer votre adresse e-mail.',
            'email.email' => 'Adresse e-mail invalide.',
        ]);

        Newsletter::firstOrCreate(
            ['email' => $validated['email']],
            ['actif' => true]
        );

        return back()->with('newsletter', 'Inscription réussie !');
    }
}
