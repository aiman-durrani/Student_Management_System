<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherCourseController extends Controller
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
     * Display course details and enrolled students list.
     */
    public function show(Course $course)
    {
        $teacher = $this->getTeacherRecord();

        // Verify course ownership
        $this->authorizeCourseOwnership($teacher, $course);

        $course->load(['students' => function ($query) {
            $query->orderBy('name');
        }]);

        return view('teacher.courses.show', compact('teacher', 'course'));
    }
}
