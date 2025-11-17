@extends('admin.dashboard')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <div class="row">
            <div class="col-12">
                <!-- Title -->
                <div class="page-title-box d-sm-flex align-items-center justify-content-center">
                    @if (session('facility_info'))
                    @foreach (session('facility_info') as $facility)
                    <h4 style="font-size: 1.5rem; font-weight: bold; text-align: center;">
                        CREATE PICKLIST BALIKAN
                    </h4>
                    <div class="page-title-right">
                        {{-- <ol class="breadcrumb m-0"> </ol> --}}
                    </div>
                    @endforeach
                    @else
                    @endif
                </div>
            </div>
            <!-- Row pertama untuk parameter -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white fw-bold">
                            Menu
                        </div>
                        <div class="card-body">
                            <!-- Grid untuk Input Store Tujuan, Nama Store, dan Tombol Show -->
                            <div class="row align-items-end">
                                <div class="col-md-2 mb-2">
                                    <button type="button" class="btn btn-success w-100" onclick="showForm('new')">
                                        New Picklist
                                    </button>
                                </div>

                                <div class="col-md-2 mb-2">
                                    <button type="button" class="btn btn-primary w-100" style="background-color: blue; color: white;" onclick="showForm('edit')">
                                        Edit Picklist
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Untuk new Picklist -->
            <!-- Row Kedua untuk input -->
            <div id="newPicklistForm" style="display: none;">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white fw-bold"> INPUT </div>
                            <div class="card-body">
                                <div class="row align-items-end">
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label d-block">Pilih Owner</label>
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
                                    <input type="hidden" id="selected_owners" name="selected_owners" value="{{ implode(';', session("input_data_" . Auth::guard('admin')->id() . ".selected_owners", [])) }}">
                                    <!-- END Membuat Button Owner -->
                                    <!-- Store Tujuan -->
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">Store Tujuan</label>
                                        <input type="text" class="form-control" id="tostore" name="to_store"
                                            value="{{ old('to_store') }}" placeholder="Masukan Store Tujuan">
                                    </div>
                                    <!-- Input Tanggal Kirim -->
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">Tanggal Kirim </label>
                                        <input type="date" class="form-control" id="deliverydate" name="deliverydate" required
                                            value="{{ old('deliveri_date', session('input_databr_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.deliveri_date')) }}">
                                    </div>
                                    <!-- Grid untuk Input SKU -->
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">Cari Nomor Article / SKU</label>
                                        <input type="text" class="form-control" id="skuInputpicklistshow" name="nomor_sku_picklist_show"
                                            value="{{ old('nomor_sku_picklist_show') }}"
                                            placeholder="Cari SKU lebih dari satu (pisahkan dengan ;)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Article</th>
                                            <th>DESCR</th>
                                            <th>Reference No</th>
                                            <th>Available Qty</th>
                                            <th>Foto 1</th>
                                            <th>Foto 2</th>
                                            <th>Foto 3</th>
                                            <th>Request QTY</th> <!-- Tambahkan kolom baru -->
                                        </tr>
                                    </thead>
                                    <tbody id="data-body">
                                        <!-- Data akan dimasukkan di sini -->
                                    </tbody>
                                </table>
                                <!-- Tombol Save -->
                                <div class="row">
                                    <div class="col-md-2 mb-2">
                                        <button type="button" id="saveButton" class="btn btn-info w-100">Save</button>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <button type="button" class="btn btn-danger w-100" id="cancelButton">Cancel</button>
                                    </div>
                                </div>
                                <!-- Modal Bootstrap untuk Preview Gambar -->
                                <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="imageModalLabel">Preview Gambar</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img id="modalImage" src="" class="img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Untuk new Picklist -->
            <!-- Untuk edit Picklist -->
            <div id="editPicklistForm" style="display: none;">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white fw-bold">
                                Parameter
                            </div>
                            <div class="card-body">
                                <div class="row align-items-end">
                                    <div class="col-md-3 mb-2">
                                        <label class="form-label">Picklist ID</label>
                                        <input type="text" class="form-control" id="picklistid" name="picklist_id"
                                            value="{{ old('picklist_id') }}" placeholder="Masukan Picklist ID">
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <button type="button" class="btn btn-success w-100" id="caributton">Find</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <table class="table mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Picklist ID</th>
                                                            <th>Store Tujuan</th>
                                                            <th>Tanggal Kirim</th>
                                                            <th>Owner</th>
                                                            <th>Reference No</th>
                                                            <th>Article</th>
                                                            <th>DESCR</th>
                                                            <th>Available Qty</th>
                                                            <th>Request Qty</th> <!-- Tambahkan kolom baru -->
                                                            <th>Foto 1</th>
                                                            <th>Foto 2</th>
                                                            <th>Foto 3</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="data-body1">
                                                        <!-- Data akan dimasukkan di sini -->
                                                    </tbody>
                                                </table>
                                                <div class="row">
                                                    <div class="col-md-2 mb-2">
                                                        <button type="button" id="saveeditButton" class="btn btn-info w-100">Save</button>
                                                    </div>
                                                    <div class="col-md-2 mb-2">
                                                        <button type="button" class="btn btn-danger w-100" id="canceeditlButton">Cancel</button>
                                                    </div>
                                                </div>
                                                <!-- Modal Bootstrap untuk Preview Gambar -->
                                                <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="imagePreviewLabel">Preview Gambar</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                <img id="previewImage" src="" class="img-fluid" alt="Preview Gambar">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal Konfirmasi -->
                                                <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteConfirmLabel">Konfirmasi Hapus</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menghapus item <b id="modalArticle"></b> dari Picklist <b id="modalPicklistID"></b>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Ya, Hapus!</button>
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
                    </div>
                </div>
             </div>
            <!-- end Untuk edit Picklist -->
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('backend/assets/js/owner-dropdown.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function showForm(type) {
        let newForm = document.getElementById('newPicklistForm');
        let editForm = document.getElementById('editPicklistForm');

        if (!newForm || !editForm) {
            alert("Form tidak ditemukan!");
            return;
        }
        console.log(document.getElementById('editPicklistForm').parentElement);
        console.log(document.getElementById('editPicklistForm').parentElement.parentElement);

        if (type === 'new') {
            newForm.style.display = 'block';
            editForm.style.display = 'none';
        } else {
            newForm.style.display = 'none';
            editForm.style.display = 'block';
        }
    }


    $(document).ready(function() {
        $("#saveeditButton").click(function() {
            let updatedData = [];

            $("#data-body1 tr").each(function() {
                let row = $(this);
                let picklistid = row.find("td:eq(0)").text().trim();
                let to_storer = row.find("td:eq(1)").text().trim();
                let deliverydate = row.find("td:eq(2)").text().trim();
                let storerkey = row.find("td:eq(3)").text().trim();
                let ID = row.find("td:eq(4)").text().trim();  // ID diambil dari kolom ke-4
                let article = row.find("td:eq(5)").text().trim();
                let DESCR = row.find("td:eq(6)").text().trim();
                let Available_Qty = row.find("td:eq(7)").text().trim();
                let requestQty = row.find(".request-qty").val().trim();
                if (picklistid && article && requestQty) {
                    updatedData.push({
                        picklistid: picklistid,
                        to_storer: to_storer,
                        deliverydate: deliverydate,
                        storerkey: storerkey,
                        ID: ID,   // Tambahkan ID ke dalam objek
                        article: article,
                        DESCR: DESCR,
                        Available_Qty: Available_Qty,
                        request_qty: requestQty
                    });
                }
            });

            if (updatedData.length === 0) {
                alert("Tidak ada perubahan data untuk disimpan.");
                return;
            }

            $.ajax({
                url: "{{ route('admin.transaction.storing.saveeditpicklist') }}", 
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    data: updatedData
                },
                beforeSend: function() {
                    console.log("Menyimpan data:", updatedData);
                    $("#saveeditButton").prop("disabled", true);
                },
                success: function(response) {
                    if (response.success === true) { // Cek pakai response.success
                        alert("Data berhasil disimpan!");
                        location.reload();
                    } else {
                        alert("Gagal menyimpan data: " + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.log("Error:", error);
                    alert("Terjadi kesalahan saat menyimpan data.");
                },
                complete: function() {
                    $("#saveeditButton").prop("disabled", false);
                }
            });
        });
    });

    $(document).ready(function() {
        $("#caributton").click(function() {
            let picklistId = $("#picklistid").val().trim();

            if (picklistId === "") {
                alert("Masukkan Picklist ID terlebih dahulu!");
                return;
            }
            $.ajax({
                url: '{{ route("admin.transaction.storing.findpicklist") }}', // Ganti dengan route yang sesuai
                method: "GET",
                data: {
                    _token: '{{ csrf_token() }}',
                    picklist_id: picklistId
                },
                beforeSend: function() {
                    console.log("Mencari Picklist ID:", picklistId);
                    $("#data-body1").html('<tr><td colspan="10">Loading...</td></tr>'); // Loading indicator
                },
                success: function(response) {
                    if (response.status === "success" && Array.isArray(response.datatabel2)) {
                        let tabelData = response.datatabel2;
                        let tableBody = $("#data-body1");
                        tableBody.empty(); // Kosongkan tabel sebelum diisi ulang
                        tabelData.forEach(function(item) {
                            let picklistid = item.picklistid ?? "N/A";
                            let to_storer = item.to_storer ?? "N/A";
                            let deliverydate = item.deliverydate ?? "N/A";
                            let storerkey = item.storerkey ?? "N/A";
                            let ID = item.ID ?? "N/A";
                            let article = item.article ?? "N/A";
                            let DESCR = item.DESCR ?? "N/A";
                            let Available_Qty = item.Available_Qty ?? "0";
                            let Request_Qty = item.Request_Qty ?? "0";
                            let foto1 = item.Foto1_base64 ?
                            `<img src="${item.Foto1_base64}" class="img-thumbnail img-clickable1" data-img="${item.Foto1_base64}">` :
                            "No Image";
                            let foto2 = item.Foto2_base64 ?
                            `<img src="${item.Foto2_base64}" class="img-thumbnail img-clickable1" data-img="${item.Foto2_base64}">` :
                            "No Image";
                            let foto3 = item.Foto3_base64 ?
                            `<img src="${item.Foto3_base64}" class="img-thumbnail img-clickable1" data-img="${item.Foto3_base64}">` :
                            "No Image";
                            let requestQtyInput = `
                            <input type="number" class="form-control request-qty" 
                                value="${Request_Qty}" min="1" max="${Available_Qty}" 
                                data-picklistid="${picklistid}">
                            `;
                            let row = `
                            <tr>
                                <td>${picklistid}</td>
                                <td>${to_storer}</td>
                                <td>${deliverydate}</td>
                                <td>${storerkey}</td>
                                <td>${ID}</td>
                                <td>${article}</td>
                                <td>${DESCR}</td>
                                <td>${Available_Qty}</td>
                                <td>${requestQtyInput}</td>
                                <td>${foto1}</td>
                                <td>${foto2}</td>
                                <td>${foto3}</td>
                                <td><button class="btn btn-danger btn-sm btn-delete-row">Hapus</button></td>
                            </tr>`;
                            tableBody.append(row);
                        });

                    } else {
                        alert("Picklist tidak ditemukan!");
                        $("#data-body1").html('<tr><td colspan="10" style="color:red;">Picklist tidak ditemukan</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.log("Error:", error);
                    alert("Terjadi kesalahan saat mencari Picklist!");
                }
            });
        });
    });

    let selectedRow; // Untuk menyimpan baris yang akan dihapus
    $(document).on("click", ".btn-delete-row", function() {
        selectedRow = $(this).closest("tr"); // Simpan referensi baris yang akan dihapus
        let picklistid = selectedRow.find("td:eq(0)").text().trim();
        let article = selectedRow.find("td:eq(5)").text().trim();
        let reference_no = selectedRow.find("td:eq(4)").text().trim();
        let qtyrequest = selectedRow.find(".request-qty").val()?.trim() || selectedRow.find("td:eq(8)").text().trim();

        qtyrequest = qtyrequest === "" ? 0 : parseInt(qtyrequest);

        // Tampilkan data di modal konfirmasi
        $("#modalPicklistID").text(picklistid);
        $("#modalArticle").text(article);

        // Simpan data untuk konfirmasi
        $("#confirmDeleteBtn").data("deleteData", { picklistid, article, reference_no, qtyrequest });

        // Tampilkan modal
        $("#deleteConfirmModal").modal("show");
    });

    $("#confirmDeleteBtn").on("click", function() {
        setTimeout(() => {
            $("#deleteConfirmModal").modal("hide");
        }, 100); // Tunggu 100ms sebelum menutup modal
        let deleteData = $(this).data("deleteData");

        $.ajax({
            url: "{{ route('admin.transaction.storing.deletepicklistitem') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                picklistid: deleteData.picklistid,
                article: deleteData.article,
                reference_no: deleteData.reference_no,
                qtyrequest: deleteData.qtyrequest
            },

            success: function(response) {
                if (response.status === "success") {
                    alert("Item berhasil dihapus!");
                    selectedRow.remove(); // Hapus baris dari tabel tanpa reload
                } else {
                    alert("Gagal menghapus item: " + response.message);
                }
                $("#deleteConfirmModal").modal("hide"); // Tutup modal setelah aksi
            },
            error: function(xhr, status, error) {
                console.log("Error:", error);
                alert("Terjadi kesalahan saat menghapus item.");
            }
        });
    });

    document.getElementById("skuInputpicklistshow").addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();

            let selectedOwners = document.getElementById("selected_owners").value.trim();

            // Jika selectedOwners kosong, tampilkan peringatan
            if (!selectedOwners) {
                alert("Silakan pilih minimal satu Owner sebelum mencari SKU!");
                return;
            }

            // Pecah nilai selectedOwners dengan pemisah ";"
            let ownerValues = selectedOwners ? selectedOwners.split(';') : [];

            // Jika lebih dari satu owner dipilih, tampilkan peringatan dan hentikan proses
            if (ownerValues.length > 1) {
                alert("Hanya boleh memilih satu owner!");
                return;
            }

            searchSKU();
        }
    });

    function searchSKU() {
        let skuValue = document.getElementById("skuInputpicklistshow").value;
        let Storerkey = document.getElementById("selected_owners").value;

        console.log("Selected Owners:", Storerkey);
        console.log("SKU Value:", skuValue);

        $.ajax({
            url: '{{ route("admin.transaction.storing.picklistbalikan") }}',
            method: 'GET',
            data: {
                _token: '{{ csrf_token() }}',
                nomor_sku_picklist_show: skuValue,
                selected_owners: Storerkey
            },
            beforeSend: function() {
                console.log("Mencari SKU: " + skuValue + " dengan Owners: " + Storerkey);
                $("#skuTableBody").html('<tr><td colspan="10">Loading...</td></tr>');
            },
            success: function(response) {
                if (response.status === "success" && Array.isArray(response.datatabel1)) {
                    let tabelData = response.datatabel1;
                    let tableBody = $("#data-body");
                    tableBody.empty(); // Kosongkan tabel sebelum diisi ulang
                    tabelData.forEach(function(item) {
                        let article = item.Article ?? "N/A";
                        let descr = item.DESCR ?? "N/A";
                        let Reference_no = item.Reference_no ?? "N/A";
                        let availableQty = item["Available Qty"] ?? "0";

                        let foto1 = item.Foto1_base64 ?
                            `<img src="${item.Foto1_base64}" class="img-thumbnail img-clickable">` :
                            "No Image";
                        let foto2 = item.Foto2_base64 ?
                            `<img src="${item.Foto2_base64}" class="img-thumbnail img-clickable">` :
                            "No Image";
                        let foto3 = item.Foto3_base64 ?
                            `<img src="${item.Foto3_base64}" class="img-thumbnail img-clickable">` :
                            "No Image";
                        let qtyInput = `<input type="number" class="form-control request_qty" 
                            min="1" max="${availableQty}" value="1"
                            data-article="${article}"
                            data-Reference_no="${Reference_no}">`;
                        let row = `
                            <tr>
                                <td>${article}</td>
                                <td>${descr}</td>
                                <td>${Reference_no}</td>
                                <td>${availableQty}</td>
                                <td>${foto1}</td>
                                <td>${foto2}</td>
                                <td>${foto3}</td>
                                <td>${qtyInput}</td>
                            </tr>
                            `;
                        tableBody.append(row);
                    });

                } else {
                    console.log("Data tidak ditemukan atau kosong.");
                    $("#skuTableBody").html('<tr><td colspan="10" style="color:red;">Data tidak ditemukan</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                console.log("Error:", error);
                $("#skuTableBody").html('<tr><td colspan="10" style="color:red;">Terjadi kesalahan dalam pencarian</td></tr>');
            }
        });
    }

    // Simpan Data ke Controller via AJAX
    $("#saveButton").click(function() {
        // Ambil nilai dari input
        let toStorer = $("#tostore").val();
        let skuInput = $("#skuInputpicklistshow").val();
        let Storerkey = document.getElementById("selected_owners").value;
        // Cek apakah toStorer dan skuInput kosong
        if (!toStorer) {
            alert("Store tujuan belum di isi");
            return; // Hentikan eksekusi jika to_storer kosong
        }

        if (!skuInput) {
            alert("SKU belum di cari");
            return; // Hentikan eksekusi jika skuInput kosong
        }

        let items = [];

        // Ambil data barang
        $(".request_qty").each(function() {
            // Pastikan qty tidak kosong atau 0 sebelum ditambahkan ke items
            let qty = $(this).val();
            if (qty && qty > 0) {
                items.push({
                    article: $(this).data("article"),
                    Reference_no:$(this).data("reference_no"),
                    qty: qty
                });
            }
        });

        // Cek jika tidak ada data di items
        if (items.length === 0) {
            alert("Tidak ada data yang dapat disimpan.");
            return; // Hentikan eksekusi jika items kosong
        }

        console.log("Data yang dikirim:", {
            to_storer: $("#tostore").val(),
            deliverydate: $("#deliveri_date").val(),
            selected_owners: $("#selected_owners").val(),
            items: items
        });

        $.ajax({
            url: "{{ route('admin.transaction.storing.summary_picklistbalikan') }}",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            data: {
                to_storer: $("#tostore").val(),
                deliverydate: $("#deliverydate").val(),
                selected_owners: $("#selected_owners").val(),
                items: items
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    window.location.href = response.redirect; // Redirect ke halaman lain
                } else {
                    alert("Gagal menyimpan data.");
                }
            },
            error: function() {
                alert("Terjadi kesalahan saat menyimpan data.");
            }
        });

    });

    // EVENT LISTENER UNTUK GAMBAR (Delegated Event)
    $(document).ready(function() {
        $(document).on("click", ".img-clickable", function() {
            let src = $(this).attr("src");

            if (src) {
                console.log("Gambar diklik:", src);
                $("#modalImage").attr("src", src);
                $("#imageModal").modal("show"); // Panggil modal dengan jQuery
            }
        });
    });

    // Event delegation untuk menangani klik pada gambar yang ditambahkan secara dinamis
    $(document).on("click", ".img-clickable1", function() {
        let imgSrc = $(this).data("img");
        $("#previewImage").attr("src", imgSrc);
        $("#imagePreviewModal").modal("show");
    });


    $(document).on("input", ".request_qty", function() {
        let maxQty = parseInt($(this).attr("max"), 10);
        let currentQty = parseInt($(this).val(), 10);

        if (currentQty > maxQty) {
            $(this).val(maxQty); // Jika lebih dari max, set ke max
        } else if (currentQty < 1 || isNaN(currentQty)) {
            $(this).val(1); // Jika kurang dari 1 atau kosong, set ke 1
        }
    });


    $(document).on("input", ".request-qty", function() {
        let maxQty = parseInt($(this).attr("max"), 10);
        let currentQty = parseInt($(this).val(), 10);

        if (currentQty > maxQty) {
            $(this).val(maxQty); // Jika lebih dari max, set ke max
        } else if (currentQty < 1 || isNaN(currentQty)) {
            $(this).val(1); // Jika kurang dari 1 atau kosong, set ke 1
        }
    });

    document.getElementById("cancelButton").addEventListener("click", function() {
        // 1. Kosongkan tabel
        document.getElementById("data-body").innerHTML = "";

        // 2. Hapus checklist pada Owner (asumsi input checkbox)
        let ownerCheckboxes = document.querySelectorAll("#selected_owners input[type='checkbox']");
        ownerCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });

        // 3. Kosongkan input "To Store"
        document.getElementById("tostore").value = "";

        // 4. Kosongkan input "Tanggal Kirim"
        document.getElementById("deliverydata").value = "";

        // 5. Kosongkan input SKU
        document.getElementById("skuInputpicklistshow").value = "";

        // 6. Kosongkan nilai selectedOwners jika menggunakan input hidden atau select
        document.getElementById("selected_owners").value = "";
    });
</script>

<style>
    .img-thumbnail {
        width: 70px;
        height: auto;
        cursor: pointer;
        border-radius: 5px;
        border: 1px solid #ddd;
        padding: 2px;
    }
</style>



@endsection