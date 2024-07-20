<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Nicolaslopezj\Searchable\SearchableTrait;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SearchableTrait;
    protected $searchable = [
        'columns' => [
            'name' => 10,
            'email' => 10,
        ],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'akses',
        'nohp',
        'nohp_verified_at',
        'foto',
        'fcm_token'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'fcm_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeWali($q)
    {
        return $q->where('akses', 'wali');
    }

    /**
     * Get all of the siswa for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function siswa(): HasMany
    {
        return $this->hasMany(Siswa::class, 'wali_id', 'id');
    }

    public function getAllSiswaId(): array
    {
        return $this->siswa->pluck('id')->toArray();
    }

    public function sendPasswordResetNotification($token)
    {
        // $this->notify(new ResetPasswordNotification($token));
    }

    protected function getFotoUrlAttribute($value)
    {
        $defaultFoto = 'images/user.png';

        if ($this->attributes['foto'] == null || $this->attributes['foto'] == "") {
            return asset($defaultFoto);
        }

        $foto = (Storage::exists($this->attributes['foto'])) ? $this->attributes['foto'] : $defaultFoto;
        
        return Storage::url($foto);
    }

    public function getNameWithNohpAttribute()
    {
        return $this->name . '(' . $this->nohp . ')';
    }

}
