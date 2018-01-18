<?php

namespace ideas;

use Illuminate\Database\Eloquent\Model;

class Arqueo extends Model
{
    protected $table = 'arqueo';
    protected $primaryKey = 'idarqueo';

    public $timestamps = false;

    protected $fillable = ['fecha', 'descripcion', 'monto'];
}
