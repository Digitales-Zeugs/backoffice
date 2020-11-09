<?php

namespace App\Models\Jingles;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'jingles_people';

    protected $fillable = [
        'cuit',
        'name',
        'address',
        'phone',
        'email'
    ];

    public $timestamps = false;
}
