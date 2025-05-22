<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['name', 'type_id'];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
    public function histories()
    {
        return $this->hasMany(History::class);
    }
    public function activeHistories()
    {
        return $this->hasMany(History::class)->whereNull('finished_at');
    }
    public function inactiveHistories()
    {
        return $this->hasMany(History::class)->whereNotNull('finished_at');
    }
}
