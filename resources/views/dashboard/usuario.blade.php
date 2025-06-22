@php
    use Illuminate\Support\Facades\Auth;
@endphp

@if (!Auth::check() || Auth::user()->rol !== 'usuario')
    @php abort(403, 'Acceso denegado: solo usuarios'); @endphp
@endif
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de usuario</title>
    <link rel="stylesheet" href="css/styles.css">
</head><body>
    <header>
        <div class="menu">
            <div class="logo-container">
                <img src="img/logo.png" alt="Logo" class="logo">
            </div>
            <div>
                <nav>
                <a href="{{ route('clientes.usuario') }}" class="link" style="background-color: rgb(255, 255, 255);">
                    <img src="{{ asset('img/clientes.png') }}" alt="Clientes" class="icon">
                    <span class="title">Clientes</span>
                </a>
                </div>
                <a href="inventario.php" class="link" style="background-color: rgb(192, 192, 192);">
                    <img src="img/inventario.png" alt="Inventario" class="icon">
                    <span class="title">Inventario</span>
                </a>
                <a href="facturacion.php" class="link" style="background-color: rgb(192, 192, 192);">
                    <img src="img/factura.png" alt="Facturación" class="icon">
                    <span class="title">Facturación</span>
                </a>
                <span>👤 {{ Auth::user()->name }}</span>
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
</html>
</body>

