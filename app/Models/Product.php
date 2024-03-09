<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function productSupply()
    {
        return $this->hasMany(ProductSupply::class);
    }
    public function productTransactions()
    {
        return $this->hasMany(ProductTransaction::class);
    }
}
