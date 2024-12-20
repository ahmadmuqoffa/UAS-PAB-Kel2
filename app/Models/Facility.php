<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $table = 'facilities';

    public function accessLogs()
    {
        return $this->hasMany(AccessLog::class);
    }
}