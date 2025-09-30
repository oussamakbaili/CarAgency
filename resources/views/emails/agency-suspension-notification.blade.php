<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suspension de compte</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .suspension-box {
            background: #fef2f2;
            border: 2px solid #ef4444;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .suspension-icon {
            font-size: 24px;
            margin-right: 10px;
        }
        .details {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-item:last-child {
            border-bottom: none;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            background: #3b82f6;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 0;
        }
        .contact-info {
            background: #eff6ff;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚫 Suspension de Compte</h1>
        <p>RentCar Platform</p>
    </div>
    
    <div class="content">
        <h2>Bonjour {{ $agency->agency_name }},</h2>
        
        <p>Nous vous informons que votre compte d'agence a été suspendu en raison de trop d'annulations de réservations.</p>
        
        <div class="suspension-box">
            <h3><span class="suspension-icon">🚫</span>Compte Suspendu</h3>
            <p><strong>Votre compte a été suspendu le {{ $suspendedAt->format('d/m/Y à H:i') }}</strong></p>
            <p><strong>Raison :</strong> {{ $suspensionReason }}</p>
        </div>
        
        <div class="details">
            <h3>Détails de la suspension :</h3>
            <div class="detail-item">
                <span>Nombre d'annulations :</span>
                <strong>{{ $agency->cancellation_count }}</strong>
            </div>
            <div class="detail-item">
                <span>Limite autorisée :</span>
                <strong>{{ $agency->max_cancellations }}</strong>
            </div>
            <div class="detail-item">
                <span>Date de suspension :</span>
                <strong>{{ $suspendedAt->format('d/m/Y à H:i') }}</strong>
            </div>
            <div class="detail-item">
                <span>Statut actuel :</span>
                <strong style="color: #ef4444">Suspendu</strong>
            </div>
        </div>
        
        <h3>Conséquences de la suspension :</h3>
        <ul>
            <li>❌ Vous ne pouvez plus recevoir de nouvelles réservations</li>
            <li>❌ Votre profil n'apparaît plus dans les recherches</li>
            <li>❌ Vous ne pouvez pas modifier vos véhicules</li>
            <li>❌ Les réservations existantes restent valides</li>
        </ul>
        
        <div class="contact-info">
            <h3>Comment réactiver votre compte ?</h3>
            <p>Pour réactiver votre compte, vous devez :</p>
            <ol>
                <li>Contacter notre équipe de support</li>
                <li>Expliquer les raisons des annulations</li>
                <li>Fournir un plan d'amélioration</li>
                <li>Attendre l'approbation de l'administrateur</li>
            </ol>
        </div>
        
        <div style="text-align: center;">
            <a href="mailto:support@rentcarplatform.com" class="btn">Contacter le support</a>
        </div>
    </div>
    
    <div class="footer">
        <p>Cet email a été envoyé automatiquement par RentCar Platform</p>
        <p>© {{ date('Y') }} RentCar Platform. Tous droits réservés.</p>
    </div>
</body>
</html>
