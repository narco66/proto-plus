<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Note verbale - {{ $demande->reference }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            color: #031a59;
            margin: 0;
            padding: 2.5rem;
        }
        header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        header img {
            width: 100px;
            height: auto;
            margin-right: 1rem;
        }
        header h1 {
            font-size: 1.4rem;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        section {
            margin-top: 1rem;
            line-height: 1.6;
        }
        .details {
            margin: 1rem 0;
            padding: 1rem;
            border: 1px solid #dcdfe6;
            border-radius: 6px;
            background: #f9fafc;
        }
        .details strong {
            display: inline-block;
            width: 180px;
        }
        .signature {
            margin-top: 3rem;
        }
    </style>
</head>
<body>
    <header>
        <img src="{{ $logo }}" alt="Logo CEEAC">
        <div>
            <h1>Note verbale</h1>
            <p>Référence : {{ $demande->reference }}</p>
            <p>Date : {{ $dateGenerated }}</p>
        </div>
    </header>

    <section>
        <p>
            Suite à la validation par {{ $validator->full_name }}, nous vous transmettons cette note verbale concernant la demande
            référencée ci-dessus relative à {{ ucfirst(str_replace('_', ' ', $demande->type_demande)) }}.
        </p>
        <div class="details">
            <p><strong>Type de la demande :</strong> {{ $typeLabel }}</p>
            <p><strong>Demandeur :</strong> {{ $demande->demandeur->full_name }}</p>
            <p><strong>Motif :</strong> {{ $demande->motif ?? '—' }}</p>
            <p><strong>Destination :</strong> {{ $demande->pays_destination }}</p>
            <p><strong>Date de départ :</strong> {{ $demande->date_depart_prevue ? $demande->date_depart_prevue->format('d/m/Y') : '—' }}</p>
        </div>
        <p>
            Nous vous remercions de bien vouloir prendre les dispositions nécessaires afin de faciliter la procédure,
            en conformité avec les protocoles diplomatiques en vigueur.
        </p>
    </section>

    <div class="signature">
        <p><strong>{{ $validator->full_name }}</strong></p>
        <p>{{ ucfirst(str_replace('_', ' ', $validator->roles->first()?->name ?? 'Validation')) }}</p>
    </div>
</body>
</html>
