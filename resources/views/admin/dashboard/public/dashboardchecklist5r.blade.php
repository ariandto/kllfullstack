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
                                    DASHBOARD CHECKLIST 5R {{ $facility['Name'] }}
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
                            {{-- method="POST" action="{{ route('admin.dashboard.inventory.summary_monitoringbarangrusak') }}"           --}}
                            <form id="dashboardform" class="row g-3 align-items-end" method="POST"
                                action="{{ route('admin.dashboard.public.dashboardchecklist5r_submit') }}">
                                @csrf

                                @php
                                    $selectedOwners = session(
                                        'input_datad5r_' .
                                            Auth::guard('admin')->id() .
                                            '_' .
                                            session('facility_info')[0]['Relasi'] .
                                            '.selected_owners',
                                        [],
                                    );
                                    if (is_string($selectedOwners)) {
                                        $selectedOwners = explode(';', $selectedOwners);
                                    }
                                @endphp

                                <input type="hidden" id="selected_owners" name="selected_owners"
                                    value="{{ implode(';', $selectedOwners) }}">

                                <!-- Start Date -->
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="startDate" name="start_date" required
                                        value="{{ old('start_date', session('input_datad5r_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.start_date')) }}">
                                </div>

                                <!-- End Date -->
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <label class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="endDate" name="end_date" required
                                        value="{{ old('end_date', session('input_datad5r_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.end_date')) }}">
                                </div>

                                <!-- Owner Button -->
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <label class="form-label d-block">Owner</label>
                                    <div class="position-relative">
                                        <button type="button" class="btn btn-light w-100"
                                            onclick="toggleDropdown(event, 'owner-checkbox-list')"
                                            id="dropdown-owner-button">
                                            Owners
                                        </button>
                                        <div id="owner-checkbox-list" class="dropdown-menu"
                                            style="display: none; position: absolute; top: 100%; left: 0; width: 100%; z-index: 1000;">
                                            <div style="max-height: 100px; overflow-y: auto;">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="check_all">
                                                    <label class="form-check-label" for="check_all">All</label>
                                                </div>
                                                <!-- Checkbox yang diisi dinamis dari Blade -->
                                                @if (session()->has('dataowner5r_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi']))
                                                    @foreach (session('dataowner5r_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi']) as $owner)
                                                        @if (strcasecmp($owner->Owner, 'All') !== 0)
                                                            <div class="form-check">
                                                                <input type="checkbox"
                                                                    class="form-check-input owner-checkbox"
                                                                    name="selected_owners[]" id="Owner_{{ $loop->index }}"
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

                                <!-- Status -->
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" id="status5r" name="status5r" required>
                                        <option value="All"
                                            {{ old('status5r', session('input_datad5r_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.status5r')) == 'All' ? 'selected' : '' }}>
                                            All</option>
                                        <option value="Checked"
                                            {{ old('status5r', session('input_datad5r_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.status5r')) == 'Checked' ? 'selected' : '' }}>
                                            Checked</option>
                                        <option value="Pending"
                                            {{ old('status5r', session('input_datad5r_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.status5r')) == 'Pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="NotChecked"
                                            {{ old('status5r', session('input_datad5r_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.status5r')) == 'NotChecked' ? 'selected' : '' }}>
                                            Not Checked</option>
                                    </select>
                                </div>

                                <!-- Dept Name -->
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <label class="form-label">Dept Name</label>
                                    <select class="form-select" id="dept5r" name="dept5r" required>
                                        <option value="All"
                                            {{ session('input_datad5r_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.dept5r') == 'All' ? 'selected' : (old('dept5r') == 'All' ? 'selected' : '') }}>
                                            All</option>
                                        @if (isset($datedept5r))
                                            @foreach ($datedept5r as $deptname)
                                                <option value="{{ $deptname->Departement }}"
                                                    {{ session('input_datad5r_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.dept5r') == $deptname->Departement ? 'selected' : (old('dept5r') == $deptname->Departement ? 'selected' : '') }}>
                                                    {{ $deptname->Departement }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option disabled>No data sources available</option>
                                        @endif
                                    </select>
                                </div>

                                <!-- Area -->
                                <div class="col-lg-2 col-md-4 col-sm-6">
                                    <label class="form-label">Area</label>
                                    <select class="form-select" id="area5r" name="area5r" required>
                                        <option value="All"
                                            {{ session('input_datad5r_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.area5r') == 'All' ? 'selected' : (old('area5r') == 'All' ? 'selected' : '') }}>
                                            All</option>

                                        @if (isset($dataarea5r))
                                            @foreach ($dataarea5r as $areaname)
                                                <option value="{{ $areaname->Area }}"
                                                    {{ session('input_datad5r_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.area5r') == $areaname->Area ? 'selected' : (old('area5r') == $areaname->Area ? 'selected' : '') }}>
                                                    {{ $areaname->Area }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option disabled>No data sources available</option>
                                        @endif
                                    </select>
                                </div>

                                <!-- Buttons -->
                                <div class="col-lg-4 col-md-6 d-flex gap-2">
                                    <button type="submit" class="btn btn-info w-100">Show</button>
                                    <button type="button" id="exportButton2" class="btn btn-success w-100">Export</button>
                                </div>
                            </form>


                        </div>
                    </div>
                </div>
            </div>





            <!-- end row pertama -->
            <div class="row">
                <!-- Kolom pertama: Tabel data -->
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Outstanding Checklist 5R</h5>
                            <div id="pie-chart" data-colors='["#fd625e", "#2ab57d", "#4ba6ef", "#ffbf53", "#5156be"]'
                                class="e-charts"></div>
                            {{-- Buatkan Pie Chart Disini Saya Pake E-Chart, Warna Hijau Muda, Kuning Agak Orange, Merah Smooth.   Hijau Checked, Kuning Pending, Merah UnChecked --}}
                        </div>
                    </div>
                </div>

                <!-- Kolom kedua: Chart atau konten lain -->
                <div class="col-lg-8 col-md-8 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Achievement Checklist 5R</h5>
                            <div id="mix-line-bar" class="e-charts" style="width: 100%;"></div>
                            {{-- Buatkan Chart yang mana isinya HIT dan MISS, HIT Hijau Muda dan MISS Merah Snooth, Type Nya Stacked Column Jadi Satu Chart itu Atas bawah  --}}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row kedua untuk tabel data dan grafik -->
            <div class="row">
                <!-- Column pertama untuk tabel data dt-responsive-->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="datatable1" class="table table-striped">
                                <colgroup>
                                    <col style="width: 120px;"> <!-- Lebar untuk Periode -->
                                    <col style="width: 100px;"> <!-- Lebar untuk Dept -->
                                    <col style="width: 100px;"> <!-- Lebar untuk Area -->
                                    <col style="width: 200px;"> <!-- Lebar untuk Ringkas -->
                                    <col style="width: 200px;"> <!-- Lebar untuk Rapi -->
                                    <col style="width: 200px;"> <!-- Lebar untuk Resik -->
                                    <col style="width: 200px;"> <!-- Lebar untuk Rawat -->
                                    <col style="width: 200px;"> <!-- Lebar untuk Rajin -->
                                    <col style="width: 100px;"> <!-- Lebar untuk Status -->
                                    <col style="width: 100px;"> <!-- Lebar untuk Shift -->
                                    <col style="width: 100px;"> <!-- Lebar untuk CheckPoint -->
                                    <col style="width: 100px;"> <!-- Lebar untuk Maks Jam Check -->
                                    <col style="width: 100px;"> <!-- Lebar untuk Achievement -->
                                </colgroup>
                                @if (isset($dataTable3) && !empty($dataTable3))
                                    <thead class="table-info">
                                        <tr>
                                            @foreach ($tableHeaders3 as $header)
                                                <th class="text-center">{{ $header }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataTable3 as $item)
                                            <tr>
                                                @foreach ($tableHeaders3 as $header)
                                                    <td
                                                        class="text-start {{ in_array($header, ['Shift', 'CheckPoint']) ? 'text-end' : 'text-start' }}">
                                                        {{-- style="vertical-align: middle;" --}}
                                                        {{ $item[$header] ?? '-' }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                @else
                                    <thead class="table-info">
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

            <div id="detailsModal" class="modal fade" tabindex="-1" aria-labelledby="detailsModalLabel">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 90%; margin: auto;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailsModalLabel">Detail Checklist</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table id="modalTable" class="table table-striped table-bordered nowrap" style="width:100%">

                                <thead id="modalTableHeader">
                                    <!-- Headers will be dynamically added here -->
                                </thead>
                                <tbody id="modalTableBody">
                                    <!-- Data will be dynamically added here -->
                                </tbody>
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
    <!-- Load the custom script -->
    <script src="{{ asset('backend/assets/js/owner-dropdown.js') }}"></script>
    <script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#datatable1').DataTable({
                scrollX: true,
                autoWidth: true,
                paging: true, // Atur sesuai kebutuhan
                responsive: false
            });


        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if dataTable1 is set and not empty, otherwise default to values of 0
            @if (isset($dataTable1) && count($dataTable1) > 0)
                const rawPieData = @json($dataTable1); // Dapatkan data dari PHP

                // Ambil objek pertama dalam array (asumsi hanya satu objek)
                const pieDataObject = rawPieData[0];
                // Hitung total dari semua status
                const total = Object.values(pieDataObject).reduce((acc, value) => acc + parseInt(value, 10), 0);

                // Ubah objek menjadi array yang sesuai untuk ECharts
                const pieData = Object.entries(pieDataObject).map(([key, value]) => ({
                    name: key,
                    value: parseInt(value, 10) // Pastikan untuk mengonversi string ke integer
                }));
                // Dapatkan nama dari pieData untuk legend
                const legendData = pieData.map(item => item.name); // Ambil nama dari pieData

                // console.log(pieData); // Debugging untuk memastikan format yang benar
                // console.log(legendData); // Debugging untuk memastikan format yang benar

                const pieChart = echarts.init(document.getElementById('pie-chart'));
                const pieOption = {
                    title: {
                        text: `Total: ${total}`,
                        left: 'right',
                        textStyle: {
                            fontFamily: 'Poppins, sans-serif', // Ganti dengan font default dari MINIA
                            fontSize: 16, // Sesuaikan ukuran font jika perlu
                            fontWeight: 'bold', // Sesuaikan ketebalan font jika perlu
                            color: '#333333' // Warna yang sesuai dengan tema
                        }
                    },
                    tooltip: {
                        trigger: 'item'
                    },
                    legend: {
                        orient: 'horizontal', // Horizontal orientation
                        bottom: '0%', // Place at the bottom
                        data: legendData,
                    },
                    series: [{
                        name: 'Checklist Status',
                        type: 'pie',
                        radius: '70%', // Radius for the pie chart
                        center: ['50%', '50%'], // Center position
                        data: pieData,
                        color: ['#2ab57d', '#ffbf53', '#fd625e'], // Hijau, Kuning, Merah
                        emphasis: {
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        },
                        label: {
                            show: true,
                            formatter: '{c}', // Format label: nama: nilai {b}: 
                            position: 'outside', // Position inside the pie slices
                            fontSize: 14, // Increase font size
                            fontWeight: 'bold', // Make font bold
                            color: '#7d7d7d' // Change color to white
                        },
                        itemStyle: {
                            borderColor: '#ffffff', // Color of the border (separator)
                            borderWidth: 2 // Width of the border
                        },
                        // Memberikan offset untuk pemisahan antara potongan
                        animationType: 'scale',
                        animationEasing: 'elasticOut',
                        animationDelay: (idx) => idx * 100,
                        itemStyle: {
                            borderColor: '#ffffff',
                            borderWidth: 3,
                        },
                        labelLine: {
                            show: true,
                            length: 10, // Panjang garis label ke potongan
                            lineDash: [], // Menghapus garis putus-putus
                        },
                    }]
                };

                pieChart.setOption(pieOption);
                // Tambahkan event handler untuk klik pada pie chart  

                pieChart.on('click', function(params) {
                    let statusclick = params.name; // Gunakan 'let' agar nilainya bisa diubah

                    // Cek apakah status adalah "Not Checked" dan ubah jika perlu
                    if (statusclick === "Not Checked") {
                        statusclick = "NOTCHECKED";
                    }

                    console.log(statusclick);


                    console.log(statusclick);

                    const startDate = $('#startDate').val();
                    const endDate = $('#endDate').val();
                    const selectedOwners = $('input[name="selected_owners[]"]:checked')
                        .map(function() {
                            return $(this).val();
                        }).get();
                    const dept = $('#dept5r').val();
                    const area = $('#area5r').val();

                    $('#modalTableBody').html(
                        '<tr><td colspan="100%" class="text-center">Loading...</td></tr>');

                    $.ajax({
                        url: "{{ route('admin.dashboard.public.dashboardchecklist5r_submit_detail') }}",
                        type: "GET",
                        data: {
                            statusclick: statusclick,
                            startDate: startDate,
                            endDate: endDate,
                            selectedOwners: selectedOwners.join(','),
                            dept: dept,
                            area: area,
                        },
                        cache: false,
                        success: function(data) {
                            if (data.success) {
                                // Destroy existing DataTable if it exists
                                if ($.fn.DataTable.isDataTable('#modalTable')) {
                                    $('#modalTable').DataTable().clear().destroy();
                                }

                                const tableBody = $('#modalTableBody');
                                const tableHeader = $('#modalTableHeader');

                                console.log('Clearing previous table data...');
                                tableBody.empty();
                                tableHeader.empty();

                                if (data.data.tabelpie && data.data.tabelpie.length > 0) {
                                    // Membuat header tabel secara manual
                                    const headersRow = $('<tr></tr>');
                                    data.data.headerspie.forEach(header => {
                                        const th = $('<th></th>').text(header);
                                        headersRow.append(th);
                                    });

                                    tableHeader.append(headersRow);

                                    // Menambahkan data ke dalam tabel
                                    console.log('Filling table with new data...');
                                    data.data.tabelpie.forEach(detail => {
                                        const row = $('<tr></tr>');
                                        data.data.headerspie.forEach(header => {
                                            const td = $('<td></td>').text(
                                                detail[header] || '');
                                            row.append(td);
                                        });
                                        tableBody.append(row);
                                    });

                                    // Hapus DataTable yang lama jika sudah ada
                                    if ($.fn.DataTable.isDataTable('#modalTable')) {
                                        $('#modalTable').DataTable().clear().destroy();
                                    }

                                    // Inisialisasi DataTable
                                    var table = $('#modalTable').DataTable({
                                        "paging": true,
                                        "scrollX": true,
                                        "searching": true,
                                        "ordering": true,
                                        "info": true,
                                        "autoWidth": false, // Matikan autoWidth
                                        "responsive": false,
                                        "pageLength": 10,

                                    });

                                    // Gunakan timeout untuk menunggu beberapa saat sebelum memanggil adjust()
                                    setTimeout(function() {
                                        // Panggil columns.adjust() untuk menyesuaikan kolom setelah DataTable ter-render
                                        table.columns.adjust().responsive.recalc();

                                        // Memastikan kolom header dan body memiliki lebar yang sama
                                        $('#modalTable thead th').each(function(index) {
                                            var columnWidth = $(
                                                '#modalTable tbody tr:first td'
                                                ).eq(index).outerWidth();
                                            $(this).css('width', columnWidth);
                                        });
                                    },
                                    300); // Timeout selama 100ms untuk memastikan DataTable ter-render dengan baik


                                } else {
                                    tableBody.html(
                                        '<tr><td colspan="100%" class="text-center">No data available</td></tr>'
                                        );
                                }


                                var myModal = new bootstrap.Modal($('#detailsModal')[0]);
                                myModal.show();
                            } else {
                                alert('Error occurred: ' + data.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            alert('Failed to retrieve data.');
                        }
                    });

                });
            @endif

            @if (isset($dataTable2) && count($dataTable2) > 0)
                const rawcolumnChartData = @json($dataTable2); // Dapatkan data dari PHP
                console.log(rawcolumnChartData); // Debugging untuk memastikan format yang benar

                // Ekstrak data dari rawcolumnChartData
                const Dept = rawcolumnChartData.map(item => item.Dept); // Ambil semua Dept
                const hitData = rawcolumnChartData.map(item => item.HitCount); // Ambil semua HitCount
                const missData = rawcolumnChartData.map(item => item.MisCount); // Ambil semua MissCount
                console.log(Dept); // Debugging untuk memastikan format yang benar
                console.log(hitData); // Debugging untuk memastikan format yang benar
                console.log(missData); // Debugging untuk memastikan format yang benar

                const columnChart = echarts.init(document.getElementById('mix-line-bar'));
                const columnOption = {
                    title: {
                        text: '',
                        left: 'center'
                    },
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'shadow'
                        }
                    },
                    legend: {
                        data: ['Hit Count', 'Mis Count'],
                        top: '5%', // Mengatur jarak dari bawah
                        orient: 'horizontal' // Orientasi legend horizontal
                    },
                    grid: {
                        left: '2%',
                        right: '2%',
                        bottom: '2%', // Menambahkan ruang di bawah untuk label sumbu X
                        containLabel: true // Menghindari label yang ketutup
                    },
                    xAxis: {
                        type: 'category',
                        data: Dept,
                        axisPointer: {
                            type: 'shadow'
                        },
                        axisLabel: {
                            interval: function(index, value) {
                                // Menampilkan label setiap interval tertentu, bisa disesuaikan
                                // Misalnya tampilkan label jika jarak antar label cukup
                                return index % Math.ceil(Dept.length / 10) ===
                                0; // Sesuaikan angka sesuai kebutuhan
                            },
                            rotate: function() {
                                // Menghitung jika ada ruang cukup, rotasi otomatis berdasarkan lebar chart
                                const width = window.innerWidth; // Dapatkan lebar layar
                                return width < 1281 ? 90 :
                                0; // Memutar label jika lebar layar kurang dari 768px
                            }(),
                            margin: 5 // Menambah jarak antar label
                        }
                    },
                    yAxis: {
                        type: 'value',
                        name: 'Jumlah'
                    },
                    series: [{
                            name: 'Hit Count',
                            type: 'bar',
                            stack: 'total',
                            data: hitData,
                            itemStyle: {
                                color: '#2ab57d' // Hijau Muda
                            },
                            label: {
                                show: true,
                                fontSize: 14, // Increase font size
                                position: 'inside', // Menampilkan label di dalam kolom
                                formatter: '{c}', // Menampilkan nilai
                                fontWeight: 'bold', // Menebalkan teks
                                color: '#fff' // Mengubah warna teks menjadi putih
                            }
                        },
                        {
                            name: 'Mis Count',
                            type: 'bar',
                            stack: 'total',
                            data: missData,
                            itemStyle: {
                                color: '#fd625e' // Merah Smooth
                            },
                            label: {
                                show: true,
                                fontSize: 14, // Increase font size
                                position: 'top', // Menampilkan label di dalam kolom
                                formatter: '{c}', // Menampilkan nilai
                                fontWeight: 'bold', // Menebalkan teks
                                color: '#ff0000' // Mengubah warna teks menjadi putih
                            }
                        }
                    ]
                };

                columnChart.setOption(columnOption);
            @endif
            // Resize chart on window resize
            window.addEventListener('resize', function() {
                pieChart.resize();
                columnChart.resize();
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#dept5r').on('change', function() {
                const dept5r = $(this).val();
                console.log("Selected Dept5r:", dept5r);

                // Kosongkan dan nonaktifkan combo box sebelum mendapatkan data baru
                $('#area5r').empty().append('<option value="All">All</option>').prop('disabled', true);

                if (dept5r) {
                    $.ajax({
                        url: "{{ route('admin.dashboard.public.dashboardchecklist5r_area') }}",
                        type: "GET",
                        data: {
                            dept5r: dept5r
                        },

                        success: function(response) {
                            $('#area5r').prop('disabled', false);

                            // Iterasi response untuk menambahkan opsi
                            response.forEach((item) => {
                                if (item.Area) { // Pastikan Superior ada
                                    $('#area5r').append(new Option(item.Area, item
                                        .Area));
                                } else {
                                    console.error('Missing Superior:', item);
                                }
                            });

                            console.log($('#area5r').html()); // Debug isi dropdown
                        },
                        error: function(xhr) {
                            console.error("Error:", xhr.responseText);
                        }
                    });
                } else {
                    $('#area5r').empty().append('<option value="">Select Area</option>').prop('disabled',
                        true);
                }
            });
        });
    </script>



    <style>
        /* .active-table thead th
        {
            background-color: rgb(74, 146, 194); /* Warna latar belakang header saat tabel aktif */
        /* color: white; Warna teks header agar kontras */
        /* } */

        .table-responsive {
            max-height: 300px;
            /* Sesuaikan tinggi maksimal sesuai kebutuhan */
            overflow-y: auto;
            /* Scroll vertikal */
            /*white-space: nowrap; /* Mencegah wrap text */
        }

        .table {
            width: 100%;
            /* Mengambil lebar penuh kontainer */
            table-layout: auto;
            /* Otomatis menyesuaikan layout */
            /* white-space: nowrap; Mencegah wrap text */
        }

        /*.table td, .table th {   white-space: nowrap; } */

        .table thead {
            background-color: #ADD8E6 !important;
            /* Biru muda */
            color: white !important;
            /* Teks putih */
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
