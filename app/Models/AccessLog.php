<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'facility_id',
        'access_time',
        'check_out_time',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
