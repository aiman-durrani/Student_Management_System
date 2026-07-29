<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small text-muted">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('teacher.courses.show', $course) }}" class="text-decoration-none">{{ $course->code }}</a></li>
                        <li class="breadcrumb-item active">Mark Attendance</li>
                    </ol>
                </nav>
                <h2 class="h4 font-weight-bold mb-0">
                    <i class="bi bi-calendar-plus me-2 text-primary"></i>Mark Attendance — {{ $course->name }}
                </h2>
            </div>
            <a href="{{ route('teacher.courses.show', $course) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Course
            </a>
        </div>
    </x-slot>

    <div class="container-fluid py-2">
        <form method="POST" action="{{ route('teacher.courses.attendance.store', $course) }}">
            @csrf

            <!-- Date Selection Card -->
            <div class="card shadow-sm border-0 mb-4 bg-white">
                <div class="card-body">
                    <div class="row align-items-center g-3">
                        <div class="col-md-4 col-lg-3">
                            <label for="attendance_date" class="form-label fw-bold">Select Date:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-calendar3"></i></span>
                                <input type="date" id="attendance_date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $date) }}" required>
                            </div>
                            @error('date')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-8 col-lg-9 d-flex align-items-end justify-content-md-end gap-2">
                            <button type="button" class="btn btn-outline-success btn-sm" id="btnMarkAllPresent">
                                <i class="bi bi-check-all me-1"></i> Mark All Present
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm" id="btnMarkAllAbsent">
                                <i class="bi bi-x-circle me-1"></i> Mark All Absent
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Roster Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-people me-2 text-primary"></i>Student Roster ({{ $students->count() }} Enrolled)
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
                                    <th scope="col" class="text-center" style="min-width: 250px;">Attendance Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $index => $student)
                                    @php
                                        $currentStatus = old("attendances.{$index}.status", isset($existingAttendances[$student->id]) ? $existingAttendances[$student->id]->status : 'present');
                                    @endphp
                                    <tr>
                                        <th scope="row" class="ps-4 text-muted">{{ $loop->iteration }}</th>
                                        <td>
                                            <span class="badge bg-secondary font-monospace">{{ $student->roll_number }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $student->name }}</div>
                                            <input type="hidden" name="attendances[{{ $index }}][student_id]" value="{{ $student->id }}">
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group w-100" role="group" aria-label="Status for {{ $student->name }}">
                                                <input type="radio" 
                                                       class="btn-check status-present" 
                                                       name="attendances[{{ $index }}][status]" 
                                                       id="status_present_{{ $student->id }}" 
                                                       value="present" 
                                                       {{ $currentStatus === 'present' ? 'checked' : '' }}>
                                                <label class="btn btn-outline-success btn-sm" for="status_present_{{ $student->id }}">
                                                    <i class="bi bi-check-circle me-1"></i>Present
                                                </label>

                                                <input type="radio" 
                                                       class="btn-check status-late" 
                                                       name="attendances[{{ $index }}][status]" 
                                                       id="status_late_{{ $student->id }}" 
                                                       value="late" 
                                                       {{ $currentStatus === 'late' ? 'checked' : '' }}>
                                                <label class="btn btn-outline-warning btn-sm" for="status_late_{{ $student->id }}">
                                                    <i class="bi bi-clock-history me-1"></i>Late
                                                </label>

                                                <input type="radio" 
                                                       class="btn-check status-absent" 
                                                       name="attendances[{{ $index }}][status]" 
                                                       id="status_absent_{{ $student->id }}" 
                                                       value="absent" 
                                                       {{ $currentStatus === 'absent' ? 'checked' : '' }}>
                                                <label class="btn btn-outline-danger btn-sm" for="status_absent_{{ $student->id }}">
                                                    <i class="bi bi-x-circle me-1"></i>Absent
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            No students enrolled in this course to mark attendance.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if(!$students->isEmpty())
                    <div class="card-footer bg-white py-3 d-flex justify-content-end gap-2 border-top">
                        <a href="{{ route('teacher.courses.show', $course) }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Save Attendance
                        </button>
                    </div>
                @endif
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fast date picker refresh when date changes
            const dateInput = document.getElementById('attendance_date');
            dateInput.addEventListener('change', function() {
                const url = new URL(window.location.href);
                url.searchParams.set('date', this.value);
                window.location.href = url.toString();
            });

            // Quick action buttons for bulk selecting present/absent
            const btnMarkAllPresent = document.getElementById('btnMarkAllPresent');
            const btnMarkAllAbsent = document.getElementById('btnMarkAllAbsent');

            if (btnMarkAllPresent) {
                btnMarkAllPresent.addEventListener('click', function() {
                    document.querySelectorAll('.status-present').forEach(radio => radio.checked = true);
                });
            }

            if (btnMarkAllAbsent) {
                btnMarkAllAbsent.addEventListener('click', function() {
                    document.querySelectorAll('.status-absent').forEach(radio => radio.checked = true);
                });
            }
        });
    </script>
</x-app-layout>
