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
                                    REPORT CHECKLIST 5R {{ $facility['Name'] }}
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
                <div class="col-md-9 col-12">
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
                            <form id="dashboardform" method="POST"
                                action="{{ route('admin.report.public.reportchecklist5r_submit') }}">
                                @csrf

                                <div class="row">
                                    @php
                                        $selectedOwners = session(
                                            'input_datar5r_' .
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
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" class="form-control" id="startDate" name="start_date" required
                                            value="{{ old('start_date', session('input_datar5r_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.start_date')) }}">
                                    </div>

                                    <!-- End Date -->
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <label class="form-label">End Date</label>
                                        <input type="date" class="form-control" id="endDate" name="end_date" required
                                            value="{{ old('end_date', session('input_datar5r_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.end_date')) }}">
                                    </div>

                                    <!-- Owner Button -->
                                    <div class="col-lg-3 col-md-4 col-sm-6">
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
                                                    @if (session()->has('dataowner5rr_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi']))
                                                        @foreach (session('dataowner5rr_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi']) as $owner)
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
                                                <div class="d-flex mt-2" style="gap: 5px;">
                                                    <button type="button" class="btn btn-primary" style="flex: 1;"
                                                        onclick="closeCheckboxList()">OK</button>
                                                    <button type="button" class="btn btn-danger" style="flex: 1;"
                                                        onclick="clearCheckboxes()">Clear</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>


                                <div class="row mt-3">
                                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                        <button type="submit" class="btn btn-info w-100">Show</button>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                        <button type="button" id="exportButton3"
                                            class="btn btn-success w-100">Export</button>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                        <a href="{{ route('admin.export.checklist5rpdf') }}"
                                            class="btn btn-warning w-100">Generate PDF</a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                @if (isset($dataTable3) && !empty($dataTable3))
                                    @foreach ($dataTable3 as $item)
                                        <div class="score-box">
                                            <h5 class="score-title">Final Score</h5>
                                            <h2 class="score-value"
                                                style="color: 
                                            @if ($item['Kriteria'] == 'Istimewa') #006400; /* Hijau gelap */
                                            @elseif($item['Kriteria'] == 'Baik') 
                                                #00008B; /* Biru tua */
                                            @elseif($item['Kriteria'] == 'Cukup') 
                                                #4682B4; /* Biru lembut */
                                            @elseif($item['Kriteria'] == 'Kurang') 
                                                #B22222; /* Merah gelap */
                                            @elseif($item['Kriteria'] == 'Kurang Sekali') 
                                                #808080; /* Abu-abu */
                                            @else 
                                                #333333; /* Warna default */ @endif
                                        ">
                                                {{ $item['TotalScore'] ?? '-' }}
                                            </h2>
                                            <p class="score-description">
                                                Kriteria:
                                                {{ $item['Kriteria'] ?? '-' }}
                                            </p>
                                        </div>
                                    @endforeach
                                @else
                                    <p>-</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <!-- Kolom pertama: Bar Chart -->
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Score Achievement</h5>
                            <div id="mix-line-bar" class="e-charts" style="width: 100%; height: 350px;"></div>
                            {{-- Buatkan Chart yang mana isinya HIT dan MISS, HIT Hijau Muda dan MISS Merah Smooth, Type Nya Stacked Column Jadi Satu Chart itu Atas bawah --}}
                        </div>
                    </div>
                </div>

                <!-- Kolom kedua: Tabel biasa -->
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Summary Checklist 5R</h5>
                            <div class="table-responsive">
                                <table class="table mb-0">
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
                                                            // Default alignment to right for all columns
                                                            $alignmentClass2 = 'text-end';
                                                            $formattedValue2 = $item[$header] ?? '-'; // Default value for missing data

                                                            // Check for special left alignment columns: Dept and Kriteria
                                                            if (in_array($header, ['Dept', 'Kriteria'])) {
                                                                $alignmentClass2 = 'text-start'; // Left aligned for specific columns
                                                            } elseif ($header === 'Final Point') {
                                                                // Format 'Final Point' with one decimal place (e.g., 7,3 or 2,1)
                                                                if (is_numeric($formattedValue2)) {
                                                                    $formattedValue2 = number_format(
                                                                        floatval($formattedValue2),
                                                                        1,
                                                                        ',',
                                                                        '.',
                                                                    );
                                                                }
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

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="datatable2" class="table nowrap w-100">
                                @if (isset($dataTable2) && !empty($dataTable2))
                                    <thead class="table-light">
                                        <tr>
                                            @foreach ($tableHeaders2 as $header)
                                                @if (in_array($header, [
                                                        'Periode',
                                                        'Dept',
                                                        'Area',
                                                        'Ringkas',
                                                        'Rapi',
                                                        'Resik',
                                                        'Rawat',
                                                        'Rajin',
                                                        'Status',
                                                        'Shift',
                                                        'CheckPoint',
                                                    ]))
                                                    <th>{{ $header }}</th>
                                                @endif
                                            @endforeach
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataTable2 as $item)
                                            <tr>
                                                @foreach ($tableHeaders2 as $header)
                                                    @if (in_array($header, [
                                                            'Periode',
                                                            'Dept',
                                                            'Area',
                                                            'Ringkas',
                                                            'Rapi',
                                                            'Resik',
                                                            'Rawat',
                                                            'Rajin',
                                                            'Status',
                                                            'Shift',
                                                            'CheckPoint',
                                                        ]))
                                                        @php
                                                            $alignmentClass = 'text-start';
                                                            $formattedValue = $item[$header] ?? '-';

                                                            // Hanya memformat kolom 'Rajin' dengan 1 angka desimal
                                                            if ($header == 'Rajin' && is_numeric($formattedValue)) {
                                                                $alignmentClass = 'text-end';
                                                                // Format dengan 1 angka desimal
                                                                $formattedValue = number_format($formattedValue, 1);
                                                            }
                                                        @endphp
                                                        <td class="{{ $alignmentClass }}">
                                                            {{ $formattedValue }}
                                                        </td>
                                                    @endif
                                                @endforeach
                                                <td class="text-center">
                                                    <!-- Tombol untuk melihat gambar -->
                                                    <button class="btn btn-info"
                                                        onclick='showImages(@json($item))'>
                                                        <i class="fas fa-image"></i>
                                                        <!-- Ganti dengan ikon sesuai kebutuhan -->
                                                    </button>
                                                </td>
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

            <!-- Modal for displaying images and descriptions -->
            <!-- Modal for displaying images and descriptions -->
            <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="imageModalLabel">Image View</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Resik Section -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h6 class="category-title text-center">Resik</h6>
                                    <p id="resikDescription" class="text-center"></p> <!-- Deskripsi untuk Resik -->
                                    <p id="resikValue" class="text-center"></p> <!-- Nilai untuk Resik -->
                                </div>
                            </div>

                            <!-- Gambar Resik Section -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div id="resikContainer" class="d-flex overflow-auto">
                                        <!-- Gambar Resik akan ditambahkan di sini -->
                                    </div>
                                </div>
                            </div>

                            <!-- Rapi Section -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h6 class="category-title text-center">Rapi</h6>
                                    <p id="rapiDescription" class="text-center"></p> <!-- Deskripsi untuk Rapi -->
                                    <p id="rapiValue" class="text-center"></p> <!-- Nilai untuk Rapi -->
                                </div>
                            </div>

                            <!-- Gambar Rapi Section -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div id="rapiContainer" class="d-flex overflow-auto">
                                        <!-- Gambar Rapi akan ditambahkan di sini -->
                                    </div>
                                </div>
                            </div>

                            <!-- Ringkas Section -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h6 class="category-title text-center">Ringkas</h6>
                                    <p id="ringkasDescription" class="text-center"></p> <!-- Deskripsi untuk Ringkas -->
                                    <p id="ringkasValue" class="text-center"></p> <!-- Nilai untuk Ringkas -->
                                </div>
                            </div>

                            <!-- Gambar Ringkas Section -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div id="ringkasContainer" class="d-flex overflow-auto">
                                        <!-- Gambar Ringkas akan ditambahkan di sini -->
                                    </div>
                                </div>
                            </div>

                            <!-- Rawat Section -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h6 class="category-title text-center">Rawat</h6>
                                    <p id="rawatDescription" class="text-center"></p> <!-- Deskripsi untuk Rawat -->
                                    <p id="rawatValue" class="text-center"></p> <!-- Nilai untuk Rawat -->
                                </div>
                            </div>

                            <!-- Gambar Rawat Section -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div id="rawatContainer" class="d-flex overflow-auto">
                                        <!-- Gambar Rawat akan ditambahkan di sini -->
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 
        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel">Detail Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modal-body">
                        <!-- Tabel detail akan diisi di sini -->
                    </div>
                </div>
            </div>
        </div> --}}



        </div> <!-- container-fluid -->
    </div>
    <!-- Load the custom script -->
    <script src="{{ asset('backend/assets/js/owner-dropdown.js') }}"></script>
    <script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>


    <script>
        function showImages(item) {

            if (!item) {
                console.error("Error: Item is undefined or null");
                return;
            }
            // Mengosongkan kontainer
            document.getElementById('resikContainer').innerHTML = '';
            document.getElementById('rapiContainer').innerHTML = '';
            document.getElementById('ringkasContainer').innerHTML = '';
            document.getElementById('rawatContainer').innerHTML = '';

            // Menampilkan deskripsi untuk masing-masing
            document.getElementById('resikDescription').innerText = item['ResikDesc'] || '';
            document.getElementById('rapiDescription').innerText = item['RapiDesc'] || '';
            document.getElementById('ringkasDescription').innerText = item['RingkasDesc'] || '';
            document.getElementById('rawatDescription').innerText = item['RawatDesc'] || '';


            // Menambahkan teks "Score: " dan nilai dengan format 1 angka di belakang koma untuk masing-masing
            document.getElementById('resikValue').innerText = 'Score: ' + (item['Resik'] ? parseFloat(item['Resik'])
                .toFixed(1) : '');
            document.getElementById('rapiValue').innerText = 'Score: ' + (item['Rapi'] ? parseFloat(item['Rapi']).toFixed(
                1) : '');
            document.getElementById('ringkasValue').innerText = 'Score: ' + (item['Rajin'] ? parseFloat(item['Rajin'])
                .toFixed(1) : '');
            document.getElementById('rawatValue').innerText = 'Score: ' + (item['Ringkas'] ? parseFloat(item['Ringkas'])
                .toFixed(1) : '');


            // Menampilkan gambar untuk RINGKAS
            for (let i = 1; i <= 5; i++) {
                if (item[`I_Ringkas${i}`]) {
                    const imgElement = document.createElement('img');
                    imgElement.src = `/admin/image/ringkas/${item[`I_Ringkas${i}`]}`;
                    imgElement.alt = item[`I_Ringkas${i}`];
                    console.log("SRC:", imgElement.src); // Log nilai src 
                    imgElement.style.width = '100%';
                    imgElement.style.margin = '5px';
                    imgElement.style.border = '1px solid #ccc';
                    imgElement.style.borderRadius = '5px';
                    document.getElementById('ringkasContainer').appendChild(imgElement);
                }
            }

            // Menampilkan gambar untuk RAPI
            for (let i = 1; i <= 5; i++) {
                if (item[`I_Rapi${i}`]) {
                    const imgElement = document.createElement('img');
                    imgElement.src = `/admin/image/rapi/${item[`I_Rapi${i}`]}`;
                    imgElement.alt = item[`I_Rapi${i}`];
                    imgElement.style.width = '100%';
                    imgElement.style.margin = '5px';
                    imgElement.style.border = '1px solid #ccc';
                    imgElement.style.borderRadius = '5px';
                    document.getElementById('rapiContainer').appendChild(imgElement);
                }
            }

            // Menampilkan gambar untuk RESIK
            for (let i = 1; i <= 5; i++) {
                if (item[`I_Resik${i}`]) {
                    const imgElement = document.createElement('img');
                    imgElement.src = `/admin/image/resik/${item[`I_Resik${i}`]}`;
                    imgElement.alt = item[`I_Resik${i}`];
                    imgElement.style.width = '100%';
                    imgElement.style.margin = '5px';
                    imgElement.style.border = '1px solid #ccc';
                    imgElement.style.borderRadius = '5px';
                    document.getElementById('resikContainer').appendChild(imgElement);
                }
            }

            // Menampilkan gambar untuk RAWAT
            for (let i = 1; i <= 5; i++) {
                if (item[`I_Rawat${i}`]) {
                    const imgElement = document.createElement('img');
                    imgElement.src = `/admin/image/rawat/${item[`I_Rawat${i}`]}`;
                    imgElement.alt = item[`I_Rawat${i}`];
                    imgElement.style.width = '100%';
                    imgElement.style.margin = '5px';
                    imgElement.style.border = '1px solid #ccc';
                    imgElement.style.borderRadius = '5px';
                    document.getElementById('rawatContainer').appendChild(imgElement);
                }
            }

            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('imageModal'));
            modal.show();
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#datatable1').DataTable({
                scrollX: true,
                autoWidth: true,
                paging: true, // Atur sesuai kebutuhan
                responsive: false
            });

            $('#datatable2').DataTable({
                scrollX: true,
                autoWidth: false,
                paging: true, // Atur sesuai kebutuhan
                responsive: false

            });

            $('#datatable3').DataTable({
                scrollX: true,
                autoWidth: false,
                paging: true, // Atur sesuai kebutuhan
                responsive: false

            });


        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if dataTable1 is set and not empty, otherwise default to values of 0 
            @if (isset($dataTable1) && count($dataTable1) > 0)
                const rawData = @json($dataTable1); // Data dari Controller
                console.log(rawData); // Debugging

                // Ambil Dept unik
                const departments = [...new Set(rawData.map(item => item.Dept))];

                // Definisikan warna berdasarkan Kriteria
                const colorMapping = {
                    'Istimewa': '#90EE90', // Hijau muda
                    'Cukup': '#87CEFA', // Biru lembut
                    'Kurang': '#FF7F7F', // Merah lembut
                    'Kurang Sekali': '#A9A9A9' // Abu-abu gelap (Hitam lembut)
                };

                // Siapkan data untuk chart
                const seriesData = departments.map(dept => {
                    const item = rawData.find(d => d.Dept === dept); // Temukan data sesuai Dept

                    return {
                        value: item ? item['Final Point'] : 0, // Nilai Final Point (hanya untuk ukuran bar)
                        name: dept, // Nama Dept
                        itemStyle: {
                            color: item ? colorMapping[item.Kriteria] :
                                '#D3D3D3' // Warna berdasarkan Kriteria
                        },
                        label: {
                            show: true, // Tampilkan label
                            position: 'outside', // Label di dalam bar
                            formatter: item ? item.Kriteria : '-' // Tampilkan Kriteria sebagai label
                        }
                    };
                });

                // Inisialisasi ECharts
                const chart = echarts.init(document.getElementById('mix-line-bar'));
                // Konfigurasi Chart
                const option = {
                    tooltip: {
                        trigger: 'item',
                        formatter: '{b}: {c}' // Tampilkan Dept dan nilai Final Point
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    xAxis: {
                        type: 'value',
                        boundaryGap: [0, 0.01]
                    },
                    yAxis: {
                        type: 'category',
                        data: departments // Dept pada sumbu Y
                    },
                    series: [{
                        type: 'bar',
                        data: seriesData,
                        label: {
                            show: true,
                            position: 'outside',
                            formatter: '{b}' // Nama Dept sebagai label (opsional)
                        }
                    }]
                };

                // Set dan render chart
                chart.setOption(option);

                // Resize chart saat jendela berubah ukuran
                window.addEventListener('resize', function() {
                    chart.resize();
                });
            @else
                console.warn("Data tidak tersedia.");
            @endif


        });
    </script>

    <style>
        .table-responsive {
            max-height: 350px;
            /* Sesuaikan tinggi maksimal sesuai kebutuhan */
            overflow-y: auto;
            /* Scroll vertikal */
            white-space: nowrap;
            /* Mencegah wrap text */
        }

        .table {
            table-layout: auto;
            /* Sesuaikan agar tabel mengatur lebar kolom otomatis */
        }

        th,
        td {
            white-space: nowrap;
            /* Mencegah teks di dalam sel tabel membungkus */
        }


        .table thead th {
            position: sticky;
            /* Mengatur posisi menjadi sticky */
            top: 0;
            /* Menetapkan jarak dari atas */
            z-index: 10;
            /* Pastikan header berada di atas konten lain */
        }


        #resikContainer,
        #rapiContainer,
        #ringkasContainer,
        #rawatContainer {
            display: flex;
            overflow-x: auto;
            /* Enable horizontal scrolling */
            overflow-y: hidden;
            /* Hide vertical scrolling */
            white-space: nowrap;
            /* Prevent line breaks */
            padding: 10px 0;
            /* Optional: add vertical padding */
            gap: 15px;
            /* Space between images */
            border: 2px solid #ddd;
            /* Border around the container */
            border-radius: 8px;
            /* Rounded corners for container */
            background-color: #f9f9f9;
            /* Light background color */
            justify-content: flex-start;
            /* Align items to the left */
        }

        #resikContainer img,
        #rapiContainer img,
        #ringkasContainer img,
        #rawatContainer img {
            width: 250px;
            /* Set fixed width */
            height: 250px;
            /* Set fixed height */
            margin-right: 15px;
            /* Space between images */
            border: 1px solid #ccc;
            /* Border around images */
            border-radius: 5px;
            /* Rounded corners for images */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            /* Add subtle shadow for images */
            object-fit: cover;
            /* Ensure images cover the space properly */
            transition: transform 0.3s ease-in-out;
            /* Animasi zoom */
        }

        #resikContainer img:hover,
        #rapiContainer img:hover,
        #ringkasContainer img:hover,
        #rawatContainer img:hover {
            transform: scale(1.2);
            /* Membesarkan gambar */
            cursor: pointer;
            /* Mengubah kursor untuk menunjukkan interaksi */
        }

        /* Optional: Remove margin for the last image */
        #resikContainer img:last-child,
        #rapiContainer img:last-child,
        #ringkasContainer img:last-child,
        #rawatContainer img:last-child {
            margin-right: 0;
            /* Remove margin on the last image */
        }

        #resikDescription,
        #rapiDescription,
        #ringkasDescription,
        #rawatDescription {
            font-size: 1.1rem;
            /* Slightly larger font size for description */
            color: #4d3f3f;
            /* Descriptive text color */
            margin-bottom: 10px;
            /* Space below the description */
            font-weight: 500;
            /* Medium weight for better readability */
            line-height: 1.6;
            /* Increase line height for readability */
        }

        #resikValue,
        #rapiValue,
        #ringkasValue,
        #rawatValue {
            font-size: 1.2rem;
            /* Slightly larger font size for values */
            font-weight: bold;
            /* Bold text for value */
            color: #2c3e50;
            /* Dark color for value text */
            margin-top: 5px;
            /* Space between description and value */
        }

        .category-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #34495e;
            /* Color for the section titles */
            margin-bottom: 8px;
            /* Space between title and content */
            text-transform: uppercase;
            /* Uppercase titles */
        }

        .row.mb-4 {
            margin-bottom: 20px;
            /* Increase margin between rows */
        }

        .modal-body img {
            width: 100%;
            /* Gambar dalam modal akan mengikuti lebar container */
            height: auto;
            /* Sesuaikan tinggi gambar agar tidak terdistorsi */
            object-fit: contain;
            /* Menjaga aspek rasio gambar */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            /* Tambahkan bayangan untuk efek lebih menarik */
        }




        .modal-dialog {
            max-width: 75%;
            /* Sesuaikan persentase sesuai kebutuhan */
        }


        .category-title {
            font-size: 1.25rem;
            /* Ukuran font yang lebih besar */
            /* font-weight: bold; Tebal */
            color: #30353a;
            /* Warna biru Bootstrap */
            margin-bottom: 10px;
            /* Ruang di bawah judul */
            border-bottom: 2px solid #72d1e2;
            /* Garis di bawah judul */
            padding-bottom: 5px;
            /* Ruang di bawah garis */

        }

        .score-box {

            text-align: center;
            border: 2px solid #7b9abb;
            padding: 6px;
            border-radius: 10px;
            background-color: #f9f9f9;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .score-box:hover {
            transform: scale(1.05);
        }

        .score-title {
            font-size: 18px;
            color: #333;
            font-weight: bold;
        }

        .score-value {
            font-size: 36px;
            font-weight: bold;
            color: #2c2e2c;
            /* Default color */
            transition: color 0.3s ease;
        }

        .score-description {
            font-size: 16px;
            color: #555;
            font-style: italic;
        }

        .custom-card {
            min-height: 180px;
            /* Tinggi minimum khusus untuk card ini */
        }

        .custom-card .card-body {
            /* min-height: 400px;  */
            /* display: flex;
                                                            flex-direction: column;
                                                            justify-content: center;
                                                            align-items: center; */
        }
    </style>

@endsection
