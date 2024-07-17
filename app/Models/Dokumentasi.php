<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'status',
        'unit_id',
    ];

    public function dokumentasiAttachment()
    {
        return $this->hasMany(DokumentasiAttachment::class);
    }
}
