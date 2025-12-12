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
        'unit_cost',
        'balance'
    ];

    public function AutoPartStatement(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AutoPartStatement::class, 'auto_part_id');
    }

    protected $casts = [
        'created_at' => 'datetime',
        'balance' => 'int'
    ];
    // public function scopeLatestTen($query)
    // {
    //     return $query->orderBy('created_at', 'desc')->limit(10);
    // }
}
