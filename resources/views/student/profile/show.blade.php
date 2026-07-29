<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small text-muted">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">My Profile</li>
                    </ol>
                </nav>
                <h2 class="h4 font-weight-bold mb-0">
                    <i class="bi bi-person-badge-fill me-2 text-primary"></i>My Student Profile
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
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-person-lines-fill me-2 text-primary"></i>Personal Information (Read-Only)
                            </h5>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1">
                                <i class="bi bi-shield-check me-1"></i> Verified Student
                            </span>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <div class="text-uppercase text-muted small fw-bold mb-1">Full Name</div>
                                        <div class="fs-5 fw-bold text-dark">{{ $student->name }}</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <div class="text-uppercase text-muted small fw-bold mb-1">Roll Number</div>
                                        <div class="fs-5 fw-bold text-primary font-monospace">{{ $student->roll_number }}</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <div class="text-uppercase text-muted small fw-bold mb-1">Email Address</div>
                                        <div class="fw-semibold text-dark">{{ $student->email }}</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <div class="text-uppercase text-muted small fw-bold mb-1">Date of Birth</div>
                                        <div class="fw-semibold text-dark">
                                            {{ $student->date_of_birth ? $student->date_of_birth->format('F d, Y') : 'N/A' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <div class="text-uppercase text-muted small fw-bold mb-1">Phone Number</div>
                                        <div class="fw-semibold text-dark">{{ $student->phone ?? 'Not provided' }}</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <div class="text-uppercase text-muted small fw-bold mb-1">Residential Address</div>
                                        <div class="fw-semibold text-dark">{{ $student->address ?? 'Not provided' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light p-3 text-muted small d-flex justify-content-between">
                            <span><i class="bi bi-info-circle me-1"></i> Profile data is managed by administration.</span>
                            <span>Student ID: #{{ $student->id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
