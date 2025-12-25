<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentation - PROTO PLUS | CEEAC</title>
    <meta name="description" content="Documentation complète de l'application PROTO PLUS pour la gestion des services protocolaires">
    
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

        .doc-header {
            background: linear-gradient(135deg, var(--ceeac-primary) 0%, var(--ceeac-secondary) 100%);
            color: white;
            padding: 60px 0 40px;
        }

        .doc-sidebar {
            position: sticky;
            top: 100px;
            max-height: calc(100vh - 120px);
            overflow-y: auto;
        }

        .doc-sidebar .nav-link {
            color: var(--ceeac-dark);
            padding: 0.5rem 1rem;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .doc-sidebar .nav-link:hover,
        .doc-sidebar .nav-link.active {
            background: var(--ceeac-light);
            border-left-color: var(--ceeac-primary);
            color: var(--ceeac-primary);
        }

        .doc-content {
            padding: 2rem 0;
        }

        .doc-section {
            margin-bottom: 3rem;
            scroll-margin-top: 100px;
        }

        .doc-section h2 {
            color: var(--ceeac-primary);
            border-bottom: 2px solid var(--ceeac-secondary);
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .doc-section h3 {
            color: var(--ceeac-secondary);
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        .code-block {
            background: #f4f4f4;
            border-left: 4px solid var(--ceeac-primary);
            padding: 1rem;
            border-radius: 4px;
            overflow-x: auto;
        }

        .feature-box {
            background: var(--ceeac-light);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .feature-box h4 {
            color: var(--ceeac-primary);
            margin-bottom: 0.5rem;
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
                        <a class="nav-link active" href="{{ route('documentation') }}">Documentation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('faq') }}">FAQ</a>
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
    <div class="doc-header">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Documentation PROTO PLUS</h1>
            <p class="lead">Guide complet pour utiliser l'application de gestion des services protocolaires</p>
        </div>
    </div>

    <!-- Content -->
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="doc-sidebar">
                    <nav class="nav flex-column">
                        <a class="nav-link" href="#introduction">Introduction</a>
                        <a class="nav-link" href="#premiers-pas">Premiers Pas</a>
                        <a class="nav-link" href="#demandes">Gestion des Demandes</a>
                        <a class="nav-link" href="#ayants-droit">Ayants Droit</a>
                        <a class="nav-link" href="#workflow">Workflow de Validation</a>
                        <a class="nav-link" href="#documents">Documents</a>
                        <a class="nav-link" href="#dashboard">Tableau de Bord</a>
                        <a class="nav-link" href="#roles">Rôles et Permissions</a>
                        <a class="nav-link" href="#securite">Sécurité</a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="doc-content">
                    <!-- Introduction -->
                    <section id="introduction" class="doc-section">
                        <h2><i class="bi bi-info-circle"></i> Introduction</h2>
                        <p>
                            PROTO PLUS est l'application de gestion complète des services protocolaires de la Commission de la CEEAC. 
                            Elle permet de gérer l'ensemble des actes protocolaires (visas diplomatiques, cartes diplomatiques, franchises douanières, etc.) 
                            de manière sécurisée, traçable et conforme.
                        </p>
                        <div class="feature-box">
                            <h4><i class="bi bi-check-circle"></i> Objectifs</h4>
                            <ul>
                                <li>Simplifier et accélérer le traitement des demandes protocolaires</li>
                                <li>Assurer la traçabilité complète de tous les actes</li>
                                <li>Garantir la conformité réglementaire</li>
                                <li>Améliorer la communication entre les différents acteurs</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Premiers Pas -->
                    <section id="premiers-pas" class="doc-section">
                        <h2><i class="bi bi-play-circle"></i> Premiers Pas</h2>
                        <h3>Connexion</h3>
                        <p>
                            Pour accéder à PROTO PLUS, vous devez disposer d'un compte utilisateur créé par l'administrateur système.
                        </p>
                        <ol>
                            <li>Rendez-vous sur la page de connexion</li>
                            <li>Entrez votre adresse email professionnelle</li>
                            <li>Entrez votre mot de passe</li>
                            <li>Cliquez sur "Se connecter"</li>
                        </ol>
                        <h3>Tableau de Bord</h3>
                        <p>
                            Après connexion, vous accédez à votre tableau de bord personnalisé selon votre rôle :
                        </p>
                        <ul>
                            <li><strong>Fonctionnaires</strong> : Vue de leurs demandes et statistiques personnelles</li>
                            <li><strong>Agents du Protocole</strong> : Vue globale avec toutes les demandes</li>
                            <li><strong>Chefs de Service / Directeurs</strong> : Vue globale + demandes à valider</li>
                        </ul>
                    </section>

                    <!-- Gestion des Demandes -->
                    <section id="demandes" class="doc-section">
                        <h2><i class="bi bi-file-earmark-text"></i> Gestion des Demandes</h2>
                        <h3>Créer une Demande</h3>
                        <ol>
                            <li>Cliquez sur "Mes demandes" dans le menu</li>
                            <li>Cliquez sur "Nouvelle demande"</li>
                            <li>Remplissez le formulaire :
                                <ul>
                                    <li>Type de demande (visa, carte diplomatique, etc.)</li>
                                    <li>Motif de la demande</li>
                                    <li>Date de départ prévue</li>
                                    <li>Pays de destination</li>
                                    <li>Priorité (normale ou urgente)</li>
                                </ul>
                            </li>
                            <li>Sélectionnez les bénéficiaires (vous-même ou vos ayants droit)</li>
                            <li>Joignez les documents requis</li>
                            <li>Cliquez sur "Enregistrer en brouillon" ou "Soumettre"</li>
                        </ol>
                        <h3>Modifier une Demande</h3>
                        <p>
                            Vous pouvez modifier une demande uniquement si elle est en statut "Brouillon". 
                            Une fois soumise, la demande entre dans le circuit de validation et ne peut plus être modifiée.
                        </p>
                        <h3>Soumettre une Demande</h3>
                        <p>
                            Lorsque vous soumettez une demande :
                        </p>
                        <ul>
                            <li>Un numéro de référence unique est généré</li>
                            <li>Une instance de workflow est créée</li>
                            <li>La demande est transmise au premier validateur</li>
                            <li>Vous recevez une notification de confirmation</li>
                        </ul>
                    </section>

                    <!-- Ayants Droit -->
                    <section id="ayants-droit" class="doc-section">
                        <h2><i class="bi bi-people"></i> Gestion des Ayants Droit</h2>
                        <p>
                            Les ayants droit sont les personnes à charge (conjoint, enfants) pour lesquelles vous pouvez faire des demandes protocolaires.
                        </p>
                        <h3>Déclarer un Ayant Droit</h3>
                        <ol>
                            <li>Allez dans "Mes ayants droit"</li>
                            <li>Cliquez sur "Ajouter un ayant droit"</li>
                            <li>Remplissez les informations :
                                <ul>
                                    <li>Civilité, nom, prénom</li>
                                    <li>Date et lieu de naissance</li>
                                    <li>Lien familial (conjoint, enfant, autre)</li>
                                    <li>Nationalité</li>
                                    <li>Numéro de passeport (si applicable)</li>
                                </ul>
                            </li>
                            <li>Cliquez sur "Enregistrer"</li>
                        </ol>
                        <p class="alert alert-info">
                            <i class="bi bi-info-circle"></i> <strong>Important</strong> : Vous devez déclarer vos ayants droit avant de pouvoir les inclure dans une demande.
                        </p>
                    </section>

                    <!-- Workflow -->
                    <section id="workflow" class="doc-section">
                        <h2><i class="bi bi-diagram-3"></i> Workflow de Validation</h2>
                        <p>
                            Le workflow de validation suit un circuit hiérarchique standard :
                        </p>
                        <ol>
                            <li><strong>Instruction</strong> : Agent du Protocole vérifie la complétude</li>
                            <li><strong>Validation Niveau 1</strong> : Chef de Service valide</li>
                            <li><strong>Validation Niveau 2</strong> : Directeur du Protocole valide</li>
                            <li><strong>Validation Niveau 3</strong> : Secrétaire Général (si requis)</li>
                        </ol>
                        <h3>Valider une Demande</h3>
                        <p>Si vous êtes validateur :</p>
                        <ol>
                            <li>Allez dans "Workflow" → "Demandes à valider"</li>
                            <li>Sélectionnez une demande</li>
                            <li>Consultez les informations et documents</li>
                            <li>Prenez une décision :
                                <ul>
                                    <li><strong>Valider</strong> : Passe à l'étape suivante</li>
                                    <li><strong>Rejeter</strong> : Rejette la demande (commentaire obligatoire)</li>
                                    <li><strong>Retour pour correction</strong> : Retourne au demandeur (commentaire obligatoire)</li>
                                </ul>
                            </li>
                        </ol>
                    </section>

                    <!-- Documents -->
                    <section id="documents" class="doc-section">
                        <h2><i class="bi bi-file-earmark-pdf"></i> Gestion des Documents</h2>
                        <h3>Types de Documents Acceptés</h3>
                        <ul>
                            <li>PDF (taille max : 10 MB)</li>
                            <li>Images JPG/PNG (taille max : 5 MB)</li>
                        </ul>
                        <h3>Upload de Documents</h3>
                        <ol>
                            <li>Lors de la création ou modification d'une demande</li>
                            <li>Cliquez sur "Ajouter un document"</li>
                            <li>Sélectionnez le type de document</li>
                            <li>Choisissez le fichier</li>
                            <li>Cliquez sur "Téléverser"</li>
                        </ol>
                        <p class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> <strong>Attention</strong> : Les documents sont stockés de manière sécurisée. 
                            Seuls les utilisateurs autorisés peuvent y accéder.
                        </p>
                    </section>

                    <!-- Dashboard -->
                    <section id="dashboard" class="doc-section">
                        <h2><i class="bi bi-graph-up"></i> Tableau de Bord</h2>
                        <p>
                            Le tableau de bord affiche des informations personnalisées selon votre rôle.
                        </p>
                        <h3>Pour les Fonctionnaires</h3>
                        <ul>
                            <li>Statistiques de vos demandes (total, en cours, validées, rejetées)</li>
                            <li>Graphique de répartition par type</li>
                            <li>Liste de vos dernières demandes</li>
                        </ul>
                        <h3>Pour les Agents et Directeurs</h3>
                        <ul>
                            <li>Statistiques globales avec filtres par période</li>
                            <li>Graphiques d'évolution mensuelle</li>
                            <li>Répartition par type de demande</li>
                            <li>Dernières activités</li>
                        </ul>
                        <h3>Exports</h3>
                        <p>
                            Les utilisateurs avec permission peuvent exporter les données :
                        </p>
                        <ul>
                            <li><strong>Excel</strong> : Format .xlsx pour analyse</li>
                            <li><strong>PDF</strong> : Format .pdf pour archivage</li>
                        </ul>
                    </section>

                    <!-- Rôles -->
                    <section id="roles" class="doc-section">
                        <h2><i class="bi bi-person-badge"></i> Rôles et Permissions</h2>
                        <h3>Rôles Disponibles</h3>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="feature-box">
                                    <h4>Fonctionnaire</h4>
                                    <p>Créer et suivre ses demandes, gérer ses ayants droit</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-box">
                                    <h4>Agent du Protocole</h4>
                                    <p>Instruire les demandes, voir toutes les demandes</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-box">
                                    <h4>Chef de Service</h4>
                                    <p>Validation niveau 1, voir toutes les demandes</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-box">
                                    <h4>Directeur du Protocole</h4>
                                    <p>Validation niveau 2, accès aux documents sensibles</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-box">
                                    <h4>Secrétaire Général</h4>
                                    <p>Validation niveau 3, accès complet</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-box">
                                    <h4>Directeur SI</h4>
                                    <p>Administration technique, gestion des utilisateurs</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Sécurité -->
                    <section id="securite" class="doc-section">
                        <h2><i class="bi bi-shield-lock"></i> Sécurité</h2>
                        <h3>Authentification</h3>
                        <ul>
                            <li>Connexion sécurisée par session</li>
                            <li>Mots de passe hashés (bcrypt)</li>
                            <li>Protection CSRF sur toutes les actions</li>
                        </ul>
                        <h3>Autorisations</h3>
                        <ul>
                            <li>Système RBAC (Rôle-Based Access Control)</li>
                            <li>Permissions granulaires par domaine</li>
                            <li>Cloisonnement des données (fonctionnaires voient uniquement leurs données)</li>
                        </ul>
                        <h3>Audit et Traçabilité</h3>
                        <ul>
                            <li>Toutes les actions critiques sont journalisées</li>
                            <li>Historique complet de chaque demande</li>
                            <li>Logs d'audit consultables par les administrateurs</li>
                        </ul>
                        <h3>Données Personnelles</h3>
                        <p>
                            PROTO PLUS respecte le RGPD :
                        </p>
                        <ul>
                            <li>Minimisation des données collectées</li>
                            <li>Droits d'accès, rectification et suppression</li>
                            <li>Chiffrement des données sensibles</li>
                            <li>Conservation limitée dans le temps</li>
                        </ul>
                    </section>
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
    
    <!-- Smooth Scroll -->
    <script>
        document.querySelectorAll('.doc-sidebar .nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const target = document.querySelector(targetId);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    // Update active state
                    document.querySelectorAll('.doc-sidebar .nav-link').forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });

        // Update active link on scroll
        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('.doc-section');
            const navLinks = document.querySelectorAll('.doc-sidebar .nav-link');
            
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (window.pageYOffset >= sectionTop - 150) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>


