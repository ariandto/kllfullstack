@extends('admin.dashboard')
@section('admin')
  <!-- Toaster-->
  <link rel="stylesheet" type="text/css" href="{{asset('backend/assets/css/toastr.css')}}" >
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-center">
                    @if (session('facility_info'))
                    @foreach (session('facility_info') as $facility)
                    <h4 style="font-size: 1.5rem; font-weight: bold; text-align: center;">
                       MASTER JAM CHECKPOINT 5R {{ $facility['Name'] }}
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
                    <!-- Ini Eror Message -->                       
                    <form id="saveForm" method="POST" action="{{ route('admin.master.public.checklist5r.masterjam5r_submit') }}"> 
                        @csrf
                        <input type="hidden" id="owner5rjam_input" name="owner5rjam" value="{{ old('owner5rjam') }}">
                        <input type="hidden" id="dept5rjam_input" name="dept5rjam" value="{{ old('dept5rjam') }}">

                        <div class="row"> 
                            <div class="col-6 col-md-2 mb-3">
                                <label for="owner5rjam" class="form-label">Owner</label>
                                <div class="dropdown">
                                    <button class="btn btn-outline-light dropdown-toggle w-100 text-start d-flex align-items-center justify-content-between" 
                                            type="button" id="owner5rjam" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ old('owner5rjam', 'Select Owner') }} <i class="mdi mdi-chevron-down"></i>
                                    </button> 

                                    <div class="dropdown-menu w-100" aria-labelledby="dropdownOwnerButton" style="max-height: 200px; overflow-y: auto;">
                                        @if(isset($dataowner5rjam) && !empty($dataowner5rjam))
                                            @foreach($dataowner5rjam as $owner)
                                                <a class="dropdown-item" data-value="{{ $owner->Owner }}" href="#">{{ $owner->Owner }}</a>
                                            @endforeach
                                        @else
                                            <a class="dropdown-item disabled" href="#">No Owners Available</a>
                                        @endif
                                    </div>
                                </div>  
                            </div>

                            <div class="col-6 col-md-2 mb-3">
                                <label for="dept5rjam" class="form-label">DeptName</label>
                                <div class="dropdown">
                                    <button class="btn btn-outline-light dropdown-toggle w-100 text-start d-flex align-items-center justify-content-between" 
                                            type="button" id="dept5rjam" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ old('dept5rjam', 'Select Department') }} <i class="mdi mdi-chevron-down"></i>
                                    </button> 

                                    <div class="dropdown-menu w-100" aria-labelledby="dropdownDeptButton" style="max-height: 200px; overflow-y: auto;">
                                        @if(isset($datadept5rjam) && !empty($datadept5rjam))
                                            @foreach($datadept5rjam as $Dept)
                                                <a class="dropdown-item" data-value="{{ $Dept->Departement }}" href="#">{{ $Dept->Departement }}</a>
                                            @endforeach
                                        @else
                                            <a class="dropdown-item disabled" href="#">No Dept Available</a>
                                        @endif
                                    </div>
                                </div>  
                            </div>


                            <!-- Shift Selection (Combo Box) -->
                            <div class="col-6 col-md-2 mb-3">
                                <label for="shift_by" class="form-label">Shift</label>
                                <select id="shift_by" name="shift_by" required class="form-select">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select> 
                            </div>
                    
                            <div class="row">
                                <div class="col-md-4"> 
                                    <div class="card mb-2">
                                        <div class="card-header">
                                            <h4 class="card-title mb-0">Checkpoint 1</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row"> 
                                                <div class="mb-2">
                                                    <label for="start1" class="form-label">Start</label>
                                                    <input class="form-control" type="time" name="start1" value="{{ $startTime1 }}" id="start1">
                                                </div>
                                                <div class="mb-2">
                                                    <label for="end1" class="form-label">End</label>
                                                    <input class="form-control" type="time" name="end1" value="{{ $endTime1 }}" id="end1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-md-4"> 
                                    <div class="card mb-2">
                                        <div class="card-header">
                                            <h4 class="card-title mb-0">Checkpoint 2</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row"> 
                                                <div class="mb-2">
                                                    <label for="start2" class="form-label">Start</label>
                                                    <input class="form-control" type="time" name="start2" value="{{ $startTime2 }}" id="start2">
                                                </div>
                                                <div class="mb-2">
                                                    <label for="end2" class="form-label">End</label>
                                                    <input class="form-control" type="time" name="end2" value="{{ $endTime2 }}" id="end2">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-md-8"> 
                                    <!-- Optional space for additional content if needed -->
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

            <div class="table-responsive mb-0">
                <table class="table table-editable table-nowrap align-middle ">
                    <thead >
                        <tr>
                            <th rowspan="2" class="text-center align-middle">Add Date</th>
                            <th rowspan="2" class="text-center align-middle">Owner</th>
                            <th rowspan="2" class="text-center align-middle">Dept</th>
                            <th rowspan="2" class="text-center align-middle">Shift Number</th>
                            <th colspan="2" class="text-center">Check Point 1</th>
                            <th colspan="2" class="text-center">Check Point 2</th>
                            <th rowspan="2" class="text-center align-middle">Edit</th>
                        </tr>
                        <tr>
                            <th class="text-center">Start</th>
                            <th class="text-center">End</th>
                            <th class="text-center">Start</th>
                            <th class="text-center">End</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($dataTable)
                            @forelse ($dataTable as $data)
                                <tr data-id="{{ $data->ShiftNumber }}">
                                    <td class="text-left" data-field="AddDate">{{ $data->AddDate ?? '-' }}</td>
                                    <td class="text-left" data-field="Owner">{{ $data->Owner ?? '-' }}</td>
                                    <td class="text-left" data-field="DeptName">{{ $data->DeptName ?? '-' }}</td>
                                    <td class="text-right" data-field="ShiftNumber">{{ $data->ShiftNumber ?? '-' }}</td>
                                    <td class="text-left" data-field="Start1">{{ isset($data->Start1) ? date('H:i:s', strtotime($data->Start1)) : '-' }}</td>
                                    <td class="text-left" data-field="End1">{{ isset($data->End1) ? date('H:i:s', strtotime($data->End1)) : '-' }}</td>
                                    <td class="text-left" data-field="Start2">{{ isset($data->Start2) ? date('H:i:s', strtotime($data->Start2)) : '-' }}</td>
                                    <td class="text-left" data-field="End2">{{ isset($data->End2) ? date('H:i:s', strtotime($data->End2)) : '-' }}</td>
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
                                    <td colspan="7" class="text-center">No data available</td>
                                </tr>
                            @endforelse
                        @else
                            <tr>
                                <td colspan="7" class="text-center">Data not found</td>
                            </tr>
                        @endisset
                    </tbody>
                </table>
                
            </div>
        </div> 

    </div> 
