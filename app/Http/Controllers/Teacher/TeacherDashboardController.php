<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherDashboardController extends Controller
{
    /**
     * Resolve the logged-in user's teacher record.
     */
    private function getTeacherRecord(): Teacher
    {
        $user = auth()->user();

        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher && $user->email) {
            $teacher = Teacher::where('email', $user->email)->first();
            if ($teacher) {
                $teacher->update(['user_id' => $user->id]);
            }
        }

        if (!$teacher) {
            abort(403, 'No teacher profile found for your account.');
        }

        return $teacher;
    }

    /**
     * Display the teacher dashboard.
     */
    public function index()
    {
        $teacher = $this->getTeacherRecord();

        // Get teacher's courses with student count
        $courses = $teacher->courses()->withCount('students')->get();

        $coursesCount = $courses->count();

        // Get total distinct enrolled students across assigned courses
        $totalStudentsCount = Student::whereHas('courses', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->count();

        return view('teacher.dashboard', compact('teacher', 'courses', 'coursesCount', 'totalStudentsCount'));
    }
}
