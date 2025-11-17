<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Login | KLL Logistics Innovation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="KLL-Logistics Innovation" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.ico') }}">

    <!-- preloader css -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/preloader.min.css') }}" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet"
        type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('backend/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    <!-- Toaster-->
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/assets/css/toastr.css') }}">

    <style>
        .role-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
        }
        .facility-dropdown {
            position: relative;
        }
        .loading-spinner {
            display: none;
            text-align: center;
            padding: 10px;
        }
        .user-info-card {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            color: white;
        }
        .dropdown-item.active {
            background-color: #5156be;
            color: white;
        }
    </style>
</head>

<body>
    <div class="auth-page">
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="col-xxl-3 col-lg-4 col-md-5">
                    <div class="auth-full-page-content d-flex p-sm-5 p-4">
                        <div class="w-100">
                            <div class="d-flex flex-column h-100">
                                <div class="mb-4 mb-md-5 text-center">
                                    <a href="{{ route('admin.login') }}" class="d-block auth-logo">
                                        <img src="{{ asset('backend/assets/images/logo-sm.svg') }}" alt=""
                                            height="28"> <span class="logo-txt">Login</span>
                                    </a>
                                </div>
                                <div class="auth-content my-auto">
                                    <div class="text-center">
                                        <h5 class="mb-0">Welcome Back !</h5>
                                        <p class="text-muted mt-2">Sign in to continue to Login.</p>
                                    </div>

                                    <!-- User Info Card -->
                                    <div class="user-info-card mb-3" id="userInfoCard" style="display: none;">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 text-white" id="userName"></h6>
                                                <p class="mb-0 text-white-50" id="userRole"></p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <span class="badge bg-success" id="roleBadge"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Error Messages -->
                                    <!-- @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if (Session::has('error'))
                                        <div class="alert alert-danger">
                                            {{ Session::get('error') }}
                                        </div>
                                    @endif -->

                                    @if (Session::has('success'))
                                        <div class="alert alert-success">
                                            {{ Session::get('success') }}
                                        </div>
                                    @endif

                                    <form class="mt-4 pt-2" action="{{ route('admin.login_submit') }}" method="post"
                                        autocomplete="off" id="loginForm">
                                        @csrf
                                        
                                        <!-- User ID Input -->
                                        <div class="mb-3">
                                            <label class="form-label">User ID</label>
                                            <input type="text" name='userid' class="form-control" id="userid"
                                                placeholder="Enter User ID" autocomplete="off" 
                                                value="{{ old('userid', $userid ?? '') }}"
                                                required>
                                            <div class="loading-spinner" id="useridLoading">
                                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Password Input -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-grow-1">
                                                    <label class="form-label">Password</label>
                                                </div>
                                            </div>
                                            <div class="input-group auth-pass-inputgroup">
                                                <input type="password" class="form-control" name="password"
                                                    placeholder="Enter password" aria-label="Password"
                                                    aria-describedby="password-addon" autocomplete="off" required>
                                                <button class="btn btn-light shadow-none ms-0" type="button"
                                                    id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                            </div>
                                        </div>

                                        <!-- Facility Dropdown -->
                                        <div class="mb-3">
                                            <label class="form-label">Facility</label>
                                            <div class="facility-dropdown">
                                                <button class="btn btn-light dropdown-toggle d-flex justify-content-between align-items-center w-100"
                                                    type="button" id="facilityDropdown" data-bs-toggle="dropdown"
                                                    data-bs-auto-close="true" aria-expanded="false"
                                                    disabled>
                                                    Select Facility <span><i class="mdi mdi-chevron-down"></i></span>
                                                </button>
                                                <ul class="dropdown-menu w-100" aria-labelledby="facilityDropdown"
                                                    id="facility-list">
                                                    <li class="dropdown-item-text">Please enter User ID first</li>
                                                </ul>
                                                <!-- Input text biasa untuk facility, bukan hidden -->
                                                <input type="text" name="facility" id="selectedFacility" 
                                                    class="form-control mt-2" placeholder="Selected facility will appear here" 
                                                    readonly style="display: none;">
                                                <div class="invalid-feedback" id="facilityError">
                                                    Please select a facility
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Captcha -->
                                        <div class="mb-3">
                                            <label class="form-label">Captcha</label>
                                            <div class="mb-2">
                                                <img id="captcha" src="{{ captcha_src() }}" alt="CAPTCHA" />
                                                <button class="btn btn-soft-info waves-effect waves-light"
                                                    type="button" onclick="refreshCaptcha()">
                                                    <i class="mdi mdi-refresh"></i> Refresh
                                                </button>
                                            </div>
                                            <input type="text" name="captcha" class="form-control"
                                                placeholder="Enter Captcha" required>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="mb-3">
                                            <button class="btn btn-primary w-100 waves-effect waves-light"
                                                type="submit" id="loginButton">
                                                <span id="loginButtonText">Log In</span>
                                                <div class="spinner-border spinner-border-sm d-none" id="loginSpinner" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </button>
                                        </div>
                                    </form>

                                    <div class="mt-4 pt-2 text-center">
                                        <!-- Additional links can go here -->
                                    </div>
                                </div>
                                <div class="mt-4 mt-md-5 text-center">
                                    <p class="mb-0">©
                                        <script>
                                            document.write(new Date().getFullYear())
                                        </script> Login . Created by Logistic Innovation
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side Carousel -->
                <div class="col-xxl-9 col-lg-8 col-md-7">
                    <div class="auth-bg pt-md-5 p-4 d-flex">
                        <div class="bg-overlay bg-primary"></div>
                        <ul class="bg-bubbles">
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                        </ul>
                        
                        <div class="row justify-content-center align-items-center">
                            <div class="col-xl-7">
                                <div class="p-0 p-sm-4 px-xl-0">
                                    <!-- Carousel content (sama seperti sebelumnya) -->
                                    <div id="reviewcarouselIndicators" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-indicators carousel-indicators-rounded justify-content-start ms-0 mb-0">
                                            <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                            <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                            <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                            <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
                                            <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="4" aria-label="Slide 5"></button>
                                            <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="5" aria-label="Slide 6"></button>
                                            <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="6" aria-label="Slide 7"></button>
                                            <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="7" aria-label="Slide 8"></button>
                                        </div>
                                        
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <div class="testi-contain text-white">
                                                    <i class="bx bxs-quote-alt-left text-success display-6"></i>
                                                    <h4 class="mt-4 fw-medium lh-base text-white">"Memahami pentingnya memberikan nilai tambah untuk para pemangku kepentingan, memaksimalkan kekuatan ilmu pengetahuan dan sumber daya untuk mendorong potensi personal dan organisasi hingga maksimal."</h4>
                                                    <div class="mt-4 pt-3 pb-5">
                                                        <div class="d-flex align-items-start">
                                                            <div class="flex-shrink-0">
                                                                <img src="{{ asset('backend/assets/images/users/avatar-11.jpg') }}" class="avatar-md img-fluid rounded-circle" alt="...">
                                                            </div>
                                                            <div class="flex-grow-1 ms-3 mb-4">
                                                                <h5 class="font-size-18 text-white">Innovative</h5>
                                                                <p class="mb-0 text-white-50">Value Of Corporate</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Other carousel items... -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('backend/assets/libs/jquery/jquery.min.js') }}"></script>


    <script>
