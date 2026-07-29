<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small text-muted">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('teacher.courses.show', $course) }}" class="text-decoration-none">{{ $course->code }}</a></li>
                        <li class="breadcrumb-item active">Attendance History</li>
                    </ol>
                </nav>
                <h2 class="h4 font-weight-bold mb-0">
                    <i class="bi bi-calendar-range me-2 text-primary"></i>Attendance History — {{ $course->name }}
                </h2>
            </div>
            <div>
                <a href="{{ route('teacher.courses.attendance.create', $course) }}" class="btn btn-primary btn-sm me-2">
                    <i class="bi bi-plus-lg me-1"></i> Mark Attendance
                </a>
                <a href="{{ route('teacher.courses.show', $course) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Course
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-2">
        <!-- Date Range Filter Card -->
        <div class="card shadow-sm border-0 mb-4 bg-white">
            <div class="card-body">
                <form method="GET" action="{{ route('teacher.courses.attendance.index', $course) }}" class="row g-3 align-items-end">
                    <div class="col-md-4 col-lg-3">
                        <label for="start_date" class="form-label small fw-bold text-muted">Start Date</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <label for="end_date" class="form-label small fw-bold text-muted">End Date</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-4 col-lg-3 d-flex gap-2">
                        <button type="submit" class="btn btn-secondary">
                            <i class="bi bi-filter me-1"></i> Filter
                        </button>
                        @if(request('start_date') || request('end_date'))
                            <a href="{{ route('teacher.courses.attendance.index', $course) }}" class="btn btn-outline-secondary">Clear</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Attendance Logs Grouped By Date -->
        @forelse($groupedAttendances as $dateStr => $records)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-calendar-event me-2 text-primary"></i>Date: {{ \Carbon\Carbon::parse($dateStr)->format('F d, Y (l)') }}
                    </h6>
                    <div class="d-flex gap-2">
                        <span class="badge bg-success">Present: {{ $records->where('status', 'present')->count() }}</span>
                        <span class="badge bg-warning text-dark">Late: {{ $records->where('status', 'late')->count() }}</span>
                        <span class="badge bg-danger">Absent: {{ $records->where('status', 'absent')->count() }}</span>
                        <a href="{{ route('teacher.courses.attendance.create', [$course, 'date' => $dateStr]) }}" class="btn btn-outline-primary btn-sm ms-2" title="Edit this date's attendance">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small">
                                <tr>
                                    <th scope="col" class="ps-4">Roll Number</th>
                                    <th scope="col">Student Name</th>
                                    <th scope="col" class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($records as $attendance)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge bg-secondary font-monospace">{{ $attendance->student->roll_number ?? 'N/A' }}</span>
                                        </td>
                                        <td class="fw-semibold text-dark">
                                            {{ $attendance->student->name ?? 'Deleted Student' }}
                                        </td>
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            <div class="card shadow-sm border-0 p-5 text-center text-muted">
                <i class="bi bi-calendar-x display-3 mb-3 text-secondary"></i>
                <h5>No attendance records found for this course.</h5>
                <p class="mb-3">Click below to record your first attendance session.</p>
                <div>
                    <a href="{{ route('teacher.courses.attendance.create', $course) }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i> Mark Attendance Now
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</x-app-layout>
