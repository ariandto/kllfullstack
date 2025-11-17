@extends('admin.dashboard')
@section('admin')
<link rel="stylesheet" type="text/css" href="{{asset('backend/assets/css/toastr.css')}}" >
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-center">
                    @if (session('facility_info'))
                    @foreach (session('facility_info') as $facility)
                    <h4 style="font-size: 1.5rem; font-weight: bold; text-align: center;">
                       MASTER POINT 5R {{ $facility['Name'] }}
                    </h4>
                    <div class="page-title-right">
                        {{-- <ol class="breadcrumb m-0"> 
                        </ol> --}}
                    </div>
                    @endforeach
                    @else 
                    @endif 
                </div>
            </div>
        </div>       
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Error Messages -->
                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
        
                        @if (Session::has('error'))
                            <div class="alert alert-danger">{{ Session::get('error') }}</div>
                        @endif
        
                        @if (Session::has('success'))
                            <div class="alert alert-success">{{ Session::get('success') }}</div>
                        @endif
                        <!-- End Error Messages -->
                        
                        <form id="saveForm" method="POST" action="{{ route('admin.master.public.checklist5r.masterpoint5r_submit') }}"> 
                            @csrf
                        
                            <input type="hidden" id="owner5r_input" name="owner5r" value="{{ old('owner5r') }}">
                            <input type="hidden" id="area5r_input" name="area5r" value="{{ old('area5r') }}">
                            <input type="hidden" id="department5r_input" name="dept5r" value="{{ old('dept5r') }}">
                            <input type="hidden" id="pointcheck5r_input" name="point5r" value="{{ old('point5r') }}">

                           
                        
                            <div class="row mb-3">
                                <div class="col-6 col-md-3">
                                    <label for="owner5r" class="form-label">Owner</label>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-light dropdown-toggle w-100 text-start d-flex align-items-center justify-content-between" 
                                                type="button" id="owner5r" data-bs-toggle="dropdown" aria-expanded="false">
                                            {{ old('owner5r', 'Select Owner') }} <i class="mdi mdi-chevron-down"></i>
                                        </button>
                        
                        
                                        <div class="dropdown-menu w-100" aria-labelledby="dropdownOwnerButton" style="max-height: 200px; overflow-y: auto;">
                                            @if(isset($dataowner5r) && !empty($dataowner5r))
                                                @foreach($dataowner5r as $owner)
                                                    <a class="dropdown-item" data-value="{{ $owner->Owner }}" href="#">{{ $owner->Owner }}</a>
                                                @endforeach
                                            @else
                                                <a class="dropdown-item disabled" href="#">No Owners Available</a>
                                            @endif
                                        </div>
                                    </div>  
                                </div>
                        
                                <div class="col-6 col-md-3">
                                    <label for="department5r" class="form-label">Department</label>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-light dropdown-toggle w-100 text-start d-flex align-items-center justify-content-between" 
                                        type="button" id="department5r" data-bs-toggle="dropdown" aria-expanded="false">
                                            {{ old('dept5r', 'Select Department') }} <i class="mdi mdi-chevron-down"></i>
                                        </button>
                                 
                                        <div class="dropdown-menu w-100" aria-labelledby="dropdownDepartmentButton" style="max-height: 200px; overflow-y: auto;">
                                            @if(isset($datadept5r) && !empty($datadept5r))
                                                @foreach($datadept5r as $dept)
                                                    <a class="dropdown-item" data-value="{{ $dept->Departement }}" href="#">{{ $dept->Departement }}</a>
                                                @endforeach
                                            @else
                                                <a class="dropdown-item disabled" href="#">No Departments Available</a>
                                            @endif
                                        </div>
                                    </div>  
                                </div> 
                        
                                <div class="col-6 col-md-3 mt-3 mt-md-0">
                                    <label for="area5r" class="form-label">Area</label>
                                    <input type="text" class="form-control" id="area5r" autocomplete="off" name="area5r" placeholder="Please Input Here..." required value="{{ old('area5r') }}">

                                </div>
                        
                                <div class="col-6 col-md-3">
                                    <label for="pointcheck5r" class="form-label">Point Check</label>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-light dropdown-toggle w-100 text-start d-flex align-items-center justify-content-between" type="button" 
                                                id="pointcheck5r" data-bs-toggle="dropdown" aria-expanded="false">
                                            {{ old('point5r', 'Select Point Check') }} <i class="mdi mdi-chevron-down"></i>
                                        </button>

                                
                        
                                        <div class="dropdown-menu w-100" aria-labelledby="dropdownPointCheck" style="max-height: 200px; overflow-y: auto;">
                                            <a class="dropdown-item" href="#" data-value="RINGKAS">RINGKAS</a>
                                            <a class="dropdown-item" href="#" data-value="RAPI">RAPI</a>
                                            <a class="dropdown-item" href="#" data-value="RESIK">RESIK</a> 
                                            <a class="dropdown-item" href="#" data-value="RAWAT">RAWAT</a>
                                        </div>
                                    </div>
                                </div> 
                            </div>

                            
                        
                            <!-- Remaining Form Fields -->
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="point1" class="form-label">Point 1</label>
                                            <textarea class="form-control" id="point1" name="point1" rows="3" required></textarea>
                                        </div>
                        
                                        <div class="col-md-6">
                                            <label for="point2" class="form-label">Point 2</label>
                                            <textarea class="form-control" id="point2" name="point2" rows="3"></textarea>
                                        </div>
                                    </div>
                        
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="point3" class="form-label">Point 3</label>
                                            <textarea class="form-control" id="point3" name="point3" rows="3" required></textarea>
                                        </div>
                        
                                        <div class="col-md-6">
                                            <label for="point4" class="form-label">Point 4</label>
                                            <textarea class="form-control" id="point4" name="point4" rows="3"></textarea>
                                        </div>
                                    </div>
                        
                                    <div class="row">
                                        <div class="col-6 col-md-2 mb-0">
                                            <button type="submit" class="btn btn-info w-100 mt-2">Submit</button> 
                                        </div>
                                        <div class="col-6 col-md-2 mb-0">
                                            <button type="reset" class="btn btn-danger w-100 mt-2">Cancel</button> 
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                        
                        
                    </div>
                </div>
            </div>
        </div> 


        <div class="row"> 
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
           
            <div class="table-responsive mb-0"  data-pattern="priority-columns">
                <table id="filterableTable"  class="table table-striped" >
                    <thead class="table-info">
                        <tr>
                            <th class="text-center align-middle sticky-header add-date-column">Add Date</th>
                            {{-- <th class="text-center align-middle sticky-header">Whseid</th> --}}
                            <th class="text-center align-middle sticky-header">Owner</th>
                            {{-- <th class="text-center align-middle sticky-header">Relasi</th> --}}
                            <th class="text-center align-middle sticky-header">Dept Name</th>
                            <th class="text-center align-middle sticky-header">Area</th>
                            <th class="text-center align-middle sticky-header">Point Check</th>
                            <th class="text-center align-middle sticky-header">Point 1</th>
                            <th class="text-center align-middle sticky-header">Point 2</th>
                            <th class="text-center align-middle sticky-header">Point 3</th>
                            <th class="text-center align-middle sticky-header">Point 4</th>
                            <th class="text-center align-middle sticky-header">Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($dataTable)
                            @forelse ($dataTable as $data)
                                <tr >
                                    <td class="text-left" data-field="AddDate">{{ $data->{'Add Date'} ?? '-' }}</td>
                                    {{-- <td class="text-left" data-field="Whseid">{{ $data->Whseid ?? '-' }}</td> --}}
                                    <td class="text-left" data-field="Owner">{{ $data->Owner ?? '-' }}</td>
                                    {{-- <td class="text-left" data-field="Relasi">{{ $data->Relasi ?? '-' }}</td> --}}
                                    <td class="text-left" data-field="DeptName">{{ $data->{'Dept Name'} ?? '-' }}</td>
                                    <td class="text-left" data-field="Area">{{ $data->Area ?? '-' }}</td>
                                    <td class="text-left" data-field="PointCheck">{{ $data->{'Point Check'} ?? '-' }}</td>
                                    <td class="text-left" data-field="Point1">
                                        @if($data->Point1)
                                            <ul>
                                                @foreach(explode("\n", $data->Point1) as $line)
                                                    @if(trim($line) != '') <!-- Menghindari item kosong -->
                                                        <li>{{ trim($line) }}</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-left" data-field="Point2">
                                        @if($data->Point2)
                                            <ul>
                                                @foreach(explode("\n", $data->Point2) as $line)
                                                    @if(trim($line) != '') <!-- Menghindari item kosong -->
                                                        <li>{{ trim($line) }}</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-left" data-field="Point3">
                                        @if($data->Point3)
                                            <ul>
                                                @foreach(explode("\n", $data->Point3) as $line)
                                                    @if(trim($line) != '') <!-- Menghindari item kosong -->
                                                        <li>{{ trim($line) }}</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-left" data-field="Point4">
                                        @if($data->Point4)
                                            <ul>
                                                @foreach(explode("\n", $data->Point4) as $line)
                                                    @if(trim($line) != '') <!-- Menghindari item kosong -->
                                                        <li>{{ trim($line) }}</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    


                                    <td class="text-center" style="width: 200px">
                                        <a class="btn btn-outline-secondary btn-sm edit" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a class="btn btn-outline-success btn-sm save d-none" title="Save">
                                            <i class="fas fa-check"></i>
                                        </a>
                                        <a class="btn btn-outline-danger btn-sm delete" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No data available</td>
                                </tr>
                            @endforelse
                        @else
                            <tr>
                                <td colspan="9" class="text-center">Data not found</td>
                            </tr>
                        @endisset
                    </tbody>
                </table> 
             </div>
        </div>


    </div> 
