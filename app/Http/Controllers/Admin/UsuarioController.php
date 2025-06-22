<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    // Mostrar todos los usuarios
    public function index(Request $request)
    {
        $usuarios = User::query();
    
        if ($request->filled('buscar')) {
            $usuarios->where('name', 'like', '%' . $request->buscar . '%')
                     ->orWhere('username', 'like', '%' . $request->buscar . '%')
                     ->orWhere('email', 'like', '%' . $request->buscar . '%');
        }
    
        return view('dashboard.admin_usuarios', [
            'usuarios' => $usuarios->get()
        ]);
    }
    

    // Guardar un nuevo usuario
    public function guardar(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|max:20',
            'rol' => 'required|in:admin,usuario,cliente',
            'estado' => 'required|boolean',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'estado' => $request->estado,
        ]);

        return redirect()->route('admin.usuarios')->with('mensaje', 'Usuario registrado correctamente');
    }

    // Mostrar datos para editar
    public function editar($id)
    {
        $usuario = User::findOrFail($id);
        return response()->json($usuario);
    }

    // Actualizar un usuario existente
    public function actualizar(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username,' . $usuario->id,
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'rol' => 'required|in:admin,usuario,cliente',
            'estado' => 'required|boolean',
        ]);

        $usuario->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'rol' => $request->rol,
            'estado' => $request->estado,
        ]);

        return response()->json(['mensaje' => 'Usuario actualizado correctamente.']);
    }

    // Eliminar un usuario
    public function eliminar($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return response()->json(['success' => 'Usuario eliminado correctamente.']);
    }
}
