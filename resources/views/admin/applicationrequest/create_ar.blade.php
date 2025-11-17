@extends('admin.dashboard')
@section('admin')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-center">
                        @if (session('facility_info'))
                            @foreach (session('facility_info') as $facility)
                                <h4 class="text-center fw-bold">
                                    CREATE AR {{ $facility['Name'] }}
                                </h4>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <form id="myForm" enctype="multipart/form-data">
                @csrf
                {{-- Header --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center py-2"
                                style="cursor: pointer; font-size: 0.9rem; font-style: italic;" data-bs-toggle="collapse"
                                data-bs-target="#AR">
                                <h5 class="mb-0 text-white fs-6">Application Request</h5>
                                <i class="fas fa-eye toggle-icon fs-5"></i>
                            </div>

                            <div id="AR" class="collapse">
                                <div class="card-body">
                                    @isset($getEmpInfo)
                                        @foreach ($getEmpInfo as $emp)
                                            <div class="row">
                                                <div class="col-md-6 col-12 mb-3">
                                                    <label for="requestNo" class="form-label"
                                                        style="font-style: italic;">Request
                                                        No</label>
                                                    <input type="text" class="form-control" id="requestNo" readonly
                                                        name="requestNo" style="background-color: #f8f9fa; color: #000;">
                                                </div>
                                                <div class="col-md-6 col-12 mb-3">
                                                    <label for="email" class="form-label"
                                                        style="font-style: italic;">Email</label>
                                                    <input type="text" class="form-control" id="email" name="email"
                                                        value="{{ $emp->Employee_Email ?? '' }}" readonly
                                                        style="background-color: #f8f9fa; color: #000;">
                                                </div>
                                                <div class="col-md-6 col-12 mb-3">
                                                    <label for="userId" class="form-label"
                                                        style="font-style: italic;">UserID</label>
                                                    <input type="text" class="form-control" id="userId" name="userId"
                                                        value="{{ $emp->Employee_ID ?? '' }}" readonly
                                                        style="background-color: #f8f9fa; color: #000;">
                                                </div>
                                                <div class="col-md-6 col-12 mb-3">
                                                    <label for="jobTitle" class="form-label" style="font-style: italic;">Job
                                                        Title
                                                        Name</label>
                                                    <input type="text" class="form-control" id="jobTitle" name="jobTitle"
                                                        value="{{ $emp->Job_Title_Name ?? '' }}" readonly
                                                        style="background-color: #f8f9fa; color: #000;">
                                                </div>
                                                <div class="col-md-6 col-12 mb-3">
                                                    <label for="organizationName" class="form-label"
                                                        style="font-style: italic;">Organization Name</label>
                                                    <input type="text" class="form-control" id="organizationName"
                                                        name="organizationName" value="{{ $emp->Organization_Name ?? '' }}"
                                                        readonly style="background-color: #f8f9fa; color: #000;">
                                                </div>
                                                <div class="col-md-6 col-12 mb-3">
                                                    <label for="companyName" class="form-label"
                                                        style="font-style: italic;">Company
                                                        Name</label>
                                                    <input type="text" class="form-control" id="companyName"
                                                        name="companyName" value="{{ $emp->Company_Name ?? '' }}" readonly
                                                        style="background-color: #f8f9fa; color: #000;">
                                                </div>
                                            </div>
                                        @endforeach
                                    @endisset

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Detail --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center py-2"
                                style="cursor: pointer; font-size: 0.9rem; font-style: italic;" data-bs-toggle="collapse"
                                data-bs-target="#detailAR">
                                <h5 class="mb-0 text-white fs-6">Application Request Detail</h5>
                                <i class="fas fa-eye toggle-icon fs-5"></i>
                            </div>

                            <div id="detailAR" class="collapse">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <!-- Project Name (70%) & Pilot Project (30%) -->
                                        <div class="col-md-12">
                                            <label for="project_name" class="form-label"style="font-style: italic;">Project
                                                Name</label>
                                            <input type="text" class="form-control" id="project_name" name="project_name"
                                                placeholder="Input Project Name" required>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="pilot_project" class="form-label"
                                                style="font-style: italic;">Pilot
                                                Project</label>
                                            <select class="form-control" id="pilot_project" name="pilot_project">
                                                @isset($getDCPilot)
                                                    @foreach ($getDCPilot as $dc)
                                                        <option value="{{ $dc->Name }}">{{ $dc->Name }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="">Data tidak tersedia</option>
                                                @endisset
                                            </select>
                                        </div>

                                        <!-- Company Impact (Multiple Choice) -->
                                        <div class="col-md-3">
                                            <label class="form-label" style="font-style: italic;">Company Impact</label>
                                            <button type="button"
                                                class="form-control btn btn-light d-flex justify-content-between align-items-center"
                                                id="toggleCompanyImpact">
                                                All <span id="arrowIcon">▼</span>
                                            </button>

                                            <div id="companyImpactDropdown" class="dropdown-menu p-3"
                                                style="display: none;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="impact_all">
                                                    <label class="form-check-label" for="impact_all">All</label>
                                                </div>

                                                @isset($getCompanyImpact)
                                                    @foreach ($getCompanyImpact as $company)
                                                        <div class="form-check">
                                                            <input class="form-check-input impact-option" type="checkbox"
                                                                value="{{ $company->Company }}"
                                                                id="impact_{{ $loop->index }}">
                                                            <label class="form-check-label"
                                                                for="impact_{{ $loop->index }}">{{ $company->Company }}</label>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="text-muted">Data tidak tersedia</div>
                                                @endisset
                                            </div>

                                            <input type="hidden" id="companyImpactValue" name="company_impact">
                                        </div>

                                        <!-- Request Type & Application Type -->
                                        <div class="col-md-3">
                                            <label for="request_type" class="form-label"
                                                style="font-style: italic;">Request Type</label>
                                            <select class="form-control" id="request_type" name="request_type">
                                                @isset($getReqType)
                                                    @foreach ($getReqType as $req)
                                                        <option value="{{ $req->RequestType }}">{{ $req->RequestType }}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    <option value="">Data tidak tersedia</option>
                                                @endisset
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="application_type" class="form-label"
                                                style="font-style: italic;">Application Type</label>
                                            <select class="form-control" id="application_type" name="application_type"
                                                disabled>
                                                @isset($getListApp)
                                                    @foreach ($getListApp as $app)
                                                        <option value="{{ $app->ListApp }}">{{ $app->ListApp }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="">Data tidak tersedia</option>
                                                @endisset
                                            </select>
                                        </div>



                                        <!-- Project Owner -->
                                        <div class="col-md-2">
                                            <label class="form-label" style="font-style: italic;">Project Owner</label>
                                            <input type="text" class="form-control" id="owner_id" name="owner_id"
                                                placeholder="User ID" required>
                                        </div>

                                        <!-- Owner Name -->
                                        <div class="col-md-5">
                                            <label class="form-label" style="font-style: italic; color: white"> - </label>
                                            <input type="text" class="form-control" id="owner_name" name="owner_name"
                                                readonly placeholder="User Name" required>
                                        </div>

                                        <!-- Superior -->
                                        <div class="col-md-5">
                                            <label class="form-label" style="font-style: italic;">Superior</label>
                                            <input type="text" class="form-control" id="superior" name="superior"
                                                readonly placeholder="Superior" required>
                                        </div>




                                        <div class="col-md-2">
                                            <label for="go_to_klip" class="form-label" style="font-style: italic;">Go To
                                                Klip</label>
                                            <select class="form-control" id="go_to_klip" name="go_to_klip">
                                                @isset($getKLIP)
                                                    @foreach ($getKLIP as $klip)
                                                        <option value="{{ $klip->Improve }}">{{ $klip->Improve }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="">Data tidak tersedia</option>
                                                @endisset
                                            </select>
                                        </div>

                                        <!-- Klip Number -->
                                        <div class="col-md-5">
                                            <label for="klip_number" class="form-label"style="font-style: italic;">Klip
                                                Number</label>
                                            <input type="text" class="form-control" id="klip_number" disabled
                                                name="klip_number" placeholder="Masukkan Klip Number" required>
                                        </div>

                                        <div class="col-md-5">
                                            <label for="expected_go_live"
                                                class="form-label"style="font-style: italic;">Expected Go Live</label>
                                            <input type="date" class="form-control" id="expected_go_live"
                                                name="expected_go_live" value="{{ date('Y-m-d') }}" required>

                                        </div>
                                        <div class="col-md-12">
                                            <label for="spasiya" class="form-label"style="font-style: italic;"></label>

                                        </div>
                                        <!-- Detail Project -->
                                        <div class="col-md-6">
                                            <label class="form-label" style="font-style: italic;">Latar Belakang (Masalah
                                                dan Peluang)</label>
                                            <textarea class="form-control" name="latar_belakang" id="latar_belakang" rows="4" required></textarea>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label" style="font-style: italic;">Kondisi Yang Ingin
                                                Dicapai (Manfaat/Keuntungan/Tujuan/Target)</label>
                                            <textarea class="form-control" name="kondisi_dicapai" id="kondisi_dicapai" rows="4" required></textarea>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label" style="font-style: italic;">Kondisi Saat Ini</label>
                                            <textarea class="form-control" name="kondisi_saat_ini" id="kondisi_saat_ini" rows="4" required></textarea>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label" style="font-style: italic;">Cara Untuk Menyelesaikan
                                                Masalah/Cara Merealisasikan Peluang</label>
                                            <textarea class="form-control" name="cara_selesaikan" id="cara_selesaikan" rows="4" required></textarea>
                                        </div>



                                        <div class="col-md-12">
                                            <label class="form-label" style="font-style: italic;">Comment</label>
                                            <div class="mb-2">
                                                <!-- Input file tersembunyi -->
                                                <input type="file" id="fileUpload" name="attachment" class="d-none"
                                                    accept=".pdf,.doc,.docx,.jpg,.png ,.ods" />

                                                <!-- Tombol upload -->
                                                <button type="button" class="btn btn-primary btn-sm" id="btnUpload">
                                                    <i class="mdi mdi-upload"></i> Upload
                                                </button>

                                                <!-- Tombol download -->
                                                <button type="button" class="btn btn-success btn-sm" id="btnDownload">
                                                    <i class="mdi mdi-download"></i> Download
                                                </button>

                                                <!-- Tombol hapus -->
                                                <button type="button" class="btn btn-danger btn-sm" id="btnDelete">
                                                    <i class="mdi mdi-trash-can"></i> Hapus
                                                </button>
                                            </div>

                                            <!-- Tempat Menampilkan Nama File yang Dipilih -->
                                            <div id="fileInfo" class="text-muted"></div>

                                            <!-- Input hidden untuk memastikan pengiriman file -->
                                            <input type="hidden" id="attachmentName" name="attachmentName">

                                            <textarea class="form-control" id="comment" name="comment" rows="4" required></textarea>
                                        </div>



                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- RACI AR -->
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center py-2"
                                style="cursor: pointer; font-size: 0.9rem; font-style: italic;" data-bs-toggle="collapse"
                                data-bs-target="#raciAR">
                                <h5 class="mb-0 text-white fs-6">Raci AR</h5>
                                <i class="fas fa-eye toggle-icon fs-5"></i>
                            </div>
                            <div id="raciAR" class="collapse">
                                <div class="card-body">
                                    <div class="row">
                                        <!-- RESPONSIBLE -->
                                        <div class="col-lg-3 col-md-6 col-12 mb-3">
                                            <div class="card shadow-sm">
                                                <div class="card-body">
                                                    <h6 class="fw-bold">Responsible</h6>
                                                    <p class="text-muted" style="font-size: 0.85rem;">
                                                        Orang atau tim yang bertanggung jawab langsung untuk melakukan
                                                        pekerjaan
                                                        atau menyelesaikan tugas.
                                                    </p>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="raciR"
                                                            placeholder="Masukkan User ID To Responsible">
                                                        <button class="btn btn-primary" type="button"
                                                            onclick="addData('raciR', 'tableR')">Add</button>
                                                    </div>
                                                    <table class="table mt-2">
                                                        <tbody id="tableR"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- ACCOUNTABLE -->

                                        <div class="col-lg-3 col-md-6 col-12 mb-3">
                                            <div class="card shadow-sm">
                                                <div class="card-body">
                                                    <h6 class="fw-bold">Accountable</h6>
                                                    <p class="text-muted" style="font-size: 0.85rem;">
                                                        Orang yang memiliki kewenangan akhir dan bertanggung jawab terhadap
                                                        hasil dari tugas tersebut.
                                                    </p>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="raciA"
                                                            placeholder="Masukkan User ID To Accountable">
                                                        <button class="btn btn-primary" type="button"
                                                            onclick="addData('raciA', 'tableA')">Add</button>
                                                    </div>
                                                    <table class="table mt-2">
                                                        <tbody id="tableA"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- CONSULTED -->
                                        <div class="col-lg-3 col-md-6 col-12 mb-3">
                                            <div class="card shadow-sm">
                                                <div class="card-body">
                                                    <h6 class="fw-bold">Consulted</h6>
                                                    <p class="text-muted" style="font-size: 0.85rem;">
                                                        Orang yang perlu dikonsultasikan sebelum atau selama pekerjaan
                                                        dilakukan.
                                                    </p>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="raciC"
                                                            placeholder="Masukkan User ID To Consulted">
                                                        <button class="btn btn-primary" type="button"
                                                            onclick="addData('raciC', 'tableC')">Add</button>
                                                    </div>
                                                    <table class="table mt-2">
                                                        <tbody id="tableC"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- INFORMED -->
                                        <div class="col-lg-3 col-md-6 col-12 mb-3">
                                            <div class="card shadow-sm">
                                                <div class="card-body">
                                                    <h6 class="fw-bold">Informed</h6>
                                                    <p class="text-muted" style="font-size: 0.85rem;">
                                                        Orang yang perlu diberi tahu tentang kemajuan atau hasil pekerjaan.
                                                    </p>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="raciI"
                                                            placeholder="Masukkan User ID To Informed">
                                                        <button class="btn btn-primary" type="button"
                                                            onclick="addData('raciI', 'tableI')">Add</button>
                                                    </div>
                                                    <table class="table mt-2">
                                                        <tbody id="tableI"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Preview Data -->
                                    <div class="mt-3" id="previewContainer" style="display: none;">
                                        <h6>Data yang akan dikirim:</h6>
                                        <p id="previewData" class="text-primary fw-bold"></p>
                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </form>
            <div class="row justify-content-left">
                <!-- Tombol Save pakai AJAX -->
                <div class="col-md-2 col-6 mb-2">
                    <button type="button" class="btn btn-warning w-100" id="btnSave">Save</button>
                </div>
                <!-- Tombol Submit untuk Insert -->
                <div class="col-md-2 col-6 mb-2">
                    <button class="btn btn-primary w-100" type="submit" id="submitButton"
                        style="display: none;">Submit</button>
                </div>
            </div>




        </div>
    </div>

    <script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Toggle icon pada card header
            document.querySelectorAll(".card-header").forEach(header => {
                header.addEventListener("click", function() {
                    let icon = this.querySelector(".toggle-icon");
                    icon.classList.toggle("fa-eye");
                    icon.classList.toggle("fa-eye-slash");
                });
            });

            // Toggle input klip_number berdasarkan pilihan go_to_klip
            // Toggle klipNumber
            let goToKlip = document.getElementById("go_to_klip");
            let klipNumber = document.getElementById("klip_number");

            function toggleKlipNumber() {
                if (goToKlip.value === "KLIP") {
                    klipNumber.removeAttribute("disabled");
                } else {
                    klipNumber.setAttribute("disabled", "disabled");
                    klipNumber.value = "-"; // Set nilai ke "-" jika disabled
                }
            }

            if (goToKlip) {
                toggleKlipNumber();
                goToKlip.addEventListener("change", toggleKlipNumber);
            }

            // Toggle application_type berdasarkan request_type
            let requestType = document.getElementById("request_type");
            let applicationType = document.getElementById("application_type");

            function toggleApplicationType() {
                if (requestType.value === "Application Request (AR)") {
                    applicationType.removeAttribute("disabled");
                } else {
                    applicationType.setAttribute("disabled", "disabled");
                }
            }

            if (requestType) {
                toggleApplicationType();
                requestType.addEventListener("change", toggleApplicationType);
            }

            // Toggle Buat CheckBox Company Impact
            let toggleButton = document.getElementById("toggleCompanyImpact");
            let dropdown = document.getElementById("companyImpactDropdown");
            let allCheckbox = document.getElementById("impact_all");
            let checkboxes = document.querySelectorAll(".impact-option");
            let hiddenInput = document.getElementById("companyImpactValue");

            // **Default semua checkbox aktif saat halaman dimuat**
            allCheckbox.checked = true;
            checkboxes.forEach(cb => cb.checked = true);
            updateHiddenInput(); // Perbarui input hidden dengan nilai default
            // Toggle dropdown saat tombol diklik
            toggleButton.addEventListener("click", function() {
                dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
            });
            allCheckbox.addEventListener("change", function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateHiddenInput();
            });
            checkboxes.forEach(cb => {
                cb.addEventListener("change", function() {
                    allCheckbox.checked = [...checkboxes].every(cb => cb.checked);
                    updateHiddenInput();
                });
            });

            function updateHiddenInput() {
                let selectedValues = [...checkboxes]
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);
                hiddenInput.value = selectedValues.join(", ");
            }
            document.addEventListener("click", function(event) {
                if (!dropdown.contains(event.target) && !toggleButton.contains(event.target)) {
                    dropdown.style.display = "none";
                }
            });
            document.addEventListener("keydown", function(event) {
                if (event.key === "Enter" && dropdown.style.display === "block") {
                    dropdown.style.display = "none";
                }
            });
        });

        ////Ini Untuk Upload dan Download
        let selectedFile = null; // Menyimpan file yang dipilih sementara

        const fileInput = document.getElementById("fileUpload");
        const btnUpload = document.getElementById("btnUpload");
        const btnDownload = document.getElementById("btnDownload");
        const btnDelete = document.getElementById("btnDelete");
        const fileInfo = document.getElementById("fileInfo");
        const fileNameInput = document.getElementById("attachmentName");

        // Event ketika tombol Upload diklik
        btnUpload.addEventListener("click", function() {
            fileInput.click(); // Memunculkan file picker
        });

        // Event ketika file dipilih
        fileInput.addEventListener("change", function() {
            if (fileInput.files.length > 0) {
                let file = fileInput.files[0];

                // Validasi ukuran file (maks 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    Swal.fire({
                        icon: "warning",
                        title: "Eror",
                        text: "File terlalu besar! Maksimal 5MB.",
                        timer: 2000,
                        showConfirmButton: false
                    });
                    fileInput.value = ""; // Reset input file
                    return;
                }

                selectedFile = file;
                fileInfo.innerHTML = `File: ${file.name} (${(file.size / 1024).toFixed(2)} KB)`;

                // Simpan nama file di input hidden
                fileNameInput.value = file.name;
            }
        });

        // Event ketika tombol Download diklik
        btnDownload.addEventListener("click", function() {
            if (!selectedFile) {
                Swal.fire({
                    icon: "warning",
                    title: "Eror",
                    text: "Tidak ada file untuk di-download!",
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            const fileURL = URL.createObjectURL(selectedFile);
            const a = document.createElement("a");
            a.href = fileURL;
            a.download = selectedFile.name;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        });

        // Event ketika tombol Hapus diklik
        btnDelete.addEventListener("click", function() {
            selectedFile = null;
            fileInput.value = ""; // Reset file input
            fileInfo.innerHTML = ""; // Hapus informasi file
            fileNameInput.value = ""; // Kosongkan input hidden
        });


        //// RACI
        function addData(inputId, tableId, raciType) {
            let inputValue = $("#" + inputId).val().trim();
            if (inputValue === "") return;

            let tableBody = $("#" + tableId);

            // Cek apakah UserID sudah ada di tabel (tidak boleh duplikat)
            let isDuplicate = tableBody.find("tr").filter(function() {
                return $(this).find("td:first").text() === inputValue;
            }).length > 0;

            if (isDuplicate) {
                Swal.fire({
                    icon: "warning",
                    title: "Error",
                    text: "Data sudah ada , tidak bisa duplicate!",
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            // Jika kategori ACCOUNTABLE (A) dan sudah ada data, maka tolak input baru
            if (tableId === "tableA" && tableBody.children().length > 0) {
                Swal.fire({
                    icon: "warning",
                    title: "Error",
                    text: "Hanya boleh satu userid/orang sebagai Accountable!",
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            // Panggil AJAX untuk validasi user
            $.ajax({

                url: "{{ route('admin.ar.getValidasiUser') }}", // Pakai route() dari Laravel 
                type: "GET",
                data: {
                    userID: inputValue
                },
                success: function(response) {
                    if (response.success) {
                        let user = response.data;

                        let newRow = `
                        <tr>
                            <td>${user.Employee_ID}</td>
                            <td>${user.Employee_Name}</td>
                            <td><button class="btn btn-sm btn-danger" onclick="removeData(this)">X</button></td>
                        </tr>`;

                        tableBody.append(newRow);
                        $("#" + inputId).val(""); // Clear input
                        updatePreview(); // Update preview data
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "UserID tidak ditemukan!",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Terjadi kesalahan dalam mengambil data!",
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        }

        window.removeData = function(button) {
            $(button).closest("tr").remove();
            updatePreview(); // Update preview setelah data dihapus
        };

        function updatePreview() {
            let values = [];

            // Ambil data dari setiap tabel dan format sesuai RACI
            $("tbody").each(function() {
                let raciType = $(this).attr("id").replace("table",
                    ""); // Ambil huruf R, A, C, I
                $(this).find("tr td:first-child").each(function() {
                    values.push($(this).text() + raciType);
                });
            });

            let raciString = values.join(";");
            $("#previewData").text(raciString);
        }

        // Event listener untuk tombol tambah
        $(".btn-add").on("click", function() {
            let inputId = $(this).data("input");
            let tableId = $(this).data("table");
            let raciType = $(this).data("raci");
            addData(inputId, tableId, raciType);
        });
    </script>

    <script>
        $(document).ready(function() {
            let allowSubmit = false; // Flag untuk mengizinkan submit

            // Cegah form submit jika tekan Enter di input
            $("#owner_id").on("keypress", function(e) {
                if (e.which == 13) { // Jika Enter ditekan
                    e.preventDefault(); // Mencegah submit form

                    let ownerID = $(this).val().trim();
                    if (ownerID === "") return;

                    $.ajax({
                        url: "{{ route('admin.ar.getSuperiorAR') }}", // Pastikan route benar
                        type: "POST",
                        data: {
                            owner_id: ownerID,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                $("#owner_name").val(response.data.Employee_Name);
                                $("#superior").val(response.data.Superior);
                            } else {
                                $("#owner_name").val("");
                                $("#superior").val("");

                                Swal.fire({
                                    icon: "warning",
                                    title: "Data Tidak Ditemukan",
                                    text: "Project Owner ID tidak ditemukan!",
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: "error",
                                title: "Server Error",
                                text: "Terjadi kesalahan saat mengambil data!",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });

            // Izinkan submit saat klik tombol Save atau Submit
            $("#btnSave, #submitButton").on("click", function() {
                allowSubmit = true;
            });

            // Cegah submit jika bukan karena klik tombol Save/Submit
            $("#myForm").on("submit", function(e) {
                if (!allowSubmit) {
                    e.preventDefault();
                }
                allowSubmit = false; // Reset setelah submit
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            let allowSubmit = false;

            $("#btnSave").on("click", function(e) {

                e.preventDefault(); // Hindari submit otomatis jika ada validasi gagal

                let requiredFields = [{
                        id: "#userId",
                        label: "User ID"
                    },
                    {
                        id: "#companyName",
                        label: "Company Name"
                    },
                    {
                        id: "#jobTitle",
                        label: "Job Title"
                    },
                    {
                        id: "#organizationName",
                        label: "Organization Name"
                    },
                    {
                        id: "#email",
                        label: "Email"
                    },
                    {
                        id: "#project_name",
                        label: "Project Name"
                    },
                    {
                        id: "#pilot_project",
                        label: "Pilot Project"
                    },
                    {
                        id: "#companyImpactValue",
                        label: "Company Impact"
                    },
                    {
                        id: "#owner_id",
                        label: "Owner ID"
                    },
                    {
                        id: "#superior",
                        label: "Superior"
                    },
                    {
                        id: "#request_type",
                        label: "Request Type"
                    },
                    {
                        id: "#application_type",
                        label: "Application Type"
                    },
                    {
                        id: "#go_to_klip",
                        label: "Go to KLIP"
                    },
                    {
                        id: "#klip_number",
                        label: "KLIP Number"
                    },
                    {
                        id: "#expected_go_live",
                        label: "Expected Go Live"
                    },
                    {
                        id: "#latar_belakang",
                        label: "Latar Belakang"
                    },
                    {
                        id: "#kondisi_dicapai",
                        label: "Kondisi Dicapai"
                    },
                    {
                        id: "#kondisi_saat_ini",
                        label: "Kondisi Saat Ini"
                    },
                    {
                        id: "#cara_selesaikan",
                        label: "Cara Selesaikan"
                    },
                    {
                        id: "#attachmentName", // Pastikan file sudah dipilih sebelum submit
                        label: "File Attachment"
                    }
                ];

                let emptyFields = [];
                let isValid = true;

                // Cek setiap field apakah kosong
                requiredFields.forEach(function(field) {
                    let value = $(field.id).val();
                    if (!value || value.trim() ===
                        "") { // Pastikan null dan string kosong dicek
                        emptyFields.push(field.label);
                        isValid = false;
                    }
                });

                if (!isValid) {
                    Swal.fire({
                        icon: "error",
                        title: "Data Tidak Lengkap!",
                        text: "Mohon isi field berikut: \n\n" + emptyFields.join(", "),
                        confirmButtonText: "OK"
                    });
                    return; // Hentikan AJAX jika ada field kosong
                }


                let raciTables = [{
                        id: "#tableR",
                        label: "Responsible"
                    },
                    {
                        id: "#tableA",
                        label: "Accountable"
                    },
                    {
                        id: "#tableC",
                        label: "Consulted"
                    },
                    {
                        id: "#tableI",
                        label: "Informed"
                    }
                ];

                let emptyTables = [];
                let isTableValid = true;

                // Cek apakah ada data di dalam masing-masing tabel
                raciTables.forEach(function(table) {
                    if ($(table.id).find("tr").length === 0) { // Jika tabel kosong
                        emptyTables.push(table.label);
                        isTableValid = false;
                    }
                });

                if (!isTableValid) {
                    Swal.fire({
                        icon: "error",
                        title: "Data RACI Tidak Lengkap!",
                        text: "Mohon isi data pada tabel berikut: \n\n" + emptyTables.join(
                            ", "),
                        confirmButtonText: "OK"
                    });
                    return; // Hentikan AJAX jika tabel kosong
                }

                let raciData = $("#previewData").html()
                    .trim(); // Menggunakan .html() jika teks tidak terbaca
                console.log("RACI Data:", raciData);

                // ✅ Jika semua field valid, lanjutkan AJAX request
                $.ajax({
                    url: "{{ route('admin.ar.create_ar.saveview') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        requestNo: $("#requestNo").val(),
                        userId: $("#userId").val(),
                        companyName: $("#companyName").val(),
                        jobTitle: $("#jobTitle").val(),
                        organizationName: $("#organizationName").val(),
                        email: $("#email").val(),
                        project_name: $("#project_name").val(),
                        pilot_project: $("#pilot_project").val(),
                        company_impact: $("#companyImpactValue").val(),
                        owner_id: $("#owner_id").val(),
                        superior: $("#superior").val(),
                        request_type: $("#request_type").val(),
                        application_type: $("#application_type").val(),
                        go_to_klip: $("#go_to_klip").val(),
                        klip_number: $("#klip_number").val(),
                        expected_go_live: $("#expected_go_live").val(),
                        latar_belakang: $("#latar_belakang").val(),
                        kondisi_dicapai: $("#kondisi_dicapai").val(),
                        kondisi_saat_ini: $("#kondisi_saat_ini").val(),
                        cara_selesaikan: $("#cara_selesaikan").val(),
                        attachmentName: $("#attachmentName").val(),
                        comment: $("#comment").val(),
                        raci_data: raciData // Tambahkan data RACI
                    },

                    success: function(response) {
                        if (response.success) {
                            $("#requestNo").val(response.data.ApplicationNo);

                            let file = $("#fileUpload")[0].files[0];
                            let requestNo = $("#requestNo").val(); // Ambil nilainya
                            console.log("Request No sebelum upload:", requestNo); // Debugging 

                            if (file) {
                                let uploadForm = new FormData();
                                uploadForm.append("attachment", file);
                                uploadForm.append("requestNo",
                                    requestNo); // Kirim requestNo ke backend
                                uploadForm.append("_token", "{{ csrf_token() }}");

                                $.ajax({
                                    url: "{{ route('admin.ar.create_ar.upload.attachment') }}", // Endpoint untuk upload file
                                    type: "POST",
                                    data: uploadForm,
                                    processData: false,
                                    contentType: false,
                                    success: function(response) {
                                        if (response.success) {
                                            console.log("Upload sukses, filename:",
                                                response.filename);

                                            // === Pindahkan Swal.fire ke sini setelah upload sukses ===
                                            Swal.fire({
                                                icon: "success",
                                                title: "Nomor AR Diperoleh & Upload Berhasil",
                                                text: "Application No: " +
                                                    requestNo,
                                                timer: 2000,
                                                showConfirmButton: false
                                            });

                                            $("#btnSave")
                                                .hide(); // Sembunyikan tombol Save
                                            $("#submitButton")
                                                .show(); // Tampilkan tombol Submit
                                        } else {
                                            Swal.fire({
                                                icon: "error",
                                                title: "Upload Gagal",
                                                text: "Gagal mengunggah file ke FTP!",
                                            });
                                        }
                                    },
                                    error: function() {
                                        Swal.fire({
                                            icon: "error",
                                            title: "Server Error",
                                            text: "Terjadi kesalahan saat mengunggah file!",
                                        });
                                    }
                                });
                            } else {
                                // Jika tidak ada file, langsung tampilkan notifikasi
                                Swal.fire({
                                    icon: "success",
                                    title: "Nomor AR Diperoleh",
                                    text: "Application No: " + requestNo,
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                $("#btnSave").hide(); // Sembunyikan tombol Save
                                $("#submitButton").show(); // Tampilkan tombol Submit
                            }
                        } else {
                            Swal.fire({
                                icon: "warning",
                                title: "Gagal",
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }

                    },
                    error: function() {
                        Swal.fire({
                            icon: "error",
                            title: "Server Error",
                            text: "Terjadi kesalahan saat mendapatkan nomor AR!",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });


            });

            $("#submitButton").on("click", function() {
                allowSubmit = true;
            });

            // Cegah submit jika bukan karena tombol Save/Submit
            $("#myForm").on("submit", function(e) {
                if (!allowSubmit) {
                    e.preventDefault();
                }
                allowSubmit = false; // Reset setelah submit
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $("#submitButton").on("click", function(e) {
                e.preventDefault(); // Hindari reload halaman

                let file = $("#fileUpload")[0].files[0];
                let requestNo = $("#requestNo").val(); // Ambil requestNo

                console.log("Request No sebelum upload:", requestNo); // Debugging

                // Jika ada file, upload dulu ke FTP
                if (file) {
                    let uploadForm = new FormData();
                    uploadForm.append("attachment", file);
                    uploadForm.append("requestNo", requestNo); // Kirim requestNo ke backend
                    uploadForm.append("_token", "{{ csrf_token() }}");

                    $.ajax({
                        url: "{{ route('admin.ar.create_ar.upload.attachment') }}", // Endpoint untuk upload file
                        type: "POST",
                        data: uploadForm,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                console.log("Upload sukses, filename:", response.filename);
                                // Setelah file ter-upload, lanjutkan submit form utama
                                submitMainForm(response.filename);
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Upload Gagal",
                                    text: "Gagal mengunggah file ke FTP!",
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: "error",
                                title: "Server Error",
                                text: "Terjadi kesalahan saat mengunggah file!",
                            });
                        }
                    });
                } else {
                    // Jika tidak ada file, langsung submit form utama
                    let raciData = $("#previewData").html()
                        .trim(); // Menggunakan .html() jika teks tidak terbaca
                    console.log("RACI Data:", raciData);
                    submitMainForm("");
                }
            });


            function submitMainForm() {

                let formData = new FormData();
                let raciData = $("#previewData").html()
                    .trim(); // Menggunakan .html() jika teks tidak terbaca
                console.log("RACI Data:", raciData);
                formData.append("_token", "{{ csrf_token() }}");
                formData.append("requestNo", $("#requestNo").val());
                formData.append("userId", $("#userId").val());
                formData.append("companyName", $("#companyName").val());
                formData.append("jobTitle", $("#jobTitle").val());
                formData.append("organizationName", $("#organizationName").val());
                formData.append("email", $("#email").val());
                formData.append("project_name", $("#project_name").val());
                formData.append("pilot_project", $("#pilot_project").val());
                formData.append("company_impact", $("#companyImpactValue").val());
                formData.append("owner_id", $("#owner_id").val());
                formData.append("superior", $("#superior").val());
                formData.append("request_type", $("#request_type").val());
                formData.append("application_type", $("#application_type").val());
                formData.append("go_to_klip", $("#go_to_klip").val());
                formData.append("klip_number", $("#klip_number").val());
                formData.append("expected_go_live", $("#expected_go_live").val());
                formData.append("latar_belakang", $("#latar_belakang").val());
                formData.append("kondisi_dicapai", $("#kondisi_dicapai").val());
                formData.append("kondisi_saat_ini", $("#kondisi_saat_ini").val());
                formData.append("cara_selesaikan", $("#cara_selesaikan").val());
                formData.append("comment", $("#comment").val());
                formData.append("attachmentName", $("#attachmentName").val()); // Mengirim nama file dari FTP
                formData.append("raci_data", raciData); // Tambahkan data RACI ke FormData

                $.ajax({
                    url: "{{ route('admin.ar.create_ar.submitview') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: "success",
                                title: "Nomor AR DiSubmit",
                                text: "Application No: " + response.data.ApplicationNo,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href =
                                    "{{ route('admin.ar.create_ar.view') }}";
                            });

                            // Hilangkan tombol submit
                            $("#submitButton").hide();
                            $("input, textarea").val(""); // Kosongkan input
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: "error",
                            title: "Server Error",
                            text: "Terjadi kesalahan saat submit form!",
                        });
                    }
                });
            }
        });
    </script>

    <style>
        .form-control {
            background-color: white !important;
            /* Warna putih saat tidak disabled */
            color: black !important;
            /* Warna teks normal */
        }

        .form-control:disabled,
        .form-select:disabled {
            background-color: #fcfcfc !important;
            /* Warna abu-abu saat disabled */
            /* color: #e2e3e4 !important; */
            /* Warna teks redup */
            cursor: not-allowed;
        }
    </style>


@endsection
