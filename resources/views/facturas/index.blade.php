<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturación</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
            /* Estilos para el modal */
            .modal {
            display: none; /* Oculto por defecto */
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            }


            .modal-content {
                position: absolute;
                top: 100px; /* Ajusta según la posición deseada */
                left: 50%;
                transform: translateX(-50%);
                width: 400px; /* Ancho del formulario */
                background-color: rgb(195, 195, 195); /* Fondo gris */
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
                position: relative; /* Necesario para posicionar el botón de cierre */
            }

            .modal-header {
                display: flex;
                justify-content: flex-end;
            }

            .close {
                font-size: 24px;
                cursor: pointer;
                color: #333;
            }

            .modal-content label {
                display: block;
                margin-bottom: 10px;
            }

            .modal-content input,
            .modal-content select {
                width: calc(100% - 20px);
                padding: 8px;
                margin-bottom: 10px;
            }

            .modal-content .buttons {
                display: flex;
                justify-content: space-between;
            }

            .modal-content .buttons button {
                padding: 10px;
            }
    </style>
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
                <a href="{{ route('admin.usuarios') }}" class="link" style="background-color: rgb(192, 192, 192);">
                    <img src="{{ asset('img/registro.png') }}" alt="Usuarios" class="icon">
                    <span class="title">Usuarios</span>
                </a>
                <a href="{{ route('clientes.index') }}" class="link" style="background-color: rgb(192, 192, 192);">
                    <img src="{{ asset('img/clientes.png') }}" alt="Clientes" class="icon">
                    <span class="title">Clientes</span>
                </a>
                <a href="{{ route('inventario.index') }}" class="link" style="background-color: rgb(192, 192, 192);">
                    <img src="{{ asset('img/inventario.png') }}" alt="Inventario" class="icon">
                    <span class="title">Inventario</span>
                </a>
                <a href="{{ route('facturas.index') }}" class="link" style="background-color: rgb(255, 255, 255);">
                    <img src="{{ asset('img/factura.png') }}" alt="Facturación" class="icon">
                    <span class="title">Facturación</span>
                </a>
                <span>👤 {{ Auth::user()->username }}</span>
                <span>: {{ ucfirst(Auth::user()->rol) }} </span>
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
    <div class="main-container">
        @if(session('success'))
            <script>alert("{{ session('success') }}");</script>
        @endif

        <div class="acciones">
            <a href="#" id="openModalBtn">
                Generar Factura
                <img src="{{ asset('img/generarfactura.png') }}" width="30" alt="Generar">
            </a>
            <div class="separator"></div>
            <form method="GET" action="{{ route('facturas.index') }}" style="align-items: center; gap: 4px; margin-left: 10px;">
                <input type="text" name="buscar" placeholder="Buscar cliente o fecha" value="{{ request('buscar') }}"
                    style="padding: 7px; border: 2px solid #000; border-radius: 4px;">
                <button type="submit" style="padding: 8px 12px;">Buscar</button>
            </form>
        </div>
        <table class="custom-table" style="margin-top: 20px;">
    <thead>
        <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Fecha</th>
            <th>Total</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($facturas as $factura)
            <tr>
                <td>{{ $factura->id }}</td>
                <td>{{ $factura->cliente->nombre }}</td>
                <td>{{ $factura->fecha }}</td>
                <td>${{ number_format($factura->total, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('facturas.mostrar', $factura->id) }}" title="Ver factura">
                        <img src="{{ asset('img/ver.png') }}" alt="Ver" width="24">
                    </a>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No hay facturas registradas.</td></tr>
        @endforelse
    </tbody>
</table>
</div>

    

    <!-- Modal de Generación de Factura -->
    <div id="registerModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" id="closeModalBtn">&times;</span>
            </div>
            <h2>Generar Factura</h2>
            <form action="{{ route('facturas.store') }}" method="POST">
                @csrf
                <label for="cliente_id">Cliente:</label>
                <select name="cliente_id" required>
                    <option value="">-- Selecciona un cliente --</option>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                    @endforeach
                </select>

                <label for="fecha">Fecha:</label>
                <input type="date" name="fecha" required>

                <div id="productos-container">
                    <div class="producto-item">
                        <select name="productos[0][id]" required>
                            <option value="">-- Producto --</option>
                            @foreach ($productos as $producto)
                                <option value="{{ $producto->id }}">{{ $producto->nombre }} - ${{ $producto->precio }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="productos[0][cantidad]" placeholder="Cantidad" min="1" required>
                    </div>
                </div>

                <button type="button" onclick="agregarProducto()">Agregar otro producto</button>

                <div class="buttons" style="margin-top: 15px;">
                    <button type="submit">Generar Factura</button>
                    <button type="button" id="cancelModalBtn">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
    <footer>
        © {{ date('Y') }} Lorman S.A.S
    </footer>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const modal = document.getElementById("registerModal");
            const openModalBtn = document.getElementById("openModalBtn");
            const closeModalBtn = document.getElementById("closeModalBtn");
            const cancelModalBtn = document.getElementById("cancelModalBtn");

            openModalBtn.onclick = function (e) {
                e.preventDefault();
                modal.style.display = "block";
            };

            closeModalBtn.onclick = () => modal.style.display = "none";
            cancelModalBtn.onclick = () => modal.style.display = "none";

            window.onclick = function (event) {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            };
        });

        let index = 1;
        function agregarProducto() {
            const container = document.getElementById('productos-container');
            const item = document.createElement('div');
            item.className = 'producto-item';
            item.innerHTML = `
                <select name="productos[${index}][id]" required>
                    <option value="">-- Producto --</option>
                    @foreach ($productos as $producto)
                        <option value="{{ $producto->id }}">{{ $producto->nombre }} - ${{ $producto->precio }}</option>
                    @endforeach
                </select>
                <input type="number" name="productos[${index}][cantidad]" placeholder="Cantidad" min="1" required>
            `;
            container.appendChild(item);
            index++;
        }
    </script>
</body>
</html>