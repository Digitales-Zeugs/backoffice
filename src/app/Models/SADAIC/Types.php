<?php

namespace App\Models\SADAIC;

use Illuminate\Database\Eloquent\Model;

class Types extends Model
{
    protected $table = 'source_types';
    protected $primaryKey = 'code';

    public $incrementing = false;
    public $timestamps = false;
}
