<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Display the Admin Dashboard.
     */
    public function index()
    {
        // 1. Overall counts
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalCourses = Course::count();

        // 2. Specialized course metrics
        $unassignedCoursesCount = Course::whereNull('teacher_id')->count();
        $emptyCoursesCount = Course::doesntHave('students')->count();

        // 3. Recent activities (5 latest)
        $recentStudents = Student::latest()->take(5)->get(['id', 'name', 'roll_number', 'email', 'created_at']);
        $recentCourses = Course::with('teacher:id,name')->latest()->take(5)->get(['id', 'name', 'code', 'teacher_id', 'created_at']);

        // 4. Top 5 courses by enrolled students
        $topCourses = Course::withCount('students')
            ->orderByDesc('students_count')
            ->take(5)
            ->get(['id', 'name', 'code', 'students_count']);

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalTeachers',
            'totalCourses',
            'unassignedCoursesCount',
            'emptyCoursesCount',
            'recentStudents',
            'recentCourses',
            'topCourses'
        ));
    }
}
