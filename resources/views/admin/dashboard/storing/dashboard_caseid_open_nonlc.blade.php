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
                                <h3 style="font-size: 1.5rem; font-weight: bold; text-align: center;">
                                    DASHBOARD CASEID OPEN NON-LC <br> {{ $facility['Name'] }}
                                </h3>


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
                                action="{{ route('admin.dashboard.storing.summary_dashboardcaseidopennonlc') }}">
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

                                    <!-- Owner Button Isinya Check List Box-->
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
                                        value="{{ implode(';', session('input_data_caseidopennonlc_' . Auth::guard('admin')->id() . '.selected_owners', [])) }}">

                                    {{-- Type Order Button Isinya Check List Box --}}
                                    <div class="col-6 col-md-2 mb-3">
                                        <label class="form-label d-block">Order Type</label>
                                        <div class="position-relative">
                                            <button type="button" class="btn btn-light w-100"
                                                onclick="toggleDropdownTypeOrder(event, 'typeorder-checkbox-list')"
                                                id="dropdown-typeorder-button">
                                                Type
                                            </button>
                                            <!-- Daftar Checkbox Dinamis -->
                                            <div id="typeorder-checkbox-list" class="dropdown-menu"
                                                style="display: none; position: absolute; top: 100%; left: 0; width: 100%; z-index: 1000;">
                                                <!-- Kontainer Scroll untuk Checkbox -->
                                                <div style="max-height: 100px; overflow-y: auto;">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="check_all_typeorder">
                                                        <label class="form-check-label"
                                                            for="check_all_typeorder">All</label>
                                                    </div>
                                                    <!-- Checkbox yang diisi dinamis dari Blade -->
                                                    @if (session()->has('datatypeorder_' . Auth::guard('admin')->id()))
                                                        @foreach (session('datatypeorder_' . Auth::guard('admin')->id()) as $typeorder)
                                                            @if (strcasecmp($typeorder->TYPE, 'All') !== 0)
                                                                <div class="form-check">
                                                                    <input type="checkbox"
                                                                        class="form-check-input typeorder-checkbox"
                                                                        name="selected_typeorders[]"
                                                                        id="typeorder_{{ $loop->index }}"
                                                                        value="{{ $typeorder->TYPE }}">
                                                                    <label class="form-check-label"
                                                                        for="typeorder_{{ $loop->index }}">{{ $typeorder->TYPE }}</label>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <p class="text-warning">Data type order tidak ditemukan. Silakan
                                                            refresh data.</p>
                                                    @endif
                                                </div>
                                                <div class="d-flex mt-2" style="display: flex; gap: 5px;">
                                                    <button type="button" class="btn btn-primary"
                                                        style="flex: 1; padding: 0.375rem 0.75rem; font-size: 0.875rem;"
                                                        onclick="closeCheckboxListTypeOrder()">OK</button>
                                                    <button type="button" class="btn btn-danger"
                                                        style="flex: 1; padding: 0.375rem 0.75rem; font-size: 0.875rem;"
                                                        onclick="clearCheckboxesTypeOrder()">Clear</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="selected_typeorders" name="selected_typeorders"
                                        value="{{ implode(';', session('input_data_caseidopennonlc_' . Auth::guard('admin')->id() . '.selected_typeorders', [])) }}">
                                </div>

                                <div class="col-6 col-md-2 mb-3">

                                    <!-- Tombol Show dengan Spinner di dalamnya -->
                                    <!-- Show Button with spinner inside -->
                                    <button type="submit" class="btn btn-info w-100 mt-2" id="showButton">
                                        Show
                                        <div class="spinner"></div> <!-- Spinner inside the button -->
                                    </button>


                                </div>

                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- end row pertama -->
            <div class="row">
                @if (isset($dataTable2[0]))
                    @php
                        $summary = $dataTable2[0]; // Ambil data dari tabel2
                    @endphp
                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-h-100">
                            <!-- card body -->
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-6">
                                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Order</span>
                                        <h3 class="mb-3">
                                            <span class="counter-value"
                                                data-target={{ $summary['TotalOrder'] ?? 0 }}>0</span>
                                        </h3>
                                    </div>

                                    <div class="col-6">
                                        <div id="mini-chart1" data-colors='["#5156be"]' class="apex-charts mb-2"></div>
                                    </div>
                                </div>
                                {{-- <div class="text-nowrap">
                                    <span class="badge bg-success-subtle text-success">+$20.9k</span>
                                    <span class="ms-1 text-muted font-size-13">Since last week</span>
                                </div> --}}
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-h-100">
                            <!-- card body -->
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-6">
                                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Case ID</span>
                                        <h3 class="mb-3">
                                            <span class="counter-value"
                                                data-target={{ $summary['TotalCaseID'] ?? 0 }}>0</span>
                                        </h3>
                                    </div>
                                    <div class="col-6">
                                        <div id="mini-chart2" data-colors='["#5156be"]' class="apex-charts mb-2"></div>
                                    </div>
                                </div>
                                {{-- <div class="text-nowrap">
                                    <span class="badge bg-danger-subtle text-danger">-29 Trades</span>
                                    <span class="ms-1 text-muted font-size-13">Since last week</span>
                                </div> --}}
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col-->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-h-100">
                            <!-- card body -->
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-6">
                                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Total SKU</span>
                                        <h3 class="mb-3">
                                            <span class="counter-value"
                                                data-target="{{ $summary['TotalSKU'] ?? 0 }}">0</span>
                                        </h3>
                                    </div>
                                    <div class="col-6">
                                        <div id="mini-chart3" data-colors='["#5156be"]' class="apex-charts mb-2"></div>
                                    </div>
                                </div>
                                {{-- <div class="text-nowrap">
                                    <span class="badge bg-success-subtle text-success">+ $2.8k</span>
                                    <span class="ms-1 text-muted font-size-13">Since last week</span>
                                </div> --}}
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-h-100">
                            <!-- card body -->
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-6">
                                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Total QTY</span>
                                        <h3 class="mb-3">
                                            <span class="counter-value"
                                                data-target="{{ $summary['TotalQTY'] ?? 0 }}">0</span>
                                        </h3>
                                    </div>
                                    <div class="col-6">
                                        <div id="mini-chart4" data-colors='["#5156be"]' class="apex-charts mb-2"></div>
                                    </div>
                                </div>
                                {{-- <div class="text-nowrap">
                                    <span class="badge bg-success-subtle text-success">+2.95%</span>
                                    <span class="ms-1 text-muted font-size-13">Since last week</span>
                                </div> --}}
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                @else
                @endif

            </div><!-- end row-->

            <div class="row">

                <!-- Column pertama untuk tabel data dt-responsive-->
                <div class="col-12">
                    @if (isset($dataTable1) && !empty($dataTable1))
                        <div class="card">
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table id="filterTable" class="table table-striped table-bordered nowrap"
                                        style="width: 100%;">

                                        <thead class="table-light">
                                            <tr>
                                                @foreach ($tableHeaders1 as $header)
                                                    <th class="text-center">{{ $header }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dataTable1 as $item)
                                                <tr>
                                                    @foreach ($tableHeaders1 as $header)
                                                        @php
                                                            // Set the alignment class based on the header
                                                            $alignmentClass = 'text-start'; // default to left align
                                                            $formattedValue = $item[$header] ?? '-';
                                                            if ($header !== 'Tanggal') {
                                                                $alignmentClass = 'text-end';
                                                                $value = (float) $formattedValue;
                                                                $formattedValue =
                                                                    floatval($value) == intval($value)
                                                                        ? number_format(intval($value))
                                                                        : number_format($value, 0);
                                                            }
                                                        @endphp
                                                        <td class="{{ $alignmentClass }}">
                                                            {{ $formattedValue }}
                                                        </td>
                                                        {{-- <td class="text-start">{{ $item[$header] ?? '-' }}</td> --}}
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <thead class="table-light">
                            <tr>
                                {{-- <th>No Data Available</th> --}}
                            </tr>
                        </thead>
                    @endif
                </div>

            </div>

            <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 90%; margin: auto;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitle">Detail Data</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
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

            <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 50%; margin: auto;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="commentModalLabel">Add Comment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="commentForm">
                                <input type="hidden" id="commentDate">
                                <div class="mb-3">
                                    <label for="commentText" class="form-label">Your Comment</label>
                                    <textarea class="form-control" id="commentText" rows="3"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container-fluid -->
    </div>


    <script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/owner-dropdown.js') }}"></script>
    <script src="{{ asset('backend/assets/js/typeorder-dropdown.js') }}"></script>
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

                // Tampilkan spinner dan disable tombol Show
                $('#showButton').addClass('loading'); // Menambahkan kelas loading
                $('#showButton .spinner').show(); // Menampilkan spinner

                let table = $('#filterTable').DataTable();
                let colIndex = $(this).index();
                let rowIndex = $(this).closest('tr').index();

                if (colIndex >= 1 && colIndex <= 5) {
                    let key1 = table.cell(rowIndex, 0).data() || '';
                    let headerCell = table.column(colIndex).header();
                    let key2 = headerCell ? headerCell.textContent.trim() : '';

                    let startDate = $('input[name="start_date"]').val();
                    let endDate = $('input[name="end_date"]').val();

                    //console.log("Key1:", key1, "Key2:", key2);
                    // console.log("Start Date:", startDate, "End Date:", endDate);
                    // console.log("Mengirim Data ke Server:", {
                    //     _token: "{{ csrf_token() }}",
                    //     tanggal: key1,
                    //     status: key2,
                    //     start_date: startDate,
                    //     end_date: endDate
                    // });

                    $.ajax({
                        url: "{{ route('admin.dashboard.storing.detail_dashboardcaseidopennonlc') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            tanggal: key1,
                            status: key2,
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
                            $('#showButton').removeClass('loading'); // Menghapus kelas loading
                            $('#showButton .spinner').hide(); // Menyembunyikan spinner
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

        /* #filterTable {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
                                                                                                                                                                                table-layout: fixed;
                                                                                                                                                                                width: 100%;
                                                                                                                                                                                word-wrap: break-word;
                                                                                                                                                                                } */

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

        /* Spinner */
        .spinner {
            border: 4px solid #f3f3f3;
            /* Background spinner */
            border-top: 4px solid #3498db;
            /* Spinner color */
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            position: absolute;
            top: 10%;
            left: 50%;
            transform: translate(-50%, -50%);
            /* Memastikan spinner berada di tengah tombol */
            display: none;
            /* Sembunyikan spinner awalnya */
        }

        /* Animasi spinner berputar */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Menonaktifkan tombol saat spinner ditampilkan */
        button.loading {
            pointer-events: none;
            /* Menonaktifkan tombol saat spinner muncul */
            opacity: 0.7;
            /* Membuat tombol agak transparan saat loading */
        }

        /* Tombol harus memiliki posisi relative agar spinner bisa diatur di dalamnya */
        button {
            position: relative;
            /* Agar spinner bisa diatur di dalam tombol */
            padding: 15px 20px;
            /* Padding tombol untuk ukuran yang cukup */
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            /* Beri jarak sedikit jika diperlukan antara teks dan spinner */
        }
    </style>
@endsection
