@extends('admin.dashboard')
@section('admin')
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">


    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-center">
                        @if (session('facility_info'))
                            @foreach (session('facility_info') as $facility)
                                <h4 style="font-size: 1.5rem; font-weight: bold; text-align: center;">
                                    REPORT ASSET DC
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
                                action="{{ route('admin.report.public.summary_reportassetdc') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-6 col-md-2 mb-3">
                                        <label class="form-label">DC Name</label>
                                        <button type="button" class="btn btn-light w-100"
                                            onclick="toggleDropdown(event, 'dcname-checkbox-list')"
                                            id="dropdown-dcname-button">
                                            Pilih DC Name..</button>
                                        </button>
                                        <!-- Daftar Checkbox Dinamis -->
                                        <div id="dcname-checkbox-list" class="dropdown-menu"
                                            style="display: none; position: absolute; top: 100%; left: 0; width: 100%; z-index: 1000;">
                                            <!-- Kontainer Scroll untuk Checkbox -->
                                            <div style="max-height: 100px; overflow-y: auto;">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="check_all_dcname">
                                                    <label class="form-check-label" for="check_all_dcname">All</label>
                                                </div>
                                                <!-- Checkbox yang diisi dinamis dari Blade -->
                                                @if (session()->has('datadcname_' . Auth::guard('admin')->id()))
                                                    @foreach (session('datadcname_' . Auth::guard('admin')->id()) as $dcname)
                                                        @if (strcasecmp($dcname->DCName, 'All') !== 0)
                                                            <div class="form-check">
                                                                <input type="checkbox"
                                                                    class="form-check-input dcname-checkbox"
                                                                    name="selected_dcnames[]"
                                                                    id="Dcname_{{ $loop->index }}"
                                                                    value="{{ $dcname->DCName }}"
                                                                    {{ in_array($dcname->DCName, $selectedDCNames ?? []) ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="Dcname_{{ $loop->index }}">{{ $dcname->DCName }}</label>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <p class="text-warning">Data dcname tidak ditemukan. Silakan refresh
                                                        data.</p>
                                                @endif
                                            </div>
                                            <div class="d-flex mt-2" style="display: flex; gap: 5px;">
                                                <button type="button" class="btn btn-primary"
                                                    style="flex: 1; padding: 0.375rem 0.75rem; font-size: 0.875rem;"
                                                    onclick="closeCheckboxList('dcname')">OK</button>
                                                <button type="button" class="btn btn-danger"
                                                    style="flex: 1; padding: 0.375rem 0.75rem; font-size: 0.875rem;"
                                                    onclick="clearCheckboxes('dcname')">Clear</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6 col-md-2 mb-3">
                                        <label class="form-label">Unit Type</label>
                                        <button type="button" class="btn btn-light w-100"
                                            onclick="toggleDropdown(event, 'unittype-checkbox-list')"
                                            id="dropdown-unittype-button">
                                            Pilih Unit Type..
                                        </button>
                                        <!-- Daftar Checkbox Dinamis -->
                                        <div id="unittype-checkbox-list" class="dropdown-menu"
                                            style="display: none; position: absolute; top: 100%; left: 0; width: 100%; z-index: 1000;">
                                            <!-- Kontainer Scroll untuk Checkbox -->
                                            <div style="max-height: 100px; overflow-y: auto;">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="check_all_unittype">
                                                    <label class="form-check-label" for="check_all_unittype">All</label>
                                                </div>
                                                <!-- Checkbox yang diisi dinamis dari Blade -->
                                                @if (session()->has('dataunittype_' . Auth::guard('admin')->id()))
                                                    @foreach (session('dataunittype_' . Auth::guard('admin')->id()) as $unittype)
                                                        @if (strcasecmp($unittype->UnitType, 'All') !== 0)
                                                            <div class="form-check">
                                                                <input type="checkbox"
                                                                    class="form-check-input unittype-checkbox"
                                                                    name="selected_unittypes[]"
                                                                    id="Unittype_{{ $loop->index }}"
                                                                    value="{{ $unittype->UnitType }}"
                                                                    {{ in_array($unittype->UnitType, $selectedUnitTypes ?? []) ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="Unittype_{{ $loop->index }}">{{ $unittype->UnitType }}</label>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <p class="text-warning">Data unittype tidak ditemukan. Silakan refresh
                                                        data.</p>
                                                @endif
                                            </div>
                                            <div class="d-flex mt-2" style="display: flex; gap: 5px;">
                                                <button type="button" class="btn btn-primary"
                                                    style="flex: 1; padding: 0.375rem 0.75rem; font-size: 0.875rem;"
                                                    onclick="closeCheckboxList('unittype')">OK</button>
                                                <button type="button" class="btn btn-danger"
                                                    style="flex: 1; padding: 0.375rem 0.75rem; font-size: 0.875rem;"
                                                    onclick="clearCheckboxes('unittype')">Clear</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-2 mb-3 d-flex align-items-end">
                                        <button type="submit" class="btn btn-info w-100">Show</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <!-- end row pertama -->
            <div class="row dashboard-cards">
                @if (!empty($dataTable1))
                    @php
                        $summary = end($dataTable1); // Ambil data dari baris terakhir
                    @endphp

                    <!-- Total Unit -->
                    <div class="col-xl-2 col-md-6">
                        <div class="card card-h-100" data-status="Total" onclick="scrollAndFilter(this)">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Total</span>
                                        <h3 class="mb-0">
                                            <span class="counter-value"
                                                data-target="{{ $summary['Total'] ?? 0 }}">0</span>
                                        </h3>
                                    </div>
                                    <img src="https://img.icons8.com/color/96/use-forklift.png" alt="Tool Icon"
                                        style="width: 30px; height: 30px;" class="text-primary">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tersedia -->
                    <div class="col-xl-2 col-md-6">
                        <div class="card card-h-100" data-status="Tersedia" onclick="scrollAndFilter(this)">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Tersedia</span>
                                        <h3 class="mb-0">
                                            <span class="counter-value"
                                                data-target="{{ $summary['Tersedia'] ?? 0 }}">0</span>
                                        </h3>
                                    </div>
                                    <i data-feather="check-circle" style="font-size: 30px;" class="text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pakai -->
                    <div class="col-xl-2 col-md-6">
                        <div class="card card-h-100" data-status="Pakai" onclick="scrollAndFilter(this)">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Pakai</span>
                                        <h3 class="mb-0">
                                            <span class="counter-value"
                                                data-target="{{ $summary['Pakai'] ?? 0 }}">0</span>
                                        </h3>
                                    </div>
                                    <i data-feather="clock" style="font-size: 30px;" class="text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Perbaikan -->
                    <div class="col-xl-2 col-md-6">
                        <div class="card card-h-100" data-status="Perbaikan" onclick="scrollAndFilter(this)">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Perbaikan</span>
                                        <h3 class="mb-0">
                                            <span class="counter-value"
                                                data-target="{{ $summary['Perbaikan'] ?? 0 }}">0</span>
                                        </h3>
                                    </div>
                                    <i data-feather="tool" style="font-size: 30px;" class="text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rusak -->
                    <div class="col-xl-2 col-md-6">
                        <div class="card card-h-100" data-status="Rusak" onclick="scrollAndFilter(this)">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Rusak</span>
                                        <h3 class="mb-0">
                                            <span class="counter-value"
                                                data-target="{{ $summary['Rusak'] ?? 0 }}">0</span>
                                        </h3>
                                    </div>
                                    <i data-feather="alert-triangle" style="font-size: 30px;" class="text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            @if (isset($dataTable2) && count($dataTable2) > 0)
                <div class="swiper-container dashboard-cards">
                    <div class="swiper-wrapper">
                        @foreach (array_chunk($dataTable2, 8) as $dataChunk)
                            <div class="swiper-slide">
                                <div class="row">
                                    @foreach ($dataChunk as $data)
                                        <div class="col-xl-3 col-md-3">
                                            <div class="card mb-3">
                                                <div class="card-body d-flex flex-column align-items-start">
                                                    <h5 class="mb-2">{{ $data['UnitType'] ?? '-' }} -
                                                        {{ $data['Brand'] ?? '-' }}</h5>
                                                    <div class="d-flex align-items-center w-100">
                                                        <div class="me-3">
                                                            @php
                                                                $picture = asset('path_ke_gambar_default.png');
                                                                if (!empty($data['Picture'])) {
                                                                    $picture =
                                                                        'data:image/png;base64,' .
                                                                        base64_encode($data['Picture']);
                                                                }
                                                            @endphp
                                                            <img src="{{ $picture }}" alt="Profile Picture"
                                                                class="rounded-circle" width="60" height="60">
                                                        </div>
                                                        <div class="w-100">
                                                            <div class="d-flex justify-content-between mb-1">
                                                                <span>Total:</span>
                                                                <span class="fw-bold">{{ $data['Total'] ?? 0 }}</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between mb-1">
                                                                <span>Tersedia:</span>
                                                                <span class="fw-bold">{{ $data['Tersedia'] ?? 0 }}</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between mb-0">
                                                                <span>Pakai:</span>
                                                                <span class="fw-bold">{{ $data['Pakai'] ?? 0 }}</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between mb-0">
                                                                <span>Perbaikan:</span>
                                                                <span class="fw-bold">{{ $data['Perbaikan'] ?? 0 }}</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between mb-0">
                                                                <span>Rusak:</span>
                                                                <span class="fw-bold">{{ $data['Rusak'] ?? 0 }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Navigasi Panah dalam row -->
                    <div class="row swiper-navigation">
                        <div class="col-auto">
                            <div class="swiper-button-prev"></div>
                        </div>
                        <div class="col-auto">
                            <div class="swiper-button-next"></div>
                        </div>
                    </div>
                    <div class="swiper-pagination text-center"></div>
                </div>
            @endif

            <style>
                #filterTable th.dc-name {
                    width: 10%;
                }

                #filterTable th.unit-no {
                    width: 5%;
                }

                #filterTable th.unit-name {
                    width: 30%;
                }

                #filterTable th.unit-type {
                    width: 10%;
                }

                #filterTable th.brand {
                    width: 7%;
                }

                #filterTable th.status {
                    width: 7%;
                }

                #filterTable th.receive-date {
                    width: 7%;
                }

                #filterTable th.life-time {
                    width: 5%;
                }

                #filterTable th.aging {
                    width: 5%;
                }
            </style>

            <div class="row">
                <!-- Column pertama untuk tabel data dt-responsive-->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="filterTable" class="table nowrap w-100" style="table-layout: fixed;">
                                    @if (isset($dataTable3) && !empty($dataTable3))
                                        <thead class="table-light">
                                            <tr>
                                                @foreach ($tableHeaders3 as $header)
                                                    @if (in_array($header, [
                                                            'DC Name',
                                                            'Unit No',
                                                            'Unit Name',
                                                            'Unit Type',
                                                            'Brand',
                                                            'Status',
                                                            'Receive Date',
                                                            'Life Time (Year)',
                                                            'Aging (Year)',
                                                        ]))
                                                        @php
                                                            $thClass = match ($header) {
                                                                'DC Name' => 'dc-name',
                                                                'Unit No' => 'unit-no',
                                                                'Unit Name' => 'unit-name',
                                                                'Unit Type' => 'unit-type',
                                                                'Brand' => 'brand',
                                                                'Status' => 'status',
                                                                'Receive Date' => 'receive-date',
                                                                'Life Time (Year)' => 'life-time',
                                                                'Aging (Year)' => 'aging',
                                                                default => '',
                                                            };
                                                        @endphp
                                                        <th class="{{ $thClass }} text-center">{{ $header }}
                                                        </th>
                                                    @endif
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dataTable3 as $item)
                                                <tr>
                                                    @foreach ($tableHeaders3 as $header)
                                                        @if (in_array($header, [
                                                                'DC Name',
                                                                'Unit No',
                                                                'Unit Name',
                                                                'Unit Type',
                                                                'Brand',
                                                                'Status',
                                                                'Receive Date',
                                                                'Life Time (Year)',
                                                                'Aging (Year)',
                                                            ]))
                                                            @php
                                                                $alignmentClass = 'text-start';
                                                                $formattedValue = $item[$header] ?? '-';

                                                                // Jika kolom Picture berisi data gambar base64, tampilkan sebagai gambar
                                                                if ($header == 'Picture' && !empty($item[$header])) {
                                                                    $formattedValue =
                                                                        '<img src="data:image/png;base64,' .
                                                                        base64_encode($item[$header]) .
                                                                        '" alt="Image" width="50">';
                                                                    $alignmentClass = 'text-center';
                                                                } elseif ($header == 'Unit No') {
                                                                    $formattedValue = (string) $formattedValue;
                                                                    $alignmentClass = 'text-start';
                                                                }
                                                                // Jika kolom adalah angka, format dengan pemisah ribuan
                                                                elseif (is_numeric($formattedValue)) {
                                                                    $formattedValue = number_format(
                                                                        floatval($formattedValue),
                                                                    );
                                                                    $alignmentClass = 'text-end';
                                                                }
                                                            @endphp
                                                            <td class="{{ $alignmentClass }}">{!! $formattedValue !!}</td>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @else
                                        <thead class="table-light">
                                            <tr>
                                                <th colspan="100%" class="text-center">No data available</th>
                                            </tr>
                                        </thead>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="imageModalLabel">Image View</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3" id="attachmentContainer">
                                    <!-- Gambar akan ditambahkan di sini -->
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container-fluid -->
    </div>

    <script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

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
                autoWidth: false, // Supaya tidak pakai width default DataTable
                columnDefs: [{
                    targets: "_all",
                    className: "text-wrap"
                }],

                order: [],
                dom: '<"d-flex justify-content-between align-items-center mb-2"lf>Brtip',
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
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var swiper = new Swiper(".swiper-container", {
                slidesPerView: 1, // 1 slide per tampilan
                spaceBetween: 10,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                }
            });
        });
    </script>
    <script>
        function showImages(imageNames) {
            let attachmentContainer = document.getElementById("attachmentContainer");
            attachmentContainer.innerHTML = "";

            if (imageNames.length > 0) {
                imageNames.forEach(imageName => {

                    console.log("Image Name:", imageName);
                    let imgElement = document.createElement("img");
                    imgElement.src = `/admin/image/1/preventive/${imageName}/`;
                    console.log("SRC:", imgElement.src); // Log nilai src 
                    imgElement.style.width = '100%';
                    imgElement.style.margin = '5px';
                    imgElement.style.border = '1px solid #ccc';
                    imgElement.style.borderRadius = '5px';
                    document.getElementById('attachmentContainer').appendChild(imgElement);
                });
            } else {
                attachmentContainer.innerHTML = "<p class='text-center text-muted'>No Image Available</p>";
            }

            let imageModal = new bootstrap.Modal(document.getElementById("imageModal"));
            imageModal.show();
        }
    </script>

    <script>
        // Toggle dropdown tampil / sembunyi, bisa untuk dcname atau unittype
        function toggleDropdown(event, id) {
            event.stopPropagation();
            const dropdown = document.getElementById(id);
            if (dropdown.style.display === 'block') {
                dropdown.style.display = 'none';
            } else {
                dropdown.style.display = 'block';
            }
        }

        // Tutup dropdown saat klik OK, bisa untuk dcname atau unittype
        function closeCheckboxList(type) {
            if (type === 'dcname') {
                document.getElementById('dcname-checkbox-list').style.display = 'none';
                updateButtonLabel('dcname');

                const selectedDCNames = Array.from(document.querySelectorAll('.dcname-checkbox:checked')).map(cb => cb
                    .value);

                // Tambahan: jika tidak ada DC yang dicentang, kosongkan Unit Type
                if (selectedDCNames.length === 0) {
                    updateUnitTypeCheckboxes([]); // kosongkan
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.report.public.get_unit_type_asset') }}",
                    type: "POST",
                    data: {
                        dcNames: selectedDCNames,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        updateUnitTypeCheckboxes(data);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching Unit Type:', error);
                    }
                });
            } else if (type === 'unittype') {
                document.getElementById('unittype-checkbox-list').style.display = 'none';
                updateButtonLabel('unittype');
            }
        }


        function updateUnitTypeCheckboxes(data) {
            const container = document.querySelector('#unittype-checkbox-list > div'); // Ambil container scroll
            if (!container) return;

            // Render checkbox 'All'
            container.innerHTML = `
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="check_all_unittype">
            <label class="form-check-label" for="check_all_unittype">All</label>
        </div>
    `;

            // Tambahkan checkbox dari data
            data.forEach((item, index) => {
                const value = item.UnitType;
                const checkboxHTML = `
            <div class="form-check">
                <input type="checkbox" class="form-check-input unittype-checkbox" name="selected_unittypes[]" id="Unittype_${index}" value="${value}">
                <label class="form-check-label" for="Unittype_${index}">${value}</label>
            </div>
        `;
                container.insertAdjacentHTML('beforeend', checkboxHTML);
            });

            // ⬅️ Tambahkan ulang event listener setelah checkbox 'All' dimasukkan
            document.getElementById('check_all_unittype').addEventListener('change', function() {
                const checked = this.checked;
                document.querySelectorAll('.unittype-checkbox').forEach(cb => cb.checked = checked);
                updateButtonLabel('unittype');
            });
        }

        // Bersihkan semua checkbox sesuai type
        function clearCheckboxes(type) {
            let checkboxes;
            if (type === 'unittype') {
                checkboxes = document.querySelectorAll('.unittype-checkbox');
                document.getElementById('check_all_unittype').checked = false; // pakai id unik
            } else if (type === 'dcname') {
                checkboxes = document.querySelectorAll('.dcname-checkbox');
                document.getElementById('check_all_dcname').checked = false; // ganti jadi check_all_dcname
            } else {
                return;
            }
            checkboxes.forEach(cb => cb.checked = false);
        }

        // Event listener untuk check all dcname
        document.getElementById('check_all_dcname').addEventListener('change', function() {
            const checked = this.checked;
            document.querySelectorAll('.dcname-checkbox').forEach(cb => cb.checked = checked);
            updateButtonLabel('dcname');
        });

        // Event listener untuk check all unittype
        document.getElementById('check_all_unittype').addEventListener('change', function() {
            const checked = this.checked;
            document.querySelectorAll('.unittype-checkbox').forEach(cb => cb.checked = checked);
            updateButtonLabel('unittype');
        });


        // Update label tombol sesuai dengan pilihan yang terpilih
        function updateButtonLabel(type) {
            if (type === 'dcname') {
                const button = document.getElementById('dropdown-dcname-button');
                // Tampilkan selalu teks default tanpa menampilkan pilihan
                button.innerHTML = "Pilih DC Name&nbsp;";
            } else if (type === 'unittype') {
                const button = document.getElementById('dropdown-unittype-button');
                // Tampilkan selalu teks default tanpa menampilkan pilihan
                button.innerHTML = "Pilih Unit Type&nbsp;";
            }
        }


        // Inisialisasi update label saat load halaman
        document.addEventListener('DOMContentLoaded', () => {
            updateButtonLabel('dcname');
            updateButtonLabel('unittype');
        });

        // Tutup dropdown jika klik di luar elemen (bisa untuk keduanya)
        document.addEventListener('click', function(event) {
            const dropdownDcname = document.getElementById('dcname-checkbox-list');
            const buttonDcname = document.getElementById('dropdown-dcname-button');
            const dropdownUnittype = document.getElementById('unittype-checkbox-list');
            const buttonUnittype = document.getElementById('dropdown-unittype-button');

            if (!dropdownDcname.contains(event.target) && event.target !== buttonDcname) {
                dropdownDcname.style.display = 'none';
            }
            if (!dropdownUnittype.contains(event.target) && event.target !== buttonUnittype) {
                dropdownUnittype.style.display = 'none';
            }
        });
    </script>

    <script>
        function scrollAndFilter(element) {
            const status = element.getAttribute("data-status");

            // Scroll ke tabel
            const tableElement = document.getElementById("filterTable");
            if (tableElement) {
                tableElement.scrollIntoView({
                    behavior: "smooth"
                });
            }

            // Hanya filter kalau bukan "Total"
            if (status !== "Total") {
                // Tunggu datatable ter-load
                setTimeout(() => {
                    const table = $('#filterTable').DataTable();
                    // Kolom ke-5 (index dimulai dari 0), ubah sesuai urutan kolom "Status"
                    const statusColumnIndex = $('#filterTable thead th').filter(function() {
                        return $(this).text().trim() === "Status";
                    }).index();

                    if (statusColumnIndex !== -1) {
                        table.column(statusColumnIndex).search(status).draw();
                    }
                }, 300);
            } else {
                // Reset filter jika klik "Total"
                setTimeout(() => {
                    const table = $('#filterTable').DataTable();
                    table.search('').columns().search('').draw();
                }, 300);
            }
        }
    </script>

    <style>
        #filterTable tbody td:hover {
            background-color: rgba(0, 123, 255, 0.2);
            /* Warna biru transparan */
            cursor: pointer;

        }


        #filterTable td {
            white-space: normal !important;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .text-center {
            text-align: center !important;
            vertical-align: middle !important;
        }

        /* Pastikan container navigasi berada di dalam row */
        .swiper-navigation {
            display: flex;
            justify-content: center;
            /* Menjaga tombol tetap di tengah */
            align-items: center;
            margin-top: 10px;
            /* Sesuaikan dengan kebutuhan */
            position: relative;
        }

        /* Tombol navigasi Swiper */
        .swiper-button-next,
        .swiper-button-prev {
            position: static !important;
            /* Hindari absolute jika ingin tetap dalam row */
            width: 30px;
            height: 30px;
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            font-size: 18px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 10px;
            /* Beri jarak antar tombol */
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        /* Efek hover */
        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            background-color: #0056b3;
            transform: scale(1.1);
        }

        /* Pastikan icon panah tetap dalam ukuran proporsional */
        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 20px !important;
        }

        .swiper-container {
            padding-bottom: 20px;
            /* Memberikan jarak ke elemen bawah */
        }

        .swiper-navigation {
            margin-top: 10px;
            /* Memberikan jarak antara slider dan tombol navigasi */
        }

        .swiper-pagination {
            margin-top: 10px;
            /* Memberikan jarak ke pagination */
        }



        .modal-body img {
            width: 100%;
            max-width: 350px;
            max-height: 350px;
            /* Ukuran gambar lebih besar */
            /* height: auto; */
            object-fit: cover;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }

        .modal-dialog {
            max-width: 75%;
            /* Modal lebih lebar */
        }

        #attachmentContainer {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }

        #attachmentContainer .col {
            display: flex;
            justify-content: center;
        }

        .dashboard-cards .card:hover {
            background-color: #eaf4ff;
            border: 1px solid #007bff;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.2);
            transition: all 0.3s ease;
            cursor: pointer;
        }
    </style>

@endsection
