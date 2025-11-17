@extends('admin.dashboard')
@section('admin')

    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-center">
                        @if (session('facility_info'))
                            @foreach (session('facility_info') as $facility)
                                <h4 style="font-size: 1.5rem; font-weight: bold; text-align: center;">
                                    REPORT KONTRIBUTOR LPPB {{ $facility['Name'] }}
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
            <!-- end page title -->
            <!-- Row pertama untuk parameter -->
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

                            <form id="reportForm" method="POST"
                                action="{{ route('admin.report.outbound.summary_reportkontributorlppb') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-6 col-md-2 mb-3">

                                        <label class="form-label">Start Date</label>
                                        <input type="date" id="periodeStart" name="start_date" class="form-control"
                                            value="{{ request('start_date', date('Y-m-d')) }}" required>
                                        {{-- <input type="date" class="form-control" id="startDate" name="start_date" required
                                            value="{{ old('start_date', session('input_dataac_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.start_date')) }}"> --}}
                                    </div>
                                    <div class="col-6 col-md-2 mb-3">

                                        <label class="form-label">End Date</label>
                                        <input type="date" id="periodeEnd" name="end_date" class="form-control"
                                            value="{{ request('end_date', date('Y-m-d')) }}" required>
                                        {{-- <input type="date" class="form-control" id="startDate" name="start_date" required
                                            value="{{ old('start_date', session('input_dataac_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.start_date')) }}"> --}}
                                    </div>

                                    <div class="col-6 col-md-2 mb-3">
                                        <label class="form-label">DeptName</label>
                                        <select id="deptname" name="deptname" class="form-control">
                                            @foreach ($deptname as $dept)
                                                <option value="{{ $dept->DeptName }}"
                                                    {{ isset($selectedDept) && $selectedDept == $dept->DeptName ? 'selected' : '' }}>
                                                    {{ $dept->DeptName }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="col-6 col-md-2 mb-3">
                                        <label class="form-label"></label>
                                        <button type="submit" class="btn btn-info w-100 mt-2">Show</button>
                                    </div>

                                    <div class="col-6 col-md-2 mb-3">

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row pertama -->
            <div class="row">
                <div class="col-lg-5 col-md-6 col-sm-12 mb-3">
                    <div class="card d-flex flex-column" style="height: 100%;">
                        <div class="card-body d-flex flex-column" style="height: 100%;">
                            @if (!empty($dataTable2) && is_iterable($dataTable2))
                                <div id="pie-chart" class="e-charts">
                                </div>
                            @else
                                <p class="text-center">No data available</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-7 col-md-6 col-sm-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="filterTable" class="table table-striped table-bordered nowrap"
                                    style="width: 100%;">
                                    @if (isset($dataTable1) && !empty($dataTable1))
                                        <thead class="table-light">
                                            <tr>
                                            <tr>
                                                @foreach ($tableHeaders1 as $header)
                                                    <th>{{ $header }}</th>
                                                @endforeach
                                            </tr>

                                            </tr>

                                        </thead>
                                        <tbody>
                                            @foreach ($dataTable1 as $item)
                                                <tr>
                                                    @foreach ($tableHeaders1 as $header)
                                                        @php
                                                            // Default alignment to right for all columns
                                                            $alignmentClass2 = 'text-end';
                                                            $formattedValue2 = $item[$header] ?? '-'; // Default value for missing data

                                                            // Check for special left alignment columns: Dept and Kriteria
                                                            if (in_array($header, ['NIK', 'NAMA'])) {
                                                                $alignmentClass2 = 'text-start'; // Left aligned for specific columns
                                                            } else {
                                                                // Format numeric values for all other columns
                                                                if (is_numeric($formattedValue2)) {
                                                                    $formattedValue2 = number_format(
                                                                        floatval($formattedValue2),
                                                                    );
                                                                }
                                                            }
                                                        @endphp

                                                        <td class="{{ $alignmentClass2 }}">
                                                            {{ $formattedValue2 }}
                                                        </td>
                                                    @endforeach

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @else
                                        <thead class="table-light">
                                            <tr>
                                                <th>No Data Available</th>
                                            </tr>
                                        </thead>
                                    @endif
                                </table>
                            </div>

                        </div>


                    </div>
                </div>

            </div>


            <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 90%; margin: auto;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitle">Detail Data</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <table id="detailTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr id="modalTableHead"></tr> <!-- Header dinamis -->
                                </thead>
                                <tbody id="modalTableBody"></tbody> <!-- Isi tabel dinamis -->
                            </table>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>


        </div> <!-- container-fluid -->
    </div>
    <script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>



    <!-- DataTables & Plugins -->
    <script>
        $(document).ready(function() {
            let table = $('#filterTable').DataTable({
                scrollY: '400px',
                scrollX: true,
                scrollCollapse: true,
                paging: true,
                searching: true,
                info: true,
                fixedHeader: true, // Header tetap di atas
                fixedColumns: {
                    leftColumns: 1 // Kolom pertama tetap beku
                },
                columnDefs: [{
                    targets: 0,
                    className: 'dt-left freeze-column'
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
                    // {
                    //     extend: 'pdf',
                    //     text: 'PDF',
                    //     className: 'btn btn-success'
                    // },
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

            // Pastikan header juga memiliki freeze column
            $('.dataTables_scrollHeadInner table thead th:first-child').addClass('freeze-header');

        });

        $(document).ready(function() {
            $('#filterTable tbody').on('click', 'td', function() {
                if (!$.fn.DataTable.isDataTable('#filterTable')) {
                    console.error("DataTable belum diinisialisasi!");
                    return;
                }

                let table = $('#filterTable').DataTable();
                let colIndex = $(this).index();
                let rowIndex = $(this).closest('tr').index();

                if (colIndex >= 1 && colIndex <= 5) {
                    let key1 = table.cell(rowIndex, 0).data() || '';
                    let headerCell = table.column(colIndex).header();
                    let key2 = headerCell ? headerCell.textContent.trim() : '';

                    let startDate = $('input[name="start_date"]').val();
                    let endDate = $('input[name="end_date"]').val();

                    $.ajax({
                        url: "{{ route('admin.report.outbound.detail_reportkontributorlppb') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nik: key1,
                            kategori: key2,
                            start_date: startDate,
                            end_date: endDate
                        },
                        dataType: "json",
                        success: function(response) {
                            // console.log("Response dari Server:", response);

                            if (!response.success) {
                                alert("Data tidak ditemukan");
                                return;
                            }

                            // Hapus DataTable lama jika ada
                            if ($.fn.DataTable.isDataTable('#detailTable')) {
                                $('#detailTable').DataTable().clear().destroy();
                                $('#detailTable thead').empty();
                                $('#detailTable tbody').empty();
                            }

                            // Pastikan ada headers meskipun data kosong
                            if (!response.headers || response.headers.length === 0) {
                                response.headers = ["No Data"];
                            }

                            // Buat header tabel
                            let headerRow = "<tr>";
                            response.headers.forEach(header => {
                                headerRow += "<th>" + header + "</th>";
                            });
                            headerRow += "</tr>";
                            $('#detailTable thead').append(headerRow);

                            // Isi tabel dengan data
                            if (response.data.length > 0) {
                                response.data.forEach(row => {
                                    let rowData = "<tr>";
                                    response.headers.forEach(header => {
                                        rowData += "<td>" + (row[header] ??
                                            '-') + "</td>";
                                    });
                                    rowData += "</tr>";
                                    $('#detailTable tbody').append(rowData);
                                    // Atur perataan teks berdasarkan tipe data
                                    $('#detailTable tbody tr:last td').each(function() {
                                        let cellText = $(this).text().trim();
                                        if ($.isNumeric(cellText)) {
                                            $(this).css('text-align',
                                                'right'
                                            ); // Rata kanan jika angka
                                        } else {
                                            $(this).css('text-align',
                                                'left'
                                            ); // Rata kiri jika teks atau campuran
                                        }
                                    });
                                });
                            } else {
                                $('#detailTable tbody').append(
                                    "<tr><td colspan='" + response.headers.length +
                                    "'>No data available</td></tr>"
                                );
                            }


                            // Inisialisasi DataTable
                            $('#detailTable').DataTable({
                                scrollX: true,
                                scrollY: "400px",
                                paging: true,
                                searching: true,
                                lengthChange: true,
                                autoWidth: false,
                                dom: 'Bfrtip',
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
                                    // {
                                    //     extend: 'pdf',
                                    //     text: 'PDF',
                                    //     className: 'btn btn-success'
                                    // },
                                    {
                                        extend: 'print',
                                        text: 'Print',
                                        className: 'btn btn-success'
                                    }
                                ],
                                initComplete: function(settings, json) {
                                    // console.log("DataTable initialized");
                                    setTimeout(function() {
                                        // console.log(
                                        //     "Adjusting column widths..."
                                        //     );
                                        $('#detailTable thead th').each(
                                            function(index) {
                                                var columnWidth = $(
                                                        '#detailTable tbody tr:first td'
                                                    ).eq(index)
                                                    .outerWidth();
                                                $(this).css('width',
                                                    columnWidth);

                                            });

                                        $('#detailTable').DataTable()
                                            .columns.adjust().responsive
                                            .recalc();
                                        // console.log(
                                        //     "Columns adjusted and responsive recalculated after initComplete"
                                        // );
                                    }, 500);
                                }
                            });

                            $('.modal').modal(
                                'hide'); // Tutup semua modal sebelum membuka yang baru

                            // **Pastikan modal selalu muncul**
                            let detailModal = new bootstrap.Modal(document.getElementById(
                                'detailModal'));
                            detailModal.show();
                        },
                        error: function(xhr) {
                            console.error("Error:", xhr.responseText);
                            alert("Terjadi kesalahan saat mengambil data.");
                        }
                    });
                }
            });
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var chartData = {!! isset($dataTable2) ? json_encode($dataTable2) : '[]' !!};

            // Filter data yang valid
            var pieData = chartData
                .filter(item => item.Keterangan && item.Total) // Hanya ambil data yang memiliki nilai
                .map(item => ({
                    name: item.KategoriCode, // Masih pakai KategoriCode sebagai identifier
                    name2: item.Keterangan, // Gunakan Keterangan untuk tooltip
                    value: parseInt(item.Total) || 0
                }));

            if (pieData.length === 0) {
                console.warn("Pie chart tidak memiliki data valid.");
                return;
            }

            // Generate warna acak untuk setiap label
            var colorMap = {};
            pieData.forEach((item, index) => {
                var hue = Math.floor(Math.random() * 360); // Warna acak dalam format HSL
                colorMap[item.name] = `hsl(${hue}, 70%, 60%)`;
            });

            var pieChart = echarts.init(document.getElementById("pie-chart"));

            var pieOption = {
                title: {
                    text: "Summary By Kategori",
                    left: "center",
                    bottom: "90%", // Geser title lebih ke atas agar ada jarak dengan pie chart
                    textStyle: {
                        fontSize: 16,
                        fontWeight: "bold"
                    }
                },

                tooltip: {
                    trigger: "item",
                    formatter: function(params) {
                        return params.data.name2 + ": " + params.value;
                    }
                },

                legend: {
                    type: "plain",
                    orient: "horizontal",
                    top: "10%", // Geser legend lebih ke bawah
                    left: "center",
                    data: pieData.map(item => item.name)
                },

                series: [{
                    name: "Kategori",
                    type: "pie",
                    center: ["50%", "55%"], // Pie chart dipindah ke bawah agar ada jarak dari title
                    radius: ["20%", "60%"], // Ukuran pie chart
                    data: pieData,
                    itemStyle: {
                        color: function(params) {
                            return colorMap[params.data.name];
                        }
                    },
                    label: {
                        show: true,
                        position: "outside",
                        formatter: function(params) {
                            return params.value + " = " + params.data.name2;
                        }
                    },
                    labelLine: {
                        show: true
                    }
                }]

            };


            pieChart.setOption(pieOption);

            window.addEventListener("resize", function() {
                pieChart.resize();
            });
        });
    </script>




    <style>
        #filterTable tbody td:hover {
            background-color: rgba(0, 123, 255, 0.2);
            /* Warna biru transparan */
            cursor: pointer;
        }

        .modal-dialog {
            width: 90%;
            /* Adjust width */
            max-width: 90%;
            /* Set max width to 75% */
            margin: auto;
            /* Center the modal horizontally */
        }

        .modal-content {
            height: auto;

        }

        #filterTable th,
        #filterTable td {
            text-align: center;
            vertical-align: middle;
        }

        .score-box {
            text-align: center;
            padding: 10px;
            border-radius: 10px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        /* Warna khusus untuk DONE (Hijau Soft) */
        .done {
            background-color: #ffffff;
            border: 2px solid #41e69b;
            color: #24a041;
        }

        /* Warna khusus untuk NOT YET (Merah Soft) */
        .not-yet {
            background-color: #ffffff;
            border: 2px solid #e2666e;
            color: #c72132;
        }

        /* Hover efek */
        .done:hover {
            background-color: #e8e8ee;
            transform: scale(1.05);
        }

        .not-yet:hover {
            background-color: #e8e8ee;
            transform: scale(1.05);
        }

        .score-title {
            font-size: 22px;
            font-weight: bold;
        }

        .score-value {
            font-size: 28px;
            font-weight: bold;
        }
    </style>


@endsection
