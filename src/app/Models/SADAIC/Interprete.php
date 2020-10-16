<?php

namespace App\Models\SADAIC;

use Illuminate\Database\Eloquent\Model;

class Interprete extends Model
{
    protected $connection = 'sadaic';
    protected $table = 'socios';
    protected $hidden = ['clave'];

    public $timestamps = false;
}
