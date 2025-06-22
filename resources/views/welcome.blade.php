<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minimizing System</title>
    <!-- Tu CSS personalizado -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <!-- Si necesitas algo inline adicional, agrégalo aquí -->
    <style>
        /* Centrar contenido en pantalla */
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
        }
        .welcome-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            background-color: #f0f0f0; /* ajusta según tu estilo */
        }
        .welcome-box {
            background-color: #d3d3d3;
            padding: 30px 20px;
            border-radius: 10px;
            text-align: center;
            width: 90%;
            max-width: 400px;
        }
        .welcome-box h1 {
            margin-bottom: 20px;
            font-size: 24px;
        }
        .btn-welcome {
            display: inline-block;
            width: 80%;
            max-width: 200px;
            padding: 10px;
            margin: 10px 0;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            color: white;
            background-color: #4a76a8; /* azul, ajusta si quieres otro */
            transition: background-color 0.3s;
        }
        .btn-welcome:hover {
            background-color: #3b5f82;
        }
        .logo-container {
            margin-bottom: 20px;
        }
        .logo-container img {
            height: 60px;
            /* ajusta tamaño */
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <div class="welcome-box">
            <div class="logo-container">
                <img src="{{ asset('img/logo.png') }}" alt="Logo">
            </div>
            <h1>Bienvenido a Minimizing System</h1>

            @if (Route::has('login'))
                @auth
                    <!-- Si ya está autenticado, redirigir al dashboard según rol -->
                    @php
                        $rol = Auth::user()->rol;
                        if ($rol === 'admin') {
                            $url = route('admin.dashboard');
                        } elseif ($rol === 'usuario') {
                            $url = route('usuario.dashboard');
                        } else {
                            $url = url('/dashboard');
                        }
                    @endphp
                    <a href="{{ $url }}" class="btn-welcome">Ir al Panel</a>
                @else
                    <!-- No autenticado: mostrar Login y Register -->
                    <a href="{{ route('login') }}" class="btn-welcome">Iniciar Sesión</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-welcome" style="background-color: #28a745;">
                            Registrarse
                        </a>
                    @endif
                @endauth
            @endif

        </div>
    </div>
</body>
</html>
