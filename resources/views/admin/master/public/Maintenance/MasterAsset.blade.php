@extends('admin.dashboard')
@section('admin')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.css">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-center">
                        @if (session('facility_info'))
                            @foreach (session('facility_info') as $facility)
                                <h4 class="text-center font-weight-bold">
                                    MASTER ASSET {{ $facility['Name'] }}
                                </h4>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <!-- FORM 1: Show Data -->

            <!-- FORM 2: Save Data -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            @endif

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
                            <form action="{{ route('admin.master.public.maintenance.masteraset_save') }}" method="POST"
                                id="saveForm">
                                @csrf
                                <input type="hidden" id="owner5r_input" name="owner5r" value="{{ old('owner5r') }}">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="aset_number">Aset Number</label>
                                        <input type="text" name="aset_number" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="owner5r" class="form-label">Owner</label>
                                        <div class="dropdown">

                                            <button
                                                class="btn btn-outline-light dropdown-toggle w-100 text-start d-flex align-items-center justify-content-between"
                                                type="button" id="owner5r" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                {{ old('owner5r', $dataowner5r[0]->Owner ?? 'Owner') }} <i
                                                    class="mdi mdi-chevron-down"></i>

                                            </button>
                                            <div class="dropdown-menu w-100" aria-labelledby="dropdownOwnerButton"
                                                style="max-height: 200px; overflow-y: auto;">
                                                @if (isset($dataowner5r) && !empty($dataowner5r))
                                                    @foreach ($dataowner5r as $owner)
                                                        <a class="dropdown-item" data-value="{{ $owner->Owner }}"
                                                            href="#">{{ $owner->Owner }}</a>
                                                    @endforeach
                                                @else
                                                    <a class="dropdown-item disabled" href="#">No Owners Available</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="no_unit">No Unit</label>
                                            <small class="form-text text-muted">Key Press In Here</small>
                                            <input type="text" name="no_unit" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="unit_name">Unit Name</label>
                                        <input type="text" name="unit_name" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="unit_type">Unit Type</label>
                                        <select name="unit_type" class="form-control">
                                            @if (isset($dataTable) && count($dataTable) > 0)
                                                @foreach ($dataTable as $type)
                                                    <option value="{{ $type->UnitType }}">{{ $type->UnitType }}</option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="brand">Brand</label>
                                        <input type="text" name="brand" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Receive Date</label>
                                        <input type="date" id="receive_date" name="receive_date" class="form-control"
                                            value="{{ request('receive_date', date('Y-m-d')) }}" required>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="lifetime">Lifetime</label>
                                        <input type="number" name="lifetime" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="status">Status</label>
                                        <select name="status" class="form-control">
                                            <option value="Active">Active</option>
                                            <option value="Non Active">Non Active</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <!-- Save Button -->
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100" id="saveButton">Save</button>
                                    </div>

                                    <!-- Delete Button -->
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-danger w-100"
                                            id="deleteButton">Delete</button>
                                    </div>

                                    <!-- Cancel Button -->
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-warning w-100"
                                            id="cancelButton">Cancel</button>
                                    </div>

                                    <!-- Additional Empty Column -->
                                    <div class="col-md-3"></div>
                                </div>
                            </form>



                        </div>

                    </div>
                </div>



            </div>

            <!-- Section: Datatable Row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            {{-- @php
                                $facilityInfo = session('facility_info', []);
                                $facilityID = $facilityInfo[0]['Facility_ID'] ?? '';
                                $RELASI = $facilityInfo[0]['Relasi'] ?? '';
                                $Name = $facilityInfo[0]['Name'] ?? '';
                            @endphp

                            <p>Facility ID: {{ $facilityID }}</p>
                            <p>Relasi: {{ $RELASI }}</p>
                            <p>Nama: {{ $Name }}</p> --}}
                            <div class="table-responsive mb-0" data-pattern="priority-columns">
                                <table id="filterableTable" class="table table-striped">
                                    <thead class="table-info">
                                        <tr>
                                            <th>Print QR</th>
                                            <th>Add Date</th>
                                            <th>Owner</th>
                                            <th>Asset Number</th>
                                            <th>Unit No</th>
                                            <th>Unit Name</th>
                                            <th>Unit Type</th>
                                            <th>Brand</th>
                                            <th>Receive Date</th>
                                            <th>Life Time</th>
                                            <th>Status</th>
                                            <th>IsActive</th>
                                            <th>Add User</th>
                                            <th>Last Edit Date</th>
                                            <th>Last Edit User</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @isset($dataTable1)
                                            @foreach ($dataTable1 as $data)
                                                <tr>
                                                    <td>
                                                        <!-- Print QR Code Button -->
                                                        <button class="btn btn-primary print-qr-btn"
                                                            data-unit="{{ $data->{'Unit No'} }}"
                                                            data-facility="{{ session('facility_info')[0]['Name'] ?? '' }}"
                                                            data-unit-name="{{ $data->{'Unit Name'} }}"
                                                            data-brand="{{ $data->{'Brand'} }}">
                                                            Print QR Code
                                                        </button>
                                                    </td>
                                                    <td>{{ $data->{'Add Date'} }}</td>
                                                    <td>{{ $data->{'Owner'} }}</td>
                                                    <td>{{ $data->{'Asset Number'} }}</td>
                                                    <td>{{ $data->{'Unit No'} }}</td>
                                                    <td>{{ $data->{'Unit Name'} }}</td>
                                                    <td>{{ $data->{'Unit Type'} }}</td>
                                                    <td>{{ $data->{'Brand'} }}</td>
                                                    <td>{{ $data->{'Receive Date'} }}</td>
                                                    <td>{{ $data->{'Life Time'} }}</td>
                                                    <td>{{ $data->{'Status'} }}</td>
                                                    <td>{{ $data->{'IsActive'} }}</td>
                                                    <td>{{ $data->{'Add User'} }}</td>
                                                    <td>{{ $data->{'Last Edit Date'} }}</td>
                                                    <td>{{ $data->{'Last Edit User'} }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="13" class="text-center">No data available</td>
                                            </tr>
                                        @endisset
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>

    </div>
    </div>

    <script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#filterableTable').DataTable({
                scrollY: '400px', // Tabel akan memiliki tinggi maksimum 500px dan scroll vertikal
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
                }
            });
        });
    </script>
    <script>
        // Handle Cancel button click to reset the form and prevent submission
        document.getElementById('cancelButton').addEventListener('click', function() {
            document.getElementById('saveForm').reset();
            // Optionally reset to the original route if necessary
            document.getElementById('saveForm').action =
                "{{ route('admin.master.public.maintenance.masteraset_save') }}";
        });

        // Handle Delete button by changing the form action before submission
        document.getElementById('deleteButton').addEventListener('click', function() {
            document.getElementById('saveForm').action =
                "{{ route('admin.master.public.maintenance.masteraset_delete') }}";
        });
    </script>

    {{-- <script>
        document.querySelectorAll('.print-qr-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                fetch("/module/get-printer")
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const printerPath = data.printerPath;
                            console.log("✅ Menggunakan Printer:", printerPath);

                            // Data untuk dikirim ke printer
                            const unitNo = button.getAttribute('data-unit');
                            const facility = button.getAttribute('data-facility');
                            const unitName = button.getAttribute('data-unit-name');
                            const brand = button.getAttribute('data-brand');

                            const zplCode = `^XA
                        ^FO20,0
                        ^BQN,2,10
                        ^FDQA,${unitNo}^FS
                        ^CF0,30
                        ^FO250,30^FD${facility}^FS
                        ^FO250,70^FD${unitNo}^FS
                        ^FO250,110^FD${brand}^FS
                        ^FO20,250^FD${unitName}^FS
                        ^XZ`;

                            console.log("🔹 ZPL Code yang dikirim:", zplCode);

                            fetch(`http://${printerPath}:9100`, { // Coba pakai port 9100
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "text/plain"
                                    },
                                    body: zplCode
                                })
                                .then(response => {
                                    if (response.ok) {
                                        console.log("✅ Print berhasil!");
                                        toastr.success("✅ Print berhasil!", "Success");
                                    } else {
                                        console.error("⚠️ Printer merespons dengan error:", response
                                            .statusText);
                                        toastr.error(
                                            `⚠️ Print gagal! Error: ${response.statusText}`,
                                            "Error");
                                    }
                                })
                                .catch(error => {
                                    console.error("⚠️ Gagal mengirim ZPL ke printer:", error);
                                    toastr.error(
                                        `⚠️ Gagal print! Tidak dapat mengakses printer di alamat: ${printerPath}`,
                                        "Error");
                                });

                        } else {
                            console.error("⚠️ Printer Tidak Ditemukan!");
                            toastr.error("⚠️ Printer tidak ditemukan di sistem!", "Error");
                        }
                    })
                    .catch(error => {
                        console.error("⚠️ Error Fetch Printer:", error);
                        toastr.error("⚠️ Tidak dapat mengambil data printer dari server!", "Error");
                    });
            });
        });
    </script> --}}


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownItems = document.querySelectorAll('.dropdown-item');

            dropdownItems.forEach(item => {
                item.addEventListener('click', function() {
                    const selectedValue = this.getAttribute('data-value');
                    console.log("Selected Value: ", selectedValue);

                    const dropdownMenu = this.closest('.dropdown-menu');
                    const dropdownButton = dropdownMenu.previousElementSibling;
                    const dropdownId = dropdownButton.id;

                    dropdownButton.innerHTML =
                        `${selectedValue} <i class="mdi mdi-chevron-down"></i>`;

                    const hiddenInput = document.querySelector(`#${dropdownId}_input`);
                    if (hiddenInput) {
                        hiddenInput.value = selectedValue;
                        console.log(`${dropdownId} Input Set: `, hiddenInput.value);
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector("input[name='no_unit']").addEventListener("keydown", function(event) {
                if (event.key === "Enter") {
                    event.preventDefault();

                    let noUnit = this.value.trim();
                    if (noUnit === "") return;

                    fetch("{{ route('admin.master.public.maintenance.masteraset_data') }}?no_unit=" +
                            encodeURIComponent(noUnit), {
                                method: "GET",
                                headers: {
                                    "X-Requested-With": "XMLHttpRequest"
                                }
                            })
                        .then(response => response.json())
                        .then(data => {
                            console.log("Response dari server:", data);

                            if (data.success && data.data.length > 0) {
                                let asset = data.data[0]; // Ambil objek pertama dari array
                                console.log("Data diterima:", asset);

                                // Cek apakah input ditemukan sebelum mengisi value
                                const asetNumberInput = document.querySelector(
                                    "input[name='aset_number']");
                                const unitNameInput = document.querySelector("input[name='unit_name']");
                                const unitTypeSelect = document.querySelector(
                                    "select[name='unit_type']");
                                const brandInput = document.querySelector("input[name='brand']");
                                const lifetimeInput = document.querySelector("input[name='lifetime']");
                                const receiveDateInput = document.querySelector(
                                    "input[name='receive_date']");
                                const isActiveInput = document.querySelector("input[name='is_active']");

                                const ownerInput = document.getElementById(
                                    "owner5r_input"); // Input hidden Owner
                                const ownerDropdownButton = document.getElementById(
                                    "owner5r"); // Tombol dropdown Owner
                                const ownerDropdownItems = document.querySelectorAll(
                                    ".dropdown-item[data-value]");

                                // ✅ UPDATE DROPDOWN OWNER DARI SERVER
                                if (asset.Owner) {
                                    ownerDropdownButton.innerHTML =
                                        `${asset.Owner} <i class="mdi mdi-chevron-down"></i>`;
                                    ownerInput.value = asset
                                        .Owner; // Simpan nilai Owner ke input hidden
                                }

                                // Saat klik pilihan dropdown, ubah teks tombol dan input hidden
                                ownerDropdownItems.forEach(item => {
                                    item.addEventListener("click", function(e) {
                                        e.preventDefault();
                                        ownerDropdownButton.innerHTML =
                                            `${this.dataset.value} <i class="mdi mdi-chevron-down"></i>`;
                                        ownerInput.value = this.dataset
                                            .value; // Simpan nilai Owner ke input hidden
                                    });
                                });

                                // ✅ UPDATE SELECT UNIT TYPE
                                if (unitTypeSelect) {
                                    unitTypeSelect.value = asset.UnitType ||
                                        ""; // Pastikan asset.UnitType berisi data dari server
                                }

                                // ✅ UPDATE INPUT TEXT LAINNYA
                                if (asetNumberInput) asetNumberInput.value = asset.AssetNumber || "";
                                if (unitNameInput) unitNameInput.value = asset.UnitName || "";
                                if (brandInput) brandInput.value = asset.Brand || "";
                                if (lifetimeInput) lifetimeInput.value = asset.LifeTime || "";
                                if (receiveDateInput) {
                                    receiveDateInput.value = asset.ReceiveDate ?
                                        new Date(asset.ReceiveDate).toISOString().split('T')[0] : "";
                                }
                                if (isActiveInput) {
                                    isActiveInput.value = asset.IsActive === "1" ? "Yes" : "No";
                                }
                            } else {
                                alert("Data tidak ditemukan");
                            }
                        })

                        .catch(error => console.error("Error:", error));
                }
            });
        });
    </script>


    <script>
        document.querySelectorAll('.print-qr-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const unitNo = this.getAttribute('data-unit');
                const facility = this.getAttribute('data-facility');
                const unitName = this.getAttribute('data-unit-name');
                const brand = this.getAttribute('data-brand');

                console.log(brand, unitName, unitNo, facility);

                // Membuat ZPL code untuk QR Code
                const zplCode = `^XA
            ^FO20,0
            ^BQN,2,10
            ^FDQA,${unitNo}^FS
            ^CF0,30
            ^FO250,30^FD${facility}^FS
            ^FO250,70^FD${unitNo}^FS
            ^FO250,110^FD${brand}^FS
            ^FO20,250^FD${unitName}^FS
            ^XZ`;

                // Kirim ZPL ke Labelary API
                const formData = new FormData();
                formData.append("file", new Blob([zplCode], {
                    type: "text/plain"
                }));

                fetch('https://api.labelary.com/v1/printers/8dpmm/labels/4x6/0/', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.blob()) // Mengubah response jadi gambar
                    .then(blob => {
                        const imageUrl = URL.createObjectURL(blob);
                        const previewWindow = window.open('', '', 'width=600,height=400');
                        previewWindow.document.write(`
                    <html>
                    <head><title>QR Code Preview</title></head>
                    <body>
                        <h3>QR Code Preview</h3>
                        <img src="${imageUrl}" style="max-width:100%">
                        <br>
                        <button onclick="window.print()">Print</button>
                    </body>
                    </html>
                `);
                        previewWindow.document.close();
                    })
                    .catch(error => console.error('Error generating barcode:', error));
            });
        });
    </script>

@endsection
