<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    protected $fillable = [
        'student_id',
        'latitude',
        'longitude',
        'verified_at'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
