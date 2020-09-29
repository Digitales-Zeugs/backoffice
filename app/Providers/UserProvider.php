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

        foreach ($credentials as $key => $value) {
            if (! Str::contains($key, 'clave')) {
                $query->where($key, $value);
            }
        }

        return $query->first();
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if ($user->usuarioid !== $credentials['usuarioid']) {
            return false;
        }

        if (md5($credentials['clave']) !== $member->getAuthPassword()) {
            return false;
        }

        return true;
    }
}