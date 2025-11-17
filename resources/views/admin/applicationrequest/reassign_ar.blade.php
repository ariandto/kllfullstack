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
                                    RE-ASSIGN AR {{ $facility['Name'] }}
                                </h4>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            {{-- AVAILABILITY DEVELOPER --}}
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @php
                                $developers = [
                                    ['name' => 'ABDILLAH MUHAMMAD DINULLAH', 'project' => 'Project A'],
                                    ['name' => 'ALFIAN MUTTAQIN', 'project' => 'Project Management System Revamp'],
                                    [
                                        'name' => 'DANU SUSILO DESTIAN NUGROHO',
                                        'project' => 'E-commerce Website Development',
                                    ],
                                    ['name' => 'DEDE HIDAYAT', 'project' => 'Bug Fixing Legacy System'],
                                    ['name' => 'IKHSAN MAULANA', 'project' => 'Short Name'],
                                    [
                                        'name' => 'RUDI GUNAWAN',
                                        'project' => 'Very Long Project Name That Needs to be Truncated',
                                    ],
                                ];
                            @endphp

                            @foreach ($developers as $dev)
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="card shadow-sm">
                                        <div class="card-body d-flex align-items-center">
                                            <img src="{{ asset('backend/assets/images/bus.png') }}" alt="User Avatar"
                                                class="rounded-circle border me-3" width="50" height="50">

                                            <div class="flex-grow-1">
                                                <h6 class="mb-2">{{ $dev['name'] }}</h6>

                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">Project</span>
                                                    <span
                                                        class="fw-semibold text-break text-end">{{ $dev['project'] }}</span>
                                                </div>

                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted">Availability Date</span>
                                                    <span class="fw-semibold text-end">-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>


            </div>


            {{-- Data Parameter --}}
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-3">
                                <label for="user" class="form-label" style="font-style: italic;">User</label>
                                <select class="form-control" id="user" name="user">
                                    @isset($getListApp)
                                        @foreach ($getListApp as $app)
                                            <option value="{{ $app->ListApp }}">{{ $app->ListApp }}</option>
                                        @endforeach
                                    @else
                                        <option value="">Data tidak tersedia</option>
                                    @endisset
                                </select>
                            </div>

                            <div class="col-md-9 col-sm-9">
                                <label for="project_name" class="form-label" style="font-style: italic;">Project
                                    Name</label>
                                <input type="text" class="form-control" id="project_name" name="project_name"
                                    value="" required>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <label for="start_date_analyst" class="form-label" style="font-style: italic;">Start Date
                                    Analyst</label>
                                <input type="date" class="form-control" id="start_date_analyst" name="start_date_analyst"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <label for="end_date_analyst" class="form-label" style="font-style: italic;">End Date
                                    Analyst</label>
                                <input type="date" class="form-control" id="end_date_analyst" name="end_date_analyst"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <label for="start_date_develop" class="form-label" style="font-style: italic;">Start Date
                                    Develop</label>
                                <input type="date" class="form-control" id="start_date_develop" name="start_date_develop"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <label for="end_date_develop" class="form-label" style="font-style: italic;">End Date
                                    Develop</label>
                                <input type="date" class="form-control" id="end_date_develop" name="end_date_develop"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <!-- Data Table -->
            <div class="row mt-3 mb-3">
                <!-- Isi kontennya -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="filterTable" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>AppName</th>
                                            <th>Draft</th>
                                            <th>New</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($ardashboard1) && count($ardashboard1) > 0)
                                            @foreach ($ardashboard1 as $index => $ardash)
                                                <tr>
                                                    <td>{{ $ardash->AppName ?? '-' }}</td>
                                                    <td>{{ $ardash->Draft ?? '-' }}</td>
                                                    <td>{{ $ardash->New ?? '-' }}</td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#detailModal{{ $index }}">
                                                            Show
                                                        </button>
                                                    </td>
                                                </tr>

                                                <!-- Modal untuk setiap baris -->
                                                <div class="modal fade" id="detailModal{{ $index }}" tabindex="-1"
                                                    aria-labelledby="modalLabel{{ $index }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="modalLabel{{ $index }}">
                                                                    Detail for {{ $ardash->AppName }}
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Column 1</th>
                                                                                        <th>Column 2</th>
                                                                                        <th>Column 3</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td>Data 1</td>
                                                                                        <td>Data 2</td>
                                                                                        <td>Data 3</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>Data A</td>
                                                                                        <td>Data B</td>
                                                                                        <td>Data C</td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" class="text-center">Tidak ada data tersedia</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
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
                var tableExists = {{ isset($ardashboard1) && count($ardashboard1) > 0 ? 'true' : 'false' }};

                if (tableExists) {
                    initDataTable();
                }

                function initDataTable() {
                    if ($.fn.DataTable.isDataTable("#filterTable")) {
                        $("#filterTable").DataTable().clear().destroy();
                    }

                    $("#filterTable").DataTable({
                        responsive: true,
                        autoWidth: false
                    });
                }

                // Saat form dikirim, tampilkan tabel setelah data di-load
                $("form").on("submit", function() {
                    setTimeout(function() {
                        initDataTable(); // Inisialisasi ulang DataTables setelah data diperbarui
                    }, 500); // Delay sedikit agar tabel sudah dimuat
                });
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
        </style>
    @endsection
