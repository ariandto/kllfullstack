<!doctype html>
<html lang="en">

    <head>

        <meta charset="utf-8" />
        <title>Login Vendor| KLL Logistics Innovation</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('backend/assets/images/favicon.ico')}}">

        <!-- preloader css -->
        <link rel="stylesheet" href="{{asset('backend/assets/css/preloader.min.css')}}" type="text/css" />

        <!-- Bootstrap Css -->
        <link href="{{asset('backend/assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('backend/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{asset('backend/assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />

        <!-- Toaster-->
        <link rel="stylesheet" type="text/css" href="{{asset('backend/assets/css/toastr.css')}}" >
        

    </head>

    <body>

    <!-- <body data-layout="horizontal"> -->
        <div class="auth-page">
            <div class="container-fluid p-0">
                <div class="row g-0">
                    <div class="col-xxl-3 col-lg-4 col-md-5">
                        <div class="auth-full-page-content d-flex p-sm-5 p-4">
                            <div class="w-100">
                                <div class="d-flex flex-column h-100">
                                    <div class="mb-4 mb-md-5 text-center">
                                        <a href="{{route('vendor.login')}}" class="d-block auth-logo">
                                            <img src="{{asset('backend/assets/images/logo-sm.svg')}}" alt="" height="28"> <span class="logo-txt">Login</span>
                                        </a>
                                    </div>
                                    <div class="auth-content my-auto">
                                        <div class="text-center">
                                            <h5 class="mb-0">Welcome Back Vendor !</h5>
                                            <p class="text-muted mt-2">Sign in to continue to Login.</p>
                                        </div>

                                                {{-- Ini Buat Halaman Login --}}
                                                <!-- Ini Eror Message -->
                                                @if ($errors->any())
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                                @endif

                                                {{-- Ini Sesssion Buat Eror Penting --}}
                                                @if (Session::has('error'))
                                                <li>{{ Session::get('error') }}</li>
                                                @endif

                                                @if (Session::has('success'))
                                                <li>{{ Session::get('success') }}</li> 
                                                @endif                                       

                                                <form class="mt-4 pt-2" action="{{route('vendor.login_submit')}}" method="post" autocomplete="off">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label class="form-label">User ID</label>
                                                        <input type="text" name='userid' class="form-control" id="userid" placeholder="Enter User ID" autocomplete="off">
                                                    </div>

                                                    <div class="mb-3">
                                                        <div class="d-flex align-items-start">
                                                            <div class="flex-grow-1">
                                                                <label class="form-label">Password</label>
                                                            </div>
                                                            <div class="flex-shrink-0">
                                                            </div> 
                                                        </div>
                                                        <div class="input-group auth-pass-inputgroup">
                                                            <input type="password" class="form-control" name="password" placeholder="Enter password" aria-label="Password" aria-describedby="password-addon" autocomplete="off">
                                                            <button class="btn btn-light shadow-none ms-0" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                                        </div>
                                                    </div>
                                                
                                                    {{-- Ini untuk umpetin mata --}}
                                                    <div class="row mb-4">
                                                    <div class="col">
                                                        {{-- <div class="form-check"> 
                                                        </div>   --}}
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Facility</label>
                                                        <div class="btn-group w-100">
                                                            <button class="btn btn-light dropdown-toggle d-flex justify-content-between align-items-center w-100" type="button" id="defaultDropdown" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                                                                Facility <span><i class="mdi mdi-chevron-down"></i></span>
                                                            </button>
                                                            <ul class="dropdown-menu w-100" aria-labelledby="defaultDropdown" id="facility-list">
                                                                <li><a class="dropdown-item" href="#">Facility</a></li>
                                                            </ul>
                                                        </div>
                                                        <!-- Hidden input to store selected facility -->
                                                        <input type="hidden" name="facility" id="selectedFacility" >
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Captcha</label>
                                                        <div class="mb-2">
                                                            <img id="captcha" src="{{ captcha_src() }}" alt="CAPTCHA" /> <!-- Menampilkan gambar CAPTCHA dengan ID -->
                                                            <button class="btn btn-soft-info waves-effect waves-light" type="button" onclick="refreshCaptcha()">Refresh</button>
                                                        </div> 
                                                        <input type="text" name="captcha" class="form-control" placeholder="Masukkan Captcha" required>
                                                    </div>  

                                                    </div>
                                                    <div class="mb-3">
                                                    <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Log In</button>
                                                    </div>
                                                </form>


