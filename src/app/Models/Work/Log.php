<?php

namespace App\Models\Work;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'works_logs';

    protected $fillable = [
        'registration_id',
        'distribution_id',
        'action_id',
        'time',
        'action_data'
    ];

    public $timestamps = false;
}
