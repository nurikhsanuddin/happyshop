<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;
    protected $table = 'supply';
    protected $guarded = [];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function productSupply()
    {
        return $this->hasMany(ProductSupply::class);
    }
}
