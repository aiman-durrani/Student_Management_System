<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold mb-0">
            <i class="bi bi-speedometer2 me-2 text-primary"></i>{{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <!-- Include Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .stat-card-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .stat-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            cursor: pointer;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
    </style>

    <div class="container-fluid py-2">
        <!-- Dismissible Warning Alert for Unassigned/Empty Courses -->
        @if($unassignedCoursesCount > 0 || $emptyCoursesCount > 0)
            <div class="alert alert-warning alert-dismissible fade show shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                    <div>
                        <strong>Attention Needed:</strong>
                        @if($unassignedCoursesCount > 0)
                            There {{ $unassignedCoursesCount === 1 ? 'is' : 'are' }} <strong>{{ $unassignedCoursesCount }} unassigned {{ Str::plural('course', $unassignedCoursesCount) }}</strong> without a teacher.
                        @endif
                        @if($emptyCoursesCount > 0)
                            There {{ $emptyCoursesCount === 1 ? 'is' : 'are' }} <strong>{{ $emptyCoursesCount }} empty {{ Str::plural('course', $emptyCoursesCount) }}</strong> with zero enrolled students.
                        @endif
                        <a href="{{ route('admin.courses.index') }}" class="alert-link ms-2">Manage Courses &rarr;</a>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Stat Cards Row (4 cards) -->
        <div class="row g-3 mb-4">
            <!-- Total Students -->
            <div class="col-sm-6 col-lg-3">
                <a href="{{ route('admin.students.index') }}" class="stat-card-link">
                    <div class="card stat-card bg-primary text-white shadow-sm border-0 h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.75rem;">Total Students</small>
                                <h2 class="mb-0 fw-bold mt-1">{{ $totalStudents }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                                <i class="bi bi-people-fill fs-3"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Total Teachers -->
            <div class="col-sm-6 col-lg-3">
                <a href="{{ route('admin.teachers.index') }}" class="stat-card-link">
                    <div class="card stat-card bg-success text-white shadow-sm border-0 h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.75rem;">Total Teachers</small>
                                <h2 class="mb-0 fw-bold mt-1">{{ $totalTeachers }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                                <i class="bi bi-person-badge-fill fs-3"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Total Courses -->
            <div class="col-sm-6 col-lg-3">
                <a href="{{ route('admin.courses.index') }}" class="stat-card-link">
                    <div class="card stat-card bg-info text-white shadow-sm border-0 h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.75rem;">Total Courses</small>
                                <h2 class="mb-0 fw-bold mt-1">{{ $totalCourses }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                                <i class="bi bi-journal-bookmark-fill fs-3"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Unassigned Courses -->
            <div class="col-sm-6 col-lg-3">
                <a href="{{ route('admin.courses.index') }}" class="stat-card-link">
                    <div class="card stat-card bg-warning text-white shadow-sm border-0 h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.75rem;">Unassigned Courses</small>
                                <h2 class="mb-0 fw-bold mt-1">{{ $unassignedCoursesCount }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                                <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Quick Actions Row -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-3 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                        <div class="fw-bold text-dark d-flex align-items-center">
                            <i class="bi bi-lightning-charge-fill text-warning me-2 fs-5"></i> Quick Actions
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.students.create') }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-person-plus me-1"></i> Add Student
                            </a>
                            <a href="{{ route('admin.teachers.create') }}" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-person-badge me-1"></i> Add Teacher
                            </a>
                            <a href="{{ route('admin.courses.create') }}" class="btn btn-outline-info btn-sm">
                                <i class="bi bi-journal-plus me-1"></i> Add Course
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title fw-bold mb-0 text-dark">
                            <i class="bi bi-bar-chart-line-fill text-primary me-2"></i>Top 5 Courses by Student Enrollment
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @if($topCourses->count() > 0 && $topCourses->max('students_count') > 0)
                            <div style="position: relative; height: 280px; width: 100%;">
                                <canvas id="topCoursesChart"></canvas>
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-bar-chart display-6 d-block mb-2 text-secondary"></i>
                                No enrollment data available yet to display graph.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Cards (Side by Side) -->
        <div class="row g-4">
            <!-- Recently Added Students -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title fw-bold mb-0 text-dark">
                            <i class="bi bi-people me-2 text-primary"></i>Recently Added Students
                        </h5>
                        <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-link text-decoration-none">
                            View all &rarr;
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($recentStudents as $student)
                                <div class="list-group-item p-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; font-size: 0.9rem;">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.students.show', $student) }}" class="fw-bold text-dark text-decoration-none d-block">
                                                {{ $student->name }}
                                            </a>
                                            <small class="text-muted font-monospace">{{ $student->roll_number }}</small>
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $student->created_at ? $student->created_at->diffForHumans() : '' }}</small>
                                </div>
                            @empty
                                <div class="p-4 text-center text-muted">No recent student records.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recently Added Courses -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title fw-bold mb-0 text-dark">
                            <i class="bi bi-journal-bookmark me-2 text-primary"></i>Recently Added Courses
                        </h5>
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-link text-decoration-none">
                            View all &rarr;
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($recentCourses as $course)
                                <div class="list-group-item p-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; font-size: 0.9rem;">
                                            <i class="bi bi-journal-text"></i>
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.courses.show', $course) }}" class="fw-bold text-dark text-decoration-none d-block">
                                                {{ $course->name }}
                                            </a>
                                            <span class="badge bg-secondary font-monospace">{{ $course->code }}</span>
                                            <small class="text-muted ms-2">
                                                {{ $course->teacher ? '• ' . $course->teacher->name : '• Unassigned' }}
                                            </small>
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $course->created_at ? $course->created_at->diffForHumans() : '' }}</small>
                                </div>
                            @empty
                                <div class="p-4 text-center text-muted">No recent course records.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script to render Chart.js chart -->
    @if($topCourses->count() > 0 && $topCourses->max('students_count') > 0)
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const courseNames = @json($topCourses->pluck('name'));
                const enrollmentCounts = @json($topCourses->pluck('students_count'));

                const ctx = document.getElementById('topCoursesChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: courseNames,
                        datasets: [{
                            label: 'Enrolled Students',
                            data: enrollmentCounts,
                            backgroundColor: 'rgba(59, 130, 246, 0.75)',
                            borderColor: 'rgba(37, 99, 235, 1)',
                            borderWidth: 1,
                            borderRadius: 6,
                            barThickness: 40
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return ' Enrolled: ' + context.raw + ' students';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    precision: 0
                                },
                                title: {
                                    display: true,
                                    text: 'Number of Students'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Course'
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endif
</x-app-layout>