(function() {
  // Robust jQuery handler + fallback vanilla (dipasang melalui delegation utk jaga jika elemen dibuat dinamis)
  function initPasswordToggle() {
    // jQuery path (delegation)
    if (window.jQuery) {
      $(document).off('click', '#password-addon'); // hapus handler lama jika ada
      $(document).on('click', '#password-addon', function(e) {
        e.preventDefault();
        var $btn = $(this);
        var $input = $btn.closest('.input-group').find('input[type="password"], input[type="text"]');

        if ($input.length === 0) {
          // coba selector berdasar name
          $input = $('input[name="password"]');
        }

        if ($input.length === 0) return;

        // Toggle type
        var currentType = $input.attr('type');
        var newType = (currentType === 'password') ? 'text' : 'password';
        $input.attr('type', newType);

        // Toggle icon classes (Material Design Icons) - aman jika class tidak ada
        var $icon = $btn.find('i');
        if ($icon.length) {
          $icon.toggleClass('mdi-eye-outline mdi-eye-off-outline');
        }

        // Accessibility: update aria-pressed / title
        var isShown = newType === 'text';
        $btn.attr('aria-pressed', isShown ? 'true' : 'false');
        $btn.attr('title', isShown ? 'Hide password' : 'Show password');
      });
    } else {
      // Vanilla fallback
      document.removeEventListener('click', _vanillaHandler, true);
      document.addEventListener('click', _vanillaHandler, true);
    }
  }

  function _vanillaHandler(e) {
    var target = e.target.closest ? e.target.closest('#password-addon') : (e.target.id === 'password-addon' ? e.target : null);
    if (!target) return;
    e.preventDefault();
    var container = target.closest('.input-group');
    var input = container ? container.querySelector('input[type="password"], input[type="text"]') : document.querySelector('input[name="password"]');
    if (!input) return;
    var newType = input.type === 'password' ? 'text' : 'password';
    input.type = newType;
    var icon = target.querySelector('i');
    if (icon) {
      icon.classList.toggle('mdi-eye-outline');
      icon.classList.toggle('mdi-eye-off-outline');
    }
    var isShown = newType === 'text';
    target.setAttribute('aria-pressed', isShown ? 'true' : 'false');
    target.setAttribute('title', isShown ? 'Hide password' : 'Show password');
  }

  // Inisialisasi aman saat DOM siap
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPasswordToggle);
  } else {
    initPasswordToggle();
  }
})();
</script>




    <script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/feather-icons/feather.min.js') }}"></script>
    <!-- pace js -->
    <script src="{{ asset('backend/assets/libs/pace-js/pace.min.js') }}"></script>
    <!-- password addon init -->
    <script src="{{ asset('backend/assets/js/pages/pass-addon.init.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/assets/js/toastr.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            let userRole = '';
            let userName = '';
            let selectedFacilityName = '';
            let selectedFacilityId = '';

            // Password show/hide
            $('#password-addon').on('click', function() {
                const passwordInput = $('input[name="password"]');
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);
                $(this).find('i').toggleClass('mdi-eye-outline mdi-eye-off-outline');
            });

            // Get user info and facilities when User ID is entered
            $('#userid').on('blur', function() {
                const userid = $(this).val().trim();
                
                if (userid.length === 0) {
                    resetUserInfo();
                    return;
                }

                if (userid.length < 3) {
                    toastr.warning('Please enter a valid User ID');
                    return;
                }

                $('#useridLoading').show();
                $('#facilityDropdown').prop('disabled', true);
                $('#facility-list').html('<li class="dropdown-item-text">Loading facilities...</li>');

                // Get user role and facilities
                $.ajax({
                    url: '{{ route('admin.get_user_info') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        userid: userid
                    },
                    success: function(response) {
                        $('#useridLoading').hide();
                        
                        if (response.success) {
                            // Display user info
                            userRole = response.role || '';
                            userName = response.name || '';
                            
                            displayUserInfo(userName, userRole);
                            
                            // Enable facility dropdown and load facilities
                            if (response.facilities && response.facilities.length > 0) {
                                loadFacilities(response.facilities);
                                $('#facilityDropdown').prop('disabled', false);
                            } else {
                                $('#facility-list').html('<li class="dropdown-item-text">No facilities found</li>');
                                toastr.warning('No facilities found for this user');
                            }
                        } else {
                            resetUserInfo();
                            toastr.error(response.message || 'User not found');
                        }
                    },
                    error: function(xhr) {
                        $('#useridLoading').hide();
                        resetUserInfo();
                        toastr.error('Error loading user information');
                        console.error('Error:', xhr.responseText);
                    }
                });
            });

           // Load facilities from response
