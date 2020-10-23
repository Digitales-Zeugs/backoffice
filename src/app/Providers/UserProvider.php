<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\UserProvider as UserProviderContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;

class UserProvider extends EloquentUserProvider implements UserProviderContract
{
    public function retrieveById($identifier)
    {
        $query = $this->createModel()->newQuery();
        $query->where('usuarioid', $identifier);

        return $query->first();
    }

    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials)) {
            return;
        }

        $query = $this->createModel()->newQuery();

        if (array_key_exists('username', $credentials)) {
            $query = $query->where('usuarioid', $credentials['username']);
        }

        if (array_key_exists('email', $credentials)) {
            $query = $query->where('email', $credentials['email']);
        }

        if (array_key_exists('status', $credentials)) {
            $query = $query->where('status', $credentials['status']);
        }

        return $query->first();
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if ($user->usuarioid !== $credentials['username']) {
            return false;
        }

        if (md5($credentials['password']) !== $user->getAuthPassword()) {
            return false;
        }

        if(!$user->privileges->contains('capitulo', 'nb_login')) {
            return false;
        }

        if (optional($user->privileges->firstWhere('capitulo', 'nb_login'))->privilegios != 'lee') {
            return false;
        }

        return true;
    }
}