<?php

namespace App\Models\Jingles;

use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    protected $table = 'jingles_registration_agreements';

    public const TYPE = [
        1 => 'member',
        2 => 'no-member'
    ];

    protected $fillable = [
        'type',
        'member_idx',
        'response'
    ];

    protected $casts = [
        'response' => 'boolean'
    ];

    public function type()
    {
        if (!$this->type_id) {
            return null;
        }

        if (!array_key_exists($this->type_id, $this->TYPE)) {
            return null;
        }

        return [
            'id'   => $this->type_id,
            'name' => $this->TYPE[$this->type_id]
        ];
    }
}
