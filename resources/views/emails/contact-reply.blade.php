<x-mail::message>
# Bonjour {{ $contact->nom }},

Vous avez reçu une réponse de l'équipe FITAB à votre message du **{{ $contact->created_at->format('d/m/Y') }}** concernant « {{ $contact->sujet }} ».

---

{{ nl2br(e($reponse)) }}

---

<x-mail::button :url="url('/contact')">
Nous contacter
</x-mail::button>

Cordialement,<br>
L'équipe FITAB
</x-mail::message>
