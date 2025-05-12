<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nip',
        'user_id',
        'phone',
        'address',
        'birth_date',
        'gender',
        'position',
        'is_active'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function homeRoomClasses()
    {
        return $this->hasMany(Classes::class, 'homeroom_teacher_id', 'user_id');
    }

    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'class_teacher');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'user_id', 'user_id');
    }
}
