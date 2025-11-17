@extends('admin.dashboard')
@section('admin')

<div class="page-content">
    <div class="container-fluid mt-4">
        <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
            <!-- start Tab pertama -->
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-center">
                            @if (session('facility_info'))
                            @foreach (session('facility_info') as $facility)
                            <h4 style="font-size: 1.5rem; font-weight: bold; text-align: center;">
                                MONITORING STOCK BALIKAN STORE (Non Merchandise) {{ $facility['Name'] }}
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
                                <form id="reportForm" method="POST" action="{{ route('admin.dashboard.storing.summary_stockbalikan') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-2">
                                                <div class="row">
                                                    <div class="col-6 col-md-2 mb-3">
                                                        <!-- Membuat Button Owner -->
                                                        <label class="form-label d-block">Owner</label>
                                                        <div class="position-relative">
                                                            <button type="button" class="btn btn-light w-100" onclick="toggleDropdown(event, 'owner-checkbox-list')" id="dropdown-owner-button">
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
                                                                    @if(session()->has("dataowner_" . Auth::guard('admin')->id()))
                                                                    @foreach (session("dataowner_" . Auth::guard('admin')->id()) as $owner)
                                                                    @if(strcasecmp($owner->Owner, 'All') !== 0)
                                                                    <div class="form-check">
                                                                        <input type="checkbox" class="form-check-input owner-checkbox" name="selected_owners[]"
                                                                            id="Owner_{{ $loop->index }}" value="{{ $owner->Owner }}">
                                                                        <label class="form-check-label" for="Owner_{{ $loop->index }}">{{ $owner->Owner }}</label>
                                                                    </div>
                                                                    @endif
                                                                    @endforeach
                                                                    @else
                                                                    <p class="text-warning">Data owner tidak ditemukan. Silakan refresh data.</p>
                                                                    @endif

                                                                </div>
                                                                <div class="d-flex mt-2" style="display: flex; gap: 5px;">
                                                                    <button type="button" class="btn btn-primary" style="flex: 1; padding: 0.375rem 0.75rem; font-size: 0.875rem;" onclick="closeCheckboxList()">OK</button>
                                                                    <button type="button" class="btn btn-danger" style="flex: 1; padding: 0.375rem 0.75rem; font-size: 0.875rem;" onclick="clearCheckboxes()">Clear</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- @dd(session("input_data_" . Auth::guard('admin')->id() . ".selected_owners", [])); --}}
                                                    <input type="hidden" id="selected_owners" name="selected_owners" value="{{ implode(';', session("input_data_" . Auth::guard('admin')->id() . ".selected_owners", [])) }}">
                                                    {{-- @dd(session("input_data_" . Auth::guard('admin')->id() . ".selected_owners", [])); --}}
                                                    {{-- Awalnya col-md-2 --}}
                                                    <!-- END Membuat Button Owner -->
                                                    <!-- Membuat input SKU -->
                                                    <div class="col-md-3 mb-2">
                                                        <label class="form-label">Nomor Article / SKU</label>
                                                        <input type="text" class="form-control" id="skuInput" name="nomor_sku"
                                                            value="{{ old('nomor_sku') }}"
                                                            placeholder="Masukkan SKU (pisahkan dengan ;)">
                                                        <small class="text-muted" id="skuHelper">Pisahkan lebih dari satu SKU dengan tanda <strong>;</strong></small>
                                                        <small class="text-danger d-none" id="skuError">Contoh format yang benar: <strong>12345;67890</strong></small>
                                                    </div>
                                                    <!-- Membuat input LOKASI -->
                                                    <div class="col-md-3 mb-2">
                                                        <label class="form-label">Lokasi</label>
                                                        <input type="text" class="form-control" id="locInput" name="loc"
                                                            value="{{ old('loc') }}"
                                                            placeholder="Masukkan Lokasi (pisahkan dengan ;)">
                                                        <small class="text-muted" id="locHelper">Pisahkan lebih dari satu LOKASI dengan tanda <strong>;</strong></small>
                                                        <small class="text-danger d-none" id="locError">Contoh format yang benar: <strong>DC07.01.01.1;DC07.01.01.2</strong></small>
                                                    </div>
                                                    <!-- Membuat input LPN/ID -->
                                                    <div class="col-md-3 mb-2">
                                                        <label class="form-label">ID / LPN</label>
                                                        <input type="text" class="form-control" id="idInput" name="idlpn"
                                                            value="{{ old('idlpn') }}"
                                                            placeholder="Masukkan Lokasi (pisahkan dengan ;)">
                                                        <small class="text-muted" id="idHelper">Pisahkan lebih dari satu ID / LPN dengan tanda <strong>;</strong></small>
                                                        <small class="text-danger d-none" id="idError">Contoh format yang benar: <strong>LPN0003;LPN0008</strong></small>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 col-md-2 mb-0">
                                            <button type="Show" class="btn btn-info w-100 mt-2">Show</button>
                                        </div>
                                        <div class="col-6 col-md-2 mb-0">
                                            <button type="reset" class="btn btn-danger w-100 mt-2">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Row pertama untuk parameter -->
                <!-- Row kedua untuk tabel -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                {{-- <h4 class="card-title">Monitoring Stock Balikan</h4> --}}
                                <div class="table-responsive summary-table">
                                    <table class="table mb-0">
                                        @if(isset($datatabel1) && !empty($datatabel1))
                                        <thead class="table-light">
                                            <tr>
                                                @foreach($tabelheaders1 as $header)
                                                @if(!in_array($header, ['Foto1', 'Foto2', 'Foto3','Reference_no']))
                                                <th class="{{ $header === 'DAMAGE FROM' ? 'text-wrap damage-column' : 'text-wrap' }}">
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
                                                @foreach($tabelheaders1 as $header)
                                                @if(!in_array($header, ['Foto1', 'Foto2', 'Foto3', 'Reference_no']))
                                                @php
                                                $formattedValue2 = $item[$header] ?? '-';
                                                $alignmentClass2 = is_numeric($formattedValue2) ? 'text-end' : 'text-start';
                                                if ($header === 'DAMAGE FROM') {
                                                $alignmentClass2 = 'text-start';
                                                } elseif ($header === 'CBM' && is_numeric($formattedValue2)) {
                                                // Tampilkan CBM dengan 4 desimal agar tidak terpotong
                                                $formattedValue2 = number_format((float)$formattedValue2, 4, '.', '');
                                                } elseif (is_numeric($formattedValue2)) {
                                                $formattedValue2 = intval($formattedValue2);
                                                }
                                                @endphp
                                                <td class="{{ $alignmentClass2 }}">
                                                    {{ $formattedValue2 }}
                                                </td>
                                                @endif
                                                @endforeach
                                                {{-- 🔥 Foto 1 --}}
                                                <td>
                                                    @if(!empty($item['Foto1_base64']))
                                                    <img src="{{ $item['Foto1_base64'] }}" alt="Foto1" class="img-thumbnail img-clickable" data-bs-toggle="modal" data-bs-target="#imageModal" data-img-src="{{ $item['Foto1_base64'] }}" style="max-width: 100px;">
                                                    @else
                                                    -
                                                    @endif
                                                </td>
                                                {{-- 🔥 Foto 2 --}}
                                                <td>
                                                    @if(!empty($item['Foto2_base64']))
                                                    <img src="{{ $item['Foto2_base64'] }}" alt="Foto2" class="img-thumbnail img-clickable" data-bs-toggle="modal" data-bs-target="#imageModal" data-img-src="{{ $item['Foto2_base64'] }}" style="max-width: 100px;">
                                                    @else
                                                    -
                                                    @endif
                                                </td>
                                                {{-- 🔥 Foto 3 --}}
                                                <td>
                                                    @if(!empty($item['Foto3_base64']))
                                                    <img src="{{ $item['Foto3_base64'] }}" alt="Foto3" class="img-thumbnail img-clickable" data-bs-toggle="modal" data-bs-target="#imageModal" data-img-src="{{ $item['Foto3_base64'] }}" style="max-width: 100px;">
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
                                    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="imageModalLabel">Preview Gambar</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                </div>
            </div>
            </form>
        </div>

    </div>



    <script src="{{ asset('backend/assets/js/owner-dropdown.js') }}"></script>
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

        document.getElementById('skuInput').addEventListener('input', function() {
            let value = this.value;
            let regex = /^[a-zA-Z0-9;]*$/; // Hanya angka, huruf, dan ;

            if (!regex.test(value)) {
                document.getElementById('skuHelper').classList.add('d-none'); // Sembunyikan helper normal
                document.getElementById('skuError').classList.remove('d-none'); // Tampilkan pesan error
            } else {
                document.getElementById('skuHelper').classList.remove('d-none'); // Tampilkan helper normal
                document.getElementById('skuError').classList.add('d-none'); // Sembunyikan pesan error
            }
        });

        document.getElementById('locInput').addEventListener('input', function() {
            let value = this.value;
            let regex = /^[a-zA-Z0-9;]*$/; // Hanya angka, huruf, dan ;

            if (!regex.test(value)) {
                document.getElementById('locHelper').classList.add('d-none'); // Sembunyikan helper normal
                document.getElementById('locError').classList.remove('d-none'); // Tampilkan pesan error
            } else {
                document.getElementById('locHelper').classList.remove('d-none'); // Tampilkan helper normal
                document.getElementById('locError').classList.add('d-none'); // Sembunyikan pesan error
            }
        });

        document.getElementById('idInput').addEventListener('input', function() {
            let value = this.value;
            let regex = /^[a-zA-Z0-9;]*$/; // Hanya angka, huruf, dan ;

            if (!regex.test(value)) {
                document.getElementById('idHelper').classList.add('d-none'); // Sembunyikan helper normal
                document.getElementById('idError').classList.remove('d-none'); // Tampilkan pesan error
            } else {
                document.getElementById('idHelper').classList.remove('d-none'); // Tampilkan helper normal
                document.getElementById('idError').classList.add('d-none'); // Sembunyikan pesan error
            }
        });
    </script>




    @endsection