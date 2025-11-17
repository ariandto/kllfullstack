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
                                    TASK AR {{ $facility['Name'] }}
                                </h4>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="row mt-3 mb-3">
                <!-- Isi kontennya -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="filterTable" class="table table-striped">
                                    @if (session('error'))
                                        <div class="alert alert-danger">
                                            {{ session('error') }}
                                        </div>
                                    @endif

                                    @if (isset($getTaskView) && count($getTaskView) > 0)
                                        <thead class="text-center align-middle">
                                            <tr>
                                                <th>NO</th>
                                                <th>APPLICATION NO</th>
                                                <th>REQUEST TYPE</th>
                                                <th>APPLICATION TYPE</th>
                                                <th>PROJECT NAME</th>
                                                <th>KLIP</th>
                                                <th>PILOT PROJECT</th>
                                                <th>PROJECT OWNER PROFILE</th>
                                                <th>PROJECT OWNER</th>
                                                <th>STATUS</th>
                                                <th>EXPECTED GO LIVE</th>
                                                <th>ACTION</th> <!-- ✅ Tambahkan kolom Action -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($getTaskView as $index => $data)
                                                <tr>
                                                    <td class="align-middle">{{ $index + 1 }}</td>
                                                    <td class="align-middle">{{ $data->{"Application No"} }}</td>
                                                    <td class="align-middle">{{ $data->{"Request Type"} }}</td>
                                                    <td class="align-middle">{{ $data->{"Application Type"} }}</td>
                                                    <td class="align-middle">{{ $data->{"Project Name"} }}</td>
                                                    <td class="align-middle">{{ $data->{"KLIP"} }}</td>
                                                    <td class="align-middle">{{ $data->{"Pilot Project"} }}</td>
                                                    <td class="text-center align-middle">
                                                        @php
                                                            $picture = asset('path_ke_gambar_default.png'); // Gambar default
                                                            if (
                                                                property_exists($data, 'Project Owner Profile') &&
                                                                !empty($data->{'Project Owner Profile'})
                                                            ) {
                                                                $picture =
                                                                    'data:image/png;base64,' .
                                                                    base64_encode($data->{'Project Owner Profile'});
                                                            }
                                                        @endphp
                                                        <img src="{{ $picture }}" alt="Project Owner Profile"
                                                            class="rounded-circle" width="40" height="40">
                                                    </td>
                                                    <td class="align-middle">{{ $data->{"Project Owner"} }}</td>
                                                    <td class="align-middle">{{ $data->{"Status"} }}</td>
                                                    <td class="align-middle">{{ $data->{"Expected Golive"} }}</td>
                                                    <td class="text-center">
                                                        <button class="btn btn-primary btn-sm"
                                                            onclick="showDetail('{{ $data->{'Application No'} }}')">
                                                            <i class="fas fa-hand-point-left"></i>

                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @else
                                        <p class="text-center mt-3">Tidak ada data yang ditemukan.</p>
                                    @endif

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Include modal di sini --}}
            @include('admin.applicationrequest.modal_view_ar')

        </div>
    </div>


    <script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Cek apakah tabel memiliki data saat halaman dimuat
            var tableExists = {{ isset($getTaskView) && count($getTaskView) > 0 ? 'true' : 'false' }};

            if (tableExists) {
                initDataTable();
            }

            function initDataTable() {
                // Hapus instance DataTable jika sudah ada
                if ($.fn.DataTable.isDataTable("#filterTable")) {
                    $("#filterTable").DataTable().destroy();
                }

                // Inisialisasi ulang DataTable
                $("#filterTable").DataTable({
                    scrollY: '400px',
                    scrollX: true,
                    scrollCollapse: true,
                    paging: true,
                    searching: true,
                    info: true,
                    fixedHeader: true,
                    autoWidth: false,
                    responsive: false,
                    columnDefs: [{
                        targets: "_all",
                        className: "text-wrap"
                    }],
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
            }

            // Saat form dikirim, tunggu data baru sebelum inisialisasi ulang tabel
            $("form").on("submit", function(event) {
                setTimeout(function() {
                    console.log("Inisialisasi ulang DataTables...");
                    initDataTable();
                }, 1000); // Delay untuk memastikan data sudah diperbarui
            });
        });
    </script>

    <script>
        // Tambahkan variabel global untuk menyimpan instance modal
        let detailModalInstance = null;

        function showDetail(applicationNo) {
            $.ajax({
                url: "{{ route('admin.ar.task_ar.submitviewdetail') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    application_no: applicationNo
                },
                dataType: "json",
                success: function(response) {
                    console.log("Response dari Server:", response);

                    if (!response.success || response.tabel1.length === 0) {
                        alert("Data tidak ditemukan");
                        return;
                    }

                    // Ambil status dari response
                    let statusAR = response.statusAR || "Unknown";
                    let applicationNo = response.applicationNo || "Unknown";

                    // Tentukan modal yang dipakai berdasarkan status
                    let targetModal;

                    if (statusAR === "Draft") {
                        let url = "{{ route('admin.ar.create_ar.view2') }}?applicationNo=" +
                            encodeURIComponent(applicationNo);
                        window.location.href = url;
                    } else if (statusAR === "Under Review by Logistics Innovation") {
                        targetModal = "modalA";
                        loadDataLabel3(response);
                        // Set judul modal dengan nomor AR
                        document.getElementById("modalALabel").innerText = "Detail Application - " +
                            applicationNo;

                    } else {
                        targetModal = "modalC";
                        loadDataLabel2(response);
                    }
                    // Tampilkan modal yang sesuai
                    // Hanya buat 1 instance modal jika belum ada
                    let modalElement = document.getElementById(targetModal);

                    // Cek apakah modal sudah punya instance
                    if (!detailModalInstance || detailModalInstance._element !== modalElement) {
                        detailModalInstance = new bootstrap.Modal(modalElement);
                    }

                    detailModalInstance.show();
                }
            });
        }

        function loadDataLabel2(response) {
            let data = response.tabel1[0];
            // Ambil data pertama (baris 1)
            $("#applicationNo").val(data?.["Application No"] || "N/A");
            $("#company").val(data?.["Company"] || "N/A");
            $("#projectOwner").val(data?.["Project Owner"] || "N/A");
            $("#statusProject").val(data?.["Project Status"] || "N/A");
            $("#projectName").val(data?.["Project Name"] || "N/A");
            $("#pilotProject").val(data?.["Pilot Project"] || "N/A");
            $("#applicationType").val(data?.["Application Type"] || "N/A");

            // Ambil data kedua (baris 2)
            let detailData = response.tabel1[0] || {};
            $("#point1").val(detailData["Point1"] || "");
            $("#point2").val(detailData["Point2"] || "");
            $("#point3").val(detailData["Point3"] || "");
            $("#point4").val(detailData["Point4"] || "");
            $("#comment").val(data?.["Comment"] || "N/A");
            $("#attachment").html(data?.["Attachment"] || "N/A");

            // **Tambahkan tombol Save & Submit dari Tabel 2**
            let buttonContainer = $("#buttonContainer");
            buttonContainer.empty(); // Bersihkan isi sebelumnya

            if (response.tabel2.length > 0) {
                response.tabel2.forEach(row => {
                    let buttonGroup = $("<div>").addClass("d-flex gap-2 w-100");

                    Object.keys(row).forEach(key => {
                        let button = $("<button>")
                            .addClass("btn flex-fill py-3 w-100")
                            .text(row[key]); // Isi tombol dari value kolom

                        // Tambahkan warna berdasarkan nama tombol
                        if (/save/i.test(row[key])) {
                            button.addClass("btn-primary");
                        } else if (/submit/i.test(row[key])) {
                            button.addClass("btn-success");
                        } else if (/reject/i.test(row[key])) {
                            button.addClass("btn-danger");
                        } else if (/Approve/i.test(row[key])) {
                            button.addClass("btn-success");
                        } else if (/Send Back/i.test(row[key])) {
                            button.addClass("btn-warning");
                        } else if (/assign/i.test(row[key])) {
                            button.addClass("btn-warning");
                        } else {
                            button.addClass("btn-secondary"); // Default style
                        }

                        buttonGroup.append(button);
                        button.attr("data-action", row[key].toLowerCase().replace(/\s+/g, "_"));
                        button.attr("data-name", row[key]); // Menyimpan nama tombol untuk dikirim ke server
                        button.attr("data-application-no", data?.["Application No"] ||
                            ""); // Simpan Application No

                    });

                    buttonContainer.append(buttonGroup);
                    // Event listener untuk tombol aksi
                    buttonContainer.find("button").on("click", function() {
                        let action = $(this).attr("data-action"); // Dapatkan action dari tombol
                        let buttonName = $(this).attr("data-name"); // Dapatkan nama tombol
                        let applicationNo = $(this).attr("data-application-no"); // Dapatkan Application No
                        let comment = $("#commentnew").val(); // Ambil isi dari field comment baru
                        console.log(comment);

                        $.ajax({
                            url: "{{ route('admin.ar.task_ar.submitsuperiordansupersuperior') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}", // Pastikan token dikirim dalam data
                                application_no: applicationNo,
                                button_name: buttonName,
                                comment: comment
                            },
                            dataType: "json",
                            success: function(response) {
                                console.log("Response:", response);
                                if (response.success) {
                                    Swal.fire({
                                        icon: "success",
                                        title: "Berhasil!",
                                        text: `${buttonName} berhasil diproses.`,
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        window.location.href =
                                            "{{ route('admin.ar.task_ar.view') }}";
                                    });
                                } else {
                                    Swal.fire({
                                        icon: "warning",
                                        title: "Gagal",
                                        text: response.message ||
                                            "Terjadi kesalahan, silakan coba lagi.",
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                }
                            },
                            error: function(xhr) {
                                console.log("Error:", xhr.responseText);
                                Swal.fire({
                                    icon: "error",
                                    title: "Terjadi Kesalahan",
                                    text: "Gagal memproses permintaan. Silakan coba lagi.",
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                        });
                    });

                });
            }

            function toggleSection(icon) {
                let target = document.querySelector(icon.getAttribute("data-target"));
                if (target.classList.contains("collapse")) {
                    target.classList.remove("collapse"); // Buka
                    icon.classList.replace("fa-eye", "fa-eye-slash"); // Ganti ikon mata terbuka ke tertutup
                } else {
                    target.classList.add("collapse"); // Tutup
                    icon.classList.replace("fa-eye-slash", "fa-eye"); // Ganti ikon mata tertutup ke terbuka
                }
            }

            function attachToggleEvent() {
                document.querySelectorAll(".toggle-section").forEach(function(icon) {
                    icon.removeEventListener("click", function() {
                        toggleSection(icon);
                    });

                    icon.addEventListener("click", function() {
                        toggleSection(icon);
                    });

                    // Atur kondisi awal ikon mata
                    let target = document.querySelector(icon.getAttribute("data-target"));
                    if (target.classList.contains("collapse")) {
                        icon.classList.add("fa-eye"); // Default: tertutup
                        icon.classList.remove("fa-eye-slash");
                    } else {
                        icon.classList.add("fa-eye-slash"); // Jika terbuka
                        icon.classList.remove("fa-eye");
                    }
                });
            }

            // Pastikan event tetap aktif setiap kali modal dibuka
            document.querySelectorAll(".modal").forEach(function(modal) {
                modal.addEventListener("shown.bs.modal", function() {
                    setTimeout(attachToggleEvent,
                        300); // Tambahkan sedikit delay agar event benar-benar terpasang
                });
            });


            // **Event Listener untuk Download Attachment**
            let btnDownload = document.getElementById("btnDownload");

            if (btnDownload) {
                btnDownload.removeEventListener("click", handleDownload); // Hapus event lama
                btnDownload.addEventListener("click", handleDownload);
            }

            function handleDownload(event) {
                event.preventDefault(); // Hindari aksi bawaan

                let arNumber = document.getElementById("attachment").textContent.trim().substring(0, 12);
                console.log(arNumber);

                if (!arNumber) {
                    alert("File tidak tersedia untuk diunduh!");
                    return;
                }

                fetch(`/admin/find-file/${arNumber}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }

                        let filename = encodeURIComponent(data.filename);
                        let downloadUrl = `/admin/download/${filename}`;

                        // Pakai window.location.href untuk menghindari loop
                        window.location.href = downloadUrl;
                    })
                    .catch(error => console.error("Error:", error));
            }
        }

        function loadDataLabel3(response) {
            let data = response.tabel1[0];
            // Ambil data pertama (baris 1)
            $("#applicationNoA").val(data?.["Application No"] || "N/A");
            $("#companyA").val(data?.["Company"] || "N/A");
            $("#projectOwnerA").val(data?.["Project Owner"] || "N/A");
            $("#statusProjectA").val(data?.["Project Status"] || "N/A");
            $("#projectNameA").val(data?.["Project Name"] || "N/A");
            $("#pilotProjectA").val(data?.["Pilot Project"] || "N/A");
            $("#applicationTypeA").val(data?.["Application Type"] || "N/A");

            // Ambil data kedua (baris 2)
            let detailData = response.tabel1[0] || {};
            $("#point1A").val(detailData["Point1"] || "");
            $("#point2A").val(detailData["Point2"] || "");
            $("#point3A").val(detailData["Point3"] || "");
            $("#point4A").val(detailData["Point4"] || "");
            $("#commentA").val(data?.["Comment"] || "N/A");
            $("#attachmentA").html(data?.["Attachment"] || "N/A");

            // **Tambahkan tombol Save & Submit dari Tabel 2**
            let buttonContainer = $("#buttonContainerA");
            buttonContainer.empty(); // Bersihkan isi sebelumnya

            if (response.tabel2.length > 0) {
                response.tabel2.forEach(row => {
                    let buttonGroup = $("<div>").addClass("d-flex gap-2 w-100");

                    Object.keys(row).forEach(key => {
                        let button = $("<button>")
                            .addClass("btn flex-fill py-3 w-100")
                            .text(row[key]); // Isi tombol dari value kolom

                        // Tambahkan warna berdasarkan nama tombol
                        if (/save/i.test(row[key])) {
                            button.addClass("btn-primary");
                        } else if (/submit/i.test(row[key])) {
                            button.addClass("btn-success");
                        } else if (/reject/i.test(row[key])) {
                            button.addClass("btn-danger");
                        } else if (/Approve/i.test(row[key])) {
                            button.addClass("btn-success");
                        } else if (/Send Back/i.test(row[key])) {
                            button.addClass("btn-warning");
                        } else if (/assign/i.test(row[key])) {
                            button.addClass("btn-warning");
                        } else {
                            button.addClass("btn-secondary"); // Default style
                        }

                        buttonGroup.append(button);
                        button.attr("data-action", row[key].toLowerCase().replace(/\s+/g, "_"));
                        button.attr("data-name", row[key]); // Menyimpan nama tombol untuk dikirim ke server
                        button.attr("data-application-no", data?.["Application No"] ||
                            ""); // Simpan Application No

                    });

                    buttonContainer.append(buttonGroup);
                    // Event listener untuk tombol aksi
                    buttonContainer.find("button").on("click", function() {
                        let action = $(this).attr("data-action"); // Dapatkan action dari tombol
                        let buttonName = $(this).attr("data-name"); // Dapatkan nama tombol
                        let applicationNo = $(this).attr("data-application-no"); // Dapatkan Application No
                        let comment = $("#commentnew").val(); // Ambil isi dari field comment baru
                        console.log(comment);

                        $.ajax({
                            url: "{{ route('admin.ar.task_ar.developer') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                application_no: applicationNo
                            },
                            success: function(response) {
                                if (response.success && response.developer.length > 0) {
                                    console.log("Data developer:", response.developer);
                                    console.log("Data Company Impact:", response
                                        .company_impact);
                                    console.log("Data UserID:", response
                                        .user_id);

                                    // Simpan data ke global var
                                    window._developerData = response.developer;
                                    window._companyImpactData = response.company_impact;
                                    populateCompanyImpactDropdown(response.company_impact);

                                    $('#modalA').modal('hide');
                                    loadDataLabel4(applicationNo, comment);
                                } else {
                                    Swal.fire({
                                        icon: "warning",
                                        title: "Gagal",
                                        text: "Data developer tidak ditemukan.",
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error("Gagal ambil data developer:", error);
                                Swal.fire({
                                    icon: "warning",
                                    title: "Gagal",
                                    text: xhr.responseJSON?.message ||
                                        "Terjadi kesalahan, silakan coba lagi.",
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                        });

                    });

                });
            }



            function toggleSection(icon) {
                let target = document.querySelector(icon.getAttribute("data-target"));
                if (target.classList.contains("collapse")) {
                    target.classList.remove("collapse"); // Buka
                    icon.classList.replace("fa-eye", "fa-eye-slash"); // Ganti ikon mata terbuka ke tertutup
                } else {
                    target.classList.add("collapse"); // Tutup
                    icon.classList.replace("fa-eye-slash", "fa-eye"); // Ganti ikon mata tertutup ke terbuka
                }
            }

            function attachToggleEvent() {
                document.querySelectorAll(".toggle-section").forEach(function(icon) {
                    // Hapus semua event sebelumnya dengan cloning
                    let newIcon = icon.cloneNode(true);
                    icon.parentNode.replaceChild(newIcon, icon);

                    newIcon.addEventListener("click", function() {
                        toggleSection(newIcon);
                    });

                    // Atur kondisi awal ikon mata
                    let target = document.querySelector(newIcon.getAttribute("data-target"));
                    if (target?.classList.contains("collapse")) {
                        newIcon.classList.add("fa-eye");
                        newIcon.classList.remove("fa-eye-slash");
                    } else {
                        newIcon.classList.add("fa-eye-slash");
                        newIcon.classList.remove("fa-eye");
                    }
                });
            }
            // Pastikan event tetap aktif setiap kali modal dibuka
            document.querySelectorAll(".modal").forEach(function(modal) {
                modal.addEventListener("shown.bs.modal", function() {
                    setTimeout(attachToggleEvent,
                        300); // Tambahkan sedikit delay agar event benar-benar terpasang
                });
            });
            // **Event Listener untuk Download Attachment**
            let btnDownload = document.getElementById("btnDownloadA");

            if (btnDownload) {
                btnDownload.removeEventListener("click", handleDownload); // Hapus event lama
                btnDownload.addEventListener("click", handleDownload);
            }

            function handleDownload(event) {
                event.preventDefault(); // Hindari aksi bawaan

                let arNumber = document.getElementById("attachmentA").textContent.trim().substring(0, 12);
                console.log(arNumber);
                if (!arNumber) {
                    alert("File tidak tersedia untuk diunduh!");
                    return;
                }

                fetch(`/admin/find-file/${arNumber}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }

                        let filename = encodeURIComponent(data.filename);
                        let downloadUrl = `/admin/download/${filename}`;

                        // Pakai window.location.href untuk menghindari loop
                        window.location.href = downloadUrl;
                    })
                    .catch(error => console.error("Error:", error));
            }
        }

        function loadDataLabel4(applicationNo, comment) {
            console.log("Application No:", applicationNo);

            $('#modalA').on('hidden.bs.modal', function() {
                // Set judul modal B
                $("#modalBLabel").text("Assign Application Request - " + applicationNo);

                $('#modalB').modal('show');
                $('#modalA').off('hidden.bs.modal');

                // Simpan Application No dan Comment ke input hidden
                $("#applicationNoB").val(applicationNo);
                $("#commentB").val(comment);

            });

            $('#modalB').on('shown.bs.modal', function() {
                if (window._developerData) {
                    let response = window._developerData;

                    let analystSelect = document.getElementById('nik_analyst');
                    let developerSelect = document.getElementById('nik_developer');
                    let analystImg = analystSelect.closest('.row').querySelector('img');
                    let developerImg = developerSelect.closest('.row').querySelector('img');

                    let analystTask = document.getElementById('task_analyst');
                    let analystAvailable = document.getElementById('available_analyst');

                    let developerTask = document.getElementById('task_developer');
                    let developerAvailable = document.getElementById('available_developer');

                    analystSelect.innerHTML = '';
                    developerSelect.innerHTML = '';

                    response.forEach(item => {
                        let option = `<option value="${item.Developer}">${item.Developer}</option>`;
                        analystSelect.insertAdjacentHTML('beforeend', option);
                        developerSelect.insertAdjacentHTML('beforeend', option);
                    });

                    if (response[0]?.Empphoto) {
                        const imageUrl = 'data:image/png;base64,' + response[0].Empphoto;
                        analystImg.src = imageUrl;
                        developerImg.src = imageUrl;

                        analystTask.value = response[0].TaskActive || '';
                        analystAvailable.value = response[0].AvailableDate || '';
                        developerTask.value = response[0].TaskActive || '';
                        developerAvailable.value = response[0].AvailableDate || '';
                    }

                    analystSelect.addEventListener('change', function() {
                        const selectedNIK = this.value;
                        const selectedData = response.find(item => item.Developer === selectedNIK);
                        if (selectedData) {
                            if (selectedData.Empphoto) {
                                analystImg.src = 'data:image/png;base64,' + selectedData.Empphoto;
                            }
                            analystTask.value = selectedData.TaskActive || '';
                            analystAvailable.value = selectedData.AvailableDate || '';
                        }
                    });

                    developerSelect.addEventListener('change', function() {
                        const selectedNIK = this.value;
                        const selectedData = response.find(item => item.Developer === selectedNIK);
                        if (selectedData) {
                            if (selectedData.Empphoto) {
                                developerImg.src = 'data:image/png;base64,' + selectedData.Empphoto;
                            }
                            developerTask.value = selectedData.TaskActive || '';
                            developerAvailable.value = selectedData.AvailableDate || '';
                        }
                    });

                    delete window._developerData;
                }
            });

            $(document).on('click', '.btn-detail', function() {
                const type = $(this).data('type'); // "developer" atau "analyst"
                let nikFull = $(`#nik_${type}`).val();
                console.log(nikFull);

                if (!nikFull) {
                    alert(`Silakan pilih ${type} terlebih dahulu.`);
                    return;
                }


                let nik6Digit = nikFull.substring(0, 6); // Ambil 6 digit awal

                $.ajax({
                    url: "{{ route('admin.ar.task_ar.developer_detail') }}",
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        nik: nik6Digit
                    },
                    success: function(response) {
                        console.log(response); // <--- Tambahkan di sini
                        $('#modalD .modal-title').text(
                            `Detail ${type.charAt(0).toUpperCase() + type.slice(1)}`);

                        let detailHtml = `
                        <div class="table-responsive">
                            <table class="table text-center align-middle mb-0">
                                <thead class="table-secondary">
                                    <tr>
                                        <th rowspan="2">PIC</th>
                                        <th rowspan="2">Project Name</th>
                                        <th colspan="2">Analyst</th>
                                        <th colspan="2">Develop</th>
                                    </tr>
                                    <tr>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr> 
                                        <td>${response[0]?.PIC || '-'}</td>
                                        <td>${response[0]?.ProjectName || '-'}</td>
                                        <td>${response[0]?.StartEstimateAnalyst || '-'}</td>
                                        <td>${response[0]?.EndEstimateeAnalyst || '-'}</td>
                                        <td>${response[0]?.StartEstimateDevelop || '-'}</td>
                                        <td>${response[0]?.EndEstimateDevelop || '-'}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    `;
                        $('#developer-detail-container').html(detailHtml);

                        // Tambahkan efek blur ke modalB
                        $('#modalB .modal-content').addClass('modal-blur');

                        // Sembunyikan modalB agar modalD muncul di atas (opsional tapi direkomendasikan)
                        $('#modalB').modal('hide');

                        // Atur modal agar tampil di tengah dan background abu
                        $('#modalD .modal-dialog').addClass('modal-dialog-centered');
                        $('#modalD .modal-content').css('background-color', '#f2f2f2');

                        // Tampilkan modalD dengan backdrop gelap
                        const modalD = new bootstrap.Modal(document.getElementById('modalD'), {
                            backdrop: true,
                            keyboard: false,
                            focus: true
                        });
                        modalD.show();

                        // Saat modalD ditutup, hilangkan blur dari modalB dan tampilkan kembali modalB
                        document.getElementById('modalD').addEventListener('hidden.bs.modal',
                            function() {
                                $('#modalB .modal-content').removeClass('modal-blur');
                                $('#modalB').modal('show'); // Jika ingin modalB kembali tampil
                            });

                    },


                    error: function() {
                        alert('Gagal mengambil detail.');
                    }
                });
            });



            function toggleSection(icon) {
                let target = document.querySelector(icon.getAttribute("data-target"));
                if (target.classList.contains("collapse")) {
                    target.classList.remove("collapse"); // Buka
                    icon.classList.replace("fa-eye", "fa-eye-slash"); // Ganti ikon mata terbuka ke tertutup
                } else {
                    target.classList.add("collapse"); // Tutup
                    icon.classList.replace("fa-eye-slash", "fa-eye"); // Ganti ikon mata tertutup ke terbuka
                }
            }

            function attachToggleEvent() {
                document.querySelectorAll(".toggle-section").forEach(function(icon) {
                    icon.removeEventListener("click", function() {
                        toggleSection(icon);
                    });

                    icon.addEventListener("click", function() {
                        toggleSection(icon);
                    });

                    // Atur kondisi awal ikon mata
                    let target = document.querySelector(icon.getAttribute("data-target"));
                    if (target.classList.contains("collapse")) {
                        icon.classList.add("fa-eye"); // Default: tertutup
                        icon.classList.remove("fa-eye-slash");
                    } else {
                        icon.classList.add("fa-eye-slash"); // Jika terbuka
                        icon.classList.remove("fa-eye");
                    }
                });
            }

            // Pastikan event tetap aktif setiap kali modal dibuka
            document.querySelectorAll(".modal").forEach(function(modal) {
                modal.addEventListener("shown.bs.modal", function() {
                    setTimeout(attachToggleEvent,
                        100); // Tambahkan sedikit delay agar event benar-benar terpasang
                });
            });

        }

        function populateCompanyImpactDropdown(data) {
            const dropdown = document.getElementById('companyImpactDropdown');
            const allCheckbox = document.createElement('div');
            allCheckbox.classList.add('form-check');
            allCheckbox.innerHTML = `
            <input class="form-check-input" type="checkbox" id="impact_all">
            <label class="form-check-label" for="impact_all">All</label>
            `;
            dropdown.innerHTML = '';
            dropdown.appendChild(allCheckbox);

            const container = document.createDocumentFragment();

            data.forEach((item, index) => {
                const isChecked = item.Value == 1 ? 'checked' : '';
                const div = document.createElement('div');
                div.className = 'form-check';
                div.innerHTML = `
            <input class="form-check-input impact-option" type="checkbox" value="${item.Company}" id="impact_${index}" ${isChecked}>
            <label class="form-check-label" for="impact_${index}">${item.Company}</label>
            `;
                container.appendChild(div);
            });

            dropdown.appendChild(container);

            // Re-bind all event listeners
            bindCompanyImpactEvents();
        }

        function bindCompanyImpactEvents() {
            const allCheckbox = document.getElementById('impact_all');
            const checkboxes = document.querySelectorAll('.impact-option');
            const hiddenInput = document.getElementById('companyImpactValue');
            const toggleButton = document.getElementById('toggleCompanyImpact');

            allCheckbox.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = allCheckbox.checked);
                updateHiddenInput();
            });

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    allCheckbox.checked = [...checkboxes].every(c => c.checked);
                    updateHiddenInput();
                });
            });

            function updateHiddenInput() {
                const selected = [...document.querySelectorAll('.impact-option')]
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);

                hiddenInput.value = selected.join(',');

                // Tombol hanya menampilkan teks statis, misal "Company Impact"
                toggleButton.firstChild.textContent = 'Company Impact';

                // Optional: Tooltip kalau mau kasih info jumlah
                toggleButton.title = selected.length > 0 ? `${selected.length} selected` : 'All';
            }

            // Set initial state of "All" checkbox
            allCheckbox.checked = [...checkboxes].every(cb => cb.checked);

            // Trigger input update on load
            updateHiddenInput();
        }


        function addToGrid(role) {
            const nik = $(`#nik_${role}`).val();
            const name = $(`#nik_${role} option:selected`).text().split(' - ')[1];
            const start = $(`#start_${role}`).val();
            const end = $(`#end_${role}`).val();

            // Cek apakah tanggal start dan end sudah diisi
            if (!start || !end) {
                return alert('Please fill in assignment dates.');
            }

            // Mendapatkan tanggal analyst yang sudah ditambahkan (gunakan role yang relevan)
            const analystStart = $('#start_analyst').val();
            const analystEnd = $('#end_analyst').val();

            // Validasi untuk Analyst
            if (role === 'analyst') {
                // Tanggal start analyst tidak boleh lebih dari tanggal end analyst
                if (new Date(start) > new Date(end)) {
                    return alert('Start date for analyst cannot be later than end date.');
                }
            }

            // Validasi untuk Developer
            if (role === 'developer') {
                // Tanggal start developer tidak boleh lebih kecil dari tanggal end analyst
                if (new Date(start) < new Date(analystEnd)) {
                    return alert('Start date for developer cannot be earlier than end date for analyst.');
                }
                // Tanggal end developer tidak boleh lebih kecil dari tanggal end analyst
                if (new Date(end) < new Date(analystEnd)) {
                    return alert('End date for developer cannot be earlier than end date for analyst.');
                }
            }

            // Cek apakah nik sudah ada di tabel
            let isDuplicate = false;
            $(`#table_${role} tbody tr`).each(function() {
                const existingNik = $(this).find('td:first').text();
                if (existingNik === nik) {
                    isDuplicate = true;
                    return false; // break dari loop
                }
            });

            if (isDuplicate) {
                return alert('Data already exists in the table.');
            }

            const leadTime = Math.ceil((new Date(end) - new Date(start)) / (1000 * 3600 * 24));
            const newRow = `
            <tr>
                <td>${nik}</td>
                <td>${name}</td>
                <td>${start}</td>
                <td>${end}</td>
                <td>${leadTime}</td>
            </tr>
            `;

            $(`#table_${role} tbody`).append(newRow);
        }



        function resetModalB() {
            // // Reset semua input
            // $('#modalB input').val('');
            // Kosongkan tabel
            $('#table_analyst tbody, #table_developer tbody').empty();
        }

        function submitModalB() {
            let analystData = [],
                developerData = [];

            // Ambil data dari tabel analyst
            $('#table_analyst tbody tr').each(function() {
                const cols = $(this).children();
                analystData.push({
                    nik: cols.eq(0).text().trim(),
                    name: cols.eq(1).text().trim(),
                    start: cols.eq(2).text().trim(),
                    end: cols.eq(3).text().trim(),
                    lead: cols.eq(4).text().trim(),
                });
            });

            // Ambil data dari tabel developer
            $('#table_developer tbody tr').each(function() {
                const cols = $(this).children();
                developerData.push({
                    nik: cols.eq(0).text().trim(),
                    name: cols.eq(1).text().trim(),
                    start: cols.eq(2).text().trim(),
                    end: cols.eq(3).text().trim(),
                    lead: cols.eq(4).text().trim(),
                });
            });

            // Ambil nilai dari input
            const applicationNo = $('#applicationNoB').val().trim();
            const comment = $('#commentB').val().trim();
            const go_live_date = $('#go_live_date').val()?.trim() || ''; // opsional

            // Validasi Go Live Date dengan end date developer
            let latestEndDate = new Date(Math.max(...developerData.map(dev => new Date(dev.end))));
            let goLiveDate = new Date(go_live_date);

            if (goLiveDate < latestEndDate) {
                return alert('Go Live Date cannot be earlier than the end date of the latest developer assignment.');
            }

            // Ambil company impact
            let selectedCompanyImpact = [];
            $('#companyImpactDropdown input[type=checkbox]:checked').each(function() {
                const val = $(this).val();
                if (val && val !== 'on') {
                    selectedCompanyImpact.push(val.split(' ')[0]); // Ambil H001 dari "H001 - WH AHI"
                }
            });

            // Ambil user ID dari Blade (dimasukkan di Blade view)
            const userID = `{{ Auth::guard('admin')->user()->userid ?? 'unknown' }}`;

            // Format data ke bentuk nik|start|end;
            function formatPeopleData(dataArray) {
                return dataArray.map(item => `${item.nik.split(' - ')[0]}|${item.start}|${item.end}`).join(';') + (dataArray
                    .length > 0 ? ';' : '');
            }

            const analystFormatted = formatPeopleData(analystData);
            const developerFormatted = formatPeopleData(developerData);
            const companyFormatted = selectedCompanyImpact.join(';') + (selectedCompanyImpact.length > 0 ? ';' : '');

            // Console preview
            console.log("go_live_date :", go_live_date);
            console.log("Formatted Analyst:", analystFormatted);
            console.log("Formatted Developer:", developerFormatted);
            console.log("Formatted Company Impact:", companyFormatted);

            // Kirim via AJAX
            $.ajax({
                url: "{{ route('admin.ar.task_ar.assign_ar') }}",
                type: "POST",
                data: {
                    application_no: applicationNo,
                    analyst: analystFormatted,
                    developer: developerFormatted,
                    comment: comment,
                    userid: userID,
                    company_impact: companyFormatted,
                    golive: go_live_date,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil!",
                            text: "Assignment berhasil diproses.",
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = "{{ route('admin.ar.task_ar.view') }}";
                        });
                    } else {
                        Swal.fire({
                            icon: "warning",
                            title: "Gagal",
                            text: response.message || "Terjadi kesalahan, silakan coba lagi.",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: "error",
                        title: "Terjadi Kesalahan",
                        text: "Terjadi kesalahan saat submit. Silakan coba lagi.",
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });

        }
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

        /* Buat styling untuk textarea khusus Point 1 - 4 */
        textarea#point1,
        textarea#point2,
        textarea#point3,
        textarea#point4 {
            width: 100%;
            height: 200px;
            resize: none;
            /* Opsional: biar nggak bisa di-resize manual */
        }

        textarea#point1A,
        textarea#point2A,
        textarea#point3A,
        textarea#point4A {
            width: 100%;
            height: 200px;
            resize: none;
            /* Opsional: biar nggak bisa di-resize manual */
        }

        textarea#point1B,
        textarea#point2B,
        textarea#point3B,
        textarea#point4B {
            width: 100%;
            height: 200px;
            resize: none;
            /* Opsional: biar nggak bisa di-resize manual */
        }


        /* Lebar modal jadi 90% dari layar */
        .modal-dialog {
            max-width: 90%;
        }

        /* Supaya modal bisa di-scroll jika konten panjang */
        .modal-body {
            max-height: 80vh;
            overflow-y: auto;
        }

        /* Lebar modal jadi 90% dari layar */
        #modalD .modal-dialog {
            max-width: 90%;
            margin: 1.75rem auto;
        }

        /* Supaya modal bisa di-scroll jika konten panjang */
        #modalD .modal-body {
            max-height: 80vh;
            overflow-y: auto;
        }

        /* Warna latar modalD: Biru lembut */
        #modalD .modal-content {
            background-color: #e6f0ff;
            /* Biru muda */
        }

        /* Tabel di dalam modalD semuanya rata tengah */
        #modalD table th,
        #modalD table td {
            text-align: center;
            vertical-align: middle !important;
        }
    </style>



@endsection