</div> 

<script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>

<script>
    @if(Session::has('message'))
        var type = "{{ Session::get('alert-type', 'info') }}";
        switch (type) {
            case 'info':
                toastr.info("{{ Session::get('message') }}");
                break;
            case 'success':
                toastr.success("{{ Session::get('message') }}");
                break;
            case 'warning':
                toastr.warning("{{ Session::get('message') }}");
                break;
            case 'danger':
                toastr.error("{{ Session::get('message') }}");
                break;
        }
    @endif
</script>

<script>
   document.addEventListener('DOMContentLoaded', function() {
    const dropdownItems = document.querySelectorAll('.dropdown-item');

    dropdownItems.forEach(item => {
        item.addEventListener('click', function() {
            const selectedValue = this.getAttribute('data-value');
            console.log("Selected Value: ", selectedValue);

            const dropdownMenu = this.closest('.dropdown-menu');
            const dropdownButton = dropdownMenu.previousElementSibling;
            const dropdownId = dropdownButton.id;

            dropdownButton.innerHTML = `${selectedValue} <i class="mdi mdi-chevron-down"></i>`;
            
            const hiddenInput = document.querySelector(`#${dropdownId}_input`);
            if (hiddenInput) {
                hiddenInput.value = selectedValue;
                console.log(`${dropdownId} Input Set: `, hiddenInput.value);
            }
        });
    });
});

