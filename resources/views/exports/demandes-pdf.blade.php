<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport des demandes - PROTO PLUS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>COMMISSION DE LA CEEAC</h1>
        <h2>PROTO PLUS - Rapport des demandes</h2>
        <p>Date d'export : {{ $date_export }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Référence</th>
                <th>Type</th>
                <th>Demandeur</th>
                <th>Statut</th>
                <th>Priorité</th>
                <th>Date création</th>
                <th>Date soumission</th>
            </tr>
        </thead>
        <tbody>
            @foreach($demandes as $demande)
                <tr>
                    <td>{{ $demande->reference }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $demande->type_demande)) }}</td>
                    <td>{{ $demande->demandeur->full_name ?? '-' }}</td>
                    <td>{{ ucfirst($demande->statut) }}</td>
                    <td>{{ ucfirst($demande->priorite) }}</td>
                    <td>{{ $demande->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $demande->date_soumission ? $demande->date_soumission->format('d/m/Y H:i') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Document généré le {{ $date_export }} - PROTO PLUS v1.0</p>
        <p>Document interne - Diffusion restreinte</p>
    </div>
</body>
</html>


