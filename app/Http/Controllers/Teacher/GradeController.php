<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Teacher;
use Illuminate\Http\Request;

class GradeController extends Controller
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
     * Verify that the course is assigned to the logged-in teacher.
     */
    private function authorizeCourseOwnership(Teacher $teacher, Course $course): void
    {
        if ((int)$course->teacher_id !== (int)$teacher->id) {
            abort(403, 'Unauthorized action. This course is not assigned to you.');
        }
    }

    /**
     * Display grades for a specific course.
     */
    public function index(Course $course)
    {
        $teacher = $this->getTeacherRecord();
        $this->authorizeCourseOwnership($teacher, $course);

        $gradesRaw = Grade::where('course_id', $course->id)
            ->with('student')
            ->orderBy('created_at', 'desc')
            ->get();

        // Group by assessment_name
        $groupedGrades = $gradesRaw->groupBy('assessment_name');

        return view('teacher.grades.index', compact('course', 'groupedGrades'));
    }

    /**
     * Show form to enter grades for a course.
     */
    public function create(Course $course)
    {
        $teacher = $this->getTeacherRecord();
        $this->authorizeCourseOwnership($teacher, $course);

        $students = $course->students()->orderBy('name')->get();

        return view('teacher.grades.create', compact('course', 'students'));
    }

    /**
     * Store grade records for a course.
     */
    public function store(Request $request, Course $course)
    {
        $teacher = $this->getTeacherRecord();
        $this->authorizeCourseOwnership($teacher, $course);

        $totalMarks = $request->input('total_marks');

        $request->validate([
            'assessment_name' => 'required|string|max:255',
            'total_marks' => 'required|numeric|gt:0',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.marks_obtained' => 'required|numeric|min:0|max:' . ($totalMarks ?? 100),
        ], [
            'grades.*.marks_obtained.max' => 'Marks obtained cannot exceed total marks (' . $totalMarks . ').',
        ]);

        foreach ($request->grades as $record) {
            Grade::updateOrCreate(
                [
                    'course_id' => $course->id,
                    'student_id' => $record['student_id'],
                    'assessment_name' => $request->assessment_name,
                ],
                [
                    'marks_obtained' => $record['marks_obtained'],
                    'total_marks' => $request->total_marks,
                ]
            );
        }

        return redirect()->route('teacher.courses.grades.index', $course)
            ->with('success', 'Grades recorded successfully for ' . $request->assessment_name . '.');
    }
}
