@if(file_exists(public_path('images/logo.png')))
    <img src="{{ asset('images/logo.png') }}" alt="Student Management System Logo" {{ $attributes->merge(['class' => 'img-fluid', 'style' => 'max-height: 60px;']) }}>
@else
    <div {{ $attributes->merge(['class' => 'd-flex align-items-center justify-content-center gap-2 text-decoration-none']) }}>
        <div class="rounded-circle bg-primary bg-gradient p-2 text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
            <i class="bi bi-mortarboard-fill fs-4"></i>
        </div>
        <div class="text-start">
            <span class="fs-4 fw-bold text-dark d-block lh-1">SMS Portal</span>
            <small class="text-muted fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Student Management System</small>
        </div>
    </div>
@endif
