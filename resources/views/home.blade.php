<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PROTO PLUS - Gestion des Services du Protocole | CEEAC</title>
    <meta name="description" content="Application de gestion complète des services protocolaires de la Commission de la CEEAC">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --ceeac-primary: #003366;
            --ceeac-secondary: #0066CC;
            --ceeac-accent: #FF6600;
            --ceeac-success: #28a745;
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

        .hero {
            background: linear-gradient(135deg, var(--ceeac-primary) 0%, var(--ceeac-secondary) 100%);
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }

        .btn-hero {
            padding: 0.75rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-hero-primary {
            background: white;
            color: var(--ceeac-primary);
            border: none;
        }

        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            background: var(--ceeac-light);
        }

        .btn-hero-outline {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-hero-outline:hover {
            background: white;
            color: var(--ceeac-primary);
            transform: translateY(-2px);
        }

        .features {
            padding: 80px 0;
            background: white;
        }

        .feature-card {
            padding: 2rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid #e9ecef;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-color: var(--ceeac-secondary);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--ceeac-primary), var(--ceeac-secondary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: white;
            font-size: 1.5rem;
        }

        .stats {
            background: var(--ceeac-light);
            padding: 60px 0;
        }

        .stat-item {
            text-align: center;
            padding: 2rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--ceeac-primary);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1.1rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .cta-section {
            background: linear-gradient(135deg, var(--ceeac-primary) 0%, var(--ceeac-secondary) 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cta-section p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }

        .footer {
            background: var(--ceeac-dark);
            color: white;
            padding: 40px 0 20px;
        }

        .footer h5 {
            color: var(--ceeac-accent);
            margin-bottom: 1rem;
        }

        .footer a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: white;
        }

        .badge-ceeac {
            background: var(--ceeac-accent);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.1rem;
            }
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
                        <a class="nav-link" href="#features">Fonctionnalités</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#avantages">Avantages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#stats">Statistiques</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('documentation') }}">Documentation</a>
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <span class="badge-ceeac mb-3 d-inline-block">
                        <i class="bi bi-building"></i> Commission de la CEEAC
                    </span>
                    <h1>Gestion Complète des Services du Protocole</h1>
                    <p>
                        Simplifiez et optimisez la gestion de tous vos actes protocolaires. 
                        Une solution moderne, sécurisée et conforme pour la Commission de la CEEAC.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('login') }}" class="btn btn-hero btn-hero-primary">
                            <i class="bi bi-box-arrow-in-right"></i> Accéder à l'application
                        </a>
                        <a href="#features" class="btn btn-hero btn-hero-outline">
                            <i class="bi bi-info-circle"></i> En savoir plus
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center mt-5 mt-lg-0">
                    <div class="position-relative">
                        <div style="background: rgba(255,255,255,0.1); border-radius: 20px; padding: 3rem; backdrop-filter: blur(10px);">
                            <i class="bi bi-file-earmark-text" style="font-size: 8rem; opacity: 0.8;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold mb-3">Fonctionnalités Principales</h2>
                <p class="lead text-muted">Tout ce dont vous avez besoin pour gérer efficacement vos services protocolaires</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <h4>Gestion des Demandes</h4>
                        <p class="text-muted">
                            Créez, suivez et gérez toutes vos demandes protocolaires (visas, cartes diplomatiques, franchises douanières) en un seul endroit.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-diagram-3"></i>
                        </div>
                        <h4>Workflow de Validation</h4>
                        <p class="text-muted">
                            Circuit de validation hiérarchique automatisé avec notifications et suivi en temps réel de chaque étape.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <h4>Gestion des Ayants Droit</h4>
                        <p class="text-muted">
                            Déclarez et gérez facilement les ayants droit (conjoints, enfants) pour chaque fonctionnaire.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </div>
                        <h4>Documents Sécurisés</h4>
                        <p class="text-muted">
                            Upload, stockage sécurisé et gestion des documents avec contrôle d'accès et traçabilité complète.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h4>Tableaux de Bord</h4>
                        <p class="text-muted">
                            Tableaux de bord personnalisés avec KPIs, graphiques et rapports pour un suivi optimal.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <h4>Sécurité & Conformité</h4>
                        <p class="text-muted">
                            RBAC, audit logs, chiffrement et conformité RGPD pour une sécurité maximale des données.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="stats">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Sécurisé</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Disponible</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Conforme</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">0</div>
                        <div class="stat-label">Paperasse</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Advantages Section -->
    <section id="avantages" class="features" style="background: var(--ceeac-light);">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold mb-3">Pourquoi PROTO PLUS ?</h2>
                <p class="lead text-muted">Les avantages d'une solution moderne et dédiée</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="feature-icon" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Gain de Temps</h5>
                            <p class="text-muted">
                                Automatisation des processus et réduction drastique du temps de traitement des demandes.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="feature-icon" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Traçabilité Complète</h5>
                            <p class="text-muted">
                                Historique détaillé de toutes les actions avec audit logs pour une transparence totale.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="feature-icon" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Accessibilité</h5>
                            <p class="text-muted">
                                Accès depuis n'importe où, à tout moment. Interface intuitive et responsive.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="feature-icon" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Conformité Réglementaire</h5>
                            <p class="text-muted">
                                Respect des normes et réglementations en vigueur avec gestion des permissions granulaires.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Prêt à simplifier vos services protocolaires ?</h2>
            <p>Rejoignez les fonctionnaires de la CEEAC qui utilisent déjà PROTO PLUS</p>
            <a href="{{ route('login') }}" class="btn btn-hero btn-hero-primary btn-lg">
                <i class="bi bi-box-arrow-in-right"></i> Accéder maintenant
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="bi bi-shield-check"></i> PROTO PLUS</h5>
                    <p class="text-muted">
                        Application de gestion des services protocolaires de la Commission de la CEEAC.
                    </p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Liens Utiles</h5>
                    <ul class="list-unstyled">
                        <li><a href="#features">Fonctionnalités</a></li>
                        <li><a href="#avantages">Avantages</a></li>
                        <li><a href="{{ route('login') }}">Connexion</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Support</h5>
                    <ul class="list-unstyled">
                        <li><a href="mailto:support@ceeac.org">support@ceeac.org</a></li>
                        <li><a href="{{ route('documentation') }}">Documentation</a></li>
                        <li><a href="{{ route('faq') }}">FAQ</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="mb-0 text-muted">
                        &copy; {{ date('Y') }} Commission de la CEEAC - PROTO PLUS. Tous droits réservés.
                    </p>
                    <p class="text-muted small mt-2">
                        Usage interne uniquement - Données confidentielles
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Smooth Scroll -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>

