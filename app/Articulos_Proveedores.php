<?php

namespace ideas;

use Illuminate\Database\Eloquent\Model;

class Articulos_Proveedores extends Model
{
    protected $table = 'articulos_proveedores';

    protected $fillable = ['idarticulo', 'idproveedor'];

}
