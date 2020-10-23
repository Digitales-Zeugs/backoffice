<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Privileges extends Model
{
    protected $connection = 'sadaic';

    protected $table = 'usuarios_privilegios';
}
