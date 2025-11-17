@extends('admin.dashboard')
@section('admin')
    <div class="page-content">
        <div class="card">
            <div class="title">
                <h4 style="font-size: 1.5rem; font-weight: bold; text-align: center;">
                    Monitoring Progress LC - {{ $Name }}
                </h4>
                <div class="card-body">
                <form id="inlinePlanForm" method="GET" action="{{ route('transport.inline-plan') }}">
                    <div class="row align-items-center">
                        <label class="form-label">Plan Delivery Date</label>
                        <div class="col-md-2 mb-3">
                            <input type="date" id="periodeStart" name="start_date" class="form-control" value="{{ request('start_date', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <input type="date" id="periodeEnd" name="end_date" class="form-control" value="{{ request('end_date', date('Y-m-d')) }}" required>
                        </div>
                        <input type="hidden" name="owners" value="">
                    <div class="col-md-1 mb-3" >
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                Pilih Owner
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" id="checkAll"> Pilih Semua
                                    </label>
                                </li>
                                @foreach ($facilities as $facility)
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="owner-checkbox" name="owners[]" value="{{ $facility->OWNER }}" {{ in_array($facility->OWNER, request()->input('owners', [])) ? 'checked' : '' }}>
                                            {{ $facility->OWNER }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>                  

                        <div class="col-md-1 mb-3">
                            <button type="submit" class="btn btn-info w-100">Show</button>
                        </div>
                    </div>                    
                </form>
                </div>
            </div>
        </div>
        <!-- DataGrid for displaying results -->
        @if (isset($dataGrid) && count($dataGrid) > 0)
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <h5 class="card-title">  Data Monitoring Progress LC 
                            @if(request()->has('owners'))
                                {{ implode(', ', request()->input('owners')) }}
                            @endif 
                        </h5>
                  
                    </div>
                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                @foreach ($columns as $column)
                                    <th class="text-center">{{ ucfirst(str_replace('_', ' ', $column)) }}</th>
                                @endforeach
                            </tr>
                        </thead>                        
                        <tbody>                           
                        @foreach ($dataGrid as $data)  <!-- Looping setiap row -->
                            <tr>
                                @foreach ($columns as $column)  <!-- Looping setiap kolom -->
                                    @php
                                        $isNumber = is_numeric($data->$column);  // Cek apakah data angka
                                    @endphp
                                    <td class="{{ $isNumber ? 'text-end' : 'text-start' }}">
                                        @if ($column === 'LC') 
                                            <a href="javascript:void(0);" class="lc-link" data-lc="{{ $data->$column }}">
                                                {{ $data->$column }}
                                            </a>
                                        @elseif (str_contains(strtolower($column), '%')) 
                                            <div class="progress" style="height: 20px; width: 100%; position: relative;">
                                                @php
                                                    $progressValue = $isNumber ? $data->$column : 0;
                                                @endphp
                                                <div class="progress-bar 
                                                    {{ $progressValue < 50 ? 'bg-danger' : ($progressValue < 80 ? 'bg-warning' : 'bg-success') }}" 
                                                    role="progressbar" 
                                                    style="width: {{ $progressValue }}%;" 
                                                    aria-valuenow="{{ $progressValue }}" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100">
                                                </div>
                                                <span class="progress-text" 
                                                    style="position: absolute; width: 100%; text-align: center; font-weight: bold; color: white;">
                                                    {{ number_format($progressValue, 2) }}%
                                                </span>
                                            </div>
                                        @elseif ($isNumber)
                                            {{ number_format($data->$column, 2) }}
                                        @else
                                            {{ $data->$column }}
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                    </table>

                </div>
            </div>
        @else
            <div class="alert alert-warning text-center" role="alert">
                <strong>⚠️ Data Belum Ada</strong>
            </div>
        @endif
    </div>  
    <div class="modal fade" id="lcModal" tabindex="-1" aria-labelledby="lcModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="lcModalLabel">Detail LC</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Tabel akan dimuat secara dinamis di sini -->
                    <table id="lcDetailTable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead class="table-light" id="lcTableHeaders">
                            <!-- Kolom akan dimuat di sini -->
                        </thead>
                        <tbody id="lcModalContent">
                      
                        </tbody>
                    </table>
        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div> 
    <script>
            document.addEventListener("DOMContentLoaded", function () {
            // Fungsi untuk memilih semua checkbox saat "Pilih Semua" dicentang
            document.getElementById("checkAll").addEventListener("change", function () {
                let checkboxes = document.querySelectorAll(".owner-checkbox");
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;  
                });
            });

            // Fungsi untuk memeriksa status "Check All" berdasarkan checkbox individu
            let checkboxes = document.querySelectorAll(".owner-checkbox");
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener("change", function () {
                    // Jika ada checkbox yang tidak dicentang, "Check All" akan ter-uncheck
                    let allChecked = true;
                    checkboxes.forEach(cb => {
                        if (!cb.checked) {
                            allChecked = false;  // Kalau ada yang tidak dicentang, set allChecked ke false
                        }
                    });
                    document.getElementById("checkAll").checked = allChecked;  // Sesuaikan status "Check All"
                });
            });

            // Validasi ketika form akan disubmit
            document.getElementById("inlinePlanForm").addEventListener("submit", function (e) {
            let checkedOwners = document.querySelectorAll('.owner-checkbox:checked');
            let ownersHidden = document.querySelector("input[name='owners']");
            ownersHidden.value = Array.from(checkedOwners).map(cb => cb.value).join(",");

            // Validasi jika tidak ada owner yang dipilih
            if (checkedOwners.length === 0) {
                e.preventDefault(); // Mencegah form submit

                toastr.warning("Pilih Owner Terlebih Dahulu!", "Warning", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                });

                // Kembalikan tombol ke status semula
                let submitButton = this.querySelector("button[type='submit']");
                submitButton.innerHTML = 'Show';  // Ganti teks tombol kembali ke semula
                submitButton.disabled = false;  // Mengaktifkan tombol kembali
            } else {
                // Update tombol saat form sedang diproses
                let submitButton = this.querySelector("button[type='submit']");
                submitButton.innerHTML = 'Loading...';
                submitButton.disabled = true;
            }
        });

            // Menambahkan event listener untuk link LC
            document.querySelectorAll(".lc-link").forEach(link => {
                link.addEventListener("click", function () {
                    let noLc = this.getAttribute("data-lc"); // Ambil nilai NO LC
                    if (noLc) {
                        // Kirim request untuk mengambil data LC
                        fetch(`/transport/lc-detail?no_lc=${noLc}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    alert("Error: " + data.error);
                                } else {
                                    // Pastikan DataTable dihapus sebelum memuat data baru
                                    if ($.fn.DataTable.isDataTable("#lcDetailTable")) {
                                        $("#lcDetailTable").DataTable().clear().destroy();
                                    }
                                    document.getElementById("lcModalContent").innerHTML = ''; // Kosongkan isi tabel sebelumnya
                                    document.getElementById("lcTableHeaders").innerHTML = ''; // Kosongkan header sebelumnya

                                    // Jika ada data, buat kolom dinamis
                                    if (data.length > 0) {
                                        const headerRow = document.createElement('tr');
                                        Object.keys(data[0]).forEach(key => {
                                            const th = document.createElement('th');
                                            th.textContent = key.replace(/_/g, ' ').toUpperCase(); // Kolom dinamis
                                            headerRow.appendChild(th);
                                        });
                                        document.getElementById("lcTableHeaders").appendChild(headerRow);

                                        // Tambahkan data ke tabel
                                        data.forEach(item => {
                                            const row = document.createElement('tr');
                                            Object.values(item).forEach(value => {
                                                const td = document.createElement('td');
                                                td.textContent = value;
                                                row.appendChild(td);
                                            });
                                            document.getElementById("lcModalContent").appendChild(row);
                                        });

                                        // Inisialisasi ulang DataTable setelah data baru dimuat
                                        $('#lcDetailTable').DataTable({
                                            responsive: true,
                                            paging: true,
                                            searching: true,
                                            ordering: true,
                                        });
                                    }

                                    // Tampilkan modal dengan data yang diperbarui
                                    let modal = new bootstrap.Modal(document.getElementById("lcModal"));
                                    modal.show();
                                }
                            })
                            .catch(error => console.error("Error:", error));
                    }
                });
            });
                       
                $(document).ready(function() {
                // Hancurkan DataTable jika sudah ada
                if ($.fn.DataTable.isDataTable('#datatable')) {
                    $('#datatable').DataTable().destroy();
                }

                // Inisialisasi ulang DataTable
                $('#datatable').DataTable({
                    scrollY: '50vh',  // Freeze header dengan batas tinggi 50% viewport
                    scrollX: true,  // Scroll horizontal aktif
                    scrollCollapse: true,
                    paging: true,
                    fixedHeader: true,  // Pastikan header tetap di atas saat scroll
                    responsive: false,  // Matikan responsif agar tidak muncul tombol "+"
                    dom: 'lBfrtip',  // Aktifkan tombol export
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            text: 'Export to Excel',
                            titleAttr: 'Download Excel',
                            className: 'btn btn-success'
                        }
                    ]
                });
            });
        });
    </script>

    <style>

.modal-body {
    width: 100%;
    height: auto;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    overflow-x: auto; /* Tambahkan untuk scroll horizontal pada modal */
}

#lcDetailTable {
    width: 100%;
    table-layout: auto; /* Agar tabel menyesuaikan lebar container */
    overflow-x: auto;
}
    .modal-dialog {
        max-width: 75%; /* Sesuaikan persentase sesuai kebutuhan */
    }
    .card-body {
    padding-bottom:75px  !important; /* Tambah ruang di bawah agar dropdown tidak terpotong */
    position: relative;
}
    
    </style>
@endsection
