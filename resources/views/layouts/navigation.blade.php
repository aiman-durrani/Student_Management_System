<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm px-3">
    <div class="container-fluid">
        <!-- Sidebar Toggle Button -->
        <button class="btn btn-outline-secondary me-3" id="menu-toggle" aria-label="Toggle Sidebar">
            <i class="bi bi-list fs-5"></i>
        </button>

        <a class="navbar-brand font-weight-bold d-flex align-items-center" href="{{ route('dashboard') }}">
            <span class="fs-5 fw-bold text-dark">Student Management System</span>
        </a>

        <!-- Logged-in User Dropdown -->
        <div class="ms-auto d-flex align-items-center">
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle d-flex align-items-center border" type="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle me-2 fs-5 text-primary"></i>
                    <span class="fw-semibold me-2">{{ Auth::user()->name }}</span>
                    <span class="badge bg-secondary text-uppercase" style="font-size: 0.7rem;">{{ Auth::user()->role }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userMenuButton">
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person me-2"></i> {{ __('Profile') }}
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger d-flex align-items-center">
                                <i class="bi bi-box-arrow-right me-2"></i> {{ __('Log Out') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
