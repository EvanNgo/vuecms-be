<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillSubItem extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'bill_item_id',
        'product_item_id',
        'name',
        'cost',
        'quatity',
        'total_cost'
    ];
}
