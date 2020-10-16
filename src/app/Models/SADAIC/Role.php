<?php

namespace App\Models\SADAIC;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'source_roles';
    protected $primaryKey = 'code';
    protected $keyType = 'string';

    public $incrementing = false;
    public $timestamps = false;
}
