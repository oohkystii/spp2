<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings'; 
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = ['pj_ttd'];
}