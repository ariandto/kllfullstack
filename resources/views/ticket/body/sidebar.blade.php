<div class="vertical-menu">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                @php
                    $EmployeeID = session('RoleFacility')['Employee_ID'] ?? '';
                    $Namee = session('RoleFacility')['Employee_Name'] ?? '';
                    $Role = session('RoleFacility')['Kordinator'] ?? '';
                    //dd($EmployeeID, $Namee, $Role);
                @endphp


                {{-- Ini tulisan yang diatas menu --}}
                <li class="menu-title" data-key="t-menu">Menu</li>
                @if ($Role === '1')
                    <li>
                        <a href="{{ route('ticket.dashboard') }}">
                            <i data-feather="home"></i>
                            <span data-key="t-apps">Home</span>
                        </a>
                    </li>

                    {{-- Ini Halaman Page Khusus Dashboard --}}
                    <li>
                        <a href="{{ route('ticket.ticketdata_dashboard') }}">
                            {{-- <a href="javascript: void(0);" class="has-arrow"> --}}
                            <i data-feather="grid"></i>
                            <span data-key="t-dashboard">Monitoring Ticket</span>
                        </a>

                    </li>


                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i data-feather="mail"></i>
                            <span data-key="t-transaction">Transaction</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a href="{{ route('ticket.ticketdata') }}">
                                    <span data-key="t-inbound">Ticket Saya</span>
                                </a>
                                {{-- <ul class="sub-menu" aria-expanded="false"> 
                            </ul> --}}
                            </li>
                            <li>
                                <a href="#" id="openScanner">
                                    <span data-key="t-inbound">Entry Ticket</span>
                                </a>

                            </li>
                        </ul>
                    </li>
                @else
                    <li>
                        <a href="{{ route('ticket.ticketdata') }}">
                            <i data-feather="mail"></i>
                            <span data-key="t-transaction">Ticket Saya</span>
                        </a>
                    </li>
                @endif
            </ul>

        </div>

    </div>

</div>

