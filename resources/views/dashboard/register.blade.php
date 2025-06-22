<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Usuario</title>
    <!-- Enlaza tu CSS -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        /* Contenedor principal similar al login */
.main-container {
    margin: 20px auto;
    width: 420px;
    background-color: #d3d3d3;
    padding: 20px;
    text-align: center;
    border-radius: 10px;
}

/* Títulos */
.main-container h2 {
    font-size: 24px;
    margin-bottom: 30px;
}

/* Grupos de formulario */
.form-group {
    width: 90%;
    text-align: left;
    margin-bottom: 15px;
    margin-left: auto;
    margin-right: auto;
}

.form-group label {
    font-size: 18px;
    display: block;
    margin-bottom: 5px;
}

.form-group input,
.form-group select {
    width: 97%;
    height: 35px;
    font-size: 16px;
    padding: 5px;
    border-radius: 5px;
    border: 1px solid #999;
}

/* Botón */
button.submit-button {
    width: 40%;
    height: 40px;
    font-size: 18px;
    margin-top: 5px;
    cursor: pointer;
}

/* Enlace “Ya registrado?” */
.login-link {
    display: inline-block;
    margin-top: 10px;
    text-decoration: underline;
    font-size: 14px;
    color: #333;
}

.login-link:hover {
    color: #000;
}

/* Opcional: ajusta colores de x-primary-button si hace falta */
/* Breeze suele usar bg-indigo-600, pero aquí mantenemos lo suyo */

    </style>
</head>
<body>
    <header style="margin-bottom: 20px; text-align: center;">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" style="height: 50px;">
    </header>

    <div class="main-container">
        <h2>Registrar Usuario</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="form-group">
                <x-input-label for="name" :value="__('Nombre')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text"
                              name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div>
            
            <!-- Name -->
            <div class="form-group">
                <x-input-label for="name" :value="__('Nombre completo')" />
                <x-text-input id="name" class="block mt-1 w-full"
                            type="text" name="name" value="{{ old('name') }}" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Username -->
            <div class="form-group">
                <x-input-label for="username" :value="__('Nombre de Usuario')" />
                <x-text-input id="username" class="block mt-1 w-full"
                            type="text" name="username" value="{{ old('username') }}" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>


            <!-- Email Address -->
            <div class="form-group">
                <x-input-label for="email" :value="__('Correo')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email"
                              name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="form-group">
                <x-input-label for="password" :value="__('Contraseña')" />
                <x-text-input id="password" class="block mt-1 w-full"
                              type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full"
                              type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Rol -->
            <div class="form-group">
                <x-input-label for="rol" :value="__('Rol')" />
                <select id="rol" name="rol" required class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="" disabled {{ old('rol') ? '' : 'selected' }}>Seleccione un rol</option>
                    <option value="admin" {{ old('rol') === 'admin' ? 'selected' : '' }}>Administrador</option>
                    <option value="usuario" {{ old('rol') === 'usuario' ? 'selected' : '' }}>Usuario</option>
                </select>
                <x-input-error :messages="$errors->get('rol')" class="mt-2" />
            </div>

            <!-- Botón Registrar -->
            <div style="text-align: center; margin-top: 20px;">
                <button type="submit" class="submit-button x-primary-button">
                    {{ __('Registrar') }}
                </button>
            </div>

            <!-- Enlace a login -->
            <div style="text-align: center;">
                <a href="{{ route('login') }}" class="login-link">
                    {{ __('¿Ya estás registrado? Iniciar Sesión') }}
                </a>
            </div>
        </form>
    </div>

    <footer style="text-align: center; margin-top: 30px;">
        <h2>© 2025 Lorman S.A.S</h2>
    </footer>
</body>
</html>
