<?php

namespace App\Models\Work;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'works_status';

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;
}
