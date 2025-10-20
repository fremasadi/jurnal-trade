<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    // 🧮 Accessor: hitung selisih harga otomatis (opsional)
    public function getPipsAttribute(): ?float
    {
        if ($this->exit_price && $this->entry_price) {
            return abs($this->exit_price - $this->entry_price);
        }
        return null;
    }
}
