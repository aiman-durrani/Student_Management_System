<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentProfileController extends Controller
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
     * Display the student's read-only profile.
     */
    public function show()
    {
        $student = $this->getStudentRecord();

        return view('student.profile.show', compact('student'));
    }
}
