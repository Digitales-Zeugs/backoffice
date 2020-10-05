<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkRegistration extends Model
{
    protected $table = 'works_registration';

    public function distribution()
    {
        return $this->hasMany('App\Models\WorkDistribution', 'registration_id', 'id');
    }
}
