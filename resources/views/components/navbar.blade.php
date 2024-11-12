<div class="navbar-header">
    <div class="row align-items-center justify-content-between">
        <div class="col-auto">
            <div class="d-flex flex-wrap align-items-center gap-4">
                <button type="button" class="sidebar-toggle">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl non-active"></iconify-icon>
                    <iconify-icon icon="iconoir:arrow-right" class="icon text-2xl active"></iconify-icon>
                </button>
                <button type="button" class="sidebar-mobile-toggle">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
                </button>
            </div>
        </div>
        <div class="col-auto">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <button type="button" data-theme-toggle class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"></button>

                <div class="dropdown">
                    <button class="d-flex justify-content-center align-items-center rounded-circle" type="button" data-bs-toggle="dropdown">
                        <img src="{{ asset('assets/images/user.png') }}" alt="image" class="w-40-px h-40-px object-fit-cover rounded-circle">
                    </button>
                    <div class="dropdown-menu to-top dropdown-menu-sm p-3" style="min-width: 200px;">
                        <div class="py-2 px-2 radius-8 bg-primary-50 mb-2 d-flex align-items-center justify-content-between gap-2">
                            <div>
                                <h6 class="text-lg text-primary-light fw-semibold mb-1">{{ Auth::user()->name }}</h6>
                                <span class="text-secondary-light fw-medium text-sm">{{ Auth::user()->jabatan }}</span>
                            </div>
                            <button type="button" class="hover-text-danger" aria-label="Close">
                                <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon>
                            </button>
                        </div>
                        
                        <ul class="list-unstyled mb-2">
                            <li><a href="{{ route('profile.show') }}" class="dropdown-item">Profil</a></li>
                        </ul>

                        <!-- Logout Button -->
                        <form method="POST" action="{{ route('logout') }}" class="d-flex justify-content-center">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100 text-left d-flex align-items-center gap-2">
                                <iconify-icon icon="heroicons-outline:logout" class="text-lg"></iconify-icon>
                                Logout
                            </button>
                        </form>
                    </div>
                </div><!-- Profile dropdown end -->
            </div>
        </div>
    </div>
</div>
