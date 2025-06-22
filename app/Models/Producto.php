<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    // Nombre de la tabla (opcional si sigue la convención)
    protected $table = 'productos';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'codigo',
        'stock',
        'precio',
        'descripcion',
        'imagen',
        'categoria',
        'estado',
    ];

    // Casts si quieres usar algún tipo especial
    protected $casts = [
        'precio' => 'decimal:2',
    ];

    // Opcional: métodos auxiliares
    public function isActivo()
    {
        return $this->estado === 'activo';
    }

    public function creador() {
        return $this->belongsTo(User::class, 'creado_por');
    }
    
    public function getImagenUrlAttribute()
    {
        return $this->imagen ? asset('storage/' . $this->imagen) : asset('images/no-image.png');
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