</script>

{{-- <script>
    $(document).on('click', '.edit', function() {
        // Menyembunyikan tombol edit dan menampilkan tombol simpan
        $(this).addClass('d-none').next('.save').removeClass('d-none');
    
        var $row = $(this).closest('tr'); // Mengambil baris terdekat
    
        // Mengiterasi setiap sel dalam baris
        $row.find("td[data-field]").each(function() {
            var $cell = $(this);
            var fieldName = $cell.data("field"); // Mengambil nama field
            var cellValue = $cell.text().trim(); // Mengambil teks saat ini dan menghapus spasi di awal/akhir
            
            // Menghapus konten sel
            $cell.empty();
            
            // Membuat input text dan mengisinya dengan nilai sel
            var $input = $('<input type="text" class="form-control" />').val(cellValue);
            $cell.append($input);
        });
    });
    
    $(document).on('click', '.save', function() {
        let $row = $(this).closest('tr'); // Mengambil baris terdekat
        let dataToSave = {
            //AddDate: $row.find('td[data-field="AddDate"] input').val(),
            //Whseid: $row.find('td[data-field="Whseid"] input').val(), 
            Owner: $row.find('td[data-field="Owner"] input').val(),
            //Relasi: $row.find('td[data-field="Relasi"] input').val(),
            DeptName: $row.find('td[data-field="DeptName"] input').val(),
            Area: $row.find('td[data-field="Area"] input').val(),
            PointCheck: $row.find('td[data-field="PointCheck"] input').val(),
            Point1: $row.find('td[data-field="Point1"] input').val(),
            Point2: $row.find('td[data-field="Point2"] input').val(),
            Point3: $row.find('td[data-field="Point3"] input').val(),
            Point4: $row.find('td[data-field="Point4"] input').val(),
        };

        //console.log("Whseid: ", dataToSave.Whseid,);
        console.log("Owner: ", dataToSave.Owner,);
        //console.log("Relasi: ", dataToSave.Relasi,);
        console.log("DeptName: ",  dataToSave.DeptName,);
        console.log("Area: ", dataToSave.Area,);
        console.log("PointCheck: ", dataToSave.PointCheck,); 
        console.log("Point1: ", dataToSave.Point1,); 
        console.log("Point2: ", dataToSave.Point2,); 
        console.log("Point3: ", dataToSave.Point3,); 
        console.log("Point4: ", dataToSave.Point4,); 

        $.ajax({
            url: "{{ route('admin.master.public.checklist5r.masterpoint5r_update') }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
               // add_date: dataToSave.AddDate,
                //whseid: dataToSave.Whseid,
                owner: dataToSave.Owner,
                //relasi: dataToSave.Relasi,
                dept_name: dataToSave.DeptName,
                area: dataToSave.Area,
                point_check: dataToSave.PointCheck,
                point1: dataToSave.Point1,
                point2: dataToSave.Point2,
                point3: dataToSave.Point3,
                point4: dataToSave.Point4
            },

            success: function(response) {
                console.log(response); // Menampilkan respons di console
    
                // Menampilkan notifikasi dengan Toastr
                toastr.success(response.message); // Ganti dengan respons yang sesuai
    
                // Mengubah input kembali ke teks
                $row.find("td[data-field]").each(function() {
                    var $cell = $(this);
                    var inputValue = $cell.find('input').val(); // Mengambil nilai dari input
                    $cell.text(inputValue); // Mengembalikan nilai ke sel
                });
    
                // Menyembunyikan tombol simpan dan menampilkan tombol edit
                $row.find('.edit').removeClass('d-none');
                $row.find('.save').addClass('d-none');
            },
            error: function(xhr) {
                alert('An error occurred while saving data. Please try again.');
            }
        });
    });
    
    $(document).on('click', '.delete', function() {
        let $row = $(this).closest('tr'); 
        let dataToDelete = {
            // AddDate: $row.find('td[data-field="AddDate"]').text().trim(),
            //Whseid: $row.find('td[data-field="Whseid"]').text().trim(),
            Owner: $row.find('td[data-field="Owner"]').text().trim(), 
            //Relasi: $row.find('td[data-field="Relasi"]').text().trim(),
            DeptName: $row.find('td[data-field="DeptName"]').text().trim(),
            Area: $row.find('td[data-field="Area"]').text().trim(),
            PointCheck: $row.find('td[data-field="PointCheck"]').text().trim(),
            // Point1: $row.find('td[data-field="Point1"]').text().trim(),
            // Point2: $row.find('td[data-field="Point2"]').text().trim(),
            // Point3: $row.find('td[data-field="Point3"]').text().trim(),
            // Point4: $row.find('td[data-field="Point4"]').text().trim(),
        };
    
        // console.log("Whseid: ", dataToDelete.Whseid);
        // console.log("Owner: ", dataToDelete.Owner);
        // console.log("Relasi: ", dataToDelete.Relasi);
        // console.log("DeptName: ", dataToDelete.DeptName);
        // console.log("Area: ", dataToDelete.Area);
        // console.log("PointCheck: ", dataToDelete.PointCheck); 
    
        if (confirm('Are you sure you want to delete this record?')) {
            $.ajax({
                url: "{{ route('admin.master.public.checklist5r.masterpoint5r_delete') }}", 
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    //add_date: dataToDelete.AddDate,
                    //whseid: dataToDelete.Whseid,
                    owner: dataToDelete.Owner,
                   // relasi: dataToDelete.Relasi,
                    dept_name: dataToDelete.DeptName,
                    area: dataToDelete.Area,
                    point_check: dataToDelete.PointCheck,
                    // point1: dataToDelete.Point1,
                    // point2: dataToDelete.Point2,
                    // point3: dataToDelete.Point3,
                    // point4: dataToDelete.Point4
                },
                success: function(response) {
                    console.log(response.message); // Menampilkan respons di console
    
                    // Menampilkan notifikasi dengan Toastr
                    toastr.success(response.message);
    
                    // Menghapus baris dari tabel
                    $row.remove();
                },
                error: function(xhr) {
                    alert('An error occurred while deleting the data. Please try again.');
                }
            });
        }
    });
