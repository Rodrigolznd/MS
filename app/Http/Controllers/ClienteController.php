<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $clientes = Cliente::with(['creadoPor', 'actualizadoPor']) // ← Carga relaciones
            ->when($buscar, function ($query, $buscar) {
                $query->where('nombre', 'like', "%$buscar%")
                      ->orWhere('email', 'like', "%$buscar%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('clientes.index', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:50',
            'direccion' => 'nullable|string',
            'estado' => 'required|boolean'
        ]);

        $cliente = new Cliente($request->all());
        $cliente->created_by = auth()->id();
        $cliente->updated_by = auth()->id();
        $cliente->save();

        return redirect()->route('clientes.index')->with('success', 'Cliente registrado');
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:50',
            'direccion' => 'nullable|string',
            'estado' => 'required|boolean'
        ]);

        $cliente->fill($request->all());
        $cliente->updated_by = auth()->id();
        $cliente->save();

        return response()->json(['success' => true]);
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return response()->json(['success' => true]);
    }
}
