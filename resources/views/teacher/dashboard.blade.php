<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold mb-0">
            {{ __('Teacher Dashboard') }}
        </h2>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title text-success">Welcome, Teacher {{ Auth::user()->name }}!</h5>
                        <p class="card-text text-muted">Access your assigned classes, courses, and student evaluations.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
