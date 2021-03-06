<?php

namespace ideas;

use Illuminate\Database\Eloquent\Model;

class Pagos extends Model
{
    protected $table = 'pagos';
    protected $primaryKey = 'idpago';

    public $timestamps = false;

    protected $fillable = ['fecha', 'descripcion', 'monto'];
}