<<!-- Modal -->
    <div id="scanModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Scan Barcode</h5>
                    <button type="button" class="close close-modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <div id="scannerPreview" style="width: 100%;"></div>
                    <input type="text" id="barcodeInput" class="form-control mt-3" readonly>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            let scanner;
            let isProcessing = false; // Flag untuk cegah multiple scan
            const openScannerButton = document.getElementById("openScanner");
            const scanModal = $("#scanModal");
            const barcodeInput = document.getElementById("barcodeInput");
            const closeButton = document.querySelector(".close");
            let isFrontCamera = false; // Flag untuk cek kamera depan

            // **Cek apakah tombol openScanner ada sebelum menambahkan event listener**
            if (openScannerButton) {
                openScannerButton.addEventListener("click", function() {
                    scanModal.modal("show");

                    barcodeInput.value = ""; // Kosongkan input sebelum scanning

                    if (!scanner) {
                        scanner = new Html5Qrcode("scannerPreview");
                    }

                    // Html5Qrcode.getCameras().then(cameras => {
                    //     let cameraId = cameras.length > 1 ? cameras[1].id : cameras[0].id;
                    //     isFrontCamera = cameras[0].label.toLowerCase().includes("front");
                    //     startScanner(cameraId);
                    // }).catch(err => {
                    //     console.error("Camera error:", err);
                    //     alert("Kamera tidak bisa diakses.");
                    // });


                    Html5Qrcode.getCameras().then(cameras => {
                        let backCamera = cameras.find(camera => !camera.label.toLowerCase()
                            .includes("front"));
                        let cameraId = backCamera ? backCamera.id : cameras[cameras.length - 1]
                            .id; // Pilih kamera belakang atau terakhir

                        startScanner(cameraId);
                    }).catch(err => {
                        console.error("Camera error:", err);
                        alert("Kamera tidak bisa diakses.");
                    });



                });
            } else {
                console.warn("openScannerButton tidak ditemukan, script tidak berjalan.");
            }

            function startScanner(cameraId) {
                if (!scanner.isScanning) {
                    scanner.start(cameraId, {
                            fps: 10,
                            qrbox: {
                                width: 250,
                                height: 250
                            }
                        },
                        function(decodedText) {
                            if (!isProcessing) { // Cegah multiple scan
                                isProcessing = true;
                                barcodeInput.value = decodedText;
                                sendBarcodeData(decodedText);
                            }
                        },
                        function(errorMessage) {
                            console.log(errorMessage);
                        }
                    ).catch(err => console.log("Scanner error:", err));
                }
            }

            function sendBarcodeData(barcode) {
                $.ajax({
                    url: "{{ route('ticket.insert') }}",
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    data: {
                        barcode: barcode
                    },
                    beforeSend: function() {
                        toastr.info("Processing...");
                    },
                    success: function(response) {
                        setTimeout(() => { // Tambahkan delay agar pesan terlihat
                            if (response.success) {
                                toastr.success(response.message); // Gunakan pesan dari response
                            } else {
                                toastr.error(response.message); // Gunakan pesan dari response
                            }
                        }, 1000);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                        toastr.error("Terjadi kesalahan saat insert barcode!");
                    },
                    complete: function() {
                        setTimeout(() => {
                            isProcessing = false; // Reset flag setelah request selesai
                        }, 1000);
                    }
                });
            }


            // Kosongkan input saat modal ditutup
            scanModal.on("hidden.bs.modal", function() {
                barcodeInput.value = ""; // Kosongkan input saat modal ditutup
                if (scanner && scanner.isScanning) {
                    scanner.stop().catch(err => console.log("Scanner stop error:", err));
                }
            });

            // Modal hanya tertutup saat tombol "X" diklik
            closeButton.addEventListener("click", function() {
                scanModal.modal("hide");
            });
        });
    </script> --}}

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let scanner;
            let isProcessing = false; // Flag untuk cegah multiple scan
            const openScannerButton = document.getElementById("openScanner");
            const scanModal = $("#scanModal");
            const barcodeInput = document.getElementById("barcodeInput");
            const closeButton = document.querySelector(".close");
            let isFrontCamera = false; // Flag untuk cek kamera depan

            // **Cek apakah tombol openScanner ada sebelum menambahkan event listener**
            if (openScannerButton) {
                openScannerButton.addEventListener("click", function() {
                    scanModal.modal("show");

                    barcodeInput.value = ""; // Kosongkan input sebelum scanning

                    if (!scanner) {
                        scanner = new Html5Qrcode("scannerPreview");
                    }

                    // Html5Qrcode.getCameras().then(cameras => {
                    //     let cameraId = cameras.length > 1 ? cameras[1].id : cameras[0].id;
                    //     isFrontCamera = cameras[0].label.toLowerCase().includes("front");
                    //     startScanner(cameraId);
                    // }).catch(err => {
                    //     console.error("Camera error:", err);
                    //     alert("Kamera tidak bisa diakses.");
                    // }); 
                    Html5Qrcode.getCameras().then(cameras => {
                        if (cameras.length === 0) {
                            alert("Tidak ada kamera yang terdeteksi.");
                            return;
                        }

                        // Cari kamera belakang berdasarkan nama
                        let backCamera = cameras.find(camera => !camera.label.toLowerCase()
                            .includes("front"));

                        // Jika tidak ditemukan, pilih kamera terakhir (biasanya kamera belakang)
                        let cameraId = backCamera ? backCamera.id : cameras[cameras.length - 1].id;

                        // Jika hanya ada satu kamera, pakai kamera tersebut
                        if (cameras.length === 1) {
                            cameraId = cameras[0].id;
                        }

                        console.log("Kamera yang dipilih:", cameraId);
                        startScanner(cameraId);
                    }).catch(err => {
                        console.error("Camera error:", err);
                        alert("Kamera tidak bisa diakses.");
                    });
                });
            } else {
                console.warn("openScannerButton tidak ditemukan, script tidak berjalan.");
            }

            function startScanner(cameraId) {
                if (!scanner.isScanning) {
                    scanner.start(cameraId, {
                            fps: 10,
                            qrbox: {
                                width: 250,
                                height: 250
                            }
                        },
                        function(decodedText) {
                            if (!isProcessing) { // Cegah multiple scan
                                isProcessing = true;
                                barcodeInput.value = decodedText;
                                sendBarcodeData(decodedText);
                            }
                        },
                        function(errorMessage) {
                            console.log(errorMessage);
                        }
                    ).catch(err => console.log("Scanner error:", err));
                }
            }

            function sendBarcodeData(barcode) {
                $.ajax({
                    url: "{{ route('ticket.insert') }}",
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    data: {
                        barcode: barcode
                    },
                    beforeSend: function() {
                        toastr.info("Processing...");
                    },
                    success: function(response) {
                        setTimeout(() => { // Tambahkan delay agar pesan terlihat
                            if (response.success) {
                                toastr.success(response.message); // Gunakan pesan dari response
                            } else {
                                toastr.error(response.message); // Gunakan pesan dari response
                            }
                        }, 1000);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                        toastr.error("Terjadi kesalahan saat insert barcode!");
                    },
                    complete: function() {
                        setTimeout(() => {
                            isProcessing = false; // Reset flag setelah request selesai
                        }, 1000);
                    }
                });
            }


            // Kosongkan input saat modal ditutup
            scanModal.on("hidden.bs.modal", function() {
                barcodeInput.value = ""; // Kosongkan input saat modal ditutup
                if (scanner && scanner.isScanning) {
                    scanner.stop().catch(err => console.log("Scanner stop error:", err));
                }
            });

            // Modal hanya tertutup saat tombol "X" diklik
            closeButton.addEventListener("click", function() {
                scanModal.modal("hide");
            });
        });
    </script>
