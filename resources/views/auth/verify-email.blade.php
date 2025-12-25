<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérifier l'email - PROTO PLUS | CEEAC</title>
    
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
            background: linear-gradient(135deg, var(--ceeac-primary) 0%, var(--ceeac-secondary) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 2rem 0;
        }

        .auth-container {
            max-width: 500px;
            width: 100%;
            margin: 0 auto;
        }

        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .auth-header {
            background: linear-gradient(135deg, var(--ceeac-primary) 0%, var(--ceeac-secondary) 100%);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
        }

        .auth-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .auth-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }

        .auth-body {
            padding: 2.5rem 2rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--ceeac-primary) 0%, var(--ceeac-secondary) 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .btn-outline-secondary {
            border: 2px solid #6c757d;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background: #6c757d;
            color: white;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .info-text {
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .icon-large {
            font-size: 4rem;
            color: var(--ceeac-primary);
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <!-- Header -->
            <div class="auth-header">
                <h1><i class="bi bi-envelope-check"></i> Vérification de l'email</h1>
                <p>Confirmez votre adresse email</p>
            </div>

            <!-- Body -->
            <div class="auth-body text-center">
                <div class="icon-large">
                    <i class="bi bi-envelope-check"></i>
                </div>

                <p class="info-text">
                    Merci pour votre inscription ! Avant de commencer, veuillez vérifier votre adresse email 
                    en cliquant sur le lien que nous venons de vous envoyer. 
                    Si vous n'avez pas reçu l'email, nous pouvons vous en renvoyer un.
                </p>

                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> 
                        Un nouveau lien de vérification a été envoyé à l'adresse email que vous avez fournie.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-envelope-paper"></i> Renvoyer l'email de vérification
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-box-arrow-right"></i> Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
