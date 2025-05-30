<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceProductHistory extends Model
{
    protected $fillable = [
        'device_id',
        'product_id',
        'count',
        'sold',
        'status',
        'created_at',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
