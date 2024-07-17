<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kandidat extends Model
{
    use HasFactory;
    public function pemilu()
    {
        return $this->belongsTo(Pemilu::class);
    }

    public function voting()
    {
        return $this->hasMany(Voting::class);
    }
}
