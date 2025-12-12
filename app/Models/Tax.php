<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * @method static isActive()
 * @method static isInactive()
 */
class Tax extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    protected $table = 'taxes';

}
