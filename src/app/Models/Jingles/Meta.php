<?php

namespace App\Models\Jingles;

use App\Models\SADAIC\Cities;
use App\Models\SADAIC\Countries;
use App\Models\SADAIC\States;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    protected $table = 'jingles_meta';

    protected $fillable = [
        'agreement_id',
        'address_country_id',
        'address_state_id',
        'address_state_text',
        'address_city_id',
        'address_city_text',
        'address_zip',
        'apartment',
        'birth_country_id',
        'birth_date',
        'doc_type_id',
        'email',
        'floor',
        'name',
        'phone_area',
        'phone_country',
        'phone_number',
        'street_name',
        'street_number'
    ];

    protected $dates = [
        'birth_date'
    ];

    public function getCountryAttribute()
    {
        $output = optional(Countries::find($this->address_country_id))->name_ter;

        return ucwords(strtolower($output));
    }

    public function getStateAttribute()
    {
        $output = '';
        if ($this->address_state_id) {
            $output = optional(States::find($this->address_state_id))->state;
        } else {
            $output = $this->address_state_text;
        }

        return ucwords(strtolower($output));
    }

    public function getCityAttribute()
    {
        $output = '';
        if ($this->address_city_id) {
            $output = optional(Cities::find($this->address_city_id))->city;
        } else {
            $output = $this->address_city_text;
        }

        return ucwords(strtolower($output));
    }

    public function getFullAddressAttribute()
    {
        $output = $this->street_name . ' ' . $this->street_number;
        if ($this->floor) { $output .= ' ' . $this->floor; }
        if ($this->apartment) { $output .= ' ' . $this->apartment; }
        $output .= ', ' . $this->address_zip . ' ' . $this->city;
        $output .= ', ' . $this->state;
        $output .= ', ' . $this->country;

        return $output;
    }

    public function getFullPhoneAttribute()
    {
        $output = '+' . ltrim($this->phone_country, '0');
        $output .= ' ' . ltrim($this->phone_area, '0');
        $output .= ' ' . $this->phone_number;

        return $output;
    }

    public function doc_type()
    {
        return $this->hasOne('App\Models\SADAIC\Types', 'code', 'doc_type');
    }

    public function birth_country()
    {
        return $this->hasOne('App\Models\SADAIC\Countries', 'idx', 'birth_country_id');
    }
}
