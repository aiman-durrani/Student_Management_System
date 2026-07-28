<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-people-fill me-2 text-primary"></i>{{ __('Manage Students') }}
            </h2>
            <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Add New Student
            </a>
        </div>
    </x-slot>

    <div class="container-fluid py-2">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <!-- Search & Filter Bar -->
                <form method="GET" action="{{ route('admin.students.index') }}" class="row g-2 align-items-center">
                    <div class="col-md-5 col-lg-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name or roll number..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-secondary">Search</button>
                        @if(request('search'))
                            <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">Clear</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="ps-4">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Roll Number</th>
                                <th scope="col">Email</th>
                                <th scope="col">Date of Birth</th>
                                <th scope="col" class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                <tr>
                                    <th scope="row" class="ps-4 text-muted">{{ $loop->iteration + ($students->currentPage() - 1) * $students->perPage() }}</th>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $student->name }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary font-monospace">{{ $student->roll_number }}</span>
                                    </td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ $student->date_of_birth ? $student->date_of_birth->format('M d, Y') : 'N/A' }}</td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Student Actions">
                                            <a href="{{ route('admin.students.show', $student) }}" class="btn btn-outline-info" title="View Details">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                            <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-outline-primary" title="Edit Student">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-outline-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal"
                                                    data-action="{{ route('admin.students.destroy', $student) }}"
                                                    data-name="{{ $student->name }}"
                                                    title="Delete Student">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-folder-x display-6 d-block mb-2 text-secondary"></i>
                                        No student records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($students->hasPages())
                <div class="card-footer bg-white border-top-0 d-flex justify-content-end py-3">
                    {{ $students->links('pagination::bootstrap-5') }}
                </div>
            @endif
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
