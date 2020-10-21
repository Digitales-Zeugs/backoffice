<?php

namespace App\Models\Members;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $table = 'members_registration';

    public function status()
    {
        return $this->hasOne('App\Models\Members\Status', 'id', 'status_id');
    }
}
