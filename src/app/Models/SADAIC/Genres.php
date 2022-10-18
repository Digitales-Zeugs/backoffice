<?php

namespace App\Models\SADAIC;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Genres extends Model
{
    protected $table = 'source_genres';
    protected $primaryKey = 'cod_int_gen';

    public $incrementing = false;
    public $timestamps = false;

    public function getIdAttribute()
    {
        return $this->cod_int_gen;
    }

    public function getNameAttribute()
    {
        return Str::of($this->des_int_gen)->trim()->title();
    }
}
