<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutoPart extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
        'is_archived',
        'unit_cost'
    ];
    
}
