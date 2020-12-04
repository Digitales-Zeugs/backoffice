<?php
declare(strict_types=1);

namespace App\Models\Jingles;

use App\Models\SADAIC\Countries;
use App\Models\SADAIC\States;
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

    /**
     * Atributos "estáticos" (no guardados en la BBDD)
     */
    public function getRequestActionAttribute()
    {
        if (!$this->request_action_id) {
            return null;
        }

        $key = array_search($this->request_action_id, array_column(self::REQUEST_ACTION, 'id'));
        if ($key === false) {
            return null;
        }

        return self::REQUEST_ACTION[$key]['name'];
    }

    public function getBroadcastTerritoryAttribute()
    {
        if (!$this->broadcast_territory_id) {
            return null;
        }

        $key = array_search($this->broadcast_territory_id, array_column(self::BROADCAST_TERRITORY, 'id'));
        if ($key === false) {
            return null;
        }

        return self::BROADCAST_TERRITORY[$key]['name'];
    }

    public function getAgencyTypeAttribute()
    {
        if (!$this->agency_type_id) {
            return null;
        }

        $key = array_search($this->agency_type_id, array_column(self::AGENCY_TYPE, 'id'));
        if ($key === false) {
            return null;
        }

        return self::AGENCY_TYPE[$key]['name'];
    }

    public function getTariffPayerAttribute()
    {
        if (!$this->tariff_payer_id) {
            return null;
        }

        $key = array_search($this->tariff_payer_id, array_column(self::TARIFF_PAYER, 'id'));
        if ($key === false) {
            return null;
        }

        return self::TARIFF_PAYER[$key]['name'];
    }

    /**
     * Personas relacionadas
     */
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

    /**
     * Territorios relacionados
     */
    public function getTerritoriesAttribute()
    {
        // Si no está seteado, es falsy, no es un array o no tiene elementos
        if (!$this->territory_id || !is_array($this->territory_id) || count($this->territory_id) == 0) {
            return collect([]);
        }

        // Provincias
        if ($this->broadcast_territory_id == 2) {
            return States::whereIn('id', $this->territory_id)->get();
        // Países
        } elseif ($this->broadcast_territory_id == 3) {
            return Countries::whereIn('idx', $this->territory_id)->get();
        }

        return collect([]);
    }

    /**
     * Otras relaciones
     */
    public function status()
    {
        return $this->hasOne('App\Models\Jingles\Status', 'id', 'status_id');
    }

    public function people()
    {
        return $this->belongsToMany('App\Models\Jingles\Person', 'jingles_parts')->withPivot(['type']);
    }

    public function agreements()
    {
        return $this->hasMany('App\Models\Jingles\Agreement');
    }

    public function media()
    {
        return $this->hasOne('App\Models\Jingles\Media', 'id', 'media_id');
    }
}
