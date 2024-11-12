<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="{{ route('index') }}" class="sidebar-logo">
            <img src="{{ asset('assets/images/logo.png') }}" alt="site logo" class="light-logo">
            <img src="{{ asset('assets/images/logo-light.png') }}" alt="site logo" class="dark-logo">
            <img src="{{ asset('assets/images/logo-icon.png') }}" alt="site logo" class="logo-icon">
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">

            <!-- Dashboard Menu Item (Always visible) -->
            <!-- Sidebar link dashboard -->
            <li class="sidebar-item">
                @if (Auth::user()->role == 'superadmin')
                    <a href="{{ route('dashboard.direktur') }}" class="sidebar-link">
                        <iconify-icon icon="ic:baseline-dashboard" class="icon"></iconify-icon>
                        <span>Dashboard Direktur</span>
                    </a>
                @elseif(Auth::user()->role == 'admin')
                    <a href="{{ route('dashboard.staf') }}" class="sidebar-link">
                        <iconify-icon icon="ic:baseline-dashboard" class="icon"></iconify-icon>
                        <span>Dashboard Admin</span>
                    </a>
                @else
                    <a href="{{ route('dashboard.user') }}" class="sidebar-link">
                        <iconify-icon icon="ic:baseline-dashboard" class="icon"></iconify-icon>
                        <span>Dashboard User</span>
                    </a>
                @endif
            </li>


            @if (Auth::user()->role == 'user')
                <!-- For User Role -->
                <li class="sidebar-item">
                    <a href="{{ route('form.pengajuan') }}" class="sidebar-link">
                        <iconify-icon icon="mdi:form-select" class="icon"></iconify-icon>
                        <span>Form Pengajuan</span>
                    </a>
                </li>
            @endif

            @if (Auth::user()->role == 'user')
            <!-- For Admin and Superadmin Roles -->
            <li class="sidebar-item">
                <a href="{{ route('list.pengajuan') }}" class="sidebar-link">
                    <iconify-icon icon="mdi:clipboard-list" class="icon"></iconify-icon>
                    <span>List Pengajuan</span>
                </a>
            </li>
        @endif

            @if (Auth::user()->role == 'admin')
                <!-- For Admin and Superadmin Roles -->
                <li class="sidebar-item">
                    <a href="{{ route('list.pengajuan.staf') }}" class="sidebar-link">
                        <iconify-icon icon="mdi:clipboard-list" class="icon"></iconify-icon>
                        <span>List Persetujuan Staf</span>
                    </a>
                </li>
            @endif

            @if (Auth::user()->role == 'superadmin')
                <!-- Only for Superadmin Role -->
                <li class="sidebar-item">
                    <a href="{{ route('list.pengajuan.direktur') }}" class="sidebar-link">
                        <iconify-icon icon="mdi:clipboard-list" class="icon"></iconify-icon>
                        <span>List Persetujuan Direktur</span>
                    </a>
                </li>
            @endif

        </ul>
    </div>
</aside>
