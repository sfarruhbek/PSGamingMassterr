<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = ['device_id', 'started_at', 'finished_at', 'paid_price'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
