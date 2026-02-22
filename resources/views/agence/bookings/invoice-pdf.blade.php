<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ str_pad($rental->id, 3, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #1f2937;
        }
        .invoice-number {
            font-size: 14px;
            color: #6b7280;
            margin-top: 5px;
        }
        .agency-info {
            text-align: right;
            font-size: 14px;
        }
        .agency-name {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e5e7eb;
        }
        .client-info {
            display: flex;
            justify-content: space-between;
        }
        .client-details {
            flex: 1;
        }
        .client-name {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }
        .vehicle-details {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .vehicle-info {
            flex: 1;
        }
        .vehicle-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }
        .rental-details {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }
        .detail-item {
            text-align: center;
        }
        .detail-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 5px;
        }
        .detail-value {
            font-weight: bold;
            font-size: 14px;
        }
        .pricing-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .pricing-table th {
            background-color: #f9fafb;
            padding: 12px;
            text-align: left;
            font-size: 12px;
            font-weight: bold;
            color: #374151;
            border: 1px solid #e5e7eb;
        }
        .pricing-table td {
            padding: 12px;
            border: 1px solid #e5e7eb;
            font-size: 14px;
        }
        .total-row {
            background-color: #f9fafb;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 12px;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div>
            <div class="invoice-title">FACTURE</div>
            <div class="invoice-number">N° {{ str_pad($rental->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div class="invoice-number">Date: {{ \Carbon\Carbon::parse($rental->created_at)->format('d/m/Y') }}</div>
        </div>
        <div class="agency-info">
            <div class="agency-name">{{ $rental->agency->name ?? 'Agence de Location' }}</div>
            <div>{{ $rental->agency->address ?? 'Adresse de l\'agence' }}</div>
            <div>{{ $rental->agency->phone ?? 'Téléphone' }}</div>
            <div>{{ $rental->agency->email ?? 'Email' }}</div>
        </div>
    </div>

    <!-- Client Information -->
    <div class="section">
        <div class="section-title">Facturé à:</div>
        <div class="client-info">
            <div class="client-details">
                <div class="client-name">{{ $rental->user->name ?? 'N/A' }}</div>
                <div>{{ $rental->user->email ?? 'N/A' }}</div>
                @if($rental->user->phone)
                    <div>{{ $rental->user->phone }}</div>
                @endif
            </div>
            <div>
                <div>ID Client: #{{ $rental->user->id ?? 'N/A' }}</div>
                <div>Date de réservation: {{ \Carbon\Carbon::parse($rental->created_at)->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Vehicle Details -->
    <div class="section">
        <div class="section-title">Détails du Véhicule</div>
        <div class="vehicle-details">
            <div class="vehicle-info">
                <div class="vehicle-title">{{ $rental->car->brand }} {{ $rental->car->model }} {{ $rental->car->year }}</div>
                <div>Immatriculation: {{ $rental->car->registration_number }}</div>
                @if($rental->car->color)
                    <div>Couleur: {{ $rental->car->color }}</div>
                @endif
                @if($rental->car->category)
                    <div>Catégorie: {{ $rental->car->category->name ?? 'N/A' }}</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Rental Details -->
    <div class="section">
        <div class="section-title">Détails de la Location</div>
        <div class="rental-details">
            <div class="detail-item">
                <div class="detail-label">Date de début</div>
                <div class="detail-value">{{ $rental->start_date ? \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') : 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Date de fin</div>
                <div class="detail-value">{{ $rental->end_date ? \Carbon\Carbon::parse($rental->end_date)->format('d/m/Y') : 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Durée</div>
                <div class="detail-value">
                    @if($rental->start_date && $rental->end_date)
                        {{ \Carbon\Carbon::parse($rental->start_date)->diffInDays(\Carbon\Carbon::parse($rental->end_date)) }} jours
                    @else
                        N/A
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Breakdown -->
    <div class="section">
        <div class="section-title">Détail des Tarifs</div>
        <table class="pricing-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Location {{ $rental->car->brand }} {{ $rental->car->model }}</td>
                    <td>
                        @if($rental->start_date && $rental->end_date)
                            {{ \Carbon\Carbon::parse($rental->start_date)->diffInDays(\Carbon\Carbon::parse($rental->end_date)) }} jours
                        @else
                            1 jour
                        @endif
                    </td>
                    <td>{{ number_format($rental->car->price_per_day, 0) }} DH</td>
                    <td>{{ number_format($rental->total_price, 0) }} DH</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="3">Total TTC</td>
                    <td>{{ number_format($rental->total_price, 0) }} DH</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div>Merci pour votre confiance!</div>
        <div style="margin-top: 10px;">
            Cette facture a été générée automatiquement le {{ now()->format('d/m/Y à H:i') }}
        </div>
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
