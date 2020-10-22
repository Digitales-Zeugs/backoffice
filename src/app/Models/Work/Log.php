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

    protected $casts = [
        'action_data' => 'array',
    ];

    public $dates = [
        'time'
    ];

    public $timestamps = false;

    public function action() {
        return $this->hasOne('App\Models\Work\LogAction', 'id', 'action_id');
    }

    public function distribution()
    {
        return $this->belongsTo('App\Models\Work\Distribution', 'distribution_id');
    }

    public function registration()
    {
        return $this->belongsTo('App\Models\Work\Registration', 'registration_id');
    }
}
