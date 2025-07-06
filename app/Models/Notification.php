<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static isRead()
 * @method static isUnread()
 */
class Notification extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function scopeIsRead($query)
    {
        $query->where('unread', false);
    }

    public function scopeIsUnread($query)
    {
        $query->where('unread', true);
    }

    public function fromUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
