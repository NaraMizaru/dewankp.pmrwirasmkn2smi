<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengurus extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'nis',
        // 'status',
        'kelas_id',
        'unit_id',
        'bidang_id',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }


}
