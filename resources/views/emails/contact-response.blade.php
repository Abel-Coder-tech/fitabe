@component('mail::message')
# Bonjour {{ $name }},

Merci de nous avoir contactés. Voici la réponse de l'équipe **{{ config('app.name', 'FITAB') }}** :

---

{!! nl2br(e($replyMessage)) !!}

---

Si vous avez d'autres questions, n'hésitez pas à nous recontacter.

Cordialement,<br>
L'équipe **{{ config('app.name', 'FITAB') }}**
@endcomponent
