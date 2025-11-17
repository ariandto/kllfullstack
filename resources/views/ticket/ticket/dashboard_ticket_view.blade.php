@extends('ticket.dashboard')

@section('ticket')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-center">
                        @if (session()->has('RoleFacility'))
                            <div style="text-align: center;">
                                <h4 style="font-size: 1.5rem; font-weight: bold; font-style: italic;">
                                    DASHBOARD MONITORING E-TICKETING
                                </h4>
                                <h4 style="font-size: 1.5rem; font-weight: bold;">
                                    {{ now()->year }}

                                </h4>
                            </div>
                        @else
                            <h4 style="font-size: 1.5rem; font-weight: bold; font-style: italic; text-align: center;">
                                No Employee Information Available
                            </h4>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-9 col-md-8 col-sm-12 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Summary Bus</h4>
                        </div>
                        <div class="card-body">
                            @if (!empty($ticketdashboard) && is_iterable($ticketdashboard))
                                <select id="keberangkatan-filter" class="form-select mb-3">
                                    <option value="all">ALL</option>
                                    @foreach (collect($ticketdashboard)->unique('Keberangkatan') as $item)
                                        <option value="{{ $item->Keberangkatan }}">{{ $item->Keberangkatan }}</option>
                                    @endforeach
                                </select>
                                <div id="column-chart" class="e-charts" style="width: 100%;"></div>
                            @else
                                <p class="text-center">No data available</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Percentage</h4>
                        </div>
                        <div class="card-body">
                            @if (!empty($ticketdashboard) && is_iterable($ticketdashboard))
                                <div id="pie-chart" class="e-charts" style="width: 100%;"></div>
                            @else
                                <p class="text-center">No data available</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Search Data</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('ticket.ticketdata_dashboard1') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="keberangkatan" class="form-label">Keberangkatan</label>
                                        <select id="keberangkatan" name="keberangkatan" class="form-control">
                                            <option value="">-- Pilih Keberangkatan --</option>
                                            @if (!empty($keberangkatan))
                                                @foreach ($keberangkatan as $option)
                                                    <option value="{{ $option }}">{{ $option }}</option>
                                                @endforeach
                                            @else
                                                <option value="">Tidak ada data keberangkatan</option>
                                            @endif

                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="bus" class="form-label">Bus</label>
                                        <select id="bus" name="bus" class="form-control">
                                            <option value="">-- Pilih Bus --</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="status2" class="form-label">Status</label>
                                        <select id="status2" name="status2" class="form-control">
                                            <option value="Open">Open</option>
                                            <option value="Close">Close</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-3 text-start">
                                    <button type="submit" class="btn btn-primary">Show</button>
                                </div>
                            </form>


                            <div class="row mt-3 mb-3">
                                <!-- Isi kontennya -->
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="filterTable" class="table table-striped">
                                                    <thead>
                                                        <tr>

                                                            <th>NIK</th>
                                                            <th>Pemudik</th>
                                                            <th>No Bus</th>
                                                            <th>Tujuan</th>
                                                            <th>Total Tiket</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (!empty($ticketdashboard1) && count($ticketdashboard1) > 0)
                                                            @foreach ($ticketdashboard1 as $index => $ticket)
                                                                <tr>

                                                                    <td>{{ $ticket->Nik ?? '-' }}</td>
                                                                    <td>{{ $ticket->Pemudik ?? '-' }}</td>
                                                                    <td>{{ $ticket->NoBus ?? '-' }}</td>
                                                                    <td>{{ $ticket->Tujuan ?? '-' }}</td>
                                                                    <td>{{ $ticket->TotalTiket ?? '0' }}</td>
                                                                    <td>{{ $ticket->Status ?? '-' }}</td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td colspan="7" class="text-center">Tidak ada data
                                                                    tersedia</td>
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
                    </div>



                </div>



            </div>


        </div>

    </div>

    <script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Cek apakah tabel memiliki data saat halaman dimuat
            var tableExists = {{ isset($ticketdashboard1) && count($ticketdashboard1) > 0 ? 'true' : 'false' }};

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
            var ticketData = @json($ticketdashboard);

            var columnChart = echarts.init(document.getElementById("column-chart"));
            var pieChart = echarts.init(document.getElementById("pie-chart"));

            function updateChart(filterValue) {
                var filteredData = filterValue === "all" ? ticketData : ticketData.filter(item => item
                    .Keberangkatan === filterValue);
                var categories = filteredData.map(item => item.NoBus);
                var closeData = filteredData.map(item => item.Close);
                var openData = filteredData.map(item => item.Open);

                var totalOpen = openData.reduce((sum, val) => sum + parseInt(val, 10), 0);
                var totalClose = closeData.reduce((sum, val) => sum + parseInt(val, 10), 0);

                var columnOption = {
                    // title: {
                    //     text: "Summary Bus"
                    // },
                    tooltip: {
                        trigger: "axis"
                    },
                    legend: {
                        data: ["Close", "Open"],
                        bottom: 0
                    },
                    xAxis: {
                        type: "category",
                        data: categories
                    },
                    yAxis: {
                        type: "value"
                    },
                    series: [{
                            name: "Close",
                            type: "bar",
                            data: closeData,
                            itemStyle: {
                                color: "#ff4d4f"
                            }, // Merah
                            label: {
                                show: true,
                                position: "top",
                                formatter: "{c}"
                            }
                        },
                        {
                            name: "Open",
                            type: "bar",
                            data: openData,
                            itemStyle: {
                                color: "#40a9ff"
                            }, // Biru
                            label: {
                                show: true,
                                position: "top",
                                formatter: "{c}"
                            }
                        }
                    ]
                };
                columnChart.setOption(columnOption);

                var pieOption = {
                    tooltip: {
                        trigger: "item"
                    },
                    legend: {
                        bottom: 0
                    },
                    series: [{
                        name: "Status",
                        type: "pie",
                        radius: "50%",
                        data: [{
                                value: totalOpen,
                                name: "Open",
                                itemStyle: {
                                    color: "#40a9ff"
                                }
                            }, // Biru
                            {
                                value: totalClose,
                                name: "Close",
                                itemStyle: {
                                    color: "#ff4d4f"
                                }
                            } // Merah
                        ],
                        label: {
                            formatter: "{b}: {c} ({d}%)"
                        }
                    }]
                };
                pieChart.setOption(pieOption);
            }

            document.getElementById("keberangkatan-filter").addEventListener("change", function() {
                updateChart(this.value);
            });

            updateChart("all");
            window.addEventListener("resize", function() {
                columnChart.resize();
                pieChart.resize();
            });

        });
    </script>

    <script>
        $(document).ready(function() {
            $('#keberangkatan').val(''); // Reset dropdown keberangkatan
            $('#bus').html('<option value="">-- Pilih Bus --</option>'); // Reset dropdown bus

            $('#keberangkatan').change(function() {
                var keberangkatan = $(this).val();
                $('#bus').html('<option value="">-- Loading... --</option>'); // Tampilkan loading

                if (keberangkatan) {
                    $.ajax({
                        url: "{{ route('ticket.getBusByKeberangkatan') }}", // Pastikan route sesuai
                        type: "GET",
                        data: {
                            keberangkatan: keberangkatan
                        },
                        success: function(data) {
                            $('#bus').html(
                                '<option value="">-- Pilih Bus --</option>'); // Reset dropdown

                            $.each(data, function(index, item) {
                                $('#bus').append('<option value="' + item.NoBus + '">' +
                                    item.NoBus + '</option>');
                            });
                        }
                    });
                } else {
                    $('#bus').html(
                        '<option value="">-- Pilih Bus --</option>'); // Reset jika tidak ada pilihan
                }
            });
        });
    </script>


    <style>
        .card-header {
            background-color: #cce5ff;
            /* Warna biru muda */
            color: #004085;
            /* Warna teks biru tua agar kontras */
            padding: 10px;
            border-bottom: 1px solid #b8daff;
        }

        th.text-end,
        td.text-end {
            text-align: right !important;
        }

        th.text-start,
        td.text-start {
            text-align: left !important;
        }
    </style>
@endsection
