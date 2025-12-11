<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema Cofrupa - Login</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #722f37 0%, #8b4513 50%, #4a5d23 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            font-family: 'Nunito', sans-serif;
        }
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h2 {
            color: #333;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .login-header p {
            color: #666;
            margin: 0;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-control {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 12px 15px;
            font-size: 16px;
            width: 100%;
            transition: border-color 0.3s;
        }
        .form-control:focus {
            border-color: #722f37;
            box-shadow: 0 0 0 2px rgba(114, 47, 55, 0.25);
            outline: none;
        }
        .btn-login {
            background: linear-gradient(135deg, #722f37 0%, #4a5d23 100%);
            border: none;
            border-radius: 5px;
            color: white;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #8b4513 0%, #6b7d33 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(114, 47, 55, 0.4);
        }
        .btn-login:hover {
            transform: translateY(-2px);
        }
        .alert {
            border-radius: 5px;
            margin-bottom: 20px;
            padding: 10px 15px;
        }
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-logo {
            max-width: 150px;
            height: auto;
        }
        .checkbox-container {
            display: flex;
            align-items: center;
        }
        .checkbox-container input[type="checkbox"] {
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo-container">
                <img src="{{ asset('image/LOGO-sinfonfopng.png') }}" alt="Cofrupa Logo" class="login-logo">
            </div>
            <h2>Sistema Cofrupa</h2>
            <p>Ingrese sus credenciales para acceder</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" placeholder="Correo electrónico" required autocomplete="email" autofocus>
            </div>

            <div class="form-group">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                       name="password" placeholder="Contraseña" required autocomplete="current-password">
            </div>

            <div class="form-group">
                <div class="checkbox-container">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Recordarme</label>
                </div>
            </div>

            <button type="submit" class="btn-login">
                Iniciar Sesión
            </button>
        </form>
    </div>
</body>
</html>
