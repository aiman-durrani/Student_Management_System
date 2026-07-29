<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
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
        'employee_id',
        'subject',
        'phone',
        'address',
    ];

    /**
     * Get the user that owns the teacher record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the courses taught by the teacher.
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
