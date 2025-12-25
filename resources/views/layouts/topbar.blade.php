<nav class="topbar">
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Mobile Menu Toggle -->
            <button class="btn btn-link d-md-none text-dark" type="button" onclick="toggleSidebar()">
                <i class="bi bi-list fs-4"></i>
            </button>

            <div class="d-flex align-items-center gap-3">
                <!-- Notifications -->
                @php
                    $notificationsEnabled = Illuminate\Support\Facades\Schema::hasTable('notifications');
                    $unreadNotifications = $notificationsEnabled ? auth()->user()->unreadNotifications()->count() : 0;
                    $notificationsUrl = Route::has('notifications.index') ? route('notifications.index') : '#';
                @endphp
                <a href="{{ $notificationsUrl }}" class="nav-link position-relative text-dark">
                    <i class="bi bi-bell fs-5"></i>
                    @if($unreadNotifications > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $unreadNotifications }}
                        </span>
                    @endif
                </a>

                <!-- User Menu -->
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center text-dark text-decoration-none" href="#" role="button" data-bs-toggle="dropdown">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                <i class="bi bi-person"></i>
                            </div>
                            <span class="ms-2 d-none d-md-inline fw-semibold">{{ auth()->user()->firstname }} {{ auth()->user()->name }}</span>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person me-2"></i> Mon profil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
