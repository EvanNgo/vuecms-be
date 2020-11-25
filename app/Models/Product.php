<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'slug',
        'name',
        'brand',
        'cost',
        'quatity',
        'discription',
        'use'
    ];

    public function attrs() {
        return $this->hasMany(ProductAttr::class);
    }

    public function items()
    {
        return $this->hasMany(ProductItem::class);
    }
}
