<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static isActive()
 * @method static IsCompleted()
 */
class ELog extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    protected $table = 'e_log';

    public function scopeIsActive($query)
    {
        $query->where('status', 'active');
    }

    public function scopeIsCompleted($query)
    {
        $query->where('status', 'completed');
    }

    public function activities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ELogActivity::class, 'e_log_id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function car(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Car::class);

    }

    protected $casts = [
          'date_logged'=>'datetime'
    ];

     
}
