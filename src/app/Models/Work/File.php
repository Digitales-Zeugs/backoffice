<?php

namespace App\Models\Work;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'works_files';

    protected $fillable = [
        'registration_id',
        'distribution_id',
        'name',
        'path'
    ];

    public $timestamps = false;

    public function distribution()
    {
        return $this->belongsTo('App\Models\Work\Distribution', 'distribution_id');
    }
}
