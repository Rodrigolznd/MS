@if (!Auth::check() || Auth::user()->rol !== 'usuario')
    @php abort(403, 'Acceso denegado: solo usuarios'); @endphp
@endif

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
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
    <!-- Header Clientes -->
    <header>
        <div class="menu">
            <div class="logo-container">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo">
  
            </div>
            <div>
            <nav>
                <a href="{{ route('clientes.usuario') }}" class="link" style="background-color: rgb(255, 255, 255);">
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
                Registrar Cliente <img src="{{ asset('img/registrar.png') }}" width="30" alt="Registrar">
            </a>
            <div class="separator"></div>
            <form method="GET" action="{{ route('clientes.index') }}" style="align-items: center; gap: 4px; margin-left: 10px;">
                <input type="text" name="buscar" placeholder="Buscar cliente" value="{{ request('buscar') }}" style="padding: 7px; border: 2px solid #000; border-radius: 4px;">
                <button type="submit" style="background: none; border: none; cursor: pointer;">
                    <img src="{{ asset('img/lupa.png') }}" width="25" alt="buscar_cliente" class="img-lupa">
                </button>
            </form>
        </div>

        <table class="custom-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Estado</th>
                    <th>Fecha Registro</th>
                    <th>Creado por</th>
                    <th>Actualizado por</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $cliente)
                    <tr id="fila-{{ $cliente->id }}">
                        <td>{{ $cliente->nombre }}</td>
                        <td>{{ $cliente->email }}</td>
                        <td>{{ $cliente->telefono }}</td>
                        <td>{{ $cliente->direccion }}</td>
                        <td>{{ $cliente->estado ? 'Activo' : 'Inactivo' }}</td>
                        <td>{{ $cliente->created_at->format('Y-m-d') }}</td>
                        <td>{{ $cliente->creadoPor?->name ?? '—' }}</td>
                        <td>{{ $cliente->actualizadoPor?->name ?? '—' }}</td>
                        <td>
                            <!-- Editar -->
                            <button onclick="abrirModalEditar({{ $cliente }})" style="background: none; border: none;">
                                <img src="{{ asset('img/editar.png') }}" alt="Editar" width="24">
                            </button>   
                            <!-- Eliminar -->
                            <button onclick="eliminarCliente({{ $cliente->id }})" style="background: none; border: none;">
                                <img src="{{ asset('img/eliminar.png') }}" alt="Eliminar" width="24">
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Registrar -->
    <div id="registerModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" id="closeModalBtn">&times;</span>
            </div>
            <h2>Registrar Cliente</h2>
            <form action="{{ route('clientes.store') }}" method="POST">
                @csrf
                <label>Nombre</label>
                <input type="text" name="nombre" required>
                <label>Email</label>
                <input type="email" name="email">
                <label>Teléfono</label>
                <input type="text" name="telefono">
                <label>Dirección</label>
                <input type="text" name="direccion">
                <label>Estado</label>
                <select name="estado">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
                <div class="buttons">
                    <button type="submit">Registrar</button>
                    <button type="button" id="cancelModalBtn">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar -->
    <div id="modalEditar" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" id="closeEditarBtn">&times;</span>
            </div>
            <h2>Editar Cliente</h2>
            <form id="formEditar">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-id" name="id">
                <label>Nombre</label>
                <input type="text" id="edit-nombre" name="nombre">
                <label>Email</label>
                <input type="email" id="edit-email" name="email">
                <label>Teléfono</label>
                <input type="text" id="edit-telefono" name="telefono">
                <label>Dirección</label>
                <input type="text" id="edit-direccion" name="direccion">
                <label>Estado</label>
                <select id="edit-estado" name="estado">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
                <div class="buttons">
                    <button type="submit">Guardar</button>
                    <button type="button" onclick="cerrarModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <footer>
        © {{ date('Y') }} Lorman S.A.S
    </footer>

    <script>
    document.getElementById('openModalBtn').onclick = function(event) {
        event.preventDefault(); // Evita que el enlace recargue o salte a #
        document.getElementById('registerModal').style.display = 'block';
    };

    document.getElementById('closeModalBtn').onclick =
    document.getElementById('cancelModalBtn').onclick = function() {
        document.getElementById('registerModal').style.display = 'none';
    };


    // Abrir modal editar con datos
    function abrirModalEditar(cliente) {
        document.getElementById('edit-id').value = cliente.id;
        document.getElementById('edit-nombre').value = cliente.nombre;
        document.getElementById('edit-email').value = cliente.email;
        document.getElementById('edit-telefono').value = cliente.telefono;
        document.getElementById('edit-direccion').value = cliente.direccion;
        document.getElementById('edit-estado').value = cliente.estado;
        document.getElementById('modalEditar').style.display = 'block';
    }

    // Cerrar modal editar
    document.getElementById('closeEditarBtn').onclick = cerrarModal;
    function cerrarModal() {
        document.getElementById('modalEditar').style.display = 'none';
    }

    // Enviar edición (corregido fetch y backticks en URL)
    document.getElementById('formEditar').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('edit-id').value;
        const formData = new FormData(this);

        fetch(`/clientes/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-HTTP-Method-Override': 'PUT'
            },
            body: formData
        }).then(res => {
            if (res.ok) {
                alert('Cliente actualizado');
                location.reload();
            } else {
                alert('Error al actualizar');
            }
        });
    });

    // Eliminar cliente (corregido backticks y selección de fila)
    function eliminarCliente(id) {
        if (!confirm('¿Deseas eliminar este cliente?')) return;

        fetch(`/clientes/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => {
            if (res.ok) {
                document.getElementById(`fila-${id}`).remove();
            } else {
                alert('Error al eliminar');
            }
        });
    }
</script>

</body>
</html>