<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Trade extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mentor_id',
        'pair_id',
        'direction',
        'entry_price',
        'exit_price',
        'sl_price',
        'tp_price',
        'lot_size',
        'entry_time',
        'exit_time',
        'result',
        'pnl_value',
        'reason_entry',
        'notes',
        'screenshot_img',
    ];

    // 🔗 Relasi ke tabel lain
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }

    public function pair()
    {
        return $this->belongsTo(Pair::class);
    }

    public function getPipsAttribute(): ?float
{
    if ($this->exit_price && $this->entry_price) {
        // Selisih harga
        $difference = abs($this->exit_price - $this->entry_price);

        // 1 poin = 10 pips
        return $difference * 10;
    }

    return null;
}

    protected static function boot()
{
    parent::boot();

    static::creating(function ($trade) {
        if (Auth::check()) {
            $trade->user_id = Auth::id();
        }
    });
}
}
