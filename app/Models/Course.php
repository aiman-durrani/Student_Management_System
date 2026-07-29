<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'teacher_id',
        'description',
    ];

    /**
     * Get the teacher that teaches the course.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * The students that are enrolled in the course.
     */
    public function students()
    {
        return $this->belongsToMany(Student::class)->withTimestamps();
    }

    /**
     * Get the attendance records for the course.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the grade records for the course.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
