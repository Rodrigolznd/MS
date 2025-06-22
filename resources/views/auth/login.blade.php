<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .main-container {
            margin: 20px auto;
            width: 420px;
            height: 340px;
            overflow-y: auto;
            background-color: #d3d3d3;
            padding: 20px;
            text-align: center;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .form-group {
            width: 90%;
            text-align: left;
            margin-bottom: 15px;
        }

        label {
            font-size: 18px;
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 97%;
            height: 35px;
            font-size: 16px;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #999;
        }

        button {
            width: 40%;
            height: 40px;
            font-size: 18px;
            margin-top: 5px;
        }

        footer {
            width: 100%;
            height: 100%;
            text-align: center;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <div class="menu">
            <div class="logo-container">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo">
            </div>
        </div>
    </header>

    <div class="main-container">
        <h2>Iniciar Sesión</h2>

        <!-- Formulario de login funcional -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" name="email" id="email" placeholder="Correo" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="Contraseña" required>
            </div>

            <button type="submit">Ingresar</button>
        </form>

        @if ($errors->any())
            <div style="color: red; margin-top: 10px;">
                {{ $errors->first() }}
            </div>
        @endif
    </div>

    <footer>
        <h2>© 2025 Lorman s.a.s</h2>
    </footer>
</body>
</html>
