<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $table = 'machines';
    protected $primaryKey = 'M_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

}
