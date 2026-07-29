<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Teacher\TeacherDashboardController;
use App\Http\Controllers\Teacher\TeacherCourseController;
use App\Http\Controllers\Teacher\AttendanceController;
use App\Http\Controllers\Teacher\GradeController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\StudentProfileController;
use App\Http\Controllers\Student\StudentAttendanceController;
use App\Http\Controllers\Student\StudentGradeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'teacher' => redirect()->route('teacher.dashboard'),
        default => redirect()->route('student.dashboard'),
    };
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('students', StudentController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('courses', CourseController::class);
});

Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::get('courses/{course}', [TeacherCourseController::class, 'show'])->name('courses.show');
    Route::get('courses/{course}/attendance', [AttendanceController::class, 'index'])->name('courses.attendance.index');
    Route::get('courses/{course}/attendance/create', [AttendanceController::class, 'create'])->name('courses.attendance.create');
    Route::post('courses/{course}/attendance', [AttendanceController::class, 'store'])->name('courses.attendance.store');
    Route::get('courses/{course}/grades', [GradeController::class, 'index'])->name('courses.grades.index');
    Route::get('courses/{course}/grades/create', [GradeController::class, 'create'])->name('courses.grades.create');
    Route::post('courses/{course}/grades', [GradeController::class, 'store'])->name('courses.grades.store');
});

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', [StudentProfileController::class, 'show'])->name('profile.show');
    Route::get('attendance', [StudentAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('grades', [StudentGradeController::class, 'index'])->name('grades.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
