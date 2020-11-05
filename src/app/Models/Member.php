<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

class Member extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'email', 'member_id', 'heir'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAuthIdentifier()
    {
        return $this->member_id . '-' . $this->heir;
    }

    public function sadaic()
    {
        return $this->hasOne('App\Models\SADAIC\Member', 'codanita', 'member_id');
    }

    public function profile()
    {
        return $this->hasOne('App\Models\MemberProfile', 'member_id');
    }

    public function getFullNameAttribute()
    {
        if ($this->sadaic) {
            return ucwords(strtolower($this->sadaic->nombre));
        } elseif ($this->profile) {
            return ucwords(strtolower($this->profile->name));
        }

        return '';
    }

    protected function setKeysForSaveQuery(Builder $query)
    {
        return $query
            ->where('member_id', $this->getAttribute('member_id'))
            ->where('heir', $this->getAttribute('heir'));
    }

}
