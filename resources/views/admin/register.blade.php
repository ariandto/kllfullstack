@extends('admin.dashboard')
@section('admin')
<script src="{{ asset('backend/assets/js/jquery.min.js') }}"></script> 
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> --}}

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-9 col-lg-8"> 
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Registrasi User</h4>  
                        </div>
                    </div>
                </div>


                <div class="card-body "> 
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

                    <form class="needs-validation mt-4 pt-2" action="{{route('admin.register_submit')}}" method="post">
                        @csrf
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div>
                                    <div class="mb-3">
                                        <label for="userid" class="form-label">User ID</label>
                                        <input type="userid" name='userid' class="form-control" id="userid" placeholder="Enter User ID" required autocomplete="off">  
                                        <div class="invalid-feedback">
                                            Please Enter User ID
                                        </div>      
                                    </div>
                                
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Username</label>
                                        <input type="text" name='name' class="form-control" id="name" placeholder="Enter username" required autocomplete="off">
                                        <div class="invalid-feedback">
                                            Please Enter Username
                                        </div>  
                                    </div>
                                
                                    {{-- <div class="mb-3">
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
                                    </div>      --}}

                                    <div class="mb-3">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                <label class="form-label">Password</label>
                                            </div>
                                            <div class="flex-shrink-0">
                                            </div> 
                                        </div>
                                        <div class="input-group auth-pass-inputgroup">
                                            <input type="password" class="form-control" name="password" placeholder="Enter password" aria-label="Password" aria-describedby="password-addon" autocomplete="off" id="password">
                                            <button class="btn btn-light shadow-none ms-0 toggle-password" type="button" data-target="password" id="password-addon">
                                                <i class="mdi mdi-eye-outline"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    
                                    



                                    <div class="mb-3">
                                        <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Register</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    
                    </form>
                </div>
            </div>
        </div>
    </div> <!-- container-fluid -->
</div> 


<script>
    document.querySelectorAll('.toggle-password').forEach(button => {
     button.addEventListener('click', function () {
         let input = document.getElementById(this.getAttribute('data-target'));
         let icon = this.querySelector('i');
 
         if (input.type === "password") {
             input.type = "text";
             icon.classList.remove("mdi-eye-outline");
             icon.classList.add("mdi-eye-off-outline");
         } else {
             input.type = "password";
             icon.classList.remove("mdi-eye-off-outline");
             icon.classList.add("mdi-eye-outline");
         }
     });
 });
 
 </script>

@endsection



