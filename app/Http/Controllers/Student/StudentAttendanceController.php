<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentAttendanceController extends Controller
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
     * Display student attendance records.
     */
    public function index(Request $request)
    {
        $student = $this->getStudentRecord();

        if (!$student) {
            return view('student.attendance.index', [
                'student' => null,
                'enrolledCourses' => collect(),
                'attendances' => collect(),
                'courseSummaries' => collect(),
                'selectedCourseId' => null,
            ]);
        }

        $enrolledCourses = $student->courses()->orderBy('name')->get();

        // Build per-course attendance summary
        $allAttendances = $student->attendances()->with('course')->get();

        $courseSummaries = $enrolledCourses->map(function ($course) use ($allAttendances) {
            $courseRecords = $allAttendances->where('course_id', $course->id);
            $total = $courseRecords->count();
            $present = $courseRecords->where('status', 'present')->count();
            $late = $courseRecords->where('status', 'late')->count();
            $absent = $courseRecords->where('status', 'absent')->count();
            $pct = $total > 0 ? round(($present / $total) * 100, 1) : 0;

            return [
                'course' => $course,
                'total' => $total,
                'present' => $present,
                'late' => $late,
                'absent' => $absent,
                'percentage' => $pct,
            ];
        });

        // Filter attendance table records by selected course if specified
        $selectedCourseId = $request->input('course_id');
        
        $query = $student->attendances()->with('course');

        if ($selectedCourseId) {
            $query->where('course_id', $selectedCourseId);
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        return view('student.attendance.index', compact(
            'student',
            'enrolledCourses',
            'attendances',
            'courseSummaries',
            'selectedCourseId'
        ));
    }
}
