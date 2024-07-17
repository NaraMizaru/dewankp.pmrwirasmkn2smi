<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemilu extends Model
{
    use HasFactory;
    public function kandidat()
    {
        return $this->hasMany(Kandidat::class);
    }
}
