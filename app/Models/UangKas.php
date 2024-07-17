<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UangKas extends Model
{
    use HasFactory;
    protected $fillable = [
        'bulan',
        'pemasukan',
        'pengeluaran',
        'saldo'
    ];

    public $timestamps = false;
}
