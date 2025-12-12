<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarMaintenanceNote extends Model
{
    protected $fillable = [
        'car_maintenance_id',
        'status',
        'receipt_comment',
        'receipt_date',
        'user_email'
    ];

    public function carMaintenance()
    {
        return $this->belongsTo(CarMaintenance::class, 'car_maintenance_id');
    }

    protected $casts = [
        'receipt_date' => 'datetime',
    ];


    public function media()
    {
        return $this->hasOne(CarMaintenanceMedia::class, 'car_maintenance_note_id');
    }
}
