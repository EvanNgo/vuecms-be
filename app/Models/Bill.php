<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
    public $timestamps = true;
    
    protected $fillable = [
        'name',
        'status',
        'total_cost',
        'created_at',
        'updated_at'
    ];

    public function items() {
        return $this->hasMany(BillItem::class);
    }
}
