<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumentasiAttachment extends Model
{
    use HasFactory;
    protected $fillable = [
        'dokumentasi_id',
        'image_path',
    ];

    public function dokumentasi()
    {
        return $this->belongsTo(Dokumentasi::class);
    }

    public $timestamps = false;
}
