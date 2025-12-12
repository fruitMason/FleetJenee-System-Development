<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Driver extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function vendor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function full_name(): string
    {
        $name = $this->first_name .' '.$this->middle_name .' '. $this->last_name;
        return trim(str_replace('  ', ' ', $name));
    }
}