</div> 

<script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>
<script type="text/javascript" src="{{asset('backend/assets/js/toastr.min.js')}}"></script>


{{-- Ini untuk bagian Drop Down, Kek Yang Dipilih dan Di Selected --}}
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
            case 'error':
                toastr.error("{{ Session::get('message') }}");
                break;
        }
    @endif
</script>



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
        
        // Membuat input text dan mengisinya dengan nilai sel
        var $input = $('<input type="text" />').val(cellValue);
        $cell.append($input);
    });
});


$(document).on('click', '.save', function() {
    let $row = $(this).closest('tr'); // Mengambil baris terdekat
    let dataToSave = {
        Owner: $row.find('td[data-field="Owner"] input').val(),
        Dept: $row.find('td[data-field="DeptName"] input').val(),
        ShiftNumber: $row.find('td[data-field="ShiftNumber"] input').val(), 
        AddDate: $row.find('td[data-field="AddDate"] input').val(),
        Start1: $row.find('td[data-field="Start1"] input').val(),
        End1: $row.find('td[data-field="End1"] input').val(),
        Start2: $row.find('td[data-field="Start2"] input').val(),
        End2: $row.find('td[data-field="End2"] input').val(),
        
    };
    console.log(dept5rjam);

    $.ajax({
        url: "{{ route('admin.master.public.checklist5r.masterjam5r_update') }}",
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}', 

            owner : dataToSave.Owner,
            dept5rjam : dataToSave.Dept,
            shift_by: dataToSave.ShiftNumber,
            add_date: dataToSave.AddDate,
            start1: dataToSave.Start1,
            end1: dataToSave.End1,
            start2: dataToSave.Start2,
            end2: dataToSave.End2
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
    let dataToSave = {
    Owner: $row.find('td[data-field="Owner"]').text().trim(),
    Dept: $row.find('td[data-field="DeptName"]').text().trim(),
    ShiftNumber: $row.find('td[data-field="ShiftNumber"]').text().trim(), 
    AddDate: $row.find('td[data-field="AddDate"]').text().trim(),
    Start1: $row.find('td[data-field="Start1"]').text().trim(),
    End1: $row.find('td[data-field="End1"]').text().trim(),
    Start2: $row.find('td[data-field="Start2"]').text().trim(),
    End2: $row.find('td[data-field="End2"]').text().trim(),
};


    console.log("ShiftNumber: ", dataToSave.ShiftNumber);

    if (confirm('Are you sure you want to delete this record?')) {

        $.ajax({
            url: "{{ route('admin.master.public.checklist5r.masterjam5r_delete') }}", 
            method: 'POST',
            data: {
            _token: '{{ csrf_token() }}',
            owner: dataToSave.Owner,
            dept5rjam: dataToSave.Dept,
            shift_by: dataToSave.ShiftNumber, 
            add_date: dataToSave.AddDate,
            start1: dataToSave.Start1,
            end1: dataToSave.End1,
            start2: dataToSave.Start2,
            end2: dataToSave.End2
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

@endsection



