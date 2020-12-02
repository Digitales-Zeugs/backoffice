<?php

namespace App\Models\Jingles;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $table = 'jingles_registration';

    public const REQUEST_ACTION = [
        1 => 'Original',
        2 => 'Reducción',
        3 => 'Renovación',
        4 => 'Exportación'
    ];

    public const BROADCAST_TERRITORY = [
        1 => 'Nacional',
        2 => 'Provincial',
        3 => 'Extranjero'
    ];

    public const AGENCY_TYPE = [
        1 => 'Agencia',
        2 => 'Productora'
    ];

    public const TARIFF_PAYER = [
        1 => 'Anunciante',
        2 => 'Agencia',
        3 => 'Productora'
    ];

    protected $fillable = [
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
        'ads_duration'      => 'array'
    ];

    protected $dates = [
        'air_date'
    ];

    public function request_action()
    {
        if (!$this->request_action_id) {
            return null;
        }

        if (!array_key_exists($this->request_action_id, $this->REQUEST_ACTION)) {
            return null;
        }

        return [
            'id'   => $this->request_action_id,
            'name' => $this->REQUEST_ACTION[$this->request_action_id]
        ];
    }

    public function broadcast_territory()
    {
        if (!$this->broadcast_territory_id) {
            return null;
        }

        if (!array_key_exists($this->broadcast_territory_id, $this->BROADCAST_TERRITORY)) {
            return null;
        }

        return [
            'id'   => $this->broadcast_territory_id,
            'name' => $this->BROADCAST_TERRITORY[$this->broadcast_territory_id]
        ];
    }

    public function agency_type()
    {
        if (!$this->agency_type_id) {
            return null;
        }

        if (!array_key_exists($this->agency_type_id, $this->AGENCY_TYPE)) {
            return null;
        }

        return [
            'id'   => $this->agency_type_id,
            'name' => $this->AGENCY_TYPE[$this->agency_type_id]
        ];
    }

    public function tariff_payer()
    {
        if (!$this->tariff_payer_id) {
            return null;
        }

        if (!array_key_exists($this->tariff_payer_id, $this->TARIFF_PAYER)) {
            return null;
        }

        return [
            'id'   => $this->tariff_payer_id,
            'name' => $this->TARIFF_PAYER[$this->tariff_payer_id]
        ];
    }

    public function status()
    {
        return $this->hasOne('App\Models\Jingles\Status', 'id', 'status_id');
    }
}
