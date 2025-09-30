<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avertissement d'annulation</title>
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
            background: linear-gradient(135deg, #f59e0b, #d97706);
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
        .warning-box {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .warning-icon {
            font-size: 24px;
            margin-right: 10px;
        }
        .stats {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .stat-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .stat-item:last-child {
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
    </style>
</head>
<body>
    <div class="header">
        <h1>⚠️ Avertissement d'Annulation</h1>
        <p>RentCar Platform</p>
    </div>
    
    <div class="content">
        <h2>Bonjour {{ $agency->agency_name }},</h2>
        
        <p>Nous vous contactons concernant les annulations de réservations sur votre compte.</p>
        
        <div class="warning-box">
            <h3><span class="warning-icon">⚠️</span>Attention</h3>
            <p><strong>{{ $warningMessage }}</strong></p>
        </div>
        
        <div class="stats">
            <h3>Statistiques de votre compte :</h3>
            <div class="stat-item">
                <span>Annulations effectuées :</span>
                <strong>{{ $agency->cancellation_count }}</strong>
            </div>
            <div class="stat-item">
                <span>Annulations autorisées :</span>
                <strong>{{ $agency->max_cancellations }}</strong>
            </div>
            <div class="stat-item">
                <span>Annulations restantes :</span>
                <strong style="color: {{ $remainingCancellations <= 1 ? '#ef4444' : '#059669' }}">{{ $remainingCancellations }}</strong>
            </div>
        </div>
        
        <h3>Que faire maintenant ?</h3>
        <ul>
            <li>Évitez d'annuler des réservations sauf en cas d'urgence</li>
            <li>Contactez vos clients directement pour résoudre les problèmes</li>
            <li>Mettez à jour la disponibilité de vos véhicules régulièrement</li>
            <li>Si vous devez annuler, fournissez une raison valable</li>
        </ul>
        
        <p>Si vous avez des questions ou des préoccupations, n'hésitez pas à contacter notre équipe de support.</p>
        
        <div style="text-align: center;">
            <a href="{{ route('agence.dashboard') }}" class="btn">Accéder à mon tableau de bord</a>
        </div>
    </div>
    
    <div class="footer">
        <p>Cet email a été envoyé automatiquement par RentCar Platform</p>
        <p>© {{ date('Y') }} RentCar Platform. Tous droits réservés.</p>
    </div>
</body>
</html>
