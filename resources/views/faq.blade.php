<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - PROTO PLUS | CEEAC</title>
    <meta name="description" content="Questions fréquentes sur l'application PROTO PLUS">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --ceeac-primary: #003366;
            --ceeac-secondary: #0066CC;
            --ceeac-accent: #FF6600;
            --ceeac-light: #f8f9fa;
            --ceeac-dark: #212529;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--ceeac-dark);
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--ceeac-primary) !important;
        }

        .faq-header {
            background: linear-gradient(135deg, var(--ceeac-primary) 0%, var(--ceeac-secondary) 100%);
            color: white;
            padding: 80px 0 60px;
        }

        .faq-content {
            padding: 3rem 0;
        }

        .faq-category {
            margin-bottom: 3rem;
        }

        .faq-category h2 {
            color: var(--ceeac-primary);
            border-bottom: 3px solid var(--ceeac-secondary);
            padding-bottom: 0.5rem;
            margin-bottom: 2rem;
        }

        .accordion-item {
            border: 1px solid #dee2e6;
            border-radius: 8px !important;
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .accordion-button {
            background: var(--ceeac-light);
            color: var(--ceeac-dark);
            font-weight: 600;
            padding: 1.25rem;
        }

        .accordion-button:not(.collapsed) {
            background: var(--ceeac-primary);
            color: white;
        }

        .accordion-button:focus {
            box-shadow: none;
            border-color: var(--ceeac-secondary);
        }

        .accordion-body {
            background: white;
            padding: 1.5rem;
        }

        .search-box {
            background: white;
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            border: 2px solid #dee2e6;
            margin-bottom: 2rem;
        }

        .search-box:focus {
            border-color: var(--ceeac-primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 51, 102, 0.25);
        }

        .contact-box {
            background: linear-gradient(135deg, var(--ceeac-light) 0%, white 100%);
            border-radius: 12px;
            padding: 2rem;
            margin-top: 3rem;
            border: 2px solid var(--ceeac-secondary);
        }

        .footer {
            background: var(--ceeac-dark);
            color: white;
            padding: 40px 0 20px;
            margin-top: 4rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-shield-check"></i> PROTO PLUS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('documentation') }}">Documentation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('faq') }}">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right"></i> Connexion
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <div class="faq-header">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Questions Fréquentes</h1>
            <p class="lead">Trouvez rapidement les réponses à vos questions</p>
        </div>
    </div>

    <!-- Content -->
    <div class="container">
        <div class="faq-content">
            <!-- Search -->
            <div class="row justify-content-center mb-4">
                <div class="col-lg-8">
                    <input type="text" class="form-control search-box" id="faqSearch" placeholder="Rechercher dans la FAQ...">
                </div>
            </div>

            <!-- Général -->
            <div class="faq-category" data-category="general">
                <h2><i class="bi bi-question-circle"></i> Questions Générales</h2>
                <div class="accordion" id="accordionGeneral">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Qu'est-ce que PROTO PLUS ?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#accordionGeneral">
                            <div class="accordion-body">
                                PROTO PLUS est l'application de gestion complète des services protocolaires de la Commission de la CEEAC. 
                                Elle permet de gérer tous les actes protocolaires (visas diplomatiques, cartes diplomatiques, franchises douanières, etc.) 
                                de manière sécurisée, traçable et conforme aux réglementations en vigueur.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Qui peut utiliser PROTO PLUS ?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#accordionGeneral">
                            <div class="accordion-body">
                                PROTO PLUS est réservé aux fonctionnaires et agents de la Commission de la CEEAC. 
                                L'accès est géré par l'administration système qui crée les comptes utilisateurs et assigne les rôles appropriés.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Comment obtenir un compte ?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#accordionGeneral">
                            <div class="accordion-body">
                                Les comptes sont créés par le Directeur des Systèmes d'Information (DSI). 
                                Contactez le service DSI avec votre demande d'accès en précisant votre fonction et votre département.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                L'application est-elle accessible depuis l'extérieur ?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#accordionGeneral">
                            <div class="accordion-body">
                                Oui, PROTO PLUS est accessible depuis n'importe où via une connexion internet sécurisée. 
                                L'application utilise HTTPS pour garantir la sécurité des données en transit.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Demandes -->
            <div class="faq-category" data-category="demandes">
                <h2><i class="bi bi-file-earmark-text"></i> Gestion des Demandes</h2>
                <div class="accordion" id="accordionDemandes">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                Quels types de demandes puis-je créer ?
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse show" data-bs-parent="#accordionDemandes">
                            <div class="accordion-body">
                                Vous pouvez créer des demandes pour :
                                <ul>
                                    <li>Visa diplomatique</li>
                                    <li>Carte diplomatique</li>
                                    <li>Franchise douanière</li>
                                    <li>Et autres actes protocolaires selon les besoins</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                                Puis-je modifier une demande après l'avoir soumise ?
                            </button>
                        </h2>
                        <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#accordionDemandes">
                            <div class="accordion-body">
                                Non, une fois soumise, la demande entre dans le circuit de validation et ne peut plus être modifiée par le demandeur. 
                                Si des corrections sont nécessaires, la demande peut être retournée pour correction par un validateur, 
                                auquel cas vous pourrez la modifier à nouveau.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq7">
                                Combien de temps prend le traitement d'une demande ?
                            </button>
                        </h2>
                        <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#accordionDemandes">
                            <div class="accordion-body">
                                Le délai dépend du type de demande et du circuit de validation. En général :
                                <ul>
                                    <li>Instruction : 3 jours</li>
                                    <li>Validation niveau 1 : 2 jours</li>
                                    <li>Validation niveau 2 : 2 jours</li>
                                    <li>Validation niveau 3 : 3 jours (si requis)</li>
                                </ul>
                                Les demandes urgentes sont traitées en priorité.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq8">
                                Comment suivre l'état de ma demande ?
                            </button>
                        </h2>
                        <div id="faq8" class="accordion-collapse collapse" data-bs-parent="#accordionDemandes">
                            <div class="accordion-body">
                                Vous pouvez suivre l'état de vos demandes depuis votre tableau de bord. 
                                Chaque demande affiche son statut actuel (brouillon, soumis, en cours, validé, rejeté) 
                                et l'historique complet des actions effectuées. Vous recevez également des notifications 
                                à chaque changement d'état important.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ayants Droit -->
            <div class="faq-category" data-category="ayants-droit">
                <h2><i class="bi bi-people"></i> Ayants Droit</h2>
                <div class="accordion" id="accordionAyants">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq9">
                                Qu'est-ce qu'un ayant droit ?
                            </button>
                        </h2>
                        <div id="faq9" class="accordion-collapse collapse show" data-bs-parent="#accordionAyants">
                            <div class="accordion-body">
                                Un ayant droit est une personne à charge (conjoint, enfant) pour laquelle vous pouvez faire des demandes protocolaires. 
                                Vous devez d'abord déclarer vos ayants droit dans l'application avant de pouvoir les inclure dans une demande.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq10">
                                Puis-je modifier les informations d'un ayant droit ?
                            </button>
                        </h2>
                        <div id="faq10" class="accordion-collapse collapse" data-bs-parent="#accordionAyants">
                            <div class="accordion-body">
                                Oui, vous pouvez modifier les informations de vos ayants droit à tout moment depuis la section "Mes ayants droit". 
                                Cliquez sur l'ayant droit concerné puis sur "Modifier".
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="faq-category" data-category="documents">
                <h2><i class="bi bi-file-earmark-pdf"></i> Documents</h2>
                <div class="accordion" id="accordionDocuments">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq11">
                                Quels formats de fichiers sont acceptés ?
                            </button>
                        </h2>
                        <div id="faq11" class="accordion-collapse collapse show" data-bs-parent="#accordionDocuments">
                            <div class="accordion-body">
                                Les formats acceptés sont :
                                <ul>
                                    <li>PDF (taille maximale : 10 MB)</li>
                                    <li>Images JPG/PNG (taille maximale : 5 MB)</li>
                                </ul>
                                Les fichiers sont vérifiés pour leur type réel (MIME type) pour garantir la sécurité.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq12">
                                Mes documents sont-ils sécurisés ?
                            </button>
                        </h2>
                        <div id="faq12" class="accordion-collapse collapse" data-bs-parent="#accordionDocuments">
                            <div class="accordion-body">
                                Oui, tous les documents sont stockés de manière sécurisée :
                                <ul>
                                    <li>Stockage privé (non accessible publiquement)</li>
                                    <li>Contrôle d'accès par permissions</li>
                                    <li>Checksum pour vérifier l'intégrité</li>
                                    <li>Audit des téléchargements</li>
                                </ul>
                                Seuls les utilisateurs autorisés peuvent accéder aux documents selon leur rôle.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sécurité -->
            <div class="faq-category" data-category="securite">
                <h2><i class="bi bi-shield-lock"></i> Sécurité et Confidentialité</h2>
                <div class="accordion" id="accordionSecurite">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq13">
                                Mes données sont-elles protégées ?
                            </button>
                        </h2>
                        <div id="faq13" class="accordion-collapse collapse show" data-bs-parent="#accordionSecurite">
                            <div class="accordion-body">
                                Oui, PROTO PLUS respecte les normes de sécurité les plus strictes :
                                <ul>
                                    <li>Chiffrement HTTPS pour toutes les communications</li>
                                    <li>Mots de passe hashés (bcrypt)</li>
                                    <li>Protection CSRF sur toutes les actions</li>
                                    <li>Cloisonnement des données (vous ne voyez que vos données)</li>
                                    <li>Conformité RGPD</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq14">
                                Que faire si j'ai oublié mon mot de passe ?
                            </button>
                        </h2>
                        <div id="faq14" class="accordion-collapse collapse" data-bs-parent="#accordionSecurite">
                            <div class="accordion-body">
                                Contactez le service DSI pour réinitialiser votre mot de passe. 
                                Pour des raisons de sécurité, la réinitialisation se fait uniquement par l'administrateur système.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq15">
                                Qui peut voir mes demandes ?
                            </button>
                        </h2>
                        <div id="faq15" class="accordion-collapse collapse" data-bs-parent="#accordionSecurite">
                            <div class="accordion-body">
                                <ul>
                                    <li><strong>Vous</strong> : Vous voyez toutes vos demandes</li>
                                    <li><strong>Agents du Protocole</strong> : Voient toutes les demandes pour instruction</li>
                                    <li><strong>Validateurs</strong> : Voient uniquement les demandes à valider selon leur niveau</li>
                                    <li><strong>Autres fonctionnaires</strong> : Ne voient pas vos demandes</li>
                                </ul>
                                Le système respecte strictement le principe du cloisonnement des données.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact -->
            <div class="contact-box">
                <h3 class="text-center mb-4"><i class="bi bi-envelope"></i> Besoin d'aide supplémentaire ?</h3>
                <p class="text-center mb-4">
                    Si vous ne trouvez pas la réponse à votre question, n'hésitez pas à contacter le support.
                </p>
                <div class="row text-center">
                    <div class="col-md-6 mb-3">
                        <i class="bi bi-envelope fs-4 text-primary"></i>
                        <p class="mb-0"><strong>Email</strong></p>
                        <a href="mailto:support@ceeac.org">support@ceeac.org</a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <i class="bi bi-book fs-4 text-primary"></i>
                        <p class="mb-0"><strong>Documentation</strong></p>
                        <a href="{{ route('documentation') }}">Consulter la documentation complète</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="mb-0">
                        &copy; {{ date('Y') }} Commission de la CEEAC - PROTO PLUS. Tous droits réservés.
                    </p>
                    <p class="text-muted small mt-2">
                        <a href="{{ route('home') }}" class="text-white-50">Accueil</a> | 
                        <a href="{{ route('documentation') }}" class="text-white-50">Documentation</a> | 
                        <a href="{{ route('faq') }}" class="text-white-50">FAQ</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Search Functionality -->
    <script>
        document.getElementById('faqSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const categories = document.querySelectorAll('.faq-category');
            
            categories.forEach(category => {
                const questions = category.querySelectorAll('.accordion-item');
                let hasMatch = false;
                
                questions.forEach(question => {
                    const questionText = question.textContent.toLowerCase();
                    if (questionText.includes(searchTerm)) {
                        question.style.display = '';
                        hasMatch = true;
                    } else {
                        question.style.display = 'none';
                    }
                });
                
                category.style.display = hasMatch ? '' : 'none';
            });
        });
    </script>
</body>
</html>


