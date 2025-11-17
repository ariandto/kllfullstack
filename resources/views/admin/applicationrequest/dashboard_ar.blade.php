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
                                    DASHBOARD AR {{ $facility['Name'] }}
                                </h4>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            {{-- AVAILABILITY DEVELOPER --}}
            <div class="row">
                <div class="col-lg-10 col-md-9 col-sm-12">

                    <div class="card shadow-sm">
                        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center py-2"
                            style="cursor: pointer; font-size: 0.9rem; font-style: italic;" data-bs-toggle="collapse"
                            data-bs-target="#AR">
                            <h5 class="mb-0 text-white">Avaibility Developer</h5>
                            {{-- <h5 class="mb-0 text-white fs-6">Avaibility Developer</h5> --}}
                        </div>

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
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center p-2 border-bottom">
                                            <img src="{{ asset('backend/assets/images/bus.png') }}" alt="User Avatar"
                                                class="rounded-circle me-2 border" width="45" height="45">

                                            <div class="flex-grow-1">
                                                <div class="d-grid grid-template">
                                                    <span></span>
                                                    <span class="text-muted text-start">Nama</span>
                                                    <span></span>
                                                    <span class="text-break">{{ $dev['name'] }}</span>
                                                </div>

                                                <div class="d-grid grid-template">
                                                    <span></span>
                                                    <span class="text-muted text-start">Project</span>
                                                    <span></span>
                                                    <span class="text-truncate d-inline-block" style="max-width: 100%;"
                                                        title="{{ $dev['project'] }}">
                                                        {{ $dev['project'] }}
                                                    </span>
                                                </div>

                                                <div class="d-grid grid-template">
                                                    <span></span>
                                                    <span class="text-muted text-start">Avaibility Date</span>
                                                    <span></span>
                                                    <span>-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Rating -->
                <div class="col-lg-2 col-md-3 col-sm-12 d-flex flex-column">

                    <div class="card shadow-sm text-center flex-grow-1 d-flex flex-column h-100"> {{-- Pastikan tinggi penuh --}}
                        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center py-2"
                            style="cursor: pointer; font-size: 0.9rem; font-style: italic;" data-bs-toggle="collapse"
                            data-bs-target="#AR">
                            <h5 class="mb-0 text-white ">Rate Us Overview</h5>
                        </div>

                        <div class="card-body d-flex flex-column justify-content-center">

                            <h2 class="fw-bold">4.5 / 5</h2>
                            <p>200 Ratings</p>
                            <div class="d-flex justify-content-center">
                                <div id="rating-stars"></div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            {{-- Data Grafik --}}
            <div class="row">
                <!-- Pie Chart (Kiri) -->
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card">
                        {{-- <div class="card-header text-center">
                            <h5>TypeApp Distribution</h5>
                        </div> --}}
                        <div class="card-body">
                            <div id="pie-chart" style="width: 100%; height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Bar Chart (Kanan) -->
                <div class="col-lg-8 col-md-6 col-sm-12">
                    <div class="card">
                        {{-- <div class="card-header text-center">
                            <h5>Project Status</h5>
                        </div> --}}
                        <div class="card-body">
                            <div id="bar-chart" style="width: 100%; height: 300px;"></div>
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
                                                                <h5 class="modal-title" id="modalLabel{{ $index }}">
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

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // === PIE CHART ===
                const pieChart = echarts.init(document.getElementById('pie-chart'));
                const pieOption = {
                    title: {
                        text: "TypeApp",
                        left: "center"
                    },
                    tooltip: {
                        trigger: "item"
                    },
                    legend: {
                        orient: "vertical",
                        left: "left"
                    },
                    series: [{
                        name: "Usage",
                        type: "pie",
                        radius: ["40%", "70%"], // 🔥 Mengubah menjadi Doughnut Chart
                        label: {
                            show: true,
                            //formatter: "{b}: {c} ({d}%)"
                            formatter: "{c}"
                        },
                        data: [{
                                value: 4,
                                name: "Android"
                            },
                            {
                                value: 5,
                                name: "Web"
                            },
                            {
                                value: 6,
                                name: "Desktop"
                            },
                            {
                                value: 7,
                                name: "Other"
                            },
                            {
                                value: 8,
                                name: "RPA"
                            }
                        ]
                    }]
                };
                pieChart.setOption(pieOption);

                // === BAR CHART ===
                const barChart = echarts.init(document.getElementById('bar-chart'));
                const barData = [{
                        name: "Analyzed",
                        value: 15
                    },
                    {
                        name: "Developed",
                        value: 10
                    },
                    {
                        name: "GoLive",
                        value: 5
                    }
                ];

                const barOption = {
                    title: {
                        text: "Project Status",
                        left: "center"
                    },
                    tooltip: {
                        trigger: "axis"
                    },
                    xAxis: {
                        type: "category",
                        data: barData.map(item => item.name)
                    },
                    yAxis: {
                        type: "value"
                    },
                    series: [{
                        name: "Projects",
                        type: "bar",
                        data: barData.map(item => item.value),
                        itemStyle: {
                            color: "#5470c6"
                        },
                        label: {
                            show: true,
                            position: "top",
                            // formatter: "{b}: {c}"
                            formatter: "{c}"
                        }
                    }]
                };
                barChart.setOption(barOption);

                // === RESPONSIVE RESIZE ===
                window.addEventListener("resize", function() {
                    pieChart.resize();
                    barChart.resize();
                });



                ///// INI UNTUK RATING
                var ratingStars = raterJs({
                    starSize: 32,
                    element: document.querySelector("#rating-stars"),
                    rating: 4.5,
                    max: 5,
                    readOnly: true,
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

            .grid-template {
                display: grid;
                grid-template-columns: 5% 25% 10% 60%;
            }

            @media (max-width: 1024px) {

                /* Untuk iPad dan Laptop kecil */
                .grid-template {
                    grid-template-columns: 5% 30% 10% 55%;
                }
            }

            @media (max-width: 768px) {

                /* Untuk HP */
                .grid-template {
                    grid-template-columns: 10% 35% 5% 50%;
                }
            }
        </style>
    @endsection
