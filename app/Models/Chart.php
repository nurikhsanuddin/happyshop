<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    use HasFactory;
    protected $table = 'charts';
    protected $guarded = [];

    // public function product()
    // {
    //     return $this->belongsTo(Product::class);
    // }
    // public function marketing()
    // {
    //     return $this->belongsTo(Marketing::class);
    // }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
