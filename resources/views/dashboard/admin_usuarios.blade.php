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
    <title>Gestión de Usuarios</title>
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
@if (session('mensaje'))
<script>
    alert("{{ session('mensaje') }}");
</script>
@endif
    <header>
        <div class="menu">
        <div class="logo-container">
            <a href="{{ route('dashboard.admin') }}">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo">
            </a>
        </div>
            <nav>
                <a href="{{ route('admin.usuarios') }}" class="link" style="background-color: rgb(255, 255, 255);">
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
    </header>

    <div class="main-container">
        <div class="acciones">
            <a href="#" id="openModalBtn">
                Registrar Usuario <img src="{{ asset('img/registrar.png') }}" width="30" alt="Registrar">
            </a>
            <div class="separator"></div>
        <form method="GET" action="{{ route('admin.usuarios') }}" style="align-items: center; gap: 4px; margin-left: 10px;">
                <input type="text" name="buscar" placeholder="Buscar usuario" value="{{ request('buscar') }}" style="padding: 8px; border: 2px solid #000; border-radius: 4px;">
                <button type="submit" style="background: none; border: none; cursor: pointer;" title="Buscar usuario">
                    <img src="{{ asset('img/lupa.png') }}" width="25" alt="buscar_usuario" class="img-lupa">
                </button>
            </form>
        </div>

<!-- Tabla -->
<table class="custom-table">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Usuario</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Estado</th>
            <th>Fecha Registro</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($usuarios as $usuario)
            <tr id="fila-{{ $usuario->id }}">
                <td>{{ $usuario->name }}</td>
                <td>{{ $usuario->username }}</td>
                <td>{{ $usuario->email }}</td>
                <td>{{ $usuario->rol }}</td>
                <td>{{ $usuario->estado ? 'Activo' : 'Inactivo' }}</td>
                <td>{{ $usuario->created_at->format('Y-m-d') }}</td>
                <td>
                    <!-- Editar -->
                    <button onclick="abrirModal({{ $usuario }})" style="background: none; border: none;">
                        <img src="{{ asset('img/editar.png') }}" alt="Editar" width="24">
                    </button>
                    <!-- Eliminar -->
                    <button onclick="eliminarUsuario({{ $usuario->id }})" style="background: none; border: none;">
                        <img src="{{ asset('img/eliminar.png') }}" alt="Eliminar" width="24">
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

    </div>

    <div id="registerModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" id="closeModalBtn">&times;</span>
            </div>
            <h2>Registrar Usuario</h2>
            <form action="{{ route('admin.usuarios.registrar') }}" method="POST">
                @csrf
                <label for="name">Nombre completo:</label>
                <input type="text" name="name" required>

                <label for="username">Nombre de usuario:</label>
                <input type="text" name="username" required>

                <label for="email">Correo electrónico:</label>
                <input type="email" name="email" required>

                <!-- Password -->
                <div class="form-group">
                    <x-input-label for="password" :value="__('Contraseña')" />
                    <x-text-input id="password" class="block mt-1 w-full"
                                type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                
                <label for="rol">Rol:</label>
                <select name="rol" required>
                    <option value="">Seleccione</option>
                    <option value="admin">Administrador</option>
                    <option value="usuario">Usuario</option>
                </select>

                <label for="estado">Estado:</label>
                <select name="estado" required>
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

    <!-- Modal -->
<div id="modalEditar" class="modal">
<div class="modal-content">
<div class="modal-header">
<span class="close" id="closeEditarBtn">&times;</span>
    </div>
        <h2>Editar Usuario</h2>
    <form id="formEditar">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" id="edit-id">
        <label>Nombre</label>
        <input type="text" id="edit-name" name="name"><br>
        <label>Username</label>
        <input type="text" id="edit-username" name="username"><br>
        <label>Email</label>
        <input type="email" id="edit-email" name="email"><br>
        <label>Rol</label>
        <select id="edit-rol" name="rol">
            <option value="admin">Admin</option>
            <option value="usuario">Usuario</option>
            <option value="cliente">Cliente</option>
        </select><br>
        <label>Estado</label>
        <select id="edit-estado" name="estado">
            <option value="1">Activo</option>
            <option value="0">Inactivo</option>
        </select><br><br>
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
        document.addEventListener("DOMContentLoaded", function () {
            const modal = document.getElementById("registerModal");
            const openBtn = document.getElementById("openModalBtn");
            const closeBtn = document.getElementById("closeModalBtn");
            const cancelBtn = document.getElementById("cancelModalBtn");

            openBtn.addEventListener("click", function (e) {
                e.preventDefault();
                modal.style.display = "block";
            });

            closeBtn.addEventListener("click", () => modal.style.display = "none");
            cancelBtn.addEventListener("click", () => modal.style.display = "none");

            window.onclick = function (event) {
                if (event.target == modal) modal.style.display = "none";
            }
        });
        function eliminarUsuario(id) {
    if (!confirm('¿Deseas eliminar este usuario?')) return;

    fetch(`/admin/usuarios/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => res.json()) // ← leer respuesta como JSON
    .then(data => {
        if (data.success) { // ← usa la clave correcta del controlador
            document.getElementById(`fila-${id}`).remove();
            alert(data.success); // ← muestra mensaje
        } else {
            alert('Error al eliminar');
        }
    })
    .catch(() => alert('Error en la solicitud'));
}

    function abrirModal(usuario) {
        document.getElementById('edit-id').value = usuario.id;
        document.getElementById('edit-name').value = usuario.name;
        document.getElementById('edit-username').value = usuario.username;
        document.getElementById('edit-email').value = usuario.email;
        document.getElementById('edit-rol').value = usuario.rol;
        document.getElementById('edit-estado').value = usuario.estado ? 1 : 0;
        document.getElementById('modalEditar').style.display = 'block';
    }

    function cerrarModal() {
        document.getElementById('modalEditar').style.display = 'none';
    }

    document.getElementById('formEditar').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('edit-id').value;
        const formData = new FormData(this);

        fetch(`/admin/usuarios/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-HTTP-Method-Override': 'PUT'
            },
            body: formData
        }).then(res => {
            if (res.ok) {
                alert('Usuario actualizado');
                location.reload();
            } else {
                alert('Error al actualizar');
            }
        });
    });
    document.addEventListener("DOMContentLoaded", function () {
    // Referencias para el modal de editar
    const modalEditar = document.getElementById("modalEditar");
    const closeEditarBtn = document.getElementById("closeEditarBtn");

    closeEditarBtn.addEventListener("click", () => modalEditar.style.display = "none");

    });
    </script>
</body>
</html>
