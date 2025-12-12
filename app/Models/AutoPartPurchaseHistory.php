<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutoPartPurchaseHistory extends Model
{
    
    protected $fillable = [
        'auto_part_id',
        'cost'        
    ];
 
    protected $casts = [
        'created_at' => 'datetime',
    ];
}
