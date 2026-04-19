<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name',
        'nisn',
        'class',
        'latitude',
        'longitude',
        'verified_at'
    ];

    public function verification()
    {
        return $this->hasOne(Verification::class);
    }
}
