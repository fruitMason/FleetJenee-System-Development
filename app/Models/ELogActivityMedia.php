<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ELogActivityMedia extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    protected $table = 'e_log_activity_media';

    public function elogActivity(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ELogActivity::class, 'e_log_activity_id');
    }
}