</script> --}}


<script>
    $(document).on('click', '.edit', function() {
        // Menyembunyikan tombol edit dan menampilkan tombol simpan
        $(this).addClass('d-none').next('.save').removeClass('d-none');
    
        var $row = $(this).closest('tr'); // Mengambil baris terdekat
    
        // Mengiterasi setiap sel dalam baris
        $row.find("td[data-field]").each(function() {
            var $cell = $(this);
            var fieldName = $cell.data("field"); // Mengambil nama field
            var cellValue = $cell.text().trim(); // Mengambil teks saat ini dan menghapus spasi di awal/akhir
            
            // Menghapus konten sel
            $cell.empty();
            
            // Membuat textarea dan mengisinya dengan nilai sel
            var $textarea = $('<textarea class="form-control"></textarea>').val(cellValue);
            
            // Menambahkan textarea ke dalam sel
            $cell.append($textarea);
        });
    });
    
    $(document).on('click', '.save', function() {
        let $row = $(this).closest('tr'); // Mengambil baris terdekat
        let dataToSave = {
            Owner: $row.find('td[data-field="Owner"] textarea').val(), // Mengambil nilai dari textarea
            DeptName: $row.find('td[data-field="DeptName"] textarea').val(),
            Area: $row.find('td[data-field="Area"] textarea').val(),
            PointCheck: $row.find('td[data-field="PointCheck"] textarea').val(),
            Point1: $row.find('td[data-field="Point1"] textarea').val(),
            Point2: $row.find('td[data-field="Point2"] textarea').val(),
            Point3: $row.find('td[data-field="Point3"] textarea').val(),
            Point4: $row.find('td[data-field="Point4"] textarea').val(),
        };

        // Menampilkan nilai data yang akan disimpan
        console.log("Owner: ", dataToSave.Owner);
        console.log("DeptName: ",  dataToSave.DeptName);
        console.log("Area: ", dataToSave.Area);
        console.log("PointCheck: ", dataToSave.PointCheck); 
        console.log("Point1: ", dataToSave.Point1); 
        console.log("Point2: ", dataToSave.Point2); 
        console.log("Point3: ", dataToSave.Point3); 
        console.log("Point4: ", dataToSave.Point4); 

        $.ajax({
            url: "{{ route('admin.master.public.checklist5r.masterpoint5r_update') }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                owner: dataToSave.Owner,
                dept_name: dataToSave.DeptName,
                area: dataToSave.Area,
                point_check: dataToSave.PointCheck,
                point1: dataToSave.Point1,
                point2: dataToSave.Point2,
                point3: dataToSave.Point3,
                point4: dataToSave.Point4
            },

            success: function(response) {
                console.log(response); // Menampilkan respons di console
    
                // Menampilkan notifikasi dengan Toastr
                toastr.success(response.message);
    
                // Mengubah textarea kembali ke teks
                $row.find("td[data-field]").each(function() {
                    var $cell = $(this);
                    var inputValue = $cell.find('textarea').val(); // Mengambil nilai dari textarea
                    $cell.text(inputValue); // Mengembalikan nilai ke sel
                });
    
                // Menyembunyikan tombol simpan dan menampilkan tombol edit
                $row.find('.edit').removeClass('d-none');
                $row.find('.save').addClass('d-none');
            },
            error: function(xhr) {
                alert('An error occurred while saving data. Please try again.');
            }
        });
    });
    
    $(document).on('click', '.delete', function() {
        let $row = $(this).closest('tr'); 
        let dataToDelete = {
            Owner: $row.find('td[data-field="Owner"]').text().trim(), 
            DeptName: $row.find('td[data-field="DeptName"]').text().trim(),
            Area: $row.find('td[data-field="Area"]').text().trim(),
            PointCheck: $row.find('td[data-field="PointCheck"]').text().trim(),
        };
    
        if (confirm('Are you sure you want to delete this record?')) {
            $.ajax({
                url: "{{ route('admin.master.public.checklist5r.masterpoint5r_delete') }}", 
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    owner: dataToDelete.Owner,
                    dept_name: dataToDelete.DeptName,
                    area: dataToDelete.Area,
                    point_check: dataToDelete.PointCheck,
                },
                success: function(response) {
                    console.log(response.message); // Menampilkan respons di console
    
                    // Menampilkan notifikasi dengan Toastr
                    toastr.success(response.message);
    
                    // Menghapus baris dari tabel
                    $row.remove();
                },
                error: function(xhr) {
                    alert('An error occurred while deleting the data. Please try again.');
                }
            });
        }
    });
