<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small text-muted">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ $course->code }}</li>
                    </ol>
                </nav>
                <h2 class="h4 font-weight-bold mb-0">
                    <i class="bi bi-journal-text me-2 text-primary"></i>{{ $course->name }} <span class="text-muted fs-6">({{ $course->code }})</span>
                </h2>
            </div>
            <div>
                <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Courses
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-2">
        <!-- Quick Action Bar -->
        <div class="card shadow-sm border-0 mb-4 bg-white">
            <div class="card-body p-3">
                <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle-fill text-primary fs-4 me-2"></i>
                        <div>
                            <div class="fw-bold">{{ $course->students->count() }} Enrolled {{ Str::plural('Student', $course->students->count()) }}</div>
                            <div class="small text-muted">{{ $course->description ?? 'No course description.' }}</div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('teacher.courses.attendance.create', $course) }}" class="btn btn-primary">
                            <i class="bi bi-calendar-plus me-1"></i> Mark Attendance
                        </a>
                        <a href="{{ route('teacher.courses.attendance.index', $course) }}" class="btn btn-outline-primary">
                            <i class="bi bi-calendar-range me-1"></i> Attendance History
                        </a>
                        <a href="{{ route('teacher.courses.grades.create', $course) }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i> Enter Grades
                        </a>
                        <a href="{{ route('teacher.courses.grades.index', $course) }}" class="btn btn-outline-success">
                            <i class="bi bi-journal-check me-1"></i> View Grades
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrolled Students Table -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-people me-2 text-primary"></i>Enrolled Students Roster
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
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($course->students as $student)
                                <tr>
                                    <th scope="row" class="ps-4 text-muted">{{ $loop->iteration }}</th>
                                    <td>
                                        <span class="badge bg-secondary font-monospace">{{ $student->roll_number }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $student->name }}</div>
                                    </td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ $student->phone ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-people display-6 d-block mb-2 text-secondary"></i>
                                        No students enrolled in this course yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
