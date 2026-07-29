<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'roll_number',
        'date_of_birth',
        'phone',
        'address',
    ];

    /**
     * Get the user that owns the student record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array  //casts()→ Automatically converts database values into the correct PHP data type.
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    /**
     * The courses that the student is enrolled in.
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class)->withTimestamps();
    }

    /**
     * Get the attendance records for the student.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the grade records for the student.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
