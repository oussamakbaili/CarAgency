<x-mail::message>
# Compte Définitivement Rejeté

Cher(e) {{ $agency->responsable_name }},

Nous regrettons de vous informer que votre compte pour l'agence "{{ $agency->agency_name }}" a été définitivement rejeté après avoir atteint le nombre maximum de tentatives autorisées (3 tentatives).

**Dernière raison du rejet:**
{{ $agency->rejection_reason }}

Malheureusement, vous ne pourrez plus soumettre de nouvelle demande avec ces informations. Si vous souhaitez créer une nouvelle agence, veuillez utiliser des informations différentes et vous assurer de répondre à tous les critères requis.

Cordialement,<br>
{{ config('app.name') }}
</x-mail::message> 