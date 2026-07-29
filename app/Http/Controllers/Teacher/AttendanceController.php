<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
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
     * Display attendance history for a specific course.
     */
    public function index(Request $request, Course $course)
    {
        $teacher = $this->getTeacherRecord();
        $this->authorizeCourseOwnership($teacher, $course);

        $query = Attendance::where('course_id', $course->id)
            ->with('student');

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $attendancesRaw = $query->orderBy('date', 'desc')->get();

        // Group attendance records by date (YYYY-MM-DD)
        $groupedAttendances = $attendancesRaw->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('Y-m-d');
        });

        return view('teacher.attendance.index', compact('course', 'groupedAttendances'));
    }

    /**
     * Show form to mark attendance for a course.
     */
    public function create(Request $request, Course $course)
    {
        $teacher = $this->getTeacherRecord();
        $this->authorizeCourseOwnership($teacher, $course);

        $date = $request->input('date', Carbon::today()->format('Y-m-d'));

        $students = $course->students()->orderBy('name')->get();

        // Fetch existing attendances for this course and date to pre-fill
        $existingAttendances = Attendance::where('course_id', $course->id)
            ->whereDate('date', $date)
            ->get()
            ->keyBy('student_id');

        return view('teacher.attendance.create', compact('course', 'students', 'date', 'existingAttendances'));
    }

    /**
     * Store or update attendance records for a course.
     */
    public function store(Request $request, Course $course)
    {
        $teacher = $this->getTeacherRecord();
        $this->authorizeCourseOwnership($teacher, $course);

        $request->validate([
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:present,absent,late',
        ]);

        foreach ($request->attendances as $record) {
            Attendance::updateOrCreate(
                [
                    'course_id' => $course->id,
                    'student_id' => $record['student_id'],
                    'date' => $request->date,
                ],
                [
                    'status' => $record['status'],
                ]
            );
        }

        return redirect()->route('teacher.courses.attendance.index', $course)
            ->with('success', 'Attendance marked successfully for ' . $request->date . '.');
    }
}
