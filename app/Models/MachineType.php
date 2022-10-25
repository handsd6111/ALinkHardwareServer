<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachineType extends Model
{
    protected $table = 'machine_types';
    protected $primaryKey = 'MT_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

}
