<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('Edit Course') }}
            </h2>
            <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Courses
            </a>
        </div>
    </x-slot>

    <div class="container-fluid py-2">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('admin.courses.update', $course) }}">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <!-- Course Name -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" 
                                               name="name" 
                                               id="name" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               placeholder="Database Systems" 
                                               value="{{ old('name', $course->name) }}" 
                                               required>
                                        <label for="name">Course Name <span class="text-danger">*</span></label>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Course Code -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" 
                                               name="code" 
                                               id="code" 
                                               class="form-control @error('code') is-invalid @enderror" 
                                               placeholder="CS101" 
                                               value="{{ old('code', $course->code) }}" 
                                               required>
                                        <label for="code">Course Code <span class="text-danger">*</span></label>
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Assign Teacher -->
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <select name="teacher_id" id="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror">
                                            <option value="">-- Select Teacher (Optional) --</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}" {{ old('teacher_id', $course->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                                    {{ $teacher->name }} ({{ $teacher->subject }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="teacher_id">Assigned Teacher</label>
                                        @error('teacher_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <textarea name="description" 
                                                  id="description" 
                                                  class="form-control @error('description') is-invalid @enderror" 
                                                  placeholder="Description" 
                                                  style="height: 100px">{{ old('description', $course->description) }}</textarea>
                                        <label for="description">Course Description (Optional)</label>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Enroll Students Section -->
                                <div class="col-12 mt-4">
                                    <div class="border rounded p-3 bg-light">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="fw-bold text-dark mb-0">
                                                <i class="bi bi-people-fill me-1 text-primary"></i> Enroll Students
                                            </label>
                                            <span class="badge bg-secondary">{{ count($students) }} Total Available</span>
                                        </div>
                                        <small class="text-muted d-block mb-3">Check/uncheck students to update course enrollment.</small>

                                        @error('student_ids')
                                            <div class="text-danger small mb-2">{{ $message }}</div>
                                        @enderror

                                        @php
                                            $enrolledIds = old('student_ids', $course->students->pluck('id')->toArray());
                                        @endphp

                                        <div class="row g-2" style="max-height: 220px; overflow-y: auto;">
                                            @forelse($students as $student)
                                                <div class="col-md-6 col-lg-4">
                                                    <div class="form-check p-2 bg-white rounded border">
                                                        <input class="form-check-input ms-0 me-2" 
                                                               type="checkbox" 
                                                               name="student_ids[]" 
                                                               value="{{ $student->id }}" 
                                                               id="student_{{ $student->id }}"
                                                               {{ in_array($student->id, $enrolledIds) ? 'checked' : '' }}>
                                                        <label class="form-check-label text-truncate d-block" for="student_{{ $student->id }}" title="{{ $student->name }}">
                                                            <strong>{{ $student->name }}</strong>
                                                            <br><small class="text-muted font-monospace">{{ $student->roll_number }}</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="col-12 text-muted text-center py-3">
                                                    No students available to enroll.
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary px-4">Cancel</a>
                                    <button type="submit" class="btn btn-primary px-4">Update Course</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
