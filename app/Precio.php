<?php

namespace ideas;

use Illuminate\Database\Eloquent\Model;

class Precio extends Model
{
    protected $table = 'precio';
    protected $primaryKey = 'idprecio';

    public $timestamps = false;

    protected $fillable = ['idarticulo', 'precio', 'fecha'];
}
