<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::query();
    
        if ($request->has('buscar') && !empty($request->buscar)) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%$buscar%")
                  ->orWhere('codigo', 'like', "%$buscar%");
            });
        }
    
        $productos = $query->get();
        return view('inventario.index', compact('productos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required',
            'codigo' => 'required',
            'stock' => 'required|integer',
            'precio' => 'required|numeric',
            'descripcion' => 'nullable',
            'categoria' => 'nullable|string',
            'estado' => 'required|in:activo,inactivo',
            'imagen' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('imagenes', 'public');
        }

        Producto::create($data);
        return redirect()->route('inventario.index')->with('success', 'Producto registrado.');
    }

    public function update(Request $request, Producto $producto)
    {
        $data = $request->validate([
            'nombre' => 'required',
            'codigo' => 'required',
            'stock' => 'required|integer',
            'precio' => 'required|numeric',
            'descripcion' => 'nullable',
            'categoria' => 'nullable|string',
            'estado' => 'required|in:activo,inactivo',
            'imagen' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('imagenes', 'public');
        }

        $producto->update($data);
        return redirect()->route('inventario.index')->with('success', 'Producto actualizado.');
    }

    public function destroy(Producto $producto)
    {
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }
        $producto->delete();
        return redirect()->route('inventario.index')->with('success', 'Producto eliminado.');
    }
}

