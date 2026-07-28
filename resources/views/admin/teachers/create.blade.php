<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-person-plus-fill me-2 text-primary"></i>{{ __('Add New Teacher') }}
            </h2>
            <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Teachers
            </a>
        </div>
    </x-slot>

    <div class="container-fluid py-2">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('admin.teachers.store') }}">
                            @csrf

                            <div class="row g-3">
                                <!-- Full Name -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" 
                                               name="name" 
                                               id="name" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               placeholder="Jane Smith" 
                                               value="{{ old('name') }}" 
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
                                               placeholder="teacher@example.com" 
                                               value="{{ old('email') }}" 
                                               required>
                                        <label for="email">Email Address <span class="text-danger">*</span></label>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Employee ID -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" 
                                               name="employee_id" 
                                               id="employee_id" 
                                               class="form-control @error('employee_id') is-invalid @enderror" 
                                               placeholder="EMP-2001" 
                                               value="{{ old('employee_id') }}" 
                                               required>
                                        <label for="employee_id">Employee ID <span class="text-danger">*</span></label>
                                        @error('employee_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Subject -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" 
                                               name="subject" 
                                               id="subject" 
                                               class="form-control @error('subject') is-invalid @enderror" 
                                               placeholder="Mathematics" 
                                               value="{{ old('subject') }}" 
                                               required>
                                        <label for="subject">Subject / Department <span class="text-danger">*</span></label>
                                        @error('subject')
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
                                               value="{{ old('phone') }}">
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
                                                  style="height: 100px">{{ old('address') }}</textarea>
                                        <label for="address">Address (Optional)</label>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary px-4">Cancel</a>
                                    <button type="submit" class="btn btn-primary px-4">Save Teacher</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