</script>




<script>
   $(document).ready(function() {
    $('#filterableTable').DataTable({
        scrollY: '400px', // Tabel akan memiliki tinggi maksimum 500px dan scroll vertikal
        scrollCollapse: true, // Aktifkan scroll collapse jika data kurang dari tinggi tabel
        paging: true,    // Aktifkan fitur paginasi
        searching: true, // Aktifkan fitur pencarian
        info: true,      // Tampilkan informasi jumlah data
        order: [],       // Nonaktifkan sorting default
        language: {
            search: "Filter data:", // Label untuk input pencarian
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                previous: "Previous",
                next: "Next"
            },
            zeroRecords: "Tidak ada data yang ditemukan",
            infoEmpty: "Tidak ada data tersedia",
            infoFiltered: "(difilter dari total _MAX_ data)"
        }
    });
});

</script>

<style>
    .sticky-header {
        position: sticky;
        top: 0; /* Position from the top */
        background-color: white; /* Change this to match your header's background */
        z-index: 10; /* Higher value to keep it above other content */
    }
    
    /* Set specific width for the Add Date column */
    .add-date-column {
        width: 150px; /* Adjust this value to your preferred width */
    }
    
    /* Optional: Add some shadow for better visibility when scrolling */
    .table th {
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }


        .table td[data-field="Point1"] ul,
        .table td[data-field="Point2"] ul,
        .table td[data-field="Point3"] ul,
        .table td[data-field="Point4"] ul {
            list-style-type: disc; /* Menetapkan bullet point */
            padding-left: 20px; /* Menambahkan ruang untuk bullet */
            margin: 0;
        }

        .table td[data-field="Point1"] li,
        .table td[data-field="Point2"] li,
        .table td[data-field="Point3"] li,
        .table td[data-field="Point4"] li {
            margin-bottom: 5px; /* Menambahkan jarak antar item */
        }


    /* CSS untuk cell yang sedang diedit */
   /* Mengubah min-height dan height untuk textarea */
    table td textarea.form-control {
        height: 200px !important; /* Menetapkan tinggi tetap untuk textarea */
        min-height: 200px !important; /* Mengatur min-height agar sama dengan height */
        width: 100%;
        box-sizing: border-box;
        white-space: normal;
        word-wrap: break-word;
        line-height: 1.5;
        overflow-wrap: break-word;
    }


</style>

@endsection



