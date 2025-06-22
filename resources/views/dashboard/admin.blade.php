@php
    use Illuminate\Support\Facades\Auth;
@endphp

@if (!Auth::check() || Auth::user()->rol !== 'admin')
    @php abort(403, 'Acceso denegado: solo administradores'); @endphp
@endif

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    
    <!-- ✅ Ruta correcta al CSS desde /public/css -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <header>
        <div class="menu">
            <div class="logo-container">
                <a href="{{ route('dashboard.admin') }}">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo">
                </a>
            </div>
            <div>
                <nav>
                    <div>
                    <a href="{{ route('admin.usuarios') }}" class="link" id="registro-link" style="background-color: rgb(192, 192, 192);">
                        <img src="{{ asset('img/registro.png') }}" alt="Registro" class="icon">
                        <span class="title">Usuarios</span>
                    </a>
                    </div>
                    <a href="{{ route('clientes.index') }}" class="link" style="background-color: rgb(192, 192, 192);">
                        <img src="{{ asset('img/clientes.png') }}" alt="Clientes" class="icon">
                        <span class="title">Clientes</span>
                    </a>
                    <a href="{{ route('inventario.index') }}" class="link" style="background-color: rgb(192, 192, 192);">
                        <img src="{{ asset('img/inventario.png') }}" alt="Inventario" class="icon">
                        <span class="title">Inventario</span>
                    </a>
                    <a href="{{ route('facturas.index') }}" class="link" style="background-color: rgb(192, 192, 192);">
                        <img src="{{ asset('img/factura.png') }}" alt="Facturación" class="icon">
                        <span class="title">Facturación</span>
                    </a>
                    <span>👤 {{ Auth::user()->username }}</span>
                    <span>: {{ ucfirst(Auth::user()->rol) }}</span>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: blue; cursor: pointer;">
                            Cerrar sesión
                        </button>
                    </form>
                </nav>
            </div>
        </div>
    </header>

    <h4>Minimizing System</h4>

    <footer>
        © {{ date('Y') }} Lorman S.A.S
    </footer>
</body>
</html>
