<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function vendor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function invoiceItem(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // public function maintenance(): \Illuminate\Database\Eloquent\Relations\HasOne
    // {
    //     return $this->hasOne(CarMaintenance::class, 'invoice_id');
    // }
    public function car_maintenance(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CarMaintenance::class, 'car_maintenance_id');
    }

    protected $casts = [
        'due_date' => 'datetime',
    ];
}
