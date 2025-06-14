<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceProductHistory extends Model
{
    protected $fillable = [
        'device_id',
        'history_id',
        'product_id',
        'count',
        'sold',
        'status',
        'created_at',
    ];

    public function device()
    {
        return $this->belongsTo(History::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
