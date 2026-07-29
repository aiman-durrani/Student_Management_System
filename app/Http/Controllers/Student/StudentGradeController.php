<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentGradeController extends Controller
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
     * Display student grades.
     */
    public function index(Request $request)
    {
        $student = $this->getStudentRecord();

        if (!$student) {
            return view('student.grades.index', [
                'student' => null,
                'enrolledCourses' => collect(),
                'gradesByCourse' => collect(),
                'courseAverages' => collect(),
                'selectedCourseId' => null,
            ]);
        }

        $enrolledCourses = $student->courses()->orderBy('name')->get();

        $selectedCourseId = $request->input('course_id');

        $query = $student->grades()->with('course');

        if ($selectedCourseId) {
            $query->where('course_id', $selectedCourseId);
        }

        $allGrades = $query->orderBy('created_at', 'desc')->get();

        // Group grades by course
        $gradesByCourse = $allGrades->groupBy('course_id');

        // Calculate average grade percentage and progress bar value per course
        $courseAverages = $enrolledCourses->mapWithKeys(function ($course) use ($student) {
            $courseGrades = $student->grades->where('course_id', $course->id);
            
            $averagePct = $courseGrades->isNotEmpty()
                ? round($courseGrades->avg(function ($g) {
                    return $g->total_marks > 0 ? ($g->marks_obtained / $g->total_marks) * 100 : 0;
                }), 1)
                : 0;

            return [$course->id => [
                'course' => $course,
                'count' => $courseGrades->count(),
                'average_pct' => $averagePct,
            ]];
        });

        return view('student.grades.index', compact(
            'student',
            'enrolledCourses',
            'gradesByCourse',
            'courseAverages',
            'selectedCourseId'
        ));
    }
}