// Load facilities from response - SIMPLIFIED VERSION
function loadFacilities(facilities) {
    $('#facility-list').empty();
    facilities.forEach(function(facility, index) {
        // Gunakan text sebagai primary value, simpan semua data di attribute
        const facilityName = facility.NAME || facility.Name || `Facility ${index + 1}`;
        const facilityId = facility.ID || facility.FACILITY_ID || facilityName; // Fallback to name if no ID
        
        const facilityItem = `
            <li>
                <a class="dropdown-item facility-item" href="#" 
                   data-facility="${facilityName}">
                    ${facilityName}
                </a>
            </li>
        `;
        $('#facility-list').append(facilityItem);
    });
}

// Handle facility selection - SIMPLIFIED VERSION
$(document).on('click', '.facility-item', function(e) {
    e.preventDefault();
    
    $('.facility-item').removeClass('active');
    $(this).addClass('active');
    
    // Langsung ambil dari text, ini yang paling reliable
    selectedFacilityName = $(this).text().trim();
    
    $('#facilityDropdown').html(selectedFacilityName + ' <span><i class="mdi mdi-chevron-down"></i></span>');
    
    // Simpan nama facility ke input
    $('#selectedFacility').val(selectedFacilityName);
    
    //console.log('Facility Selected:', selectedFacilityName);
    //console.log('Input Value:', $('#selectedFacility').val());
    
    $('#selectedFacility').removeClass('is-invalid');
    $('#facilityError').hide();
    
    toastr.success('Facility selected: ' + selectedFacilityName);
});

            // Display user information
            function displayUserInfo(name, role) {
                if (name && role) {
                    $('#userName').text(name);
                    // $('#userRole').text(`Role: ${role}`);
                    // $('#roleBadge').text(role);
                    $('#userRole').hide();
                    $('#roleBadge').hide();
                    
                    // Set badge color based on role
                    const badgeColors = {
                        'Admin': 'bg-danger',
                        'Driver': 'bg-success', 
                        'Vendor': 'bg-warning',
                        'default': 'bg-info'
                    };
                    
                    $('#roleBadge').removeClass('bg-danger bg-success bg-warning bg-info')
                                  .addClass(badgeColors[role] || badgeColors.default);
                    
                    $('#userInfoCard').slideDown();
                }
            }

            // Reset user information
            function resetUserInfo() {
                $('#userInfoCard').slideUp();
                $('#userName').text('');
                $('#userRole').text('');
                $('#roleBadge').text('');
                $('#facilityDropdown').prop('disabled', true);
                $('#facilityDropdown').html('Select Facility <span><i class="mdi mdi-chevron-down"></i></span>');
                $('#facility-list').html('<li class="dropdown-item-text">Please enter User ID first</li>');
                $('#selectedFacility').val('').hide();
                userRole = '';
                userName = '';
                selectedFacilityName = '';
                selectedFacilityId = '';
            }

            // Form submission validation
            $('#loginForm').on('submit', function(e) {
                const facility = $('#selectedFacility').val();
                
                //console.log('Form submission - Facility value:', facility);
                
                if (!facility || facility === '') {
                    e.preventDefault();
                    $('#selectedFacility').addClass('is-invalid');
                    $('#facilityError').show();
                    toastr.error('Please select a facility');
                    return false;
                }

                // Show loading state
                $('#loginButton').prop('disabled', true);
                $('#loginButtonText').text('Logging in...');
                $('#loginSpinner').removeClass('d-none');
                
                return true;
            });

            $(document).ready(function() {
    const useridVal = $('#userid').val().trim();
    if (useridVal.length >= 3) {
        setTimeout(function() {
            //console.log('Auto reload facilities for existing userid:', useridVal);
            $('#userid').trigger('blur');
            $('input[name="captcha"]').focus();
        }, 800);
    }
});
        });

        // Refresh captcha
        function refreshCaptcha() {
            const captcha = document.getElementById('captcha');
            captcha.src = "{{ captcha_src() }}" + "?t=" + new Date().getTime();
        }

        // Toastr notifications
        @if (Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}"
            switch (type) {
                case 'info':
                    toastr.info(" {{ Session::get('message') }} ");
                    break;
                case 'success':
                    toastr.success(" {{ Session::get('message') }} ");
                    break;
                case 'warning':
                    toastr.warning(" {{ Session::get('message') }} ");
                    break;
                case 'error':
                    toastr.error(" {{ Session::get('message') }} ");
                    break;
            }
        @endif

       
    </script>

     <script>
