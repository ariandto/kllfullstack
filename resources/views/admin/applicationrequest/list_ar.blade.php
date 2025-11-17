@extends('admin.dashboard')
@section('admin')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-center">
                        @if (session('facility_info'))
                            @foreach (session('facility_info') as $facility)
                                <h4 class="text-center fw-bold">
                                    LIST AR {{ $facility['Name'] }}
                                </h4>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.ar.list_ar.submitview') }}" method="POST">
                @csrf
                {{-- Data Parameter --}}
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <label for="start_date" class="form-label" style="font-style: italic;">Create StartDate</label>
                        <input type="date" class="form-control" id="start_date" name="start_date"
                            value="{{ old('start_date', request('start_date', date('Y-m-d'))) }}" required>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <label for="end_date" class="form-label" style="font-style: italic;">Expected Go Create End
                            Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date"
                            value="{{ old('end_date', request('end_date', date('Y-m-d'))) }}" required>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <label for="application_type" class="form-label" style="font-style: italic;">Application
                            Type</label>
                        <select class="form-control" id="application_type" name="application_type">
                            <option value="All"
                                {{ old('application_type', request('application_type')) == 'All' ? 'selected' : '' }}>All
                            </option>
                            @isset($getListApp)
                                @foreach ($getListApp as $app)
                                    <option value="{{ $app->ListApp }}"
                                        {{ old('application_type', request('application_type')) == $app->ListApp ? 'selected' : '' }}>
                                        {{ $app->ListApp }}
                                    </option>
                                @endforeach
                            @else
                                <option value="">Data tidak tersedia</option>
                            @endisset
                        </select>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <label for="application_status" class="form-label" style="font-style: italic;">Application
                            Status</label>
                        <select class="form-control" id="application_status" name="application_status">
                            <option value="All"
                                {{ old('application_status', request('application_status')) == 'All' ? 'selected' : '' }}>
                                All</option>
                            @isset($getStatusAR)
                                @foreach ($getStatusAR as $app)
                                    <option value="{{ $app->StatusDescr }}"
                                        {{ old('application_status', request('application_status')) == $app->StatusDescr ? 'selected' : '' }}>
                                        {{ $app->StatusDescr }}
                                    </option>
                                @endforeach
                            @else
                                <option value="">Data tidak tersedia</option>
                            @endisset
                        </select>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100" id="btnShow">Show</button>
                    </div>

                </div>
            </form>
            <!-- Data Table -->
            <div class="row mt-3 mb-3">
                <!-- Isi kontennya -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="filterTable" class="table table-striped">
                                    @if (session('error'))
                                        <div class="alert alert-danger">
                                            {{ session('error') }}
                                        </div>
                                    @endif

                                    @if (isset($getDataView) && count($getDataView) > 0)
                                        <thead class="text-center align-middle">
                                            <tr>
                                                <th>NO</th>
                                                <th>APPLICATION NO</th>
                                                <th>REQUEST TYPE</th>
                                                <th>APPLICATION TYPE</th>
                                                <th>PROJECT NAME</th>
                                                <th>KLIP</th>
                                                <th>PILOT PROJECT</th>
                                                <th>PROJECT OWNER PROFILE</th>
                                                <th>PROJECT OWNER</th>
                                                <th>STATUS</th>
                                                <th>EXPECTED GO LIVE</th>
                                                <th>ACTION</th> <!-- ✅ Tambahkan kolom Action -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($getDataView as $index => $data)
                                                <tr>
                                                    <td class="align-middle">{{ $index + 1 }}</td>
                                                    <td class="align-middle">{{ $data->{"Application No"} }}</td>
                                                    <td class="align-middle">{{ $data->{"Request Type"} }}</td>
                                                    <td class="align-middle">{{ $data->{"Application Type"} }}</td>
                                                    <td class="align-middle">{{ $data->{"Project Name"} }}</td>
                                                    <td class="align-middle">{{ $data->{"KLIP"} }}</td>
                                                    <td class="align-middle">{{ $data->{"Pilot Project"} }}</td>
                                                    <td class="text-center align-middle">
                                                        @php
                                                            $picture = asset('path_ke_gambar_default.png'); // Gambar default
                                                            if (
                                                                property_exists($data, 'Project Owner Profile') &&
                                                                !empty($data->{'Project Owner Profile'})
                                                            ) {
                                                                $picture =
                                                                    'data:image/png;base64,' .
                                                                    base64_encode($data->{'Project Owner Profile'});
                                                            }
                                                        @endphp
                                                        <img src="{{ $picture }}" alt="Project Owner Profile"
                                                            class="rounded-circle" width="40" height="40">
                                                    </td>
                                                    <td class="align-middle">{{ $data->{"Project Owner"} }}</td>
                                                    <td class="align-middle">{{ $data->{"Status"} }}</td>
                                                    <td class="align-middle">{{ $data->{"Expected Golive"} }}</td>
                                                    <td class="text-center">
                                                        <button class="btn btn-primary btn-sm"
                                                            onclick="showDetail('{{ $data->{'Application No'} }}')">
                                                            <i class="fas fa-hand-point-left"></i>
                                                            <!-- Ikon tangan menunjuk ke kiri -->
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @else
                                        <p class="text-center mt-3">Tidak ada data yang ditemukan.</p>
                                    @endif

                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailModalLabel">Detail Application</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">

                                <!-- Row 1: Data Utama -->
                                <div class="card mb-3">
                                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center py-2"
                                        style="cursor: pointer; font-size: 0.9rem; font-style: italic;"
                                        data-bs-toggle="collapse" data-bs-target="#AR">
                                        <h5 class="mb-0 text-white fs-6">Header Project</h5>
                                        <i class="fas fa-eye float-end toggle-section" data-target="#dataUtamaCard"></i>
                                    </div>
                                    <div class="card-body" id="dataUtamaCard">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Application No</label>
                                                <input type="text" class="form-control" id="applicationNo" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Company Name</label>
                                                <input type="text" class="form-control" id="company" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Project Owner</label>
                                                <input type="text" class="form-control" id="projectOwner" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Status Project</label>
                                                <input type="text" class="form-control" id="statusProject" readonly>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Project Name</label>
                                                <input type="text" class="form-control" id="projectName" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Pilot Project</label>
                                                <input type="text" class="form-control" id="pilotProject" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Application Type</label>
                                                <input type="text" class="form-control" id="applicationType" readonly>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <!-- Row 2: Point 1 - Point 4 -->
                                <div class="card mb-3">
                                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center py-2"
                                        style="cursor: pointer; font-size: 0.9rem; font-style: italic;"
                                        data-bs-toggle="collapse" data-bs-target="#AR">
                                        <h5 class="mb-0 text-white fs-6">Detail Project</h5>
                                        <i class="fas fa-eye float-end toggle-section"
                                            data-target="#pointDetailsCard"></i>
                                    </div>
                                    <div class="card-body collapse" id="pointDetailsCard">
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label fw-bold">Point 1</label>
                                                <textarea class="form-control" id="point1" rows="2" readonly></textarea>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label fw-bold">Point 2</label>
                                                <textarea class="form-control" id="point2" rows="2" readonly></textarea>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label fw-bold">Point 3</label>
                                                <textarea class="form-control" id="point3" rows="2" readonly></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">Point 4</label>
                                                <textarea class="form-control" id="point4" rows="2" readonly></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label" style="font-style: italic;">Comment</label>
                                            <div class="mb-2">
                                                <button type="button" class="btn btn-success btn-sm" id="btnDownload">
                                                    <i class="mdi mdi-download"></i> Download
                                                </button>
                                            </div>

                                            <div id="attachment" class="text-muted"></div>

                                            <textarea class="form-control" id="comment" name="comment" rows="4" readonly></textarea>
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


    <script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Cek apakah tabel memiliki data saat halaman dimuat
            var tableExists = {{ isset($getDataView) && count($getDataView) > 0 ? 'true' : 'false' }};

            if (tableExists) {
                initDataTable();
            }

            function initDataTable() {
                // Hapus instance DataTable jika sudah ada
                if ($.fn.DataTable.isDataTable("#filterTable")) {
                    $("#filterTable").DataTable().destroy();
                }

                // Inisialisasi ulang DataTable
                $("#filterTable").DataTable({
                    scrollY: '400px',
                    scrollX: true,
                    scrollCollapse: true,
                    paging: true,
                    searching: true,
                    info: true,
                    fixedHeader: true,
                    autoWidth: false,
                    responsive: false,
                    columnDefs: [{
                        targets: "_all",
                        className: "text-wrap"
                    }],
                    order: [],
                    dom: 'lBfrtip',
                    buttons: [{
                            extend: 'copy',
                            text: 'Copy',
                            className: 'btn btn-success'
                        },
                        {
                            extend: 'csv',
                            text: 'CSV',
                            className: 'btn btn-success'
                        },
                        {
                            extend: 'excel',
                            text: 'Excel',
                            className: 'btn btn-success'
                        },
                        {
                            extend: 'print',
                            text: 'Print',
                            className: 'btn btn-success'
                        }
                    ],
                    language: {
                        search: "Filter data:",
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
            }

            // Saat form dikirim, tunggu data baru sebelum inisialisasi ulang tabel
            $("form").on("submit", function(event) {
                setTimeout(function() {
                    console.log("Inisialisasi ulang DataTables...");
                    initDataTable();
                }, 1000); // Delay untuk memastikan data sudah diperbarui
            });
        });
    </script>


    <script>
        function showDetail(applicationNo) {
            $.ajax({
                url: "{{ route('admin.ar.list_ar.submitviewdetail') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    application_no: applicationNo
                },
                dataType: "json",
                success: function(response) {
                    console.log("Response dari Server:", response);

                    if (!response.success || response.data.length === 0) {
                        alert("Data tidak ditemukan");
                        return;
                    }
                    // Ambil data pertama (baris 1)
                    let data = response.data[0];
                    // Isi input dalam modal
                    $("#applicationNo").val(data["Application No"] || "N/A");
                    $("#company").val(data["Company"] || "N/A");
                    $("#projectOwner").val(data["Project Owner"] || "N/A");
                    $("#statusProject").val(data["Project Status"] || "N/A");
                    $("#projectName").val(data["Project Name"] || "N/A");
                    $("#pilotProject").val(data["Pilot Project"] || "N/A");
                    $("#applicationType").val(data["Application Type"] || "N/A");
                    // Ambil data kedua (baris 2)
                    let detailData = response.data[0] || {};
                    $("#point1").val(detailData["Point1"] || "");
                    $("#point2").val(detailData["Point2"] || "");
                    $("#point3").val(detailData["Point3"] || "");
                    $("#point4").val(detailData["Point4"] || "");
                    $("#comment").val(data["Comment"] || "N/A");
                    $("#attachment").html(data["Attachment"] || "N/A");


                    // Tampilkan modal
                    let detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
                    detailModal.show();
                },
                error: function(xhr) {
                    console.error("Error:", xhr.responseText);
                    alert("Terjadi kesalahan saat mengambil data.");
                }
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".toggle-section").forEach(function(icon) {
                icon.addEventListener("click", function() {
                    let target = document.querySelector(this.getAttribute("data-target"));
                    if (target.classList.contains("collapse")) {
                        target.classList.remove("collapse"); // Buka
                        this.classList.replace("fa-eye",
                            "fa-eye-slash"); // Ganti ikon mata terbuka ke tertutup
                    } else {
                        target.classList.add("collapse"); // Tutup
                        this.classList.replace("fa-eye-slash",
                            "fa-eye"); // Ganti ikon mata tertutup ke terbuka
                    }
                });
            });
        });


        document.getElementById("btnDownload").addEventListener("click", function() {
            let arNumber = document.getElementById("attachment").textContent.trim().substring(0, 12);

            if (!arNumber) {
                alert("File tidak tersedia untuk diunduh!");
                return;
            }

            // Cari file berdasarkan AR Number
            fetch(`/admin/find-file/${arNumber}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    let filename = encodeURIComponent(data.filename);
                    console.log(filename);
                    let downloadUrl = `/admin/download/${filename}`;

                    // Buat link dan trigger download
                    let a = document.createElement("a");
                    a.href = downloadUrl;
                    a.download = data.filename;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                })
                .catch(error => console.error("Error:", error));
        });
    </script>

    <style>
        .form-control {
            background-color: white !important;
            /* Warna putih saat tidak disabled */
            color: black !important;
            /* Warna teks normal */
        }

        .form-control:disabled,
        .form-select:disabled {
            background-color: #fcfcfc !important;
            /* Warna abu-abu saat disabled */
            /* color: #e2e3e4 !important; */
            /* Warna teks redup */
            cursor: not-allowed;
        }


        /* Lebar modal jadi 90% dari layar */
        .modal-dialog {
            max-width: 90%;
        }

        /* Supaya modal bisa di-scroll jika konten panjang */
        .modal-body {
            max-height: 80vh;
            overflow-y: auto;
        }
    </style>










@endsection
