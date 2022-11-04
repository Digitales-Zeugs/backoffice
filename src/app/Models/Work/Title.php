<?php

namespace App\Models\Work;

use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    protected $table = 'works_titles';

    protected $fillable = [
        'registration_id',
        'title'
    ];

    public $timestamps = false;
}
