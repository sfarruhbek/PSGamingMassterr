<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductHistory extends Model
{
    protected $fillable = [
        'product_id',
        'count',
        'income',
        'expense',
        'created_at',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
