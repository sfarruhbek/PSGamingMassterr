<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = [
        'name',
        'price11',
        'price12',
        'price21',
        'price22'
    ];

    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
