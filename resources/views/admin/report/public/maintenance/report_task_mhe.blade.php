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
                                    REPORT TASK MHE {{ $facility['Name'] }}
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
                                action="{{ route('admin.report.public.summary_reporttaskmhe') }}">
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
                @if (!empty($dataTable1))
                    @php
                        $summary = end($dataTable1); // Ambil data dari baris terakhir
                    @endphp

                    <!-- Total Task -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Task</span>
                                        <h3 class="mb-0">
                                            <span class="counter-value"
                                                data-target="{{ $summary['Total Task'] ?? 0 }}">0</span>
                                        </h3>
                                    </div>
                                    <i data-feather="clipboard" style="font-size: 30px;" class="text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- New -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted mb-3 lh-1 d-block text-truncate">New</span>
                                        <h3 class="mb-0">
                                            <span class="counter-value" data-target="{{ $summary['New'] ?? 0 }}">0</span>
                                        </h3>
                                    </div>
                                    <i data-feather="plus-circle" style="font-size: 30px;" class="text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- On Progress -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted mb-3 lh-1 d-block text-truncate">On Progress</span>
                                        <h3 class="mb-0">
                                            <span class="counter-value"
                                                data-target="{{ $summary['On Progress'] ?? 0 }}">0</span>
                                        </h3>
                                    </div>
                                    <i data-feather="clock" style="font-size: 30px;" class="text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Closed -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Closed</span>
                                        <h3 class="mb-0">
                                            <span class="counter-value"
                                                data-target="{{ $summary['Closed'] ?? 0 }}">0</span>
                                        </h3>
                                    </div>
                                    <i data-feather="check-circle" style="font-size: 30px;" class="text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            @if (isset($dataTable2) && count($dataTable2) > 0)
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        @foreach (array_chunk($dataTable2, 8) as $dataChunk)
                            <div class="swiper-slide">
                                <div class="row">
                                    @foreach ($dataChunk as $data)
                                        <div class="col-xl-3 col-md-3">
                                            <div class="card mb-3">
                                                <div class="card-body d-flex align-items-center">
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
                                                        <h5 class="mb-2">{{ $data['Employee_ID'] ?? '-' }} -
                                                            {{ $data['Employee_Name'] ?? '-' }}</h5>
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <span>Ongoing Task:</span>
                                                            <span class="fw-bold">{{ $data['Ongoing Task'] ?? 0 }}</span>
                                                        </div>
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <span>Completed Task:</span>
                                                            <span class="fw-bold">{{ $data['Completed Task'] ?? 0 }}</span>
                                                        </div>
                                                        <div class="d-flex justify-content-between mb-0">
                                                            <span>AVG Completed Task:</span>
                                                            <span
                                                                class="fw-bold">{{ $data['AVG Completed Task'] ?? 0 }}</span>
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

            <div class="row">
                <!-- Column pertama untuk tabel data dt-responsive-->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="filterTable" class="table nowrap w-100">


                                    @if (isset($dataTable3) && !empty($dataTable3))
                                        <thead class="table-light">
                                            <tr>
                                                @foreach ($tableHeaders3 as $header)
                                                    @if (in_array($header, [
                                                            'Activity',
                                                            'Picture',
                                                            'Unit No',
                                                            'Unit Name',
                                                            'Unit Type',
                                                            'Task',
                                                            'Assignment Date',
                                                            'On Progress',
                                                            'Closed',
                                                            'PIC',
                                                            'Team Mate',
                                                            'Status',
                                                            'Aging (Day)',
                                                            'Handling Time',
                                                        ]))
                                                        <th>{{ $header }}</th>
                                                    @endif
                                                @endforeach
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dataTable3 as $item)
                                                <tr>
                                                    @foreach ($tableHeaders3 as $header)
                                                        @if (in_array($header, [
                                                                'Activity',
                                                                'Picture',
                                                                'Unit No',
                                                                'Unit Name',
                                                                'Unit Type',
                                                                'Task',
                                                                'Assignment Date',
                                                                'On Progress',
                                                                'Closed',
                                                                'PIC',
                                                                'Team Mate',
                                                                'Status',
                                                                'Aging (Day)',
                                                                'Handling Time',
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
                                                    <td class="text-center">
                                                        @php
                                                            $attachments = [];
                                                            foreach (
                                                                [
                                                                    'Attachment1',
                                                                    'Attachment2',
                                                                    'Attachment3',
                                                                    'Attachment4',
                                                                    'Attachment5',
                                                                    'Attachment6',
                                                                ]
                                                                as $key
                                                            ) {
                                                                if (!empty($item[$key])) {
                                                                    $attachments[] = $item[$key]; // Simpan nama file gambar
                                                                }
                                                            }
                                                        @endphp

                                                        @if (!empty($attachments))
                                                            <button class="btn btn-info"
                                                                onclick="showImages({{ json_encode($attachments) }})">
                                                                <i class="fas fa-image"></i> Show
                                                            </button>
                                                        @else
                                                            <span class="text-muted">No Image</span>
                                                        @endif
                                                    </td>

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
                // fixedColumns: {
                //     leftColumns: 1 // Kolom pertama tetap beku
                // },
                // columnDefs: [{
                //     targets: 0,
                //     className: 'dt-left freeze-column'
                // }],
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
    </style>





@endsection
