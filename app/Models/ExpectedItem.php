<?php

namespace App\Models;

use App\Models\CompositeKey;
use Illuminate\Database\Eloquent\Model;

class ExpectedItem extends Model
{
    use CompositeKey;
    
    protected $table = 'expected_items';
    protected $primaryKey = ['EI_sequence', 'M_id', 'MGI_sequence'];
    protected $keyType = 'tinyInteger';
    public $incrementing = false;
    public $timestamps = false;
}
