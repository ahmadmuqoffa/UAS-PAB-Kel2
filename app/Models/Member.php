<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'membership_type',
        'valid_until',
    ];

    protected $table = 'members';

    // Relasi ke log akses
    public function accessLogs()
    {
        return $this->hasMany(AccessLog::class);
    }
}
