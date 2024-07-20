<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $dates = ['tanggal_bayar', 'tanggal_konfirmasi'];
    protected $with = ['user', 'tagihan'];
    protected $append = ['status_konfirmasi'];

    public function getStatusStyleAttribute()
    {
        if ($this->tanggal_konfirmasi == null) {
            return 'secondary';
        }
        return 'success';
    }

    /**
     * Interact with the user's first name.
     * 
     * @return \\Illminate\Database\Eloquent\Casts\Attribute
     */
    protected function statusKonfirmasi(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ($this->tanggal_konfirmasi == null) ? 'Belum Dikonfirmasi' : 'Sudah Dikonfirmasi',
        );
    }

    /**
     * Get the tagihan that owns the Pembayaran
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tagihan(): BelongsTo
    {
        return $this->belongsTo(Tagihan::class);
    }

    /**
     * Get the user that owns the Pembayaran
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The "booted" method of the model.
     * 
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($pembayaran) {
            $pembayaran->tagihan->updateStatus();
        });
        static::updated(function ($pembayaran) {
            $pembayaran->tagihan->updateStatus();
        });
        static::deleted(function ($pembayaran) {
            $pembayaran->tagihan->updateStatus();
        });
        static::creating(function ($pembayaran) {
            $pembayaran->user_id = auth()->user()->id;
        });

        static::updating(function ($pembayaran) {
            $pembayaran->user_id = auth()->user()->id;
        });
    }

    /**
     * Get the wali that owns the Pembayaran
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wali(): BelongsTo
    {
        return $this->belongsTo(User::class, 'wali_id');
    }
}
