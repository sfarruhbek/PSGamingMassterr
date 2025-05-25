<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'count',
        'income',
        'expense',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
