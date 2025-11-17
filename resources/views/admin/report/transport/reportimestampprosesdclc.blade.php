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
                                    REPORT MONITORING TIME STAMP PROSES {{ $facility['Name'] }}
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
                                action="{{ route('admin.report.transport.summary_reportimestampprosesdclc') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-6 col-md-2 mb-3">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" id="periodeStart" name="start_date" class="form-control"
                                            value="{{ request('start_date', date('Y-m-d')) }}" required>
                                    </div>

                                    <div class="col-6 col-md-2 mb-3">
                                        <label class="form-label">End Date</label>
                                        <input type="date" id="periodeEnd" name="end_date" class="form-control"
                                            value="{{ request('end_date', date('Y-m-d')) }}" required>
                                    </div>

                                    <div class="col-6 col-md-2 mb-3">
                                        <label class="form-label">TypeLC</label>
                                        <select id="typeLC" name="type_lc" class="form-control">
                                            <option value="ALL"
                                                {{ request('type_lc', 'ALL') == 'ALL' ? 'selected' : '' }}>ALL</option>
                                            <option value="Internal"
                                                {{ request('type_lc') == 'Internal' ? 'selected' : '' }}>Internal</option>
                                            <option value="External"
                                                {{ request('type_lc') == 'External' ? 'selected' : '' }}>External</option>
                                        </select>
                                    </div>

                                    <div class="col-6 col-md-2 mb-3">
                                        <label class="form-label">&nbsp;</label>
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
                                                    @if ($header !== 'DOC_SERAHTERIMA' && $header !== 'DOC_SERAHTERIMA1' && $header !== 'DOC_SERAHTERIMA2')
                                                        <th>{{ $header }}</th>
                                                    @endif
                                                @endforeach
                                                <th>DOC_SERAHTERIMA</th>
                                                {{-- <th>DOC_SERAHTERIMA1</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dataTable1 as $item)
                                                <tr>
                                                    @foreach ($tableHeaders1 as $index => $header)
                                                        @if ($header !== 'DOC_SERAHTERIMA' && $header !== 'DOC_SERAHTERIMA1' && $header !== 'DOC_SERAHTERIMA2')
                                                            @php
                                                                $rawValue = $item[$header] ?? '-';
                                                                $alignmentClass = 'text-start'; // Default: rata kiri
                                                                $formattedValue = $rawValue;

                                                                // Jika kolom ke-13 atau lebih, rata kanan
                                                                if ($index >= 12) {
                                                                    $alignmentClass = 'text-end';
                                                                }

                                                                // Cek apakah nilai adalah angka (tapi bukan tanggal)
                                                                if (
                                                                    is_numeric($rawValue) &&
                                                                    !preg_match(
                                                                        '/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/',
                                                                        $rawValue,
                                                                    )
                                                                ) {
                                                                    $alignmentClass = 'text-end'; // Rata kanan jika angka
                                                                    $formattedValue = number_format(
                                                                        floatval($rawValue),
                                                                    );
                                                                }
                                                                // Cek apakah nilai adalah format tanggal
                                                                elseif (
                                                                    preg_match(
                                                                        '/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/',
                                                                        $rawValue,
                                                                    )
                                                                ) {
                                                                    $alignmentClass = 'text-center'; // Rata tengah jika tanggal
                                                                    $formattedValue = \Carbon\Carbon::parse(
                                                                        $rawValue,
                                                                    )->format('Y-m-d H:i:s');
                                                                }
                                                            @endphp
                                                            <td class="{{ $alignmentClass }}">{{ $formattedValue }}</td>
                                                        @endif
                                                    @endforeach

                                                    <!-- Tambahkan kolom DOC_SERAHTERIMA dan DOC_SERAHTERIMA1 -->
                                                    <td class="text-center">
                                                        @php
                                                            $doc1 = $item['DOC_SERAHTERIMA'] ?? '';
                                                            $doc2 = $item['DOC_SERAHTERIMA1'] ?? '';
                                                            $doc3 = $item['DOC_SERAHTERIMA2'] ?? '';
                                                        @endphp
                                                        @if (!empty($doc1) || !empty($doc2))
                                                            <button class="btn btn-info"
                                                                onclick="showImages(['{{ $doc1 }}', '{{ $doc2 }}', '{{ $doc3 }}'])">
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
            <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="imageModalLabel">Image View</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

    <!-- DataTables & Plugins -->
    <script>
        $(document).ready(function() {
            let table = $('#filterTable').DataTable({
                scrollY: '500px',
                scrollX: true,
                scrollCollapse: true,
                paging: true,
                searching: true,
                info: true,
                fixedHeader: true, // Header tetap di atas 
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

        });
    </script>
    <script>
        function showImages(imageNames) {
            let attachmentContainer = document.getElementById("attachmentContainer");
            attachmentContainer.innerHTML = "";

            if (imageNames.length > 0) {
                imageNames.forEach((imageName, index) => {
                    console.log("Image Name:", imageName);

                    // Hapus bagian "_WMWHSE5RTL_250314H010.jpg" dari nama file
                    let cleanedName = imageName.replace(/_WMWHSE5RTL_.*\.jpg$/, '');

                    // Buat wrapper untuk setiap gambar dan judulnya
                    let imageWrapper = document.createElement("div");
                    imageWrapper.classList.add("image-wrapper");

                    // Buat label dengan nama foto yang sudah diubah
                    let label = document.createElement("p");
                    label.innerText = `Foto Serah Terima ${cleanedName}`;
                    label.classList.add("image-title"); // Tambahkan class untuk styling
                    imageWrapper.appendChild(label);

                    // Buat elemen gambar
                    let imgElement = document.createElement("img");
                    imgElement.src = `/admin/image/2/dokumen/${imageName}/`;
                    console.log("SRC:", imgElement.src);
                    imageWrapper.appendChild(imgElement);

                    // Tambahkan ke container utama
                    attachmentContainer.appendChild(imageWrapper);
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

        .modal-body img {
            width: 100%;
            max-width: 350px;
            max-height: 350px;
            object-fit: cover;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            display: block;
            margin: 0 auto;
            /* Gambar berada di tengah */
        }

        .modal-dialog {
            max-width: 75%;
            /* Modal lebih lebar */
        }

        #attachmentContainer {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            /* Jarak antar elemen lebih lebar */
            text-align: center;
            /* Semua teks rata tengah */
        }

        #attachmentContainer .image-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            /* Posisikan gambar dan teks ke tengah */
        }

        #attachmentContainer .image-title {
            font-weight: bold;
            margin-bottom: 5px;
            /* Beri jarak antara title dan gambar */
        }
    </style>


@endsection
