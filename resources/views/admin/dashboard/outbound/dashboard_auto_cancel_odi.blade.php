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
                                    DASHBOARD AUTO CANCEL ODI {{ $facility['Name'] }}
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
                                action="{{ route('admin.dashboard.outbound.summary_autocancelodi') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-6 col-md-2 mb-3">

                                        <label class="form-label">Periode</label>
                                        <input type="date" class="form-control" id="startDate" name="start_date" required
                                            value="{{ old('start_date', session('input_dataac_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.start_date')) }}">
                                    </div>

                                    <div class="col-6 col-md-2 mb-3">
                                        <label class="form-label d-block">Owner</label>
                                        <div class="position-relative">
                                            <button type="button" class="btn btn-light w-100"
                                                onclick="toggleDropdown(event, 'owner-checkbox-list')"
                                                id="dropdown-owner-button">
                                                Owners
                                            </button>
                                            <!-- Daftar Checkbox Dinamis -->
                                            <div id="owner-checkbox-list" class="dropdown-menu"
                                                style="display: none; position: absolute; top: 100%; left: 0; width: 100%; z-index: 1000;">
                                                <!-- Kontainer Scroll untuk Checkbox -->
                                                <div style="max-height: 100px; overflow-y: auto;">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="check_all">
                                                        <label class="form-check-label" for="check_all">All</label>
                                                    </div>
                                                    <!-- Checkbox yang diisi dinamis dari Blade -->
                                                    @if (session()->has('dataowner_' . Auth::guard('admin')->id()))
                                                        @foreach (session('dataowner_' . Auth::guard('admin')->id()) as $owner)
                                                            @if (strcasecmp($owner->Owner, 'All') !== 0)
                                                                <div class="form-check">
                                                                    <input type="checkbox"
                                                                        class="form-check-input owner-checkbox"
                                                                        name="selected_owners[]"
                                                                        id="Owner_{{ $loop->index }}"
                                                                        value="{{ $owner->Owner }}">
                                                                    <label class="form-check-label"
                                                                        for="Owner_{{ $loop->index }}">{{ $owner->Owner }}</label>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <p class="text-warning">Data owner tidak ditemukan. Silakan refresh
                                                            data.</p>
                                                    @endif

                                                </div>
                                                <div class="d-flex mt-2" style="display: flex; gap: 5px;">
                                                    <button type="button" class="btn btn-primary"
                                                        style="flex: 1; padding: 0.375rem 0.75rem; font-size: 0.875rem;"
                                                        onclick="closeCheckboxList()">OK</button>
                                                    <button type="button" class="btn btn-danger"
                                                        style="flex: 1; padding: 0.375rem 0.75rem; font-size: 0.875rem;"
                                                        onclick="clearCheckboxes()">Clear</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="selected_owners" name="selected_owners"
                                        value="{{ implode(';', session('input_dataac_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.selected_owners', [])) }}">

                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-2 mb-3">
                                        <button type="submit" class="btn btn-info w-100 mt-2">Show</button>
                                    </div>
                                    <!-- <div class="col-6 col-md-2 mb-3">
                                                                                                    <button type="button" id="exportButton2"
                                                                                                        class="btn btn-success w-100 mt-2">Export</button>
                                                                                                </div> -->
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row pertama -->

            <div class="row">
                <!-- Column pertama untuk tabel data dt-responsive-->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="filterTable" class="table table-striped table-bordered nowrap"
                                    style="width: 100%;">
                                    @if (isset($dataTable1) && !empty($dataTable1))
                                        <thead class="table-light">
                                            <tr>
                                                @foreach ($tableHeaders1 as $header)
                                                    <th>{{ $header }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dataTable1 as $item)
                                                <tr>
                                                    @foreach ($tableHeaders1 as $header)
                                                        @php
                                                            $alignmentClass = 'text-start'; // Default kiri
                                                            $formattedValue = $item[$header] ?? '-';
                                                            if ($header !== 'KURIR') {
                                                                $alignmentClass = 'text-end';
                                                                $value = (float) $formattedValue;
                                                                $formattedValue =
                                                                    floatval($value) == intval($value)
                                                                        ? number_format(intval($value))
                                                                        : number_format($value, 2);
                                                            }
                                                        @endphp
                                                        <td class="{{ $alignmentClass }}">{{ $formattedValue }}</td>
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
    <script src="{{ asset('backend/assets/js/owner-dropdown.js') }}"></script>
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

                if (colIndex > 0) {
                    let key1 = table.cell(rowIndex, 0).data() || '';
                    let headerCell = table.column(colIndex).header();
                    let key2 = headerCell ? headerCell.textContent.trim() : '';

                    // console.log("Key1:", key1, "Key2:", key2);
                    // console.log("Mengirim Data ke Server:", {
                    //     _token: "{{ csrf_token() }}",
                    //     kurir: key1,
                    //     jam: key2
                    // });

                    $.ajax({
                        url: "{{ route('admin.dashboard.outbound.detail_autocancelodi') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            kurir: key1,
                            jam: key2
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
                                    {
                                        extend: 'pdf',
                                        text: 'PDF',
                                        className: 'btn btn-success'
                                    },
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
                                                // console.log("Column " +
                                                //     index +
                                                //     " width adjusted: " +
                                                //     columnWidth);
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
    <style>
        /* Header tetap di atas */
        /* Header tetap di atas */
        #filterTable_wrapper .dataTables_scrollHead {
            position: sticky;
            top: 0;
            background: white;
            z-index: 1000;
        }

        /* Kolom pertama di dalam tbody tetap freeze */
        #filterTable_wrapper .dataTables_scrollBody .freeze-column {
            position: sticky !important;
            left: 0 !important;
            background: white;
            z-index: 1000;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        /* Kolom pertama di dalam thead juga harus freeze */
        #filterTable_wrapper .dataTables_scrollHeadInner table thead th:first-child {
            position: sticky !important;
            left: 0 !important;
            background: white;
            z-index: 1050;
            /* Lebih tinggi agar tidak tertutup */
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        #filterTable tbody tr:hover td {
            background-color: rgba(0, 123, 255, 0.2);
            /* Warna biru transparan saat hover */
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
            /* Ensure the content height adjusts to its content */
        }
    </style>


@endsection
