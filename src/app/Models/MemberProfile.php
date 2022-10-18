<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberProfile extends Model
{
    protected $table = 'members_profiles';

    protected $fillable = [
        'member_id',
        'name',
        'address_type',
        'address',
        'address_zip',
        'address_city',
        'address_state',
        'address_country',
        'phone_country',
        'phone_area',
        'phone_number',
        'cell_country',
        'cell_area',
        'cell_number'
    ];
}
