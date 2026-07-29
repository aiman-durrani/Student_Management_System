<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'student_id',
        'assessment_name',
        'marks_obtained',
        'total_marks',
    ];

    /**
     * Get the course that the grade belongs to.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the student that the grade belongs to.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
