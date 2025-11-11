<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reportTypeLabel }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 12px;
            opacity: 0.9;
        }
        
        .info-section {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #667eea;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
        }
        
        .info-value {
            color: #333;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 20px;
        }
        
        table thead {
            background: #667eea;
            color: white;
        }
        
        table th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
        }
        
        table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 10px;
        }
        
        table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .summary-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            border-left: 4px solid #28a745;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .summary-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 13px;
            margin-top: 5px;
            padding-top: 10px;
        }
        
        .summary-label {
            color: #555;
        }
        
        .summary-value {
            color: #333;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e0e0e0;
            text-align: center;
            color: #666;
            font-size: 9px;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .badge-success {
            background: #28a745;
            color: white;
        }
        
        .badge-pending {
            background: #ffc107;
            color: #333;
        }
        
        .badge-failed {
            background: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $reportTypeLabel }}</h1>
        <p>{{ $agency->agency_name ?? 'Agence' }}</p>
    </div>
    
    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Période :</span>
            <span class="info-value">{{ $dateRange['start']->format('d/m/Y') }} - {{ $dateRange['end']->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Date de génération :</span>
            <span class="info-value">{{ now()->format('d/m/Y H:i') }}</span>
        </div>
    </div>
    
    @if($reportType === 'revenue')
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Véhicule</th>
                    <th class="text-right">Montant (MAD)</th>
                    <th class="text-center">Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                <tr>
                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ ($item->car_brand ?? '') . ' ' . ($item->car_model ?? '') }}</td>
                    <td class="text-right">{{ number_format($item->total_price, 2, ',', ' ') }}</td>
                    <td class="text-center">
                        <span class="badge {{ $item->status === 'completed' ? 'badge-success' : 'badge-pending' }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Aucune donnée disponible</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($data->count() > 0)
        <div class="summary-box">
            <div class="summary-row">
                <span class="summary-label">Total des revenus :</span>
                <span class="summary-value">{{ number_format($data->sum('total_price'), 2, ',', ' ') }} MAD</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Nombre de locations :</span>
                <span class="summary-value">{{ $data->count() }}</span>
            </div>
        </div>
        @endif
        
    @elseif($reportType === 'expenses')
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th class="text-right">Montant (MAD)</th>
                    <th class="text-center">Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                <tr>
                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $item->type)) }}</td>
                    <td>{{ $item->description ?? 'N/A' }}</td>
                    <td class="text-right">{{ number_format($item->amount, 2, ',', ' ') }}</td>
                    <td class="text-center">
                        <span class="badge {{ $item->status === 'completed' ? 'badge-success' : ($item->status === 'pending' ? 'badge-pending' : 'badge-failed') }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Aucune donnée disponible</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($data->count() > 0)
        <div class="summary-box">
            <div class="summary-row">
                <span class="summary-label">Total des dépenses :</span>
                <span class="summary-value">{{ number_format($data->sum('amount'), 2, ',', ' ') }} MAD</span>
            </div>
        </div>
        @endif
        
    @elseif($reportType === 'profit_loss')
        <div class="summary-box">
            <div class="summary-row">
                <span class="summary-label">Revenus totaux :</span>
                <span class="summary-value">{{ number_format($data['revenue'], 2, ',', ' ') }} MAD</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Dépenses totales :</span>
                <span class="summary-value">{{ number_format($data['expenses'], 2, ',', ' ') }} MAD</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Bénéfice net :</span>
                <span class="summary-value" style="color: {{ $data['profit'] >= 0 ? '#28a745' : '#dc3545' }};">
                    {{ number_format($data['profit'], 2, ',', ' ') }} MAD
                </span>
            </div>
        </div>
        
    @elseif($reportType === 'tax')
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th class="text-right">Montant Total (MAD)</th>
                    <th class="text-right">Montant TVA (20%)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                <tr>
                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td class="text-right">{{ number_format($item->total_price, 2, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($item->tax_amount, 2, ',', ' ') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center">Aucune donnée disponible</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($data->count() > 0)
        <div class="summary-box">
            <div class="summary-row">
                <span class="summary-label">Total TVA :</span>
                <span class="summary-value">{{ number_format($data->sum('tax_amount'), 2, ',', ' ') }} MAD</span>
            </div>
        </div>
        @endif
        
    @else
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th class="text-right">Montant (MAD)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                <tr>
                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $item->description ?? 'N/A' }}</td>
                    <td class="text-right">{{ number_format($item->total_price ?? $item->amount ?? 0, 2, ',', ' ') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center">Aucune donnée disponible</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    @endif
    
    <div class="footer">
        <p>Rapport généré le {{ now()->format('d/m/Y à H:i') }} par {{ $agency->agency_name ?? 'Système' }}</p>
        <p>Ce document est confidentiel et destiné à un usage interne uniquement.</p>
    </div>
</body>
</html>

