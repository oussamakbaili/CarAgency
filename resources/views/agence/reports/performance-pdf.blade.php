<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de Performance - {{ date('d/m/Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #1f2937;
            margin: 0;
            font-size: 28px;
        }
        
        .header p {
            color: #6b7280;
            margin: 5px 0;
        }
        
        .stats {
            display: flex;
            justify-content: space-around;
            margin: 30px 0;
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
        }
        
        .stat {
            text-align: center;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 14px;
            color: #6b7280;
        }
        
        .section {
            margin: 30px 0;
        }
        
        .section h2 {
            color: #1f2937;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
        }
        
        th, td {
            border: 1px solid #d1d5db;
            padding: 12px;
            text-align: left;
        }
        
        th {
            background-color: #f3f4f6;
            font-weight: bold;
            color: #374151;
        }
        
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        
        .chart-placeholder {
            background-color: #f3f4f6;
            border: 2px dashed #d1d5db;
            padding: 40px;
            text-align: center;
            margin: 20px 0;
            border-radius: 8px;
        }
        
        .chart-placeholder p {
            color: #6b7280;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport de Performance</h1>
        <p><strong>Agence:</strong> {{ $agency->name ?? 'Agence de Location' }}</p>
        <p><strong>Période:</strong> {{ date('d/m/Y') }}</p>
        <p><strong>Généré le:</strong> {{ date('d/m/Y à H:i') }}</p>
    </div>
    
    <div class="stats">
        <div class="stat">
            <div class="stat-value">{{ number_format($performanceData['total_revenue'] ?? 0, 0) }} MAD</div>
            <div class="stat-label">Revenus Totaux</div>
        </div>
        <div class="stat">
            <div class="stat-value">{{ $performanceData['total_rentals'] ?? 0 }}</div>
            <div class="stat-label">Locations</div>
        </div>
        <div class="stat">
            <div class="stat-value">{{ number_format($performanceData['occupancy_rate'] ?? 0, 1) }}%</div>
            <div class="stat-label">Taux d'Occupation</div>
        </div>
        <div class="stat">
            <div class="stat-value">{{ number_format($performanceData['average_revenue'] ?? 0, 0) }} MAD</div>
            <div class="stat-label">Revenus Moyens</div>
        </div>
    </div>
    
    <div class="section">
        <h2>Détails des Performances par Mois</h2>
        <table>
            <thead>
                <tr>
                    <th>Mois</th>
                    <th>Revenus (MAD)</th>
                    <th>Locations</th>
                    <th>Taux d'Occupation (%)</th>
                    <th>Revenus Moyens (MAD)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($performanceData['monthly_data'] ?? [] as $month)
                    <tr>
                        <td>{{ $month['month'] }}</td>
                        <td>{{ number_format($month['revenue'], 0) }}</td>
                        <td>{{ $month['rentals'] }}</td>
                        <td>{{ number_format($month['occupancy_rate'], 1) }}</td>
                        <td>{{ number_format($month['average_revenue'], 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="section">
        <h2>Analyse des Performances</h2>
        <div class="chart-placeholder">
            <p>📊 Graphiques d'évolution des revenus et locations</p>
            <p><em>Les graphiques interactifs sont disponibles dans la version web du rapport</em></p>
        </div>
    </div>
    
    <div class="footer">
        <p>Ce rapport a été généré automatiquement par le système de gestion d'agence</p>
        <p>Pour plus d'informations, contactez l'administrateur du système</p>
    </div>
</body>
</html>
