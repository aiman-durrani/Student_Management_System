<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small text-muted">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('teacher.courses.show', $course) }}" class="text-decoration-none">{{ $course->code }}</a></li>
                        <li class="breadcrumb-item active">Grades Overview</li>
                    </ol>
                </nav>
                <h2 class="h4 font-weight-bold mb-0">
                    <i class="bi bi-journal-check me-2 text-success"></i>Grades Overview — {{ $course->name }}
                </h2>
            </div>
            <div>
                <a href="{{ route('teacher.courses.grades.create', $course) }}" class="btn btn-success btn-sm me-2">
                    <i class="bi bi-plus-lg me-1"></i> Enter Grades
                </a>
                <a href="{{ route('teacher.courses.show', $course) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Course
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-2">
        @forelse($groupedGrades as $assessmentName => $grades)
            @php
                $firstGrade = $grades->first();
                $totalMarks = $firstGrade ? $firstGrade->total_marks : 0;
            @endphp
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-file-earmark-spreadsheet me-2 text-success"></i>Assessment: {{ $assessmentName }}
                        <span class="badge bg-secondary ms-2">Total Marks: {{ number_format($totalMarks, 2) }}</span>
                    </h6>
                    <span class="badge bg-info text-dark">{{ $grades->count() }} {{ Str::plural('Student', $grades->count()) }} Graded</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small">
                                <tr>
                                    <th scope="col" class="ps-4">Roll Number</th>
                                    <th scope="col">Student Name</th>
                                    <th scope="col" class="text-center">Marks Obtained</th>
                                    <th scope="col" class="text-center">Total Marks</th>
                                    <th scope="col" class="text-center">Percentage</th>
                                    <th scope="col" class="text-center">Grade / Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($grades as $grade)
                                    @php
                                        $pct = $totalMarks > 0 ? round(($grade->marks_obtained / $totalMarks) * 100, 2) : 0;
                                        $badgeColor = match(true) {
                                            $pct >= 85 => 'bg-success',
                                            $pct >= 70 => 'bg-info text-dark',
                                            $pct >= 50 => 'bg-warning text-dark',
                                            default => 'bg-danger',
                                        };
                                    @endphp
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge bg-secondary font-monospace">{{ $grade->student->roll_number ?? 'N/A' }}</span>
                                        </td>
                                        <td class="fw-semibold text-dark">
                                            {{ $grade->student->name ?? 'Deleted Student' }}
                                        </td>
                                        <td class="text-center fw-bold">
                                            {{ number_format($grade->marks_obtained, 2) }}
                                        </td>
                                        <td class="text-center text-muted">
                                            {{ number_format($grade->total_marks, 2) }}
                                        </td>
                                        <td class="text-center fw-bold">
                                            {{ $pct }}%
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $badgeColor }} px-3 py-1">
                                                {{ $pct >= 50 ? 'Passed' : 'Failed' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            <div class="card shadow-sm border-0 p-5 text-center text-muted">
                <i class="bi bi-file-earmark-x display-3 mb-3 text-secondary"></i>
                <h5>No grades have been entered for this course yet.</h5>
                <p class="mb-3">Click below to enter the first assessment grades.</p>
                <div>
                    <a href="{{ route('teacher.courses.grades.create', $course) }}" class="btn btn-success">
                        <i class="bi bi-plus-lg me-1"></i> Enter Grades Now
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</x-app-layout>
