<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pair extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'symbol_name',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Trade
    public function trades()
    {
        return $this->hasMany(Trade::class);
    }
}
