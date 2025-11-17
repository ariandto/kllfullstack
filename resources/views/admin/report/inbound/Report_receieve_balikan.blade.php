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
                                    REPORT RECEIVE BARANG BALIKAN STORE (Non Merchandise) {{ $facility['Name'] }}
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

                            <form id="reportForm" method="POST"
                                action="{{ route('admin.report.inbound.summary_receivebalikan') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <!-- Row untuk menampung semua input dalam satu baris -->
                                            <div class="row">
                                                <!-- Input Start Date -->
                                                <div class="col-md-3 mb-2">
                                                    <label class="form-label">Start Receieve Date</label>
                                                    <input type="date" id="startDate" name="start_date"
                                                        class="form-control"
                                                        value="{{ request('start_date', date('Y-m-d')) }}" required>

                                                    {{-- <label class="form-label">Start Receieve Date </label>
                                                            <input type="date" class="form-control" id="startDate" name="start_date" 
                                                            value="{{ old('start_date', session('input_databr_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.start_date')) }}">
                                                         --}}

                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <!-- Input End Date -->
                                                    <label class="form-label">End Receieve Date</label>
                                                    <input type="date" id="endDate" name="end_date"
                                                        class="form-control"
                                                        value="{{ request('end_date', date('Y-m-d')) }}" required>
                                                    {{-- <label class="form-label">End Receieve Date</label>
                                                            <input type="date" class="form-control" id="endDate" name="end_date" 
                                                                value="{{ old('end_date', session('input_databr_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.end_date')) }}">
                                                         --}}
                                                </div>

                                                <!-- Input Nomor TTO -->
                                                <div class="col-md-3 mb-2">
                                                    <label class="form-label">Nomor TTO</label>
                                                    <input type="text" class="form-control" name="nomor_tto"
                                                        value="{{ old('nomor_tto') }}" placeholder="Masukkan Nomor TTO">
                                                </div>

                                                <!-- Input Nomor SKU -->
                                                <div class="col-md-3 mb-2">
                                                    <label class="form-label">Nomor Article / SKU</label>
                                                    <input type="text" class="form-control" name="nomor_sku"
                                                        value="{{ old('nomor_sku') }}"
                                                        placeholder="Masukkan Nomor Article / SKU">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 col-md-2 mb-0">
                                                    <button type="submit" class="btn btn-info w-100 mt-2">Show</button>
                                                </div>
                                                <div class="col-6 col-md-2 mb-0">
                                                    <button type="reset" class="btn btn-danger w-100 mt-2">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row pertama untuk parameter -->
            <!-- Row Kedua untuk parameter -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            {{-- <h4 class="card-title">Monitoring Receive Balikan</h4> --}}
                            <div class="table-responsive summary-table">

                                <table class="table mb-0">
                                    @if (isset($datatabel1) && !empty($datatabel1))
                                        <thead class="table-light">
                                            <tr>
                                                @foreach ($tabelheaders1 as $header)
                                                    @if (!in_array($header, ['Foto1', 'Foto2', 'Foto3']))
                                                        <th
                                                            class="{{ $header === 'DAMAGE FROM' ? 'text-wrap damage-column' : 'text-wrap' }}">
                                                            {{ $header }}
                                                        </th>
                                                    @endif
                                                @endforeach
                                                <th>Foto1</th>
                                                <th>Foto2</th>
                                                <th>Foto3</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($datatabel1 as $item)
                                                <tr>
                                                    @foreach ($tabelheaders1 as $header)
                                                        @if (!in_array($header, ['Foto1', 'Foto2', 'Foto3']))
                                                            @php
                                                                $alignmentClass2 = 'text-end';
                                                                $formattedValue2 = $item[$header] ?? '-';

                                                                if ($header === 'DAMAGE FROM') {
                                                                    $alignmentClass2 = 'text-start';
                                                                } else {
                                                                    if (is_numeric($formattedValue2)) {
                                                                        $formattedValue2 = intval($formattedValue2);
                                                                    }
                                                                }
                                                            @endphp
                                                            <td class="{{ $alignmentClass2 }}">
                                                                {{ $formattedValue2 }}
                                                            </td>
                                                        @endif
                                                    @endforeach

                                                    {{-- 🔥 Foto 1 --}}
                                                    <td>
                                                        @if (!empty($item['Foto1_base64']))
                                                            <img src="{{ $item['Foto1_base64'] }}" alt="Foto1"
                                                                class="img-thumbnail img-clickable" data-bs-toggle="modal"
                                                                data-bs-target="#imageModal"
                                                                data-img-src="{{ $item['Foto1_base64'] }}"
                                                                style="max-width: 100px;">
                                                        @else
                                                            -
                                                        @endif
                                                    </td>

                                                    {{-- 🔥 Foto 2 --}}
                                                    <td>
                                                        @if (!empty($item['Foto2_base64']))
                                                            <img src="{{ $item['Foto2_base64'] }}" alt="Foto2"
                                                                class="img-thumbnail img-clickable" data-bs-toggle="modal"
                                                                data-bs-target="#imageModal"
                                                                data-img-src="{{ $item['Foto2_base64'] }}"
                                                                style="max-width: 100px;">
                                                        @else
                                                            -
                                                        @endif
                                                    </td>

                                                    {{-- 🔥 Foto 3 --}}
                                                    <td>
                                                        @if (!empty($item['Foto3_base64']))
                                                            <img src="{{ $item['Foto3_base64'] }}" alt="Foto3"
                                                                class="img-thumbnail img-clickable" data-bs-toggle="modal"
                                                                data-bs-target="#imageModal"
                                                                data-img-src="{{ $item['Foto3_base64'] }}"
                                                                style="max-width: 100px;">
                                                        @else
                                                            -
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

                                <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="imageModalLabel">Preview Gambar</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img id="modalImage" src="" class="img-fluid" alt="Preview">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                </row>
                <!-- End Row Kedua untuk parameter -->
            </div>

        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const imageModal = document.getElementById("imageModal");
                const modalImage = document.getElementById("modalImage");

                document.querySelectorAll(".img-clickable").forEach(img => {
                    img.addEventListener("click", function() {
                        modalImage.src = this.getAttribute("data-img-src");
                    });
                });
            });
        </script>

    @endsection
