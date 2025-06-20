<x-mail::message>
# Demande d'Inscription Rejetée

Cher(e) {{ $agency->responsable_name }},

Nous regrettons de vous informer que votre demande d'inscription pour l'agence "{{ $agency->agency_name }}" a été rejetée.

**Raison du rejet:**
{{ $agency->rejection_reason }}

Vous pouvez vous connecter à votre compte pour mettre à jour vos informations et soumettre une nouvelle demande.

<x-mail::button :url="route('login')">
Se Connecter
</x-mail::button>

Cordialement,<br>
{{ config('app.name') }}
</x-mail::message> 