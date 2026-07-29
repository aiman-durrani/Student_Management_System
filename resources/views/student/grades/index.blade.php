<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small text-muted">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">My Grades</li>
                    </ol>
                </nav>
                <h2 class="h4 font-weight-bold mb-0">
                    <i class="bi bi-award me-2 text-success"></i>My Academic Grades
                </h2>
            </div>
            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="container-fluid py-2">
        @if(!$student)
            <div class="card border-0 shadow-sm p-5 text-center bg-white">
                <i class="bi bi-exclamation-triangle display-3 text-warning mb-3"></i>
                <h4 class="fw-bold text-dark">Student Profile Not Found</h4>
                <p class="text-muted">No student record is associated with your account.</p>
            </div>
        @else
            <!-- Per-Course Average Percentage & Progress Bar Summary -->
            <div class="mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-bar-chart-line me-2 text-success"></i>Per-Course Performance Overview
                </h5>

                @if($courseAverages->isEmpty())
                    <div class="alert alert-info border-0 shadow-sm">You are not enrolled in any courses.</div>
                @else
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                        @foreach($courseAverages as $avgData)
                            @php
                                $course = $avgData['course'];
                                $avgPct = $avgData['average_pct'];
                                $badgeClass = match(true) {
                                    $avgPct >= 85 => 'bg-success',
                                    $avgPct >= 70 => 'bg-info text-dark',
                                    $avgPct >= 50 => 'bg-warning text-dark',
                                    default => 'bg-danger',
                                };
                            @endphp
                            <div class="col">
                                <div class="card border-0 shadow-sm h-100 bg-white">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge bg-secondary font-monospace">{{ $course->code }}</span>
                                            <span class="badge {{ $badgeClass }} fs-6">
                                                Average: {{ $avgPct }}%
                                            </span>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-2">{{ $course->name }}</h6>
                                        <div class="progress mb-2" style="height: 8px;">
                                            <div class="progress-bar {{ $avgPct >= 50 ? 'bg-success' : 'bg-danger' }}" 
                                                 role="progressbar" 
                                                 style="width: {{ $avgPct }}%" 
                                                 aria-valuenow="{{ $avgPct }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between small text-muted">
                                            <span>Recorded Assessments: <strong>{{ $avgData['count'] }}</strong></span>
                                            <span>Status: <strong>{{ $avgPct >= 50 ? 'Passing' : 'Needs Improvement' }}</strong></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Course Filter Card -->
            <div class="card shadow-sm border-0 mb-4 bg-white">
                <div class="card-body">
                    <form method="GET" action="{{ route('student.grades.index') }}" class="row g-3 align-items-center">
                        <div class="col-md-5 col-lg-4">
                            <label for="course_id" class="form-label small fw-bold text-muted mb-1">Filter by Course</label>
                            <select id="course_id" name="course_id" class="form-select" onchange="this.form.submit()">
                                <option value="">-- All Enrolled Courses --</option>
                                @foreach($enrolledCourses as $course)
                                    <option value="{{ $course->id }}" {{ (string)$selectedCourseId === (string)$course->id ? 'selected' : '' }}>
                                        {{ $course->code }} - {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if($selectedCourseId)
                            <div class="col-auto align-self-end">
                                <a href="{{ route('student.grades.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Clear Filter
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Grades Table Grouped by Course -->
            @forelse($gradesByCourse as $courseId => $grades)
                @php
                    $courseObj = $enrolledCourses->firstWhere('id', $courseId) ?? $grades->first()->course;
                @endphp
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                        <h6 class="mb-0 fw-bold text-dark">
                            <i class="bi bi-journal-bookmark me-2 text-success"></i>{{ $courseObj->name ?? 'Course' }} ({{ $courseObj->code ?? 'N/A' }})
                        </h6>
                        <span class="badge bg-secondary">{{ $grades->count() }} {{ Str::plural('Assessment', $grades->count()) }}</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light small">
                                    <tr>
                                        <th scope="col" class="ps-4">Assessment Name</th>
                                        <th scope="col" class="text-center">Marks Obtained</th>
                                        <th scope="col" class="text-center">Total Marks</th>
                                        <th scope="col" class="text-center">Percentage</th>
                                        <th scope="col" class="text-center">Result</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($grades as $grade)
                                        @php
                                            $pct = $grade->total_marks > 0 ? round(($grade->marks_obtained / $grade->total_marks) * 100, 2) : 0;
                                            $badgeClass = match(true) {
                                                $pct >= 85 => 'bg-success',
                                                $pct >= 70 => 'bg-info text-dark',
                                                $pct >= 50 => 'bg-warning text-dark',
                                                default => 'bg-danger',
                                            };
                                        @endphp
                                        <tr>
                                            <td class="ps-4 fw-semibold text-dark">
                                                {{ $grade->assessment_name }}
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
                                                <span class="badge {{ $badgeClass }} px-3 py-1">
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
                    <i class="bi bi-award display-6 d-block mb-2 text-secondary"></i>
                    <h5>No grade records found.</h5>
                    <p class="mb-0">Grades will appear here once entered by your teachers.</p>
                </div>
            @endforelse
        @endif
    </div>
</x-app-layout>
