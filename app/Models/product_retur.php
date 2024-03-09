<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product_retur extends Model
{
    use HasFactory;
    protected $table = 'product_returs';
    protected $guarded = [];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function retur()
    {
        return $this->belongsTo(Retur::class);
    }
}
