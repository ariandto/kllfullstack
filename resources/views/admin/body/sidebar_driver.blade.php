@php
    $facilityInfo = session('facility_info', []);
    $facilityID = $facilityInfo[0]['Facility_ID'] ?? '';
    $RELASI = $facilityInfo[0]['Relasi'] ?? '';
    $relasiSuffix = substr($RELASI, -3); // contoh: ambil "IND"
@endphp

<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">

                <!-- Header Menu -->
                <li class="menu-title" data-key="t-menu">Driver Menu</li>

                <!-- Dashboard -->
                <li>
                    <a href="{{ route('driver.dashboard') }}">
                        <i data-feather="home"></i>
                        <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                <!-- Data Lembur -->
                <li>
                    <a href="{{ route('driver.overtime') }}">
                        <i data-feather="clock"></i>
                        <span data-key="t-overtime">Data Lembur</span>
                    </a>
                </li>

                <!-- Report Lembur -->
                <li>
                    <a href="{{ route('driver.overtime.report') }}">
                        <i data-feather="file-text"></i>
                        <span data-key="t-report">Report Lembur</span>
                    </a>
                </li>

                <!-- Profile -->
                <li>
                    <a href="{{ route('admin.profile') }}">
                        <i data-feather="user"></i>
                        <span data-key="t-profile">Profile</span>
                    </a>
                </li>

                <!-- Ganti Password -->
                <li>
                    <a href="{{ route('admin.change.password') }}">
                        <i data-feather="lock"></i>
                        <span data-key="t-password">Ganti Password</span>
                    </a>
                </li>

                <!-- Logout -->
                <li>
                    <a href="{{ route('admin.logout') }}">
                        <i data-feather="log-out"></i>
                        <span data-key="t-logout">Logout</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>
