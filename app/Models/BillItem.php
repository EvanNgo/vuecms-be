<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'bill_id',
        'product_id',
        'name',
        'cost',
        'quatity',
        'ship_cost',
        'total_cost'
    ];

    public function sub_items() {
        return $this->hasMany(BillSubItem::class);
    }
}
