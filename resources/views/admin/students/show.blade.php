<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-person-badge-fill me-2 text-primary"></i>{{ __('Student Details') }}
            </h2>
            <div>
                <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-primary me-2">
                    <i class="bi bi-pencil me-1"></i> Edit Student
                </a>
                <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-2">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; font-size: 1.25rem;">
                                {{ strtoupper(substr($student->name, 0, 1)) }}
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">{{ $student->name }}</h5>
                                <span class="badge bg-secondary font-monospace">Roll No: {{ $student->roll_number }}</span>
                            </div>
                        </div>
                        <span class="badge bg-success">Active Student</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded border">
                                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem;">Email Address</small>
                                    <div class="fs-6 fw-semibold text-dark">
                                        <i class="bi bi-envelope me-2 text-primary"></i>{{ $student->email }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded border">
                                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem;">Date of Birth</small>
                                    <div class="fs-6 fw-semibold text-dark">
                                        <i class="bi bi-calendar-event me-2 text-primary"></i>{{ $student->date_of_birth ? $student->date_of_birth->format('F d, Y') : 'Not Provided' }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded border">
                                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem;">Phone Number</small>
                                    <div class="fs-6 fw-semibold text-dark">
                                        <i class="bi bi-telephone me-2 text-primary"></i>{{ $student->phone ?? 'Not Provided' }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded border">
                                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem;">Record Created</small>
                                    <div class="fs-6 fw-semibold text-dark">
                                        <i class="bi bi-clock-history me-2 text-primary"></i>{{ $student->created_at ? $student->created_at->format('M d, Y H:i A') : 'N/A' }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="p-3 bg-light rounded border">
                                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem;">Residential Address</small>
                                    <div class="fs-6 fw-semibold text-dark">
                                        <i class="bi bi-geo-alt me-2 text-primary"></i>{{ $student->address ?? 'Not Provided' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top py-3 text-end">
                        <button type="button" 
                                class="btn btn-outline-danger btn-sm" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteModal"
                                data-action="{{ route('admin.students.destroy', $student) }}"
                                data-name="{{ $student->name }}">
                            <i class="bi bi-trash me-1"></i> Delete Student
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="deleteStudentForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteModalLabel">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirm Deletion
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete student <strong id="deleteStudentName" class="text-danger"></strong>?
                        <p class="small text-muted mb-0 mt-2">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteModal = document.getElementById('deleteModal');
            if (deleteModal) {
                deleteModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const action = button.getAttribute('data-action');
                    const name = button.getAttribute('data-name');
                    
                    const form = deleteModal.querySelector('#deleteStudentForm');
                    const nameContainer = deleteModal.querySelector('#deleteStudentName');
                    
                    form.action = action;
                    nameContainer.textContent = name;
                });
            }
        });
    </script>
</x-app-layout>
