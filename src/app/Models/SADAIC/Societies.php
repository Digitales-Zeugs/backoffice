<?php

namespace App\Models\SADAIC;

use Illuminate\Database\Eloquent\Model;

class Societies extends Model
{
    protected $table = 'source_societies';
    protected $primaryKey = 'code';

    public $incrementing = false;
    public $timestamps = false;
}
