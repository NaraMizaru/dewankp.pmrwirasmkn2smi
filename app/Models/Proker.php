<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proker extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'tanggal',
        'unit_id',
        'dokuementasi_id'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
