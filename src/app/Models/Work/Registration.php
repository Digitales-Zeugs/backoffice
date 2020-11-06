<?php

namespace App\Models\Work;

use App\Models\Work\File;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $table = 'works_registration';

    protected $fillable = [
        'title',
        'dnda_title',
        'member_id',
        'user_id',
        'genre_id',
        'duration',
        'dnda_ed_date',
        'audio_dnda_ed_file',
        'lyric_dnda_ed_file',
        'dnda_in_date',
        'audio_dnda_in_file',
        'lyric_dnda_in_file',
    ];

    protected $dates = [
        'dnda_ed_date',
        'dnda_in_date'
    ];

    protected $casts = [
        'submitted' => 'boolean',
        'approved'  => 'boolean'
    ];

    public function distribution()
    {
        return $this->hasMany('App\Models\Work\Distribution', 'registration_id', 'id');
    }

    public function files()
    {
        return $this->hasMany('App\Models\Work\File', 'registration_id', 'id');
    }

    public function genre()
    {
        return $this->hasOne('App\Models\SADAIC\Genres', 'cod_int_gen', 'genre_id');
    }

    public function getFile($name)
    {
        return File::where([
            'registration_id' => $this->id,
            'name'            => $name
        ])->first();
    }

    public function logs()
    {
        return $this->hasMany('App\Models\Work\Log', 'registration_id', 'id');
    }

    public function status()
    {
        return $this->hasOne('App\Models\Work\Status', 'id', 'status_id');
    }

    public function initiator()
    {
        if ($this->member_id) {
            return $this->belongsTo('App\Models\Member', 'member_id');
        } else {
            return $this->belongsTo('App\Models\User', 'user_id');
        }
    }
}
