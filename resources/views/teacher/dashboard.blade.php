<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-speedometer2 me-2 text-primary"></i>{{ __('Teacher Dashboard') }}
            </h2>
            <span class="badge bg-primary fs-6 fw-normal">
                <i class="bi bi-person-badge me-1"></i>{{ $teacher->name }} ({{ $teacher->employee_id }})
            </span>
        </div>
    </x-slot>

    <div class="container-fluid py-2">
        <!-- Overview Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm bg-primary text-white h-100">
                    <div class="card-body p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 text-uppercase fw-bold mb-1">Assigned Courses</h6>
                            <h2 class="display-5 fw-bold mb-0">{{ $coursesCount }}</h2>
                        </div>
                        <div class="rounded-circle bg-white bg-opacity-25 p-3 text-white">
                            <i class="bi bi-journal-bookmark-fill fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm bg-success text-white h-100">
                    <div class="card-body p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 text-uppercase fw-bold mb-1">Enrolled Students</h6>
                            <h2 class="display-5 fw-bold mb-0">{{ $totalStudentsCount }}</h2>
                        </div>
                        <div class="rounded-circle bg-white bg-opacity-25 p-3 text-white">
                            <i class="bi bi-people-fill fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Cards Section -->
        <h4 class="fw-bold mb-3">
            <i class="bi bi-journal-check me-2 text-secondary"></i>My Assigned Courses
        </h4>

        @if($courses->isEmpty())
            <div class="card border-0 shadow-sm p-5 text-center text-muted">
                <i class="bi bi-folder-x display-3 mb-3 text-secondary"></i>
                <h5>No courses currently assigned to you.</h5>
                <p class="mb-0">Please contact an administrator if you believe this is an error.</p>
            </div>
        @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($courses as $courseItem)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm hover-shadow transition-all position-relative">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-secondary font-monospace">{{ $courseItem->code }}</span>
                                    <span class="badge bg-info text-dark">
                                        <i class="bi bi-people me-1"></i>{{ $courseItem->students_count }} {{ Str::plural('Student', $courseItem->students_count) }}
                                    </span>
                                </div>
                                <h5 class="card-title fw-bold text-dark mb-2">{{ $courseItem->name }}</h5>
                                <p class="card-text text-muted small mb-4">
                                    {{ Str::limit($courseItem->description ?? 'No description provided.', 100) }}
                                </p>
                            </div>
                            <div class="card-footer bg-light border-0 p-3">
                                <a href="{{ route('teacher.courses.show', $courseItem) }}" class="btn btn-outline-primary w-100 fw-semibold stretched-link">
                                    <i class="bi bi-arrow-right-circle me-1"></i> Manage Course
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
