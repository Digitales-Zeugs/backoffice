<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkDistribution extends Model
{
    protected $table = 'works_distribution';

    public function registration()
    {
        return $this->belongsTo('App\Models\WorkRegistration', 'registration_id');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\SADAICRoles', 'function', 'code');
    }
}
