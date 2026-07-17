@component('mail::message')
# Bonjour {{ $contact->nom }},

Nous vous confirmons la bonne réception de votre message.

**Sujet :** {{ $contact->sujet }}

**Votre message :**
{{ $contact->message }}

Notre équipe vous répondra dans les plus brefs délais.

Merci de votre confiance.

Cordialement,<br>
L'équipe **{{ config('app.name', 'FITAB') }}**
@endcomponent
