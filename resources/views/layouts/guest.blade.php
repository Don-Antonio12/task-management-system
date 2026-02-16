<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>{{ config('app.name', 'TMS') }} - Login</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="{{ asset('build/assets/favicon.ico') }}" />
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700,700i" rel="stylesheet" />
        <style>
            .login-section {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                padding: 2rem 0;
            }
            .login-card {
                background: white;
                border-radius: 10px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
                padding: 3rem;
                max-width: 450px;
                width: 100%;
            }
            .login-card h2 {
                color: #764ba2;
                font-weight: 700;
                margin-bottom: 0.5rem;
                text-align: center;
            }
            .login-card .subtitle {
                color: #6c757d;
                text-align: center;
                margin-bottom: 2rem;
                font-size: 0.95rem;
            }
            .login-card .form-group {
                margin-bottom: 1.5rem;
            }
            .login-card label {
                color: #212529;
                font-weight: 600;
                margin-bottom: 0.5rem;
                display: block;
            }
            .login-card input {
                width: 100%;
                padding: 0.75rem;
                border: 1px solid #dee2e6;
                border-radius: 5px;
                font-size: 1rem;
                transition: border-color 0.3s;
            }
            .login-card input:focus {
                outline: none;
                border-color:#764ba2;
                box-shadow: 0 0 0 3px rgba(29, 128, 159, 0.1);
            }
            .login-card select.form-select-role {
                width: 100%;
                padding: 0.75rem;
                border: 1px solid #dee2e6;
                border-radius: 5px;
                font-size: 1rem;
                background: #fff;
                cursor: pointer;
            }
            .login-card select.form-select-role:focus {
                outline: none;
                border-color: #764ba2;
                box-shadow: 0 0 0 3px rgba(118, 75, 162, 0.15);
            }
            .login-card .form-hint {
                font-size: 0.8rem;
                color: #6c757d;
                margin-top: 0.35rem;
                margin-bottom: 0;
            }
            .login-card .btn-login {
                width: 100%;
                padding: 0.75rem;
                background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border: none;
                border-radius: 5px;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: background 0.3s;
            }
            .login-card .btn-login:hover {
                background:rgb(76, 35, 117);
            }
            .login-card .links {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 1.5rem;
                font-size: 0.9rem;
            }
            .login-card .links a {
                color: #764ba2;
                text-decoration: none;
                transition: color 0.3s;
            }
            .login-card .links a:hover {
                color:rgb(76, 35, 117);
                text-decoration: none;
            }
            .login-card .register-link {
                text-align: center;
                margin-top: 2rem;
                padding-top: 1.5rem;
                border-top: 1px solid #dee2e6;
            }
            .login-card .register-link p {
                color: #6c757d;
                margin: 0;
            }
            .login-card .register-link a {
                color: #764ba2;
                font-weight: 600;
                text-decoration: none;
            }
            .login-card .register-link a:hover {
                color: rgb(76, 35, 117);
                text-decoration: underline;
            }
            .back-home {
                position: absolute;
                top: 20px;
                left: 20px;
            }
            .back-home a {
                color: white;
                text-decoration: none;
                font-size: 0.95rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                transition: opacity 0.3s;
            }
            .back-home a:hover {
                opacity: 0.8;
            }
        </style>
    </head>
    <body id="page-top">
        <div class="login-section">
            <div class="back-home">
                <a href="{{ route('welcome') }}">
                    <i class="fas fa-arrow-left"></i> Back Home
                </a>
            </div>
            <div class="login-card">
                {{ $slot }}
            </div>
        </div>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    </body>
</html>
