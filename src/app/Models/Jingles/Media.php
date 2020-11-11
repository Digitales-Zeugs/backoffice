<?php

namespace App\Models\Jingles;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'jingles_registration_media';

    protected $fillable = [
        'name',
        'description'
    ];

    public $timestamps = false;
}
