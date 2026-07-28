<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('Edit Student') }}
            </h2>
            <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Students
            </a>
        </div>
    </x-slot>

    <div class="container-fluid py-2">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('admin.students.update', $student) }}">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <!-- Full Name -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" 
                                               name="name" 
                                               id="name" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               placeholder="John Doe" 
                                               value="{{ old('name', $student->name) }}" 
                                               required>
                                        <label for="name">Full Name <span class="text-danger">*</span></label>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email Address -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" 
                                               name="email" 
                                               id="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               placeholder="student@example.com" 
                                               value="{{ old('email', $student->email) }}" 
                                               required>
                                        <label for="email">Email Address <span class="text-danger">*</span></label>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Roll Number -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" 
                                               name="roll_number" 
                                               id="roll_number" 
                                               class="form-control @error('roll_number') is-invalid @enderror" 
                                               placeholder="STU-1001" 
                                               value="{{ old('roll_number', $student->roll_number) }}" 
                                               required>
                                        <label for="roll_number">Roll Number <span class="text-danger">*</span></label>
                                        @error('roll_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Date of Birth -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" 
                                               name="date_of_birth" 
                                               id="date_of_birth" 
                                               class="form-control @error('date_of_birth') is-invalid @enderror" 
                                               placeholder="YYYY-MM-DD" 
                                               value="{{ old('date_of_birth', $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '') }}" 
                                               required>
                                        <label for="date_of_birth">Date of Birth <span class="text-danger">*</span></label>
                                        @error('date_of_birth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Phone Number -->
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <input type="text" 
                                               name="phone" 
                                               id="phone" 
                                               class="form-control @error('phone') is-invalid @enderror" 
                                               placeholder="+123456789" 
                                               value="{{ old('phone', $student->phone) }}">
                                        <label for="phone">Phone Number (Optional)</label>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Address -->
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <textarea name="address" 
                                                  id="address" 
                                                  class="form-control @error('address') is-invalid @enderror" 
                                                  placeholder="Address" 
                                                  style="height: 100px">{{ old('address', $student->address) }}</textarea>
                                        <label for="address">Address (Optional)</label>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary px-4">Cancel</a>
                                    <button type="submit" class="btn btn-primary px-4">Update Student</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
