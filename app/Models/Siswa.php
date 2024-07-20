<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Spatie\ModelStatus\HasStatuses;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Storage;

class Siswa extends Model
{
    use HasFactory;
    use SearchableTrait;
    use HasStatuses;

    protected $dates = ['tanggal_lunas'];
    protected $guarded = [];
    protected $searchable = [
        'columns' => [
            'nama' => 10,
            'nisn' => 10,
        ],
    ];

    // Set default foto attribute
    protected function getFotoAttribute($value)
    {
        $defaultFoto = 'images/user.png';
        if ($value == null) {
            return $defaultFoto;
        }
        return (Storage::exists($value)) ? $value : $defaultFoto;
    }

    /**
     * Get all of the biaya for the Siswa
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function biaya(): BelongsTo
    {
        return $this->belongsTo(Biaya::class);
    }

    /**
     * Get the user that owns the Siswa
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the wali that owns the Siswa
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wali(): BelongsTo
    {
        return $this->belongsTo(User::class, 'wali_id');
    }

    /**
     * Get all of the tagihan for the Siswa
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tagihan(): HasMany
    {
        return $this->hasMany(Tagihan::class);
    }

    /**
     * The "booted" method of the model.
     * 
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($siswa) {
            $siswa->user_id = auth()->user()->id;
        });

        static::created(function ($siswa) {
            $siswa->user_id = auth()->user()->id;
            $siswa->setStatus('aktif');
        });

        static::updating(function ($siswa) {
            $siswa->user_id = auth()->user()->id;
        });
    }
}
