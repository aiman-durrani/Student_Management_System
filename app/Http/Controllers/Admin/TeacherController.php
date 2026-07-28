<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    /**
     * Display a listing of the teachers.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $teachers = Teacher::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('employee_id', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.teachers.index', compact('teachers', 'search'));
    }

    /**
     * Show the form for creating a new teacher.
     */
    public function create()
    {
        return view('admin.teachers.create');
    }

    /**
     * Store a newly created teacher in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:teachers,email'],
            'employee_id' => ['required', 'string', 'max:255', 'unique:teachers,employee_id'],
            'subject' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
        ]);

        Teacher::create($validated);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    /**
     * Display the specified teacher.
     */
    public function show(Teacher $teacher)
    {
        return view('admin.teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified teacher.
     */
    public function edit(Teacher $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified teacher in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('teachers', 'email')->ignore($teacher->id)],
            'employee_id' => ['required', 'string', 'max:255', Rule::unique('teachers', 'employee_id')->ignore($teacher->id)],
            'subject' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
        ]);

        $teacher->update($validated);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher details updated successfully.');
    }

    /**
     * Remove the specified teacher from storage.
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher record deleted successfully.');
    }
}
