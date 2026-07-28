<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold mb-0">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Welcome, Admin {{ Auth::user()->name }}!</h5>
                        <p class="card-text text-muted">You have full administrative privileges to manage students, teachers, and courses.</p>
                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded border">
                                    <h6 class="fw-bold"><i class="bi bi-people me-2"></i>Students Module</h6>
                                    <small class="text-muted">Manage student records & enrollments</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded border">
                                    <h6 class="fw-bold"><i class="bi bi-person-badge me-2"></i>Teachers Module</h6>
                                    <small class="text-muted">Manage teacher profiles & assignments</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded border">
                                    <h6 class="fw-bold"><i class="bi bi-journal-bookmark me-2"></i>Courses Module</h6>
                                    <small class="text-muted">Manage curriculum & subjects</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
