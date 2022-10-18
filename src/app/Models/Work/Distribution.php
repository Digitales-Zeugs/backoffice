<?php

namespace App\Models\Work;

use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    protected $table = 'works_distribution';

    protected $fillable = [
        'registration_id',
        'type',
        'fn',
        'member_id',
        'doc_number',
        'public',
        'mechanic',
        'sync'
    ];

    public function registration()
    {
        return $this->belongsTo('App\Models\Work\Registration', 'registration_id');
    }

    public function member()
    {
        $rel = $this->hasOne('App\Models\SADAIC\Member', 'codanita', 'member_id');
        if ($this->doc_number) {
            $rel->where('num_doc', $this->doc_number);
        }

        return $rel;
    }

    public function files()
    {
        return $this->hasMany('App\Models\Work\File', 'distribution_id', 'id');
    }

    public function meta()
    {
        return $this->hasOne('App\Models\Work\Meta', 'distribution_id');
    }

    public function role()
    {
        return $this->hasOne('App\Models\SADAIC\Role', 'code', 'fn');
    }

    public function getFile($name)
    {
        return File::where([
            'distribution_id' => $this->id,
            'name'            => $name
        ])->first();
    }
}
