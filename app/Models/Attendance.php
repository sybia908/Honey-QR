<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'qrcode_id',
        'date',
        'time_in',
        'time_out',
        'status',
        'latitude_in',
        'longitude_in',
        'latitude_out',
        'longitude_out'
    ];

    protected $casts = [
        'date' => 'date',
        'time_in' => 'datetime',
        'time_out' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function qrcode()
    {
        return $this->belongsTo(QRCode::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id', 'user_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'user_id', 'user_id');
    }
}
