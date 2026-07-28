<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $courses = Course::query()
            ->with('teacher')
            ->withCount('students')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.courses.index', compact('courses', 'search'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        $teachers = Teacher::orderBy('name')->get();
        $students = Student::orderBy('name')->get();

        return view('admin.courses.create', compact('teachers', 'students'));
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:courses,code'],
            'teacher_id' => ['nullable', 'exists:teachers,id'],
            'description' => ['nullable', 'string'],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['exists:students,id'],
        ]);

        $course = Course::create([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'teacher_id' => $validated['teacher_id'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        $course->students()->sync($request->input('student_ids', []));

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        $course->load(['teacher', 'students']);

        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        $course->load('students');
        $teachers = Teacher::orderBy('name')->get();
        $students = Student::orderBy('name')->get();

        return view('admin.courses.edit', compact('course', 'teachers', 'students'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', Rule::unique('courses', 'code')->ignore($course->id)],
            'teacher_id' => ['nullable', 'exists:teachers,id'],
            'description' => ['nullable', 'string'],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['exists:students,id'],
        ]);

        $course->update([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'teacher_id' => $validated['teacher_id'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        $course->students()->sync($request->input('student_ids', []));

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course details updated successfully.');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course record deleted successfully.');
    }
}
