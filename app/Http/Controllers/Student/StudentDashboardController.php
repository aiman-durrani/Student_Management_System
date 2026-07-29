<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    /**
     * Resolve the logged-in user's student record.
     */
    private function getStudentRecord(): ?Student
    {
        $user = auth()->user();

        $student = Student::where('user_id', $user->id)->first();

        if (!$student && $user->email) {
            $student = Student::where('email', $user->email)->first();
            if ($student) {
                $student->update(['user_id' => $user->id]);
            }
        }

        return $student;
    }

    /**
     * Display the student dashboard.
     */
    public function index()
    {
        $student = $this->getStudentRecord();

        if (!$student) {
            return view('student.dashboard', [
                'student' => null,
                'courses' => collect(),
                'coursesCount' => 0,
                'overallAttendancePct' => 0,
                'overallGradeAvgPct' => 0,
            ]);
        }

        // Load enrolled courses with teacher relationship
        $courses = $student->courses()->with('teacher')->get();
        $coursesCount = $courses->count();

        // Calculate overall attendance percentage (present days / total marked days)
        $totalAttendance = $student->attendances()->count();
        $presentAttendance = $student->attendances()->where('status', 'present')->count();
        $overallAttendancePct = $totalAttendance > 0 
            ? round(($presentAttendance / $totalAttendance) * 100, 1) 
            : 0;

        // Calculate average grade percentage across all recorded assessments
        $grades = $student->grades;
        $overallGradeAvgPct = $grades->isNotEmpty() 
            ? round($grades->avg(function ($grade) {
                return $grade->total_marks > 0 ? ($grade->marks_obtained / $grade->total_marks) * 100 : 0;
            }), 1) 
            : 0;

        return view('student.dashboard', compact(
            'student',
            'courses',
            'coursesCount',
            'overallAttendancePct',
            'overallGradeAvgPct'
        ));
    }
}
