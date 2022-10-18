<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileUpdates extends Model
{
    protected $table = 'profile_updates';

    protected $casts = [
        'confirmed' => 'boolean',
    ];

    public function status()
    {
        return $this->hasOne('App\Models\ProfileUpdatesStatus', 'id', 'status_id');
    }
}