document.addEventListener('DOMContentLoaded', function() {
    const captcha = document.getElementById('captcha');
    const refreshBtn = document.getElementById('refreshCaptcha');

    // Fungsi untuk refresh captcha manual atau otomatis
    function refreshCaptcha() {
        captcha.src = "{{ captcha_src('default', true) }}" + "?t=" + new Date().getTime();
        scheduleNextRefresh(); // Jadwalkan refresh berikutnya secara acak
    }

    // Fungsi untuk menjadwalkan auto refresh acak
    function scheduleNextRefresh() {
        // Acak antara 10 detik (10000 ms) dan 60 detik (60000 ms)
        const randomInterval = Math.floor(Math.random() * (60000 - 10000 + 1)) + 10000;
        setTimeout(refreshCaptcha, randomInterval);
        //console.log("Captcha akan refresh otomatis dalam " + (randomInterval / 1000).toFixed(1) + " detik");
    }

    // Klik tombol refresh manual
    refreshBtn.addEventListener('click', refreshCaptcha);

    // Mulai auto-refresh pertama kali
    scheduleNextRefresh();
});

$(document).ready(function() {
    @if ($errors->has('captcha'))
        toastr.error("Captcha salah", "", { "timeOut": 3000, "positionClass": "toast-top-right" });
    @endif

    @if (Session::has('error'))
        @php
            $errorMessage = Session::get('error');
        @endphp
        @if (str_contains($errorMessage, 'captcha'))
            toastr.error("Captcha salah", "", { "timeOut": 3000, "positionClass": "toast-top-right" });
        @else
            toastr.error("{{ $errorMessage }}", "", { "timeOut": 3000, "positionClass": "toast-top-right" });
        @endif
    @endif
});

</script>

</body>
</html>