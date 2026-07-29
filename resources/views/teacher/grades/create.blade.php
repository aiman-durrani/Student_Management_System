<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small text-muted">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('teacher.courses.show', $course) }}" class="text-decoration-none">{{ $course->code }}</a></li>
                        <li class="breadcrumb-item active">Enter Grades</li>
                    </ol>
                </nav>
                <h2 class="h4 font-weight-bold mb-0">
                    <i class="bi bi-award me-2 text-success"></i>Enter Grades — {{ $course->name }}
                </h2>
            </div>
            <a href="{{ route('teacher.courses.show', $course) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Course
            </a>
        </div>
    </x-slot>

    <div class="container-fluid py-2">
        <form method="POST" action="{{ route('teacher.courses.grades.store', $course) }}">
            @csrf

            <!-- Assessment Details Card -->
            <div class="card shadow-sm border-0 mb-4 bg-white">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-file-earmark-text me-2 text-success"></i>Assessment Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="assessment_name" class="form-label fw-bold">Assessment Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   id="assessment_name" 
                                   name="assessment_name" 
                                   class="form-control @error('assessment_name') is-invalid @enderror" 
                                   placeholder="e.g. Midterm Exam, Assignment 1, Quiz 2" 
                                   value="{{ old('assessment_name') }}" 
                                   required>
                            @error('assessment_name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="total_marks" class="form-label fw-bold">Total Maximum Marks <span class="text-danger">*</span></label>
                            <input type="number" 
                                   step="0.01" 
                                   min="0.01" 
                                   id="total_marks" 
                                   name="total_marks" 
                                   class="form-control @error('total_marks') is-invalid @enderror" 
                                   placeholder="e.g. 100 or 50" 
                                   value="{{ old('total_marks', 100) }}" 
                                   required>
                            @error('total_marks')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Grades Roster Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-people me-2 text-success"></i>Student Marks Roster ({{ $students->count() }} Enrolled)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="ps-4">#</th>
                                    <th scope="col">Roll Number</th>
                                    <th scope="col">Student Name</th>
                                    <th scope="col" style="width: 250px;">Marks Obtained</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $index => $student)
                                    <tr>
                                        <th scope="row" class="ps-4 text-muted">{{ $loop->iteration }}</th>
                                        <td>
                                            <span class="badge bg-secondary font-monospace">{{ $student->roll_number }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $student->name }}</div>
                                            <input type="hidden" name="grades[{{ $index }}][student_id]" value="{{ $student->id }}">
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="number" 
                                                       step="0.01" 
                                                       min="0" 
                                                       name="grades[{{ $index }}][marks_obtained]" 
                                                       class="form-control @error("grades.{$index}.marks_obtained") is-invalid @enderror" 
                                                       placeholder="0.00" 
                                                       value="{{ old("grades.{$index}.marks_obtained", 0) }}" 
                                                       required>
                                                <span class="input-group-text bg-light text-muted">pts</span>
                                            </div>
                                            @error("grades.{$index}.marks_obtained")
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            No students enrolled in this course to enter grades.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if(!$students->isEmpty())
                    <div class="card-footer bg-white py-3 d-flex justify-content-end gap-2 border-top">
                        <a href="{{ route('teacher.courses.show', $course) }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-save me-1"></i> Save Grades
                        </button>
                    </div>
                @endif
            </div>
        </form>
    </div>
</x-app-layout>
