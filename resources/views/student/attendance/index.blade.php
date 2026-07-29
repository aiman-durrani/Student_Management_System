<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small text-muted">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">My Attendance</li>
                    </ol>
                </nav>
                <h2 class="h4 font-weight-bold mb-0">
                    <i class="bi bi-calendar-check me-2 text-primary"></i>My Attendance Records
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
            <!-- Per-Course Attendance Summary Cards -->
            <div class="mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-pie-chart me-2 text-primary"></i>Attendance Percentage Summary
                </h5>

                @if($courseSummaries->isEmpty())
                    <div class="alert alert-info border-0 shadow-sm">You are not enrolled in any courses.</div>
                @else
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                        @foreach($courseSummaries as $summary)
                            <div class="col">
                                <div class="card border-0 shadow-sm h-100 bg-white">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge bg-secondary font-monospace">{{ $summary['course']->code }}</span>
                                            <span class="badge {{ $summary['percentage'] >= 75 ? 'bg-success' : ($summary['percentage'] >= 50 ? 'bg-warning text-dark' : 'bg-danger') }} fs-6">
                                                {{ $summary['percentage'] }}%
                                            </span>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-2">{{ $summary['course']->name }}</h6>
                                        <div class="progress mb-2" style="height: 6px;">
                                            <div class="progress-bar {{ $summary['percentage'] >= 75 ? 'bg-success' : ($summary['percentage'] >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                                                 role="progressbar" 
                                                 style="width: {{ $summary['percentage'] }}%" 
                                                 aria-valuenow="{{ $summary['percentage'] }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between small text-muted">
                                            <span>Present: <strong>{{ $summary['present'] }}</strong></span>
                                            <span>Late: <strong>{{ $summary['late'] }}</strong></span>
                                            <span>Absent: <strong>{{ $summary['absent'] }}</strong></span>
                                            <span>Total: <strong>{{ $summary['total'] }}</strong></span>
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
                    <form method="GET" action="{{ route('student.attendance.index') }}" class="row g-3 align-items-center">
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
                                <a href="{{ route('student.attendance.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Clear Filter
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Attendance History Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-table me-2 text-primary"></i>Attendance History Logs
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="ps-4">Date</th>
                                    <th scope="col">Course Code</th>
                                    <th scope="col">Course Name</th>
                                    <th scope="col" class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $attendance)
                                    <tr>
                                        <td class="ps-4 fw-semibold text-dark">
                                            {{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y (D)') }}
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary font-monospace">{{ $attendance->course->code ?? 'N/A' }}</span>
                                        </td>
                                        <td>{{ $attendance->course->name ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            @if($attendance->status === 'present')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Present
                                                </span>
                                            @elseif($attendance->status === 'late')
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1">
                                                    <i class="bi bi-clock-history me-1"></i> Late
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1">
                                                    <i class="bi bi-x-circle-fill me-1"></i> Absent
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="bi bi-calendar-x display-6 d-block mb-2 text-secondary"></i>
                                            No attendance records found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
