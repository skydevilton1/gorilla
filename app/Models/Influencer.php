<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Influencer extends Model
{
    use HasFactory;

    public function thaiProvince()
    {
        return $this->belongsTo(Thai_province::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
