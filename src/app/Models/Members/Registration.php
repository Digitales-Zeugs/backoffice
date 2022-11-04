<?php

namespace App\Models\Members;

use App\Models\SADAIC\Cities;
use App\Models\SADAIC\Countries;
use App\Models\SADAIC\States;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $table = 'members_registration';

    protected $dates = [
        'birth_date'
    ];

    public function status()
    {
        return $this->hasOne('App\Models\Members\Status', 'id', 'status_id');
    }

    public function getBirthCountryAttribute()
    {
        $output = optional(Countries::find($this->birth_country_id))->name_ter;

        return ucwords(strtolower($output));
    }

    public function getBirthStateAttribute()
    {
        $output = '';
        if ($this->birth_state_id) {
            $output = optional(States::find($this->birth_state_id))->state;
        } else {
            $output = $this->birth_state_text;
        }

        return ucwords(strtolower($output));
    }

    public function getBirthCityAttribute()
    {
        $output = '';
        if ($this->birth_city_id) {
            $output = optional(Cities::find($this->birth_city_id))->city;
        } else {
            $output = $this->birth_city_text;
        }

        return ucwords(strtolower($output));
    }

    public function getAddressCountryAttribute()
    {
        $output = optional(Countries::find($this->address_country_id))->name_ter;

        return ucwords(strtolower($output));
    }

    public function getAddressStateAttribute()
    {
        $output = '';
        if ($this->address_state_id) {
            $output = optional(States::find($this->address_state_id))->state;
        } else {
            $output = $this->address_state_text;
        }

        return ucwords(strtolower($output));
    }

    public function getAddressCityAttribute()
    {
        $output = '';
        if ($this->address_city_id) {
            $output = optional(Cities::find($this->address_city_id))->city;
        } else {
            $output = $this->address_city_text;
        }

        return ucwords(strtolower($output));
    }

    public function genre()
    {
        return $this->hasOne('App\Models\SADAIC\Genres', 'cod_int_gen', 'genre_id');
    }
}
