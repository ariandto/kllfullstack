@extends('admin.dashboard')
@section('admin')

<div class="page-content">
    <div class="container-fluid"> 
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-center">
                    @if (session('facility_info'))
                    @foreach (session('facility_info') as $facility)
                    <h4 style="font-size: 1.5rem; font-weight: bold; text-align: center;">
                       MASTER "HOLD LPN" {{ $facility['Name'] }}
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
                        
                        <form id="saveForm" method="POST" action="{{ route('admin.master.inventory.master_damage_from_lpn_submit') }}"> 
                            @csrf
                        
                            <input type="hidden" id="ownerholdlpn_input" name="ownerholdlpn" value="{{ old('ownerholdlpn') }}">
                            <input type="hidden" id="codelpn_input" name="codelpn" value="{{ old('codelpn') }}">
                            <input type="hidden" id="datasites2_input" name="sites2" value="{{ old('sites2') }}"> 

                           
                        
                            <div class="row mb-3">
                                <div class="col-6 col-md-3">
                                    <label for="datasites2" class="form-label">Site</label>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-light dropdown-toggle w-100 text-start d-flex align-items-center justify-content-between" 
                                        type="button" id="datasites2" data-bs-toggle="dropdown" aria-expanded="false">
                                            {{ old('sites2', 'Select Site') }} <i class="mdi mdi-chevron-down"></i>
                                        </button>
                                 
                                        <div class="dropdown-menu w-100" aria-labelledby="dropdownDepartmentButton" style="max-height: 200px; overflow-y: auto;">
                                            @if(isset($datasites2) && !empty($datasites2))
                                                @foreach($datasites2 as $dept)
                                                    <a class="dropdown-item" data-value="{{ $dept->STORERKEY }}" href="#">{{ $dept->STORERKEY }}</a>
                                                @endforeach
                                            @else
                                                <a class="dropdown-item disabled" href="#">No Site Available</a>
                                            @endif
                                        </div>
                                    </div>  
                                </div> 
                                
                                <div class="col-6 col-md-3">
                                    <label for="ownerholdlpn" class="form-label">Owner</label>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-light dropdown-toggle w-100 text-start d-flex align-items-center justify-content-between" 
                                                type="button" id="ownerholdlpn" data-bs-toggle="dropdown" aria-expanded="false">
                                            {{ old('ownerholdlpn', 'Select Owner') }} <i class="mdi mdi-chevron-down"></i>
                                        </button>
                        
                        
                                        <div class="dropdown-menu w-100" aria-labelledby="dropdownOwnerButton" style="max-height: 200px; overflow-y: auto;">
                                            @if(isset($dataownerholdlpn) && !empty($dataownerholdlpn))
                                                @foreach($dataownerholdlpn as $owner)
                                                    <a class="dropdown-item" data-value="{{ $owner->Owner }}" href="#">{{ $owner->Owner }}</a>
                                                @endforeach
                                            @else
                                                <a class="dropdown-item disabled" href="#">No Owners Available</a>
                                            @endif
                                        </div>
                                    </div>  
                                </div>
                        
                               
                        
                                <div class="col-6 col-md-3 mt-3 mt-md-0">
                                    <label for="codelpn" class="form-label">LPN Code</label>
                                    <input type="text" class="form-control" id="codelpn" autocomplete="off" name="codelpn" placeholder="Please Input Here..." required value="{{ old('codelpn') }}">

                                </div> 
                            </div> 

                            <!-- Remaining Form Fields -->
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label for="holdlpndesc" class="form-label">Damage From Description</label>
                                            <textarea class="form-control" id="holdlpndesc" name="holdlpndesc" rows="3" required></textarea>
                                        </div> 
                                    </div> 
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
                            {{-- <th class="text-center align-middle sticky-header add-date-column">Whseid</th> 
                            <th class="text-center align-middle sticky-header">TypeDC</th>  --}}
                            <th class="text-center align-middle sticky-header">Site</th>
                            <th class="text-center align-middle sticky-header">Owner</th>
                            <th class="text-center align-middle sticky-header">LPN Code </th>
                            <th class="text-center align-middle sticky-header">Damage From Description</th> 
                            <th class="text-center align-middle sticky-header">Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($dataTable)
                            @forelse ($dataTable as $data)
                                <tr > 
                                    {{-- <td class="text-left" data-field="WHSEID">{{ $data->{'WHSEID'} ?? '-' }}</td> 
                                    <td class="text-left" data-field="TYPEDC">{{ $data->{'TYPEDC'} ?? '-' }}</td>  --}}
                                    <td class="text-left" data-field="SITE">{{ $data->{'SITE'} ?? '-' }}</td>
                                    <td class="text-left" data-field="OWNER">{{ $data->OWNER ?? '-' }}</td> 
                                    <td class="text-left" data-field="CODE">{{ $data->{'CODE'} ?? '-' }}</td> 
                                    <td class="text-left" data-field="DAMAGE_DESCRIPTION">{{ $data->DAMAGE_DESCRIPTION ?? '-' }}</td>
                                   
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

{{-- Ini buat ngatur supaya dropdownnya nempel di form dan kirim ke request --}}
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


{{-- Ini buat ngatur supaya tabelnya full fitur --}}
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

{{-- Ini buat ngatur edit datatable --}}
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
            Site: $row.find('td[data-field="SITE"] textarea').val(), // Mengambil nilai dari textarea
            Owner: $row.find('td[data-field="OWNER"] textarea').val(),
            Code: $row.find('td[data-field="CODE"] textarea').val(),
            DamageDesc: $row.find('td[data-field="DAMAGE_DESCRIPTION"] textarea').val(), 
        };

        // Menampilkan nilai data yang akan disimpan
        console.log("Site: ", dataToSave.Site);
        console.log("Owner: ",  dataToSave.Owner);
        console.log("Code: ", dataToSave.Code);
        console.log("DamageDesc: ", dataToSave.DamageDesc);  

        $.ajax({
            url: "{{ route('admin.master.inventory.master_damage_from_lpn_update') }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                site: dataToSave.Site, 
                owner: dataToSave.Owner,
                code: dataToSave.Code,
                damagedesc: dataToSave.DamageDesc, 
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
            Site: $row.find('td[data-field="SITE"]').text().trim(), 
            Owner: $row.find('td[data-field="OWNER"]').text().trim(),
            Code: $row.find('td[data-field="CODE"]').text().trim(),
            DamageDesc: $row.find('td[data-field="DAMAGE_DESCRIPTION"]').text().trim(),
        };
    
        if (confirm('Are you sure you want to delete this record?')) {
            $.ajax({
                url: "{{ route('admin.master.inventory.master_damage_from_lpn_delete') }}", 
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    site: dataToDelete.Site,
                    owner: dataToDelete.Owner,
                    code: dataToDelete.Code,
                    damagedesc: dataToDelete.DamageDesc,
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