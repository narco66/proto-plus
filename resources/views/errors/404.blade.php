<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Page Non Trouvée - {{ config('app.name', 'PROTO PLUS') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --ceeac-primary: #003366;
            --ceeac-secondary: #0066CC;
            --ceeac-accent: #FF6600;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .error-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            max-width: 600px;
            width: 100%;
            text-align: center;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
            background: linear-gradient(135deg, var(--ceeac-primary) 0%, var(--ceeac-secondary) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(0, 51, 102, 0.3);
        }

        .error-icon i {
            font-size: 4rem;
            color: white;
        }

        .error-code {
            font-size: 4rem;
            font-weight: 700;
            color: var(--ceeac-primary);
            margin-bottom: 1rem;
            line-height: 1;
        }

        .error-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--ceeac-dark);
            margin-bottom: 1rem;
        }

        .error-message {
            font-size: 1.1rem;
            color: #6c757d;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--ceeac-primary) 0%, var(--ceeac-secondary) 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 51, 102, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 51, 102, 0.3);
        }

        .btn-outline-primary {
            border: 2px solid var(--ceeac-primary);
            color: var(--ceeac-primary);
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: var(--ceeac-primary);
            color: white;
            transform: translateY(-2px);
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        @media (max-width: 576px) {
            .error-container {
                padding: 2rem 1.5rem;
            }

            .error-code {
                font-size: 3rem;
            }

            .error-title {
                font-size: 1.5rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="bi bi-file-earmark-x"></i>
        </div>

        <div class="error-code">404</div>
        <h1 class="error-title">Page Non Trouvée</h1>
        <p class="error-message">
            La page que vous recherchez n'existe pas ou a été déplacée.
        </p>

        <div class="action-buttons">
            <a href="{{ url()->previous() }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i>Retour
            </a>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <i class="bi bi-house me-2"></i>Tableau de bord
                </a>
            @else
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="bi bi-house me-2"></i>Page d'accueil
                </a>
            @endauth
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


