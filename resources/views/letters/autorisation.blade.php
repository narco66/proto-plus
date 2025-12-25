<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Lettre d'autorisation d'entrée - {{ $demande->reference }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            color: #031a59;
            line-height: 1.5;
            margin: 0;
        }
        .page {
            padding: 2.5rem 2.5rem 2rem;
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
        header .titles {
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        header .titles h1 {
            margin: 0;
            font-size: 1.4rem;
        }
        header .titles p {
            margin: 0.25rem 0 0;
            font-size: 0.85rem;
        }
        .document-id {
            text-align: right;
            font-size: 0.85rem;
            color: #6c757d;
        }
        .content h2 {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        .content p {
            text-align: justify;
            margin-bottom: 1rem;
        }
        .details {
            margin: 1.5rem 0;
            border: 1px solid #b4bde0;
            border-radius: 6px;
            padding: 1rem;
            background: #f6f7fc;
        }
        .details table {
            width: 100%;
            border-collapse: collapse;
        }
        .details th,
        .details td {
            padding: 0.35rem 0;
            text-align: left;
        }
        .sign-off {
            margin-top: 2rem;
        }
        .signature-block {
            margin-top: 3rem;
        }
        .signature-block strong {
            display: block;
        }
        footer {
            margin-top: 4rem;
            font-size: 0.75rem;
            text-align: center;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="page">
        <header>
            <img src="{{ $logo }}" alt="Logo CEEAC">
            <div class="titles">
                <h1>Commission de la CEDEAO et de l'Union africaine</h1>
                <p>Secrétariat général - Protocole</p>
            </div>
        </header>

        <div class="document-id">
            Référence : {{ $demande->reference }}<br>
            {{ $dateGenerated }}
        </div>

        <div class="content">
            <h2>Demande d'autorisation d'entrée</h2>
            <p>
                Suite à la validation {{ $validator->hasRole('secretaire_general') ? 'du Secrétaire général' : ($validator->hasRole('directeur_protocole') ? 'du Directeur du protocole' : 'de l\'administrateur') }},
                nous vous prions de bien vouloir autoriser l'entrée exceptionnelle de la personne concernée par la demande référencée ci-dessus.
            </p>

            <div class="details">
                <table>
                    <tr>
                        <th>Demandeur :</th>
                        <td>{{ $demande->demandeur->full_name }}</td>
                    </tr>
                    <tr>
                        <th>Type de demande :</th>
                        <td>{{ ucfirst(str_replace('_', ' ', $demande->type_demande)) }}</td>
                    </tr>
                    <tr>
                        <th>Motif :</th>
                        <td>{{ $demande->motif ?? 'Non précisé' }}</td>
                    </tr>
                    <tr>
                        <th>Date de départ prévue :</th>
                        <td>{{ $demande->date_depart_prevue ? $demande->date_depart_prevue->format('d/m/Y') : '—' }}</td>
                    </tr>
                    <tr>
                        <th>Pays de destination :</th>
                        <td>{{ $demande->pays_destination }}</td>
                    </tr>
                </table>
            </div>

            <p>
                Nous confirmons que la demande a été instruite et validée par les autorités compétentes.
                Le bénéficiaire est donc autorisé à entrer conformément aux procédures diplomatiques en vigueur.
            </p>
            <p>
                Merci de prendre acte et d'appuyer la prise en charge logistique auprès des services concernés.
            </p>

            <div class="signature-block">
                <strong>{{ $validator->full_name }}</strong>
                <span>{{ ucfirst(str_replace('_', ' ', $validator->roles->first()?->name ?? 'Validation')) }}</span>
            </div>
        </div>

        <footer>
            CEEAC – {{ now()->year }} – Tous droits réservés.
        </footer>
    </div>
</body>
</html>
