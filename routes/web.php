<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\ClienteController;
use App\Models\Cliente; // 👈 Asegúrate de incluir el modelo

// Página de inicio
Route::get('/', function () {
    return view('welcome');
});

// Redirección al dashboard según el rol
Route::get('/dashboard', function () {
    if (Auth::check()) {
        $rol = Auth::user()->rol;
        return match($rol) {
            'admin' => redirect()->route('dashboard.admin'),
            'usuario' => redirect()->route('dashboard.usuario'),
            default => redirect('/'),
        };
    }
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

// Vistas separadas por rol
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/admin', 'dashboard.admin')->name('dashboard.admin');
    Route::view('/usuario', 'dashboard.usuario')->name('dashboard.usuario');
});

// Perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Usuarios (admin)
Route::middleware('auth')->group(function () {
    Route::get('/admin/usuarios', [UsuarioController::class, 'index'])->name('admin.usuarios');
    Route::post('/admin/usuarios/registrar', [UsuarioController::class, 'guardar'])->name('admin.usuarios.registrar');
    Route::get('/admin/usuarios/{id}/editar', [UsuarioController::class, 'editar'])->name('admin.usuarios.editar');
    Route::put('/admin/usuarios/{id}', [UsuarioController::class, 'actualizar'])->name('admin.usuarios.actualizar');
    Route::delete('/admin/usuarios/{id}', [UsuarioController::class, 'eliminar'])->name('admin.usuarios.eliminar');
});

// Inventario
Route::middleware('auth')->group(function () {
    Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::post('/inventario', [InventarioController::class, 'store'])->name('inventario.store');
    Route::put('/inventario/{producto}', [InventarioController::class, 'update'])->name('inventario.update');
    Route::delete('/inventario/{producto}', [InventarioController::class, 'destroy'])->name('inventario.destroy');
});

// Facturación
Route::middleware(['auth'])->group(function () {
    Route::get('/facturas', [FacturaController::class, 'index'])->name('facturas.index');
    Route::post('/facturas', [FacturaController::class, 'store'])->name('facturas.store');
    Route::get('/facturas/{id}/mostrar', [FacturaController::class, 'mostrar'])->name('facturas.mostrar');
    Route::get('/reporte/ventas', [FacturaController::class, 'reporteVentas'])->name('reporte.ventas');
});

// Clientes (admin)
Route::middleware(['auth'])->group(function () {
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
    Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
});

// Clientes (usuarios)
Route::middleware(['auth'])->group(function () {
    Route::get('/usuario-clientes', function () {
        $clientes = Cliente::all(); // Aquí puedes agregar filtros si quieres
        return view('clientes.usuario_clientes', compact('clientes'));
    })->name('clientes.usuario');
});

// Rutas de autenticación (login, logout, registro)
require __DIR__.'/auth.php';
