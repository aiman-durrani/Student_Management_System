<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap 5 & Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                min-height: 100vh;
                background-color: #f8f9fa;
            }
            #wrapper {
                display: flex;
                min-height: 100vh;
                overflow-x: hidden;
            }
            #sidebar-wrapper {
                min-width: 250px;
                max-width: 250px;
                background: #1e293b;
                color: #fff;
                transition: margin 0.25s ease-out;
            }
            #wrapper.toggled #sidebar-wrapper {
                margin-left: -250px;
            }
            #sidebar-wrapper .sidebar-heading {
                padding: 1.25rem 1.5rem;
                font-size: 1.15rem;
                font-weight: bold;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }
            #sidebar-wrapper .list-group-item {
                background: transparent;
                color: #cbd5e1;
                border: none;
                padding: 0.85rem 1.5rem;
                font-size: 0.95rem;
            }
            #sidebar-wrapper .list-group-item:hover,
            #sidebar-wrapper .list-group-item.active {
                background: #334155;
                color: #fff;
                border-left: 4px solid #3b82f6;
            }
            #page-content-wrapper {
                flex-grow: 1;
                min-width: 0;
            }
            @media (max-width: 768px) {
                #sidebar-wrapper {
                    margin-left: -250px;
                }
                #wrapper.toggled #sidebar-wrapper {
                    margin-left: 0;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div id="wrapper">
            <!-- Sidebar -->
            <div id="sidebar-wrapper">
                <div class="sidebar-heading d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-mortarboard-fill me-2 text-primary"></i>SMS Portal</span>
                    <span class="badge bg-primary text-uppercase">{{ Auth::user()->role ?? 'Student' }}</span>
                </div>
                <div class="list-group list-group-flush mt-2">
                    @php
                        $role = Auth::user()->role ?? 'student';
                        $dashboardRoute = match($role) {
                            'admin' => route('admin.dashboard'),
                            'teacher' => route('teacher.dashboard'),
                            default => route('student.dashboard'),
                        };
                    @endphp

                    <a href="{{ $dashboardRoute }}" class="list-group-item list-group-item-action {{ request()->routeIs('*.dashboard') || request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>

                    @if($role === 'admin')
                        <div class="px-3 pt-3 pb-1 text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">Admin Management</div>
                        <a href="{{ route('admin.students.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                            <i class="bi bi-people-fill me-2"></i> Students
                        </a>
                        <a href="{{ route('admin.teachers.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
                            <i class="bi bi-person-badge-fill me-2"></i> Teachers
                        </a>
                        <a href="{{ route('admin.courses.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                            <i class="bi bi-journal-bookmark-fill me-2"></i> Courses
                        </a>
                    @elseif($role === 'teacher')
                        <div class="px-3 pt-3 pb-1 text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">Teacher Menu</div>
                        <a href="{{ route('teacher.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-journal-bookmark-fill me-2"></i> My Assigned Courses
                        </a>
                        @if(isset($course) && $course instanceof \App\Models\Course)
                            <div class="px-3 pt-3 pb-1 text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">Course: {{ $course->code }}</div>
                            <a href="{{ route('teacher.courses.show', $course) }}" class="list-group-item list-group-item-action {{ request()->routeIs('teacher.courses.show') ? 'active' : '' }}">
                                <i class="bi bi-journal-text me-2"></i> Roster & Details
                            </a>
                            <a href="{{ route('teacher.courses.attendance.index', $course) }}" class="list-group-item list-group-item-action {{ request()->routeIs('teacher.courses.attendance.*') ? 'active' : '' }}">
                                <i class="bi bi-calendar-check me-2"></i> Attendance
                            </a>
                            <a href="{{ route('teacher.courses.grades.index', $course) }}" class="list-group-item list-group-item-action {{ request()->routeIs('teacher.courses.grades.*') ? 'active' : '' }}">
                                <i class="bi bi-award me-2"></i> Grades
                            </a>
                        @endif
                    @else
                        <div class="px-3 pt-3 pb-1 text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">Student Menu</div>
                        <a href="{{ route('student.profile.show') }}" class="list-group-item list-group-item-action {{ request()->routeIs('student.profile.*') ? 'active' : '' }}">
                            <i class="bi bi-person-badge me-2"></i> My Profile
                        </a>
                        <a href="{{ route('student.attendance.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('student.attendance.*') ? 'active' : '' }}">
                            <i class="bi bi-calendar-check me-2"></i> My Attendance
                        </a>
                        <a href="{{ route('student.grades.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('student.grades.*') ? 'active' : '' }}">
                            <i class="bi bi-award me-2"></i> My Grades
                        </a>
                    @endif

                    <div class="px-3 pt-3 pb-1 text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">Account</div>
                    <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <i class="bi bi-person-gear me-2"></i> Profile
                    </a>
                </div>
            </div>
            <!-- /#sidebar-wrapper -->

            <!-- Page Content -->
            <div id="page-content-wrapper" class="d-flex flex-column min-vh-100">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white border-bottom py-3 px-4 shadow-sm">
                        {{ $header }}
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="p-4 flex-grow-1">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Bootstrap JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const sidebarToggle = document.getElementById("menu-toggle");
                if (sidebarToggle) {
                    sidebarToggle.addEventListener("click", function (e) {
                        e.preventDefault();
                        document.getElementById("wrapper").classList.toggle("toggled");
                        setTimeout(function() {
                            window.dispatchEvent(new Event('resize'));
                        }, 260);
                    });
                }
            });
        </script>
    </body>
</html>
