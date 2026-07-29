<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-speedometer2 me-2 text-primary"></i>{{ __('Student Portal Dashboard') }}
            </h2>
            @if($student)
                <span class="badge bg-primary fs-6 fw-normal">
                    <i class="bi bi-person-badge me-1"></i>Roll No: {{ $student->roll_number }}
                </span>
            @endif
        </div>
    </x-slot>

    <div class="container-fluid py-2">
        @if(!$student)
            <!-- Unlinked Account Empty State -->
            <div class="card border-0 shadow-sm p-5 text-center bg-white mb-4">
                <div class="py-4">
                    <i class="bi bi-exclamation-triangle display-3 text-warning mb-3 d-block"></i>
                    <h4 class="fw-bold text-dark mb-2">Student Profile Not Linked</h4>
                    <p class="text-muted max-w-md mx-auto mb-4">
                        Your user account is not currently linked to an active student record. 
                        Please contact the administration office to associate your email with your student roll number.
                    </p>
                    <a href="mailto:admin@example.com" class="btn btn-primary">
                        <i class="bi bi-envelope me-1"></i> Contact Administrator
                    </a>
                </div>
            </div>
        @else
            <!-- Welcome Header Card -->
            <div class="card border-0 shadow-sm mb-4 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle d-none d-sm-block">
                            <i class="bi bi-person-circle fs-1"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold text-dark mb-1">Welcome back, {{ $student->name }}!</h3>
                            <p class="text-muted mb-0">
                                Roll Number: <strong class="text-dark font-monospace">{{ $student->roll_number }}</strong> &bull; 
                                Email: <strong class="text-dark">{{ $student->email }}</strong> &bull;
                                Enrolled Courses: <span class="badge bg-info text-dark">{{ $coursesCount }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Stats Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-primary text-white h-100">
                        <div class="card-body p-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase fw-bold mb-1">Enrolled Courses</h6>
                                <h2 class="display-5 fw-bold mb-0">{{ $coursesCount }}</h2>
                            </div>
                            <div class="rounded-circle bg-white bg-opacity-25 p-3 text-white">
                                <i class="bi bi-journal-bookmark-fill fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-success text-white h-100">
                        <div class="card-body p-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase fw-bold mb-1">Overall Attendance</h6>
                                <h2 class="display-5 fw-bold mb-0">{{ $overallAttendancePct }}%</h2>
                            </div>
                            <div class="rounded-circle bg-white bg-opacity-25 p-3 text-white">
                                <i class="bi bi-calendar-check-fill fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-info text-white h-100">
                        <div class="card-body p-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase fw-bold mb-1">Average Grade</h6>
                                <h2 class="display-5 fw-bold mb-0">{{ $overallGradeAvgPct }}%</h2>
                            </div>
                            <div class="rounded-circle bg-white bg-opacity-25 p-3 text-white">
                                <i class="bi bi-award-fill fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enrolled Courses Section -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">
                    <i class="bi bi-book me-2 text-primary"></i>My Enrolled Courses
                </h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('student.attendance.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-calendar-check me-1"></i> View Attendance
                    </a>
                    <a href="{{ route('student.grades.index') }}" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-award me-1"></i> View Grades
                    </a>
                </div>
            </div>

            @if($courses->isEmpty())
                <div class="card border-0 shadow-sm p-5 text-center text-muted bg-white">
                    <i class="bi bi-journal-x display-3 mb-3 text-secondary"></i>
                    <h5>You are not enrolled in any courses yet.</h5>
                    <p class="mb-0">Contact your academic advisor or administrator to enroll in courses.</p>
                </div>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach($courses as $courseItem)
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge bg-secondary font-monospace">{{ $courseItem->code }}</span>
                                    </div>
                                    <h5 class="card-title fw-bold text-dark mb-2">{{ $courseItem->name }}</h5>
                                    <p class="card-text text-muted small mb-3">
                                        <i class="bi bi-person-badge me-1 text-primary"></i>
                                        Teacher: 
                                        <strong>
                                            {{ $courseItem->teacher ? $courseItem->teacher->name : 'Unassigned' }}
                                        </strong>
                                    </p>
                                    <p class="card-text text-muted small">
                                        {{ Str::limit($courseItem->description ?? 'No course description.', 80) }}
                                    </p>
                                </div>
                                <div class="card-footer bg-light border-0 p-3 d-flex gap-2">
                                    <a href="{{ route('student.attendance.index', ['course_id' => $courseItem->id]) }}" class="btn btn-outline-primary btn-sm flex-fill">
                                        <i class="bi bi-calendar-check me-1"></i> Attendance
                                    </a>
                                    <a href="{{ route('student.grades.index', ['course_id' => $courseItem->id]) }}" class="btn btn-outline-success btn-sm flex-fill">
                                        <i class="bi bi-award me-1"></i> Grades
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
