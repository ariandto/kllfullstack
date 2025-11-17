<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <div class="navbar-brand-box">
                <a href="{{ route('admin.dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{asset('backend/assets/images/logo-sm.svg')}}" alt="" height="24">
                    </span>
                    <span class="logo-lg">
                        <img src="{{asset('backend/assets/images/logo-sm.svg')}}" alt="" height="24"> <span class="logo-txt">SCM Web</span>
                    </span>
                </a>

                <a href="{{ route('admin.dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{asset('backend/assets/images/logo-sm.svg')}}" alt="" height="24">
                    </span>
                    <span class="logo-lg">
                        <img src="{{asset('backend/assets/images/logo-sm.svg')}}" alt="" height="24"> <span class="logo-txt">SCM Web</span>
                    </span>
                </a>
            </div>
            <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>  
        </div>

        <div class="d-flex">
            {{-- Tambahin ini maka ilang pas mobile version d-none --}}
            <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="grid" class="icon-lg"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <div class="p-2">
                        <div class="fw-bold mb-2">Facility Info:</div>
                        @if (session('facility_info'))
                            @foreach (session('facility_info') as $facility)
                                <div class="dropdown-item d-flex flex-column">
                                    <a class="dropdown-item" ><i class="bx bx-box"></i> {{ $facility['Facility_ID'] }}</a>
                                    <a class="dropdown-item"><i class="bx bx-box"></i> {{ $facility['Relasi'] }}</a>
                                    <a class="dropdown-item" ><i class="bx bx-building-house"></i> {{ $facility['Name'] }}</a>
                                </div>
                            @endforeach
                        @else
                            <div class="dropdown-item">No facility information available.</div>
                        @endif
                    </div>
                </div>
            </div> 

            <div class="dropdown d-sm-inline-block">
                <button type="button" class="btn header-item" id="mode-setting-btn">
                    <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                    <i data-feather="sun" class="icon-lg layout-mode-light"></i>
                </button>
            </div>
        
            @php
                $id = Auth::guard('admin')->id();
                $profileData = App\Models\Admin::find($id);
            @endphp
          
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item bg-light-subtle border-start border-end" id="page-header-user-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="{{ (!empty($profileData->photo)) ? url('upload/admin_images/'.$profileData->photo) : url('upload/no_image.jpg') }}"
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1 fw-medium">{{ $profileData->name }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="{{route('admin.profile')}}"><i class="mdi mdi mdi-face-man font-size-16 align-middle me-1"></i> Profile</a>
                    <a class="dropdown-item" href="{{route('admin.change.password')}}"><i class="mdi mdi-lock font-size-16 align-middle me-1"></i>Change Password</a>
                   
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{route('admin.logout')}}"><i class="mdi mdi-logout font-size-16 align-middle me-1"></i> Logout</a>
                </div>
            </div>

        </div>
    </div>
</header>