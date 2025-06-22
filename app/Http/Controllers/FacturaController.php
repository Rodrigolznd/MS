<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Factura;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\DetalleFactura;
use Illuminate\Support\Facades\DB;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        $clientes = Cliente::all();
        $productos = Producto::where('estado', 'activo')->get();
    
        $facturas = Factura::with('cliente')
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $query->whereHas('cliente', function ($q) use ($request) {
                    $q->where('nombre', 'like', '%' . $request->buscar . '%');
                })->orWhere('fecha', 'like', '%' . $request->buscar . '%');
            })
            ->orderBy('fecha', 'desc')
            ->get();
    
        // ✅ Devuelve todo a la vista para que funcione el formulario y el historial
        return view('facturas.index', compact('clientes', 'productos', 'facturas'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha' => 'required|date',
            'productos' => 'required|array',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();
        try {
            $total = 0;
            $factura = Factura::create([
                'cliente_id' => $request->cliente_id,
                'fecha' => $request->fecha,
                'total' => 0
            ]);

            foreach ($request->productos as $item) {
                $producto = Producto::findOrFail($item['id']);
                $subtotal = $producto->precio * $item['cantidad'];
                $total += $subtotal;

                DetalleFactura::create([
                    'factura_id' => $factura->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $producto->precio,
                    'subtotal' => $subtotal
                ]);
            }

            $factura->update(['total' => $total]);

            DB::commit();
            
            // ✅ Redirigir a la vista de factura generada
            return redirect()->route('facturas.mostrar', ['id' => $factura->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al generar la factura: ' . $e->getMessage());
        }
    }
    
    public function reporteVentas(Request $request)
{
    $desde = $request->input('fecha_inicio');
    $hasta = $request->input('fecha_fin');

    $facturas = Factura::with('cliente')
        ->when($desde && $hasta, function ($query) use ($desde, $hasta) {
            $query->whereBetween('fecha', [$desde, $hasta]);
        })
        ->orderBy('fecha', 'desc')
        ->get();

    $totalVentas = $facturas->sum('total');

    return view('facturas.reporte', compact('facturas', 'desde', 'hasta', 'totalVentas'));
}


    public function mostrar($id)
    {
        $factura = Factura::with('cliente', 'detalles.producto')->findOrFail($id);
        return view('facturas.factura_generada', compact('factura'));
    }
}
