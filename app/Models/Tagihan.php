<?php

namespace App\Models;

use App\Traits\HasFormatRupiah;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Nicolaslopezj\Searchable\SearchableTrait;

class Tagihan extends Model
{
    use HasFactory;
    use HasFormatRupiah;
    use SearchableTrait;

    protected $searchable = [
        'columns' => [
            'siswas.nama' => 10,
            'siswas.nisn' => 9,
        ],
        'joins' => [
            'siswas' => ['siswas.id', 'tagihans.siswa_id'],
        ],
    ];

    protected $guarded = [];
    protected $dates = ['tanggal_tagihan', 'tanggal_jatuh_tempo', 'tanggal_lunas'];
    protected $with = ['user'];
    protected $append = ['total_tagihan', 'total_pembayaran'];

    public function getStatusStyleAttribute()
    {
        if ($this->status == 'lunas') {
            return 'success';
        }
        if ($this->status == 'angsur') {
            return 'warning';
        }
        if ($this->status == 'baru') {
            return 'info';
        }
    }

    protected function totalPembayaran(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->pembayaran->sum('jumlah_dibayar'),
        );
    }

    public function getTahunAjaranAttribute()
    {
        $bulanAwal = bulanSPP()[0]; //7
        $bulanSekarang = intval(date('m'));
        if ($bulanSekarang < $bulanAwal) {
            return $this->tanggal_tagihan->year - 1;
        }
        return $this->tanggal_tagihan->year;
    }

    protected function totalTagihan(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->tagihanDetails->sum('jumlah_biaya'),
        );
    }

    /**
     * Get the user that owns the Tagihan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the siswa that owns the Tagihan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class)->withDefault();
    }

    /**
     * Get all of the tagihanDetails for the Tagihan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tagihanDetails(): HasMany
    {
        return $this->hasMany(TagihanDetail::class);
    }

    /**
     * Get all of the pembayaran for the Tagihan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function getStatusTagihanWali()
    {
        if ($this->status == 'baru') {
            return 'Belum dibayar';
        }
        if ($this->status == 'lunas') {
            return 'Sudah dibayar';
        }
        return $this->status;
    }

    public function scopeWaliSiswa($q)
    {
        return $q->whereIn('siswa_id', Auth::user()->getAllSiswaId());
    }

    // mengubah status tagihan 
    public function updateStatus()
    {
        if ($this->total_pembayaran >= $this->total_tagihan) {
            $tanggalBayar = $this->pembayaran()
                ->orderBy('tanggal_bayar', 'desc')
                ->first()
                ->tanggal_bayar;
            $this->update([
                'status' => 'lunas',
                'tanggal_lunas' => $tanggalBayar,
            ]);
        }

        if ($this->total_pembayaran > 0 && $this->total_pembayaran < $this->total_tagihan) {
            $this->update(['status' => 'angsur', 'tanggal_lunas' => null]);
        }

        if ($this->total_pembayaran <= 0) {
            $this->update(['status' => 'baru', 'tanggal_lunas' => null]);
        }
    }

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class);
    }

    public function getNomorTagihan()
    {
        return "SPP-MA-" . $this->id;
    }

    /**
     * Get the biaya that owns the Tagihan
     * 
     * @return 
     */
    public function biaya(): BelongsTo
    {
        return $this->belongsTo(Biaya::class);
    }
}