{{-- Ini Buat Halaman Login --}}  
                                        <div class="mt-4 pt-2 text-center">
                                        </div>

                                    </div>
                                    <div class="mt-4 mt-md-5 text-center">
                                        <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> Login   . Created by Logistic Innovation</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end auth full page content -->
                    </div>
                    <!-- end col -->
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
                            <!-- end bubble effect -->
                            <div class="row justify-content-center align-items-center">
                                <div class="col-xl-7">
                                    <div class="p-0 p-sm-4 px-xl-0">
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
                                            <!-- end carouselIndicators -->
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <div class="testi-contain text-white">
                                                        <i class="bx bxs-quote-alt-left text-success display-6"></i>
                    
                                                        <h4 class="mt-4 fw-medium lh-base text-white">“Memahami pentingnya memberikan nilai tambah untuk para pemangku kepentingan, memaksimalkan kekuatan ilmu pengetahuan dan sumber daya untuk mendorong potensi personal dan organisasi hingga maksimal. ”
                                                        </h4>
                                                        <div class="mt-4 pt-3 pb-5">
                                                            <div class="d-flex align-items-start">
                                                                <div class="flex-shrink-0">
                                                                    <img src="{{asset('backend/assets/images/users/avatar-11.jpg')}}" class="avatar-md img-fluid rounded-circle" alt="...">
                                                                </div>
                                                                <div class="flex-grow-1 ms-3 mb-4">
                                                                    <h5 class="font-size-18 text-white">Innovative
                                                                    </h5>
                                                                    <p class="mb-0 text-white-50">Value Of Corporate</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                    
                                                <div class="carousel-item">
                                                    <div class="testi-contain text-white">
                                                        <i class="bx bxs-quote-alt-left text-success display-6"></i>
                    
                                                        <h4 class="mt-4 fw-medium lh-base text-white">“Menghargai dan menanamkan rasa kepemilikan dan tanggung jawab, tidak takut akan kegagalan, serta memastikan semua pekerjaan dilakukan dengan penuh integritas dan konsistensi.”</h4>
                                                        <div class="mt-4 pt-3 pb-5">
                                                            <div class="d-flex align-items-start">
                                                                <div class="flex-shrink-0">
                                                                    <img src="{{asset('backend/assets/images/users/avatar-12.jpg')}}" class="avatar-md img-fluid rounded-circle" alt="...">
                                                                </div>
                                                                <div class="flex-grow-1 ms-3 mb-4">
                                                                    <h5 class="font-size-18 text-white">Accountable
                                                                    </h5>
                                                                    <p class="mb-0 text-white-50">Value Of Corporate</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                    
                                                <div class="carousel-item">
                                                    <div class="testi-contain text-white">
                                                        <i class="bx bxs-quote-alt-left text-success display-6"></i>
                    
                                                        <h4 class="mt-4 fw-medium lh-base text-white">“Meraih keseimbangan antara keahlian intrapersonal dan profesional, memiliki kemampuan untuk mengatasi batasan dan hambatan intrapersonal untuk menciptakan solusi terbaik sebagai hasil. .”</h4>
                                                        <div class="mt-4 pt-3 pb-5">
                                                            <div class="d-flex align-items-start">
                                                                <img src="{{asset('backend/assets/images/users/avatar-13.jpg')}}"
                                                                    class="avatar-md img-fluid rounded-circle" alt="...">
                                                                <div class="flex-1 ms-3 mb-4">
                                                                    <h5 class="font-size-18 text-white">Mastery</h5>
                                                                    <p class="mb-0 text-white-50">Value Of Corporate
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="carousel-item">
                                                    <div class="testi-contain text-white">
                                                        <i class="bx bxs-quote-alt-left text-success display-6"></i>
                    
                                                        <h4 class="mt-4 fw-medium lh-base text-white">“Memiliki motivasi untuk selalu unggul, namun bukan untuk “mengalahkan” orang lain, tetapi untuk mengejar pertumbuhan dan target yang lebih tinggi bagi diri sendiri dan orang lain.”</h4>
                                                        <div class="mt-4 pt-3 pb-5">
                                                            <div class="d-flex align-items-start">
                                                                <img src="{{asset('backend/assets/images/users/avatar-14.jpg')}}"
                                                                    class="avatar-md img-fluid rounded-circle" alt="...">
                                                                <div class="flex-1 ms-3 mb-4">
                                                                    <h5 class="font-size-18 text-white">Excellence</h5>
                                                                    <p class="mb-0 text-white-50">Value Of Corporate
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="carousel-item">
                                                    <div class="testi-contain text-white">
                                                        <i class="bx bxs-quote-alt-left text-success display-6"></i>
                    
                                                        <h4 class="mt-4 fw-medium lh-base text-white">“Memimpin dengan memberikan contoh, serta memahami bahwa lebih dari otoritas dan otonomi, kepemimpinan adalah soal tanggung jawab yang dipikul.”</h4>
                                                        <div class="mt-4 pt-3 pb-5">
                                                            <div class="d-flex align-items-start">
                                                                <img src="{{asset('backend/assets/images/users/avatar-15.jpg')}}"
                                                                    class="avatar-md img-fluid rounded-circle" alt="...">
                                                                <div class="flex-1 ms-3 mb-4">
                                                                    <h5 class="font-size-18 text-white">Leadership</h5>
                                                                    <p class="mb-0 text-white-50">Value Of Corporate
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="carousel-item">
                                                    <div class="testi-contain text-white">
                                                        <i class="bx bxs-quote-alt-left text-success display-6"></i>
                    
                                                        <h4 class="mt-4 fw-medium lh-base text-white">“Konsisten, komitmen, dan selaras antara kata dan perbuatan, berani untuk tidak berhenti pada apa yang “baik”, namun mendorong diri melakukan yang “benar” untuk keberlanjutan organisasi dan para pemangku kepentingan.”</h4>
                                                        <div class="mt-4 pt-3 pb-5">
                                                            <div class="d-flex align-items-start">
                                                                <img src="{{asset('backend/assets/images/users/avatar-16.jpg')}}"
                                                                    class="avatar-md img-fluid rounded-circle" alt="...">
                                                                <div class="flex-1 ms-3 mb-4">
                                                                    <h5 class="font-size-18 text-white">Integrity</h5>
                                                                    <p class="mb-0 text-white-50">Value Of Corporate
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="carousel-item">
                                                    <div class="testi-contain text-white">
                                                        <i class="bx bxs-quote-alt-left text-success display-6"></i>
                    
                                                        <h4 class="mt-4 fw-medium lh-base text-white">“Menempatkan pertumbuhan bersama di atas kepentingan pribadi, memiliki kesadaran bahwa keberhasilan hanya dapat dicapai saat saling melengkapi, memahami bahwa setiap posisi di perusahaan adalah sama pentingnya.”</h4>
                                                        <div class="mt-4 pt-3 pb-5">
                                                            <div class="d-flex align-items-start">
                                                                <img src="{{asset('backend/assets/images/users/avatar-17.jpg')}}"
                                                                    class="avatar-md img-fluid rounded-circle" alt="...">
                                                                <div class="flex-1 ms-3 mb-4">
                                                                    <h5 class="font-size-18 text-white">Teamwork</h5>
                                                                    <p class="mb-0 text-white-50">Value Of Corporate
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="carousel-item">
                                                    <div class="testi-contain text-white">
                                                        <i class="bx bxs-quote-alt-left text-success display-6"></i>
                    
                                                        <h4 class="mt-4 fw-medium lh-base text-white">“Bukan hanya sekadar rasa “senang” namun komitmen untuk tetap positif dan termotivasi walaupun terdapat tantangan — menghadapi segala hal dengan minat dan perhatian yang sama.”</h4>
                                                        <div class="mt-4 pt-3 pb-5">
                                                            <div class="d-flex align-items-start">
                                                                <img src="{{asset('backend/assets/images/users/avatar-18.jpg')}}"
                                                                    class="avatar-md img-fluid rounded-circle" alt="...">
                                                                <div class="flex-1 ms-3 mb-4">
                                                                    <h5 class="font-size-18 text-white">Enthusiasm</h5>
                                                                    <p class="mb-0 text-white-50">Value Of Corporate
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                



                                            </div>
                                            <!-- end carousel-inner -->
                                        </div>
                                        <!-- end review carousel -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container fluid -->
        </div>


        <!-- JAVASCRIPT -->
        <script src="{{asset('backend/assets/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('backend/assets/libs/metismenu/metisMenu.min.js')}}"></script>
        <script src="{{asset('backend/assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('backend/assets/libs/node-waves/waves.min.js')}}"></script>
        <script src="{{asset('backend/assets/libs/feather-icons/feather.min.js')}}"></script>
        <!-- pace js -->
        <script src="{{asset('backend/assets/libs/pace-js/pace.min.js')}}"></script>
        <!-- password addon init -->
        <script src="{{asset('backend/assets/js/pages/pass-addon.init.js')}}"></script>

        
        <script type="text/javascript" src="{{asset('backend/assets/js/toastr.min.js')}}"></script>
       
        {{-- Ini Khusus Login --}}
        <script src="{{asset('backend/assets/js/jquery-3.6.0.min.js')}}"></script>
         <script>
            $(document).ready(function() {
                // Mengambil data fasilitas berdasarkan User ID
                $('#userid').on('blur keydown', function(e) {
                    if (e.type === 'blur' || (e.type === 'keydown' && e.key === 'Enter')) {
                        var userid = $(this).val();

                        $.ajax({
                            url: '{{ route("vendor.get_facility") }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                userid: userid
                            },
                            success: function(response) {
                                $('#facility-list').empty();
                                if (response.facilities.length > 0) {
                                    response.facilities.forEach(function(facility) {
                                        $('#facility-list').append('<li><a class="dropdown-item facility-item" href="#" data-value="' + facility.ID + '">' + facility.NAME + '</a></li>');
                                    });
                                } else {
                                    $('#facility-list').append('<li><a class="dropdown-item" href="#">No facilities found</a></li>');
                                }
                            },
                            error: function() { 
                                toastr.error('An error occurred while loading facilities. Please try again.');
                            }
                        });
                    }
                });

                $(document).on('click', '.facility-item', function(e) {
                e.preventDefault();
                var selectedFacility = $(this).text();
                $('#defaultDropdown').text(selectedFacility);
                $('#selectedFacility').val(selectedFacility); // Set hidden input value

                // Debugging: Cek nilai yang diset ke hidden input
                console.log('Selected Facility:', selectedFacility);
                console.log('Hidden Input Value:', $('#selectedFacility').val()); // Pastikan nilai di-set

                // Tutup dropdown setelah memilih item
                $('.dropdown-menu').removeClass('show');
                });


            
            });
        </script>
        
        {{-- Ini Khusus Login --}}
        <script>
            @if(Session::has('message'))
                var type = "{{ Session::get('alert-type','info') }}"
                switch(type){
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
           
    function refreshCaptcha() {
        // Mengambil elemen gambar Captcha
        var captcha = document.getElementById('captcha'); 
        // Mengubah src dengan menambahkan query string untuk menghindari cache
        captcha.src = "{{ captcha_src() }}" + "?t=" + new Date().getTime();
    }

   
    </script>




    </body>

</html>