<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttrItem extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'product_id',
        'name',
        'slug',
        'can_be_deleted'
    ];
}
