@component('mail::message')
# Félicitations {{ $agency->responsable_name }} !

Votre demande d'inscription pour l'agence **{{ $agency->agency_name }}** a été approuvée. Vous pouvez maintenant accéder à votre tableau de bord et commencer à gérer votre flotte de véhicules.

## Prochaines étapes :

1. Connectez-vous à votre compte
2. Complétez votre profil d'agence
3. Ajoutez vos véhicules disponibles
4. Commencez à recevoir des réservations

@component('mail::button', ['url' => route('login')])
Accéder à mon compte
@endcomponent

Si vous avez des questions ou besoin d'assistance, n'hésitez pas à nous contacter.

Merci de nous avoir choisis !

Cordialement,<br>
{{ config('app.name') }}
@endcomponent 