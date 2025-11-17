
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>SCM Web</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="KLL-Logistics Innovation" name="description" />
    <meta content="Themesbrand" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('backend/assets/images/favicon.ico')}}">

    <!-- Plugin CSS -->
    <link href="{{asset('backend/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css" />
    
    <!-- Preloader CSS -->
    <link rel="stylesheet" href="{{asset('backend/assets/css/preloader.min.css')}}" type="text/css" />

    <!-- Choices CSS -->
    <link href="{{asset('backend/assets/libs/choices.js/public/assets/styles/choices.min.css')}}" rel="stylesheet" type="text/css" />

     <!-- datepicker css -->
     <link rel="stylesheet" href="{{asset('backend/assets/libs/flatpickr/flatpickr.min.css')}}">

    <!-- DataTables CSS -->
    <link href="{{asset('backend/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- Core CSS -->
    <link href="{{asset('backend/assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend/assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />

    <!-- Toaster Bikinan Sendiri-->
    <link rel="stylesheet" type="text/css" href="{{asset('backend/assets/css/toastr.css')}}" >

</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- Header -->
        @include('vendor.body.header')

        <!-- Sidebar -->
        @include('vendor.body.sidebar')

        <!-- Main content -->
        <div class="main-content">
            @yield('vendor')
            @include('vendor.body.footer')
        </div>
        <!-- End main content -->

    </div>
    <!-- END layout-wrapper -->

    <!-- Right Sidebar -->
    @include('vendor.body.rightside')

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JS Plugins -->
    <script src="{{asset('backend/assets/libs/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('backend/assets/libs/metismenu/metisMenu.min.js')}}"></script>
    <script src="{{asset('backend/assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('backend/assets/libs/node-waves/waves.min.js')}}"></script>
    <script src="{{asset('backend/assets/libs/feather-icons/feather.min.js')}}"></script>

    <!-- Pace JS -->
    <script src="{{asset('backend/assets/libs/pace-js/pace.min.js')}}"></script>

    <!-- ApexCharts -->
    <script src="{{asset('backend/assets/libs/apexcharts/apexcharts.min.js')}}"></script>

    <!-- Vector Map -->
    <script src="{{asset('backend/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
    <script src="{{asset('backend/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js')}}"></script>

    {{-- <!-- Dashboard Init -->
    <script src="{{asset('backend/assets/js/pages/dashboard.init.js')}}"></script> --}}

    <!-- App JS -->
    <script src="{{asset('backend/assets/js/app.js')}}"></script>

    <!-- DataTables JS -->
   
    <script src="{{asset('backend/assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('backend/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    
    <script src="{{asset('backend/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('backend/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>

    <!-- Choices JS -->
    <script src="{{asset('backend/assets/libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>

     <!-- datepicker js -->
     <script src="{{asset('backend/assets/libs/flatpickr/flatpickr.min.js')}}"></script>

    {{-- <!-- Datatables Init JS -->
    <script src="{{asset('backend/assets/js/pages/datatables.init.js')}}"></script> --}}

     <!-- Table Editable plugin -->
 <script src="{{asset('backend/assets/libs/table-edits/build/table-edits.min.js')}}"></script>

 <script src="{{asset('backend/assets/js/pages/table-editable.int.js')}}"></script> 


    <!-- echarts js -->
    <script src="{{asset('backend/assets/libs/echarts/echarts.min.js')}}"></script>
    {{-- <!-- echarts init -->
    <script src="{{asset('backend/assets/js/pages/echarts.init.js')}}"></script> --}}

     <!-- pristine js -->
     <script src="{{asset('backend/assets/libs/pristinejs/pristine.min.js')}}"></script>

    <!-- DataTables Buttons JS -->
    <script src="{{ asset('backend/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

    {{-- Ini Khusus Bikin Sendiri --}}
    <!-- Contoh Fungsi JS Bikin Sendiri Untuk Export-->
    <script src="{{ asset('backend/assets/js/exportexcel.js') }}"></script>  
    <!-- Contoh Fungsi JS Bikin Sendiri Untuk Toaster-->
    <script type="text/javascript" src="{{asset('backend/assets/js/toastr.min.js')}}"></script>
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
   


</body>

</html>
