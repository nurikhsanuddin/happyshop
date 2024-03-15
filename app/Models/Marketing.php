<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marketing extends Model
{
    use HasFactory;
    protected $table = 'marketings';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function chart()
    {
        return $this->hasMany(Chart::class);
    }
}
