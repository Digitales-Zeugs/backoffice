<?php

namespace App\Models\Jingles;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'jingles_registration_status';

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;
}
