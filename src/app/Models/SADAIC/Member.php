<?php

namespace App\Models\SADAIC;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'source_members';
    protected $primaryKey = ['codanita', 'num_doc'];

    public $incrementing = false;
    public $timestamps = false;
}
