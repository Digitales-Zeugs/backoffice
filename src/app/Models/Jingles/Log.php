<?php

namespace App\Models\Jingles;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'jingles_logs';

    protected $fillable = [
        'registration_id',
        'agreement_id',
        'action_id',
        'time',
        'action_data'
    ];

    protected $casts = [
        'action_data' => 'array',
    ];

    protected $dates = [
        'time'
    ];

    public $timestamps = false;

    public function action() {
        return $this->hasOne('App\Models\Jingles\Action', 'id', 'action_id');
    }

    public function registration()
    {
        return $this->belongsTo('App\Models\Jingles\Registration', 'registration_id');
    }
}
