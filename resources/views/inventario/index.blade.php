<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .modal {
            display: none;
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
            top: 100px;
            left: 50%;
            transform: translateX(-50%);
            width: 400px;
            background-color: rgb(195, 195, 195);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
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
        .modal-content label, .modal-content input, .modal-content select, .modal-content textarea {
            display: block;
            width: calc(100% - 20px);
            margin-bottom: 10px;
            padding: 8px;
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
@if(session('success'))
<script>
    alert("{{ session('success') }}");
</script>
@endif
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
                <a href="{{ route('inventario.index') }}" class="link" style="background-color: rgb(255, 255, 255);">
                    <img src="{{ asset('img/inventario.png') }}" alt="Inventario" class="icon">
                    <span class="title">Inventario</span>
                </a>
                <a href="{{ route('facturas.index') }}" class="link" style="background-color: rgb(192, 192, 192);">
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
    <div class="acciones">
        <a href="#" id="openModalBtn">
            Registrar Producto
            <img src="{{ asset('img/registrarproducto.png') }}" width="30" alt="registrar">
        </a>
        <div class="separator"></div>
        <a href="javascript:void(0);" onclick="openReporteModal()">
            Generar reporte de ventas
            <img src="{{ asset('img/generarreporte.png') }}" width="30" alt="generarreporte">
        </a>
        <div class="separator"></div>
        <form method="GET" action="{{ route('inventario.index') }}" style="align-items: center; gap: 4px; margin-left: 10px;">
        <input type="text" name="buscar" placeholder="Buscar producto" value="{{ request('buscar') }}" style="padding: 8px; border: 2px solid #000; border-radius: 4px;">
            <button type="submit" style="background: none; border: none; cursor: pointer;" title="Buscar producto">
                <img src="{{ asset('img/lupa.png') }}" width="25" alt="buscar_producto" class="img-lupa">
            </button>
        </form>
        </div>

    <!-- Tabla de productos -->
    <table class="custom-table">
        <thead>
        <tr>
            <th>Nombre</th>
            <th>Código</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Categoría</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($productos as $producto)
            <tr>
                <td>{{ $producto->nombre }}</td>
                <td>{{ $producto->codigo }}</td>
                <td>{{ $producto->descripcion }}</td>
                <td>${{ number_format($producto->precio, 2) }}</td>
                <td>{{ $producto->categoria }}</td>
                <td>{{ $producto->estado }}</td>
                <td>
                    <!-- Botón Editar Producto -->
                    <button onclick="document.getElementById('modalEditar{{ $producto->id }}').style.display='block'" style="background: none; border: none;">
                        <img src="{{ asset('img/editar.png') }}" alt="Editar" width="24">
                    </button>

                    <!-- Botón Eliminar Producto -->
                    <form action="{{ route('inventario.destroy', $producto->id) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar este producto?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: none; border: none;">
                            <img src="{{ asset('img/eliminar.png') }}" alt="Eliminar" width="24">
                        </button>
                    </form>
                </td>
            </tr>

            <!-- Modal Editar -->
            <div id="modalEditar{{ $producto->id }}" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="close" onclick="document.getElementById('modalEditar{{ $producto->id }}').style.display='none'">&times;</span>
                    </div>
                    <h2>Editar Producto</h2>
                    <form action="{{ route('inventario.update', $producto->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <label>Nombre</label>
                        <input type="text" name="nombre" value="{{ $producto->nombre }}" required>

                        <label>Código</label>
                        <input type="text" name="codigo" value="{{ $producto->codigo }}" required>

                        <label>Stock</label>
                        <input type="number" name="stock" value="{{ $producto->stock }}" required>

                        <label>Precio</label>
                        <input type="number" step="0.01" name="precio" value="{{ $producto->precio }}" required>

                        <label>Descripción</label>
                        <textarea name="descripcion" required>{{ $producto->descripcion }}</textarea>

                        <label>Categoría</label>
                        <select name="categoria" required>
                            <option value="Electronica" @if($producto->categoria=='Electronica') selected @endif>Electrónica</option>
                            <option value="Muebles" @if($producto->categoria=='Muebles') selected @endif>Muebles</option>
                        </select>

                        <label>Estado</label>
                        <select name="estado" required>
                            <option value="activo" @if($producto->estado=='activo') selected @endif>Activo</option>
                            <option value="inactivo" @if($producto->estado=='inactivo') selected @endif>Inactivo</option>
                        </select>

                        <label>Imagen</label>
                        <input type="file" name="imagen">

                        <div class="buttons">
                            <button type="submit">Guardar cambios</button>
                            <button type="button" onclick="document.getElementById('modalEditar{{ $producto->id }}').style.display='none'">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Registrar Producto -->
<div id="registerModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close" id="closeModalBtn">&times;</span>
        </div>
        <h2>Registrar Producto</h2>
        <form action="{{ route('inventario.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label>Nombre</label>
            <input type="text" name="nombre" required>

            <label>Código</label>
            <input type="text" name="codigo" required>

            <label>Stock</label>
            <input type="number" name="stock" value="0" required>

            <label>Precio</label>
            <input type="number" step="0.01" name="precio" required>

            <label>Descripción</label>
            <textarea name="descripcion" required></textarea>

            <label>Categoría</label>
            <select name="categoria" required>
                <option value="Electronica">Electrónica</option>
                <option value="Muebles">Muebles</option>
            </select>

            <label>Estado</label>
            <select name="estado" required>
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>

            <label>Imagen</label>
            <input type="file" name="imagen">

            <div class="buttons">
                <button type="submit">Registrar</button>
                <button type="button" id="cancelModalBtn">Cancelar</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal para generar reporte -->
        <div id="reporteModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                <span class="close" id="closeReporteModalBtn">&times;</span>
                </div>
                <h2>Generar reporte de ventas</h2>
                <form method="GET" action="{{ route('reporte.ventas') }}" target="_blank">
                <label for="fecha_inicio">Fecha de Inicio:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" required>
            <label for="fecha_fin">Fecha de Fin:</label>
            <input type="date" id="fecha_fin" name="fecha_fin" required>
                <br><br>
                 <div class="buttons">
                    <button type="submit">Generar reporte</button>
                    <button type="button" onclick="closeReporteModal()">Cancelar</button>
                 </div>
            </form>
            </div>
        </div>
    </div>
<footer>
    © {{ date('Y') }} Lorman S.A.S
</footer>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const modal = document.getElementById("registerModal");
        document.getElementById("openModalBtn").onclick = function (e) {
            e.preventDefault();
            modal.style.display = "block";
        }
        document.getElementById("closeModalBtn").onclick = () => modal.style.display = "none";
        document.getElementById("cancelModalBtn").onclick = () => modal.style.display = "none";
        window.onclick = function (event) {
            if (event.target === modal) modal.style.display = "none";
        }
    });
    function openReporteModal() {
        document.getElementById('reporteModal').style.display = 'block';
    }

    function closeReporteModal() {
        document.getElementById('reporteModal').style.display = 'none';
    }

    document.addEventListener("DOMContentLoaded", function () {
        const modal = document.getElementById('reporteModal');
        const closeBtn = document.getElementById('closeReporteModalBtn');

        // Cierra al hacer clic en la X
        closeBtn.addEventListener('click', closeReporteModal);

        // Cierra al hacer clic fuera del contenido del modal
        window.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeReporteModal();
            }
        });
    });
</script>
</body>
</html>
