<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $connection = 'sadaic';
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

    public function privileges()
    {
        return $this->hasMany('App\Privileges', 'recid_usuario', 'recid');
    }

    public function can($abilities, $arguments = [])
    {
        $validPermissions = ['sin permisos', 'lee', 'carga', 'homologa'];

        // El valor solicitado no está entre los permisos válidos
        if (!in_array($arguments, $validPermissions)) {
            return false;
        }

        $value = optional($this->privileges->firstWhere('capitulo', $abilities))->privilegios;

        // No se encontró el capítulo
        if (!$value) {
            return false;
        }

        // El permiso devuelto no estrá entre los permisos válidos
        if (!in_array($value, $validPermissions)) {
            return false;
        }

        // Obtengo la posición en la "escala" de permisos
        $pos_expected = array_search($arguments, $validPermissions);
        $pos_value = array_search($value, $validPermissions);

        // El permiso solicitado es mayor que el permiso devuelto
        if ($pos_expected > $pos_value) {
            return false;
        }

        return true;
    }
}
