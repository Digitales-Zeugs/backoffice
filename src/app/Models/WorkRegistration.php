<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkRegistration extends Model
{
    protected $table = 'works_registration';

    protected $dates = [
        'lyric_dnda_date',
        'audio_dnda_date'
    ];

    public function distribution()
    {
        return $this->hasMany('App\Models\WorkDistribution', 'registration_id', 'id');
    }
}
