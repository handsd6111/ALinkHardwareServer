<?php

namespace App\Models;

use App\Models\CompositeKey;
use Illuminate\Database\Eloquent\Model;

class MachineGameItem extends Model
{
    use CompositeKey;

    protected $table = 'machine_game_items';
    public $incrementing = false;
    protected $keyType = 'array';
    protected $primaryKey = ['M_id', 'MGI_sequence'];
    public $timestamps = false;
}
