<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable = ['cliente_id', 'fecha', 'total'];
    
    public function usuario() {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function cliente() {
        return $this->belongsTo(Cliente::class);
    }

    public function detalles() {
        return $this->hasMany(DetalleFactura::class);
    }
    public function creadoPor()
{
    return $this->belongsTo(User::class, 'created_by');
}

public function actualizadoPor()
{
    return $this->belongsTo(User::class, 'updated_by');
}
    
}