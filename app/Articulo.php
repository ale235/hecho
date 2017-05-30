<?php

namespace ideas;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table = 'articulo';
    protected $primaryKey = 'idarticulo';

    public $timestamps = false;

    protected $fillable = ['idcategoria', 'codigo', 'nombre', 'stock', 'descripcion', 'imagen', 'estado'];

    public function scopeType($query, $type)
    {
        $types = ['Activo', 'Inactivo'];
        $query->where('type','=',$type);
    }
}
