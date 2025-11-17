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
                                    DASHBOARD MONITORING HOLD LPN {{ $facility['Name'] }}
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
                                action="{{ route('admin.dashboard.inventory.summary_monitoringbarangrusak') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="mb-2">
                                            <div class="row">
                                                <label class="form-label">Date Hold</label>
                                                <div class="col-md-6 mb-2">
                                                    <input type="date" class="form-control" id="startDate"
                                                        name="start_date" required
                                                        value="{{ old('start_date', session('input_databr_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.start_date')) }}">
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <input type="date" class="form-control" id="endDate"
                                                        name="end_date" required
                                                        value="{{ old('end_date', session('input_databr_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.end_date')) }}">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="mb-2">
                                            <div class="row">
                                                <label class="form-label mb-2">Site</label>

                                                <!-- Button for Site modal -->
                                                <div class="col-md-4 mb-2">
                                                    <button type="button" class="btn btn-light w-100" id="buttonSite"
                                                        data-bs-toggle="modal" data-bs-target="#siteModal">Site</button>
                                                </div>
                                                <!-- Input for kode_site -->
                                                <div class="col-md-2 mb-2">
                                                    <input type="text" class="form-control" id="kodeSite"
                                                        name="kode_site" required readonly
                                                        value="{{ old('kode_site', session('input_databr_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.kode_site')) }}">
                                                </div>

                                                <!-- Input for nama_site -->
                                                <div class="col-md-6 mb-2">
                                                    <input type="text" class="form-control" id="namaSite"
                                                        name="nama_site" required readonly
                                                        value="{{ old('nama_site', session('input_databr_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.nama_site')) }}">
                                                </div>


                                                <!-- Hidden input for owner_site -->
                                                <div class="col-md-8 mb-2">
                                                    <input type="text" class="form-control" id="ownerSite"
                                                        name="owner_site" hidden
                                                        value="{{ old('owner_site', session('input_databr_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.owner_site')) }}">
                                                </div>
                                            </div>

                                        </div>

                                        <!-- Modal Bootstrap untuk form site -->
                                        <div class="modal fade" id="siteModal" tabindex="-1"
                                            aria-labelledby="siteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="siteModalLabel">Select Site</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table id="filterableTable" class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Storerkey</th>
                                                                    <th>Owner</th>
                                                                    <th>Company</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if (session()->has('datasites2_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi']))
                                                                    @foreach (session('datasites2_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi']) as $site)
                                                                        <tr data-storerkey="{{ $site->STORERKEY }}"
                                                                            data-owner="{{ $site->OWNER }}"
                                                                            data-company="{{ $site->COMPANY }}">
                                                                            <td>{{ $site->STORERKEY }}</td>
                                                                            <td>{{ $site->OWNER }}</td>
                                                                            <td>{{ $site->COMPANY }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    @foreach ($datasites2 as $site)
                                                                        <tr data-storerkey="{{ $site->STORERKEY }}"
                                                                            data-owner="{{ $site->OWNER }}"
                                                                            data-company="{{ $site->COMPANY }}">
                                                                            <td>{{ $site->STORERKEY }}</td>
                                                                            <td>{{ $site->OWNER }}</td>
                                                                            <td>{{ $site->COMPANY }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
                                                            </tbody>

                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <div class="row">
                                                <div class="col-md-4 mb-2">
                                                    <label class="form-label">Status ADF</label>
                                                    <select class="form-select" id="statusADF" name="status_adf" required>
                                                        <option value="All"
                                                            {{ old('status_adf', session('input_databr_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.status_adf')) == 'All' ? 'selected' : '' }}>
                                                            All</option>
                                                        <option value="Created"
                                                            {{ old('status_adf', session('input_databr_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.status_adf')) == 'Created' ? 'selected' : '' }}>
                                                            Created</option>
                                                        <option value="UnCreated"
                                                            {{ old('status_adf', session('input_databr_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.status_adf')) == 'UnCreated' ? 'selected' : '' }}>
                                                            UnCreated</option>
                                                    </select>
                                                </div>
                                                {{-- Type Order Button Isinya Check List Box --}}
                                                <div class="col-md-4 mb-2">
                                                    <label class="form-label d-block">Damage From</label>
                                                    <div class="position-relative">
                                                        <button type="button" class="btn btn-light w-100"
                                                            onclick="toggleDropdowndamage_from(event, 'damage_from-checkbox-list')"
                                                            id="dropdown-damage_from-button">
                                                            Click Me
                                                        </button>
                                                        <!-- Daftar Checkbox Dinamis -->
                                                        <div id="damage_from-checkbox-list" class="dropdown-menu"
                                                            style="display: none; position: absolute; top: 100%; left: 0; width: 100%; z-index: 1000;">
                                                            <!-- Kontainer Scroll untuk Checkbox -->
                                                            <div style="max-height: 100px; overflow-y: auto;">
                                                                <div class="form-check">
                                                                    <input type="checkbox" class="form-check-input"
                                                                        id="check_all_damage_from">
                                                                    <label class="form-check-label"
                                                                        for="check_all_damage_from">All</label>
                                                                </div>
                                                                <!-- Checkbox yang diisi dinamis dari Blade -->
                                                                @if (session()->has('damage_from_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi']))
                                                                    @foreach (session('damage_from_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi']) as $damage_from)
                                                                        @if (strcasecmp($damage_from->{'DAMAGE FROM'}, 'All') !== 0)
                                                                            <div class="form-check">
                                                                                <input type="checkbox"
                                                                                    class="form-check-input damage_from-checkbox"
                                                                                    name="selected_damage_from[]"
                                                                                    id="damage_from_{{ $loop->index }}"
                                                                                    value="{{ $damage_from->{'DAMAGE FROM'} }}">
                                                                                <label class="form-check-label"
                                                                                    for="damage_from_{{ $loop->index }}">{{ $damage_from->{'DAMAGE FROM'} }}</label>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <p class="text-warning">Data damage from tidak
                                                                        ditemukan. Silakan refresh data.</p>
                                                                @endif
                                                            </div>
                                                            <div class="d-flex mt-2" style="display: flex; gap: 5px;">
                                                                <button type="button" class="btn btn-primary"
                                                                    style="flex: 1; padding: 0.375rem 0.75rem; font-size: 0.875rem;"
                                                                    onclick="closeCheckboxListdamage_from()">OK</button>
                                                                <button type="button" class="btn btn-danger"
                                                                    style="flex: 1; padding: 0.375rem 0.75rem; font-size: 0.875rem;"
                                                                    onclick="clearCheckboxesdamage_from()">Clear</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="selected_damage_from"
                                                    name="selected_damage_from"
                                                    value="{{ implode(';', session('input_databr_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.selected_damage_from', [])) }}">

                                                <div class="col-md-4 mb-2">
                                                    <label class="form-label">Damage Kategori</label>
                                                    <select class="form-select" id="damageKategori"
                                                        name="damage_kategori" required>
                                                        <option value="All"
                                                            {{ session('input_databr_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.damage_kategori') == 'All' ? 'selected' : (old('damage_kategori') == 'All' ? 'selected' : '') }}>
                                                            All</option>

                                                        @if (isset($datakategori))
                                                            @foreach ($datakategori as $kategori)
                                                                <option value="{{ $kategori->{'DAMAGE KATEGORI'} }}"
                                                                    {{ session('input_databr_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.damage_kategori') == $kategori->{'DAMAGE KATEGORI'} ? 'selected' : (old('damage_kategori') == $kategori->{'DAMAGE KATEGORI'} ? 'selected' : '') }}>
                                                                    {{ $kategori->{'DAMAGE KATEGORI'} }}
                                                                </option>
                                                            @endforeach
                                                        @else
                                                            <option disabled>No damage sources available</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-2">
                                                    <label class="form-label">Hold Type</label>
                                                    <select class="form-select" id="damageType" name="damage_type"
                                                        required>
                                                        <option value="All"
                                                            {{ session('input_databr_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.damage_type') == 'All' ? 'selected' : (old('damage_type') == 'All' ? 'selected' : '') }}>
                                                            All</option>

                                                        @if (isset($datadamagetype))
                                                            @foreach ($datadamagetype as $type)
                                                                <option value="{{ $type->{'STATUS'} }}"
                                                                    {{ session('input_databr_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.damage_type') == $type->{'STATUS'} ? 'selected' : (old('damage_type') == $type->{'STATUS'} ? 'selected' : '') }}>
                                                                    {{ $type->{'STATUS'} }}
                                                                </option>
                                                            @endforeach
                                                        @else
                                                            <option disabled>No damage type available</option>
                                                        @endif
                                                    </select>
                                                </div>

                                                <div class="col-md-4 mb-2">
                                                    <label class="form-label">View By</label>
                                                    <select class="form-select" id="statusView" name="status_view"
                                                        required>
                                                        <option value="LPN"
                                                            {{ old('status_view', session('input_databr_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.status_view')) == 'LPN' ? 'selected' : '' }}>
                                                            LPN</option>
                                                        <option value="Qty"
                                                            {{ old('status_view', session('input_databr_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.status_view')) == 'Qty' ? 'selected' : '' }}>
                                                            Qty</option>
                                                        <option value="Value"
                                                            {{ old('status_view', session('input_databr_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.status_view')) == 'Value' ? 'selected' : '' }}>
                                                            Value</option>
                                                    </select>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <div class="row">
                                                <div class="col-md-4 mb-2">
                                                    <button type="submit" class="btn btn-info w-100 mt-2">Show</button>
                                                </div>
                                                <div class="col-md-4 mb-2">
                                                    <button type="button" id="exportButton2"
                                                        class="btn btn-success w-100 mt-2">Export</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Column for Summary Table -->
                                    <div class="col-md-7">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5>Summary</h5>
                                            <div class="total-value-box">
                                                <strong>Total Value : </strong>
                                                Rp.{{ number_format($dataTable3[0]['TOTALPRICE'] ?? 0, 0, ',', '.') }}
                                            </div>

                                        </div>
                                        <div class="row">
                                            <!-- Column kedua untuk tabel data -->
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="table-responsive summary-table">
                                                            <table class="table mb-0">
                                                                @if (isset($dataTable2) && !empty($dataTable2))
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            @foreach ($tableHeaders2 as $header)
                                                                                <th
                                                                                    class="{{ $header === 'DAMAGE FROM' ? 'text-wrap damage-column' : 'text-wrap' }}">
                                                                                    {{ $header }}
                                                                                </th>
                                                                            @endforeach
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($dataTable2 as $item)
                                                                            <tr>
                                                                                @foreach ($tableHeaders2 as $header)
                                                                                    @php
                                                                                        // Default alignment to right for all columns
                                                                                        $alignmentClass2 = 'text-end';
                                                                                        $formattedValue2 =
                                                                                            $item[$header] ?? '-'; // Default value for missing data

                                                                                        // Check if the header is 'DAMAGE FROM' for special left alignment
                                                                                        if ($header === 'DAMAGE FROM') {
                                                                                            $alignmentClass2 =
                                                                                                'text-start'; // Left aligned for DAMAGE FROM
                                                                                        } else {
                                                                                            // Format numeric values for all other columns
                                                                                            if (
                                                                                                is_numeric(
                                                                                                    $formattedValue2,
                                                                                                )
                                                                                            ) {
                                                                                                $formattedValue2 = number_format(
                                                                                                    floatval(
                                                                                                        $formattedValue2,
                                                                                                    ),
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
                                    </div>





                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row pertama -->
            <!-- Row kedua untuk tabel data dan grafik -->
            <div class="row">
                <!-- Column pertama untuk tabel data dt-responsive-->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="datatable1" class="table nowrap w-100">
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
                                                        // Set the alignment class based on the header
                                                        $alignmentClass = 'text-start'; // default to left align
                                                        $formattedValue = $item[$header] ?? '-';
                                                        if ($header === 'QTY' || $header === 'AGING (DAY)') {
                                                            $alignmentClass = 'text-end';
                                                            $value = (float) $formattedValue;
                                                            $formattedValue =
                                                                floatval($value) == intval($value)
                                                                    ? number_format(intval($value))
                                                                    : number_format($value, 2);
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

            <!-- end row kedua -->

        </div> <!-- container-fluid -->
    </div>
    <script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/damagefrom-dropdown.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable di dalam modal
            var table = $('#filterableTable').DataTable({
                scrollY: '400px', // Tabel akan memiliki tinggi maksimum 400px dan scroll vertikal
                scrollCollapse: true, // Aktifkan scroll collapse jika data kurang dari tinggi tabel
                paging: true, // Aktifkan fitur paginasi
                searching: true, // Aktifkan fitur pencarian
                info: true, // Tampilkan informasi jumlah data
                order: [], // Nonaktifkan sorting default
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
                },
                initComplete: function(settings, json) {
                    console.log("DataTable initialized");

                    // Menyesuaikan kolom header dengan lebar kolom body setelah DataTable ter-render
                    setTimeout(function() {
                        console.log("Adjusting column widths...");

                        $('#filterableTable thead th').each(function(index) {
                            var columnWidth = $('#filterableTable tbody tr:first td')
                                .eq(index).outerWidth();
                            $(this).css('width', columnWidth);
                            console.log("Column " + index + " width adjusted: " +
                                columnWidth);
                        });

                        // Adjust columns and make responsive
                        table.columns.adjust().responsive.recalc();
                        console.log(
                            "Columns adjusted and responsive recalculated after initComplete"
                        );
                    }, 500); // Timeout untuk memastikan DataTable ter-render dengan baik
                }
            });

            // Event listener untuk klik baris
            $('#filterableTable tbody').on('click', 'tr', function() {
                console.log("Row clicked");
                console.log("Data attributes: ", $(this).data());

                var storerkey = $(this).data('storerkey');
                var owner = $(this).data('owner');
                var company = $(this).data('company');

                $('#kodeSite').val(storerkey);
                $('#ownerSite').val(owner);
                $('#namaSite').val(company);
                $('#siteModal').modal('hide');
            });

            // Event listener untuk modal show, menyesuaikan kolom setelah modal terbuka
            $('#siteModal').on('shown.bs.modal', function() {
                console.log("Modal is now visible");

                // Pastikan DataTable di-render ulang setelah modal terbuka
                table.columns.adjust().responsive.recalc();
                console.log("Columns adjusted and responsive recalculated after modal show");
            });

            $('#datatable1').DataTable({
                scrollX: true,
                autoWidth: false,
                paging: true, // Atur sesuai kebutuhan
                responsive: false
            });

            $('#datatable2').DataTable(); // Untuk tabel kedua 
            $('#datatable3').DataTable(); // Untuk tabel kedua 

            $('#datatable2 tbody').on('click', 'tr', function() {
                console.log("Row clicked");
                console.log("Data attributes: ", $(this).data());

                var storerkey = $(this).data('storerkey');
                var owner = $(this).data('owner');
                var company = $(this).data('company');

                $('#kodeSite').val(storerkey);
                $('#ownerSite').val(owner);
                $('#namaSite').val(company);
                $('#siteModal').modal('hide');
            });



        });
    </script>

    <style>
        /* .active-table thead th
                    {
                        background-color: rgb(74, 146, 194); /* Warna latar belakang header saat tabel aktif */
        /* color: white; Warna teks header agar kontras */
        /* } */

        .summary-table {
            max-height: 350px;
            /* Tinggi maksimal untuk tabel summary */
            overflow-y: auto;
            /* Scroll vertikal */
        }

        .summary-table .table {
            table-layout: fixed;
            width: 100%;
        }

        .summary-table th {
            white-space: normal;
            /* Header wrap text */
            word-wrap: break-word;
            text-align: center;
            font-size: 14px;
            padding: 8px;
        }

        .summary-table td {
            white-space: nowrap;
            /* Isi tidak wrap text */
            font-size: 14px;
            padding: 12px;
            text-align: right;
        }

        .summary-table .damage-column {
            width: 200px;
            /* Lebar khusus kolom DAMAGE FROM */
        }

        .summary-table th:not(.damage-column) {
            width: 80px;
            /* Lebar tetap untuk kolom angka */
        }



        #filterableTable th,
        #filterableTable td {
            width: auto !important;
        }



        .total-value-box {
            background-color: #f0f0f0;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            color: #333;
            font-weight: bold;
        }
    </style>



@endsection
