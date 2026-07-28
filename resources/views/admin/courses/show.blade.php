<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-journal-bookmark-fill me-2 text-primary"></i>{{ __('Course Details') }}
            </h2>
            <div>
                <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-primary me-2">
                    <i class="bi bi-pencil me-1"></i> Edit Course
                </a>
                <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-2">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <!-- Course Overview Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; font-size: 1.25rem;">
                                <i class="bi bi-journal-text"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">{{ $course->name }}</h5>
                                <span class="badge bg-secondary font-monospace">Code: {{ $course->code }}</span>
                            </div>
                        </div>
                        <span class="badge bg-info text-dark fs-6 font-monospace">
                            <i class="bi bi-people me-1"></i>{{ $course->students->count() }} {{ Str::plural('Enrolled Student', $course->students->count()) }}
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Assigned Teacher -->
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded border">
                                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem;">Assigned Teacher</small>
                                    <div class="fs-6 fw-semibold text-dark">
                                        @if($course->teacher)
                                            <a href="{{ route('admin.teachers.show', $course->teacher) }}" class="text-decoration-none text-primary">
                                                <i class="bi bi-person-badge me-2"></i>{{ $course->teacher->name }} 
                                                <span class="text-muted font-normal">({{ $course->teacher->subject }})</span>
                                            </a>
                                        @else
                                            <span class="text-muted"><i class="bi bi-person-x me-2"></i>Unassigned</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Created Timestamp -->
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded border">
                                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem;">Record Created</small>
                                    <div class="fs-6 fw-semibold text-dark">
                                        <i class="bi bi-clock-history me-2 text-primary"></i>{{ $course->created_at ? $course->created_at->format('M d, Y H:i A') : 'N/A' }}
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <div class="p-3 bg-light rounded border">
                                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem;">Course Description</small>
                                    <div class="fs-6 text-dark">
                                        <i class="bi bi-file-text me-2 text-primary"></i>{{ $course->description ?? 'No description provided.' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enrolled Students Table Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="bi bi-people-fill me-2 text-primary"></i>Enrolled Students ({{ $course->students->count() }})
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="ps-4">#</th>
                                        <th scope="col">Student Name</th>
                                        <th scope="col">Roll Number</th>
                                        <th scope="col">Email</th>
                                        <th scope="col" class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($course->students as $student)
                                        <tr>
                                            <th scope="row" class="ps-4 text-muted">{{ $loop->iteration }}</th>
                                            <td>
                                                <a href="{{ route('admin.students.show', $student) }}" class="fw-bold text-dark text-decoration-none">
                                                    {{ $student->name }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary font-monospace">{{ $student->roll_number }}</span>
                                            </td>
                                            <td>{{ $student->email }}</td>
                                            <td class="text-end pe-4">
                                                <a href="{{ route('admin.students.show', $student) }}" class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-eye me-1"></i> View Student
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <i class="bi bi-person-x display-6 d-block mb-2 text-secondary"></i>
                                                No students are currently enrolled in this course.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top py-3 text-end">
                        <button type="button" 
                                class="btn btn-outline-danger btn-sm" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteModal"
                                data-action="{{ route('admin.courses.destroy', $course) }}"
                                data-name="{{ $course->name }}">
                            <i class="bi bi-trash me-1"></i> Delete Course
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
                <form id="deleteCourseForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteModalLabel">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirm Deletion
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete course <strong id="deleteCourseName" class="text-danger"></strong>?
                        <p class="small text-muted mb-0 mt-2">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Course</button>
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
                    
                    const form = deleteModal.querySelector('#deleteCourseForm');
                    const nameContainer = deleteModal.querySelector('#deleteCourseName');
                    
                    form.action = action;
                    nameContainer.textContent = name;
                });
            }
        });
    </script>
</x-app-layout>
