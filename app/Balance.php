<?php

namespace ideas;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    protected $table = 'balance';
    protected $primaryKey = 'idbalance';

    public $timestamps = false;

    protected $fillable = ['capitalinicial', 'retirodecapital', 'fecha'];
}
