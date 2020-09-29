<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'usuarioid';
    public $incrementing = false;

    protected $table = 'usuarios';

    protected $fillable = [
        'email', 'usuarioid', 'clave', 'status',
    ];

    protected $hidden = [
        'clave'
    ];

    public function getAuthPassword()
    {
        return $this->clave;
    }
}
