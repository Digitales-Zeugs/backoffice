<?php

namespace App\Models\Jingles;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $table = 'jingles_registration';

    protected $hidden = ['people'];

    public const REQUEST_ACTION = [
        [ 'id' => 1, 'name' => 'Original' ],
        [ 'id' => 2, 'name' => 'Reducción' ],
        [ 'id' => 3, 'name' => 'Renovación' ],
        [ 'id' => 4, 'name' => 'Exportación' ]
    ];

    public const BROADCAST_TERRITORY = [
        [ 'id' => 1, 'name' => 'Nacional' ],
        [ 'id' => 2, 'name' => 'Provincial' ],
        [ 'id' => 3, 'name' => 'Extranjero' ]
    ];

    public const AGENCY_TYPE = [
        [ 'id' => 1, 'name' => 'Agencia' ],
        [ 'id' => 2, 'name' => 'Productora' ]
    ];

    public const TARIFF_PAYER = [
        [ 'id' => 1, 'name' => 'Anunciante' ],
        [ 'id' => 2, 'name' => 'Agencia' ],
        [ 'id' => 3, 'name' => 'Productora' ]
    ];

    protected $fillable = [
        'member_id',
        'user_id',
        'is_special',
        'request_action_id',
        'validity',
        'air_date',
        'ads_duration',
        'broadcast_territory_id',
        'territory_id',
        'media_id',
        'subsection_i',
        'agency_type_id',
        'product_brand',
        'product_type',
        'product_name',
        'work_title',
        'work_original',
        'work_dnda',
        'work_authors',
        'work_composers',
        'work_editors',
        'work_script_mod',
        'work_music_mod',
        'authors_agreement',
        'authors_tariff',
        'tariff_payer_id',
        'tariff_representation',
        'status_id'
    ];

    protected $casts = [
        'is_special'        => 'boolean',
        'work_original'     => 'boolean',
        'work_script_mod'   => 'boolean',
        'work_music_mod'    => 'boolean',
        'authors_agreement' => 'boolean',
        'authors_tariff'    => 'decimal:2',
        'ads_duration'      => 'array',
        'territory_id'      => 'array'
    ];

    protected $dates = [
        'air_date'
    ];

    protected $attributes = [
        'is_special'        => false,
        'validity'          => 1,
        'media_id'          => 1,
        'agency_type_id'    => 1,
        'work_original'     => 1,
        'authors_agreement' => false
    ];

    public function request_action()
    {
        if (!$this->request_action_id) {
            return null;
        }

        $key = array_search($this->request_action_id, array_column($this->REQUEST_ACTION, 'id'));
        if ($key === false) {
            return null;
        }

        return $this->REQUEST_ACTION[$key];
    }

    public function broadcast_territory()
    {
        if (!$this->broadcast_territory_id) {
            return null;
        }

        $key = array_search($this->broadcast_territory_id, array_column($this->BROADCAST_TERRITORY, 'id'));
        if ($key === false) {
            return null;
        }

        return $this->BROADCAST_TERRITORY[$key];
    }

    public function agency_type()
    {
        if (!$this->agency_type_id) {
            return null;
        }

        $key = array_search($this->agency_type_id, array_column($this->AGENCY_TYPE, 'id'));
        if ($key === false) {
            return null;
        }

        return $this->AGENCY_TYPE[$key];
    }

    public function tariff_payer()
    {
        if (!$this->tariff_payer_id) {
            return null;
        }

        $key = array_search($this->tariff_payer_id, array_column($this->TARIFF_PAYER, 'id'));
        if ($key === false) {
            return null;
        }

        return $this->TARIFF_PAYER[$key];
    }

    public function people()
    {
        return $this->belongsToMany('App\Models\Jingles\Person', 'jingles_parts')->withPivot(['type']);
    }

    public function agreements()
    {
        return $this->hasMany('App\Models\Jingles\Agreement');
    }

    public function loadPeople()
    {
        $this->setRelation('applicant', $this->people->first(function ($item, $key) {
            return $item->pivot->type == 'applicant';
        }));

        $this->setRelation('advertiser', $this->people->first(function ($item, $key) {
            return $item->pivot->type == 'advertiser';
        }));

        $this->setRelation('agency', $this->people->first(function ($item, $key) {
            return $item->pivot->type == 'agency';
        }));
    }

    public function getApplicantAttribute()
    {
        if (!array_key_exists('applicant', $this->relations)) $this->loadPeople();

        return $this->getRelation('applicant');
    }

    public function getAdvertiserAttribute()
    {
        if (!array_key_exists('advertiser', $this->relations)) $this->loadPeople();

        return $this->getRelation('advertiser');
    }

    public function getAgencyAttribute()
    {
        if (!array_key_exists('agency', $this->relations)) $this->loadPeople();

        return $this->getRelation('agency');
    }

    public function status()
    {
        return $this->hasOne('App\Models\Jingles\Status', 'id', 'status_id');
    }
}
