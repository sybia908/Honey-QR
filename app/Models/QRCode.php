<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QRCode extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'qrcodes';

    protected $fillable = [
        'code',
        'created_by',
        'valid_until',
        'is_active',
        'class_id',
        'subject_id',
        'active_from',
        'active_until'
    ];

    protected $casts = [
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
        'active_from' => 'datetime:H:i',
        'active_until' => 'datetime:H:i'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
