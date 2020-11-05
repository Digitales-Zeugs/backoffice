<?php

namespace App\Models\SADAIC;

use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    protected $table = 'source_countries';
    protected $primaryKey = 'idx';

    public $incrementing = false;
    public $timestamps = false;
}
