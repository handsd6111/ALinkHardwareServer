<?php

namespace App\Models;

use App\Models\CompositeKey;
use Illuminate\Database\Eloquent\Model;

class RealityItem extends Model
{
    use CompositeKey;

    protected $table = 'reality_items';
    protected $primaryKey = ['RI_sequence', 'M_id', 'MGI_sequence'];
    protected $keyType = 'tinyInteger';
    public $incrementing = false;
    public $timestamps = false;
}
