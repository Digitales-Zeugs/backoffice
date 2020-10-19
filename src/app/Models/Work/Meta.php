<?php

namespace App\Models\Work;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    protected $table = 'works_meta';

    protected $fillable = [
        'distribution_id',
        'address_country',
        'address_state',
        'address_city',
        'address_zip',
        'apartment',
        'birth_country',
        'birth_date',
        'doc_type',
        'email',
        'floor',
        'name',
        'phone_area',
        'phone_country',
        'phone_number',
        'street_name',
        'street_number'
    ];
}
