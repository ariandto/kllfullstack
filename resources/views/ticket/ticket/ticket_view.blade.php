@extends('ticket.dashboard')

@section('ticket')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-center">
                        @if (session()->has('RoleFacility'))
                            <div style="text-align: center;">
                                <h4 style="font-size: 1.5rem; font-weight: bold; font-style: italic;">
                                    E-TICKETING
                                </h4>
                                <h4 style="font-size: 1.0rem; font-weight: bold;">
                                    {{ session('RoleFacility')['Employee_Name'] }}
                                </h4>
                            </div>
                        @else
                            <h4 style="font-size: 1.5rem; font-weight: bold; font-style: italic; text-align: center;">
                                No Employee Information Available
                            </h4>
                        @endif
                    </div>
                </div>
            </div>


            <div id="ticketContainer" class="row justify-content-center">
                @if (isset($formattedTickets) && is_array($formattedTickets) && count($formattedTickets) > 0)
                    @foreach ($formattedTickets as $ticket)
                        @if (isset($ticket) && is_array($ticket))
                            <div class="col-md-6 col-lg-6 mb-4">
                                <div class="card shadow-sm p-3">
                                    <div
                                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center px-3 position-relative">
                                        <strong class="text-start">BUS MUDIK KLG 2025</strong>
                                        <strong class="text-end">TICKET</strong>
                                    </div>
                                    <div class="card-body position-relative">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="info">
                                                    <div class="label">NIK - NAMA</div>
                                                    <div class="input-box">{{ $ticket['nik_name'] ?? '-' }}</div>
                                                </div>
                                                <div class="info">
                                                    <div class="label">TUJUAN</div>
                                                    <div class="input-box">{{ $ticket['tujuan'] ?? '-' }}</div>
                                                </div>
                                                <div class="info">
                                                    <div class="label">NOMOR BUS</div>
                                                    <div class="input-box">{{ $ticket['nomor_bus'] ?? '-' }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="info">
                                                    <div class="label">TANGGAL</div>
                                                    <div class="input-box">{{ $ticket['tanggal'] ?? '-' }}</div>
                                                </div>
                                                <div class="info">
                                                    <div class="label">JAM BERANGKAT</div>
                                                    <div class="input-box">{{ $ticket['jam_berangkat'] ?? '-' }}</div>
                                                </div>
                                                <div class="info">
                                                    <div class="label">NO. KURSI</div>
                                                    <div class="input-box">{{ $ticket['no_kursi'] ?? '-' }}</div>
                                                </div>
                                            </div>
                                            <div
                                                class="col-md-4 d-flex flex-column align-items-center justify-content-center">
                                                <div class="barcode">{!! $ticket['barcode'] ?? '' !!}</div>
                                                <div class="ticket-id">ID: {{ $ticket['ticket_id'] ?? '-' }}</div>
                                            </div>
                                        </div>
                                        <div class="stamp">{{ strtoupper($ticket['status'] ?? 'UNKNOWN') }}</div>
                                        <div class="footer1 text-center bg-custom-blue">
                                            <strong>{{ $ticket['keberangkatan'] ?? '-' }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <p class="text-center">Tidak ada data tiket tersedia.</p>
                @endif
            </div>
        </div>


    </div>

    </div>

    <script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script>
        let intervalID; // Variabel untuk menyimpan interval

        function refreshTicketStatus() {
            $.ajax({
                url: "{{ route('ticket.ticketdata') }}",
                type: "GET",
                data: {
                    status_only: true
                }, // Hanya ambil status tiket
                dataType: "json",
                success: function(response) {
                    let foundActiveTicket = false; // Cek apakah ada tiket belum close

                    response.forEach(tiket => {
                        let ticketElement = $(".ticket-id").filter(function() {
                            return $(this).text().trim().includes(tiket.ticket_id);
                        }).closest('.card');

                        if (ticketElement.length) {
                            ticketElement.find('.stamp').text(tiket.status.toUpperCase());

                            // Jika ada tiket yang belum "Close", set foundActiveTicket ke true
                            if (tiket.status.toLowerCase() !== "close") {
                                foundActiveTicket = true;
                            }
                        }
                    });

                    // Jika semua tiket sudah "Close", hentikan interval
                    if (!foundActiveTicket) {
                        clearInterval(intervalID);
                        console.log("✅ Semua tiket sudah 'Close'. Loop dihentikan.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching ticket status:", error);
                }
            });
        }

        // Jalankan polling setiap 5 detik
        $(document).ready(function() {
            intervalID = setInterval(refreshTicketStatus, 2000);
        });
    </script>

    <style>
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(to right, #FF5722, #2196F3);
            padding: 12px;
            font-size: 18px;
            font-weight: bold;
            color: #fff;
            border-radius: 8px 8px 0 0;
            text-transform: uppercase;
        }

        .separator-body {
            position: absolute;
            top: 0px;
            bottom: 5px;
            left: 66%;
            /* Menempatkan separator di tengah */
            width: 1px;
            background: repeating-linear-gradient(to bottom,
                    white 0px,
                    white 3px,
                    /* Lebih kecil */
                    transparent 3px,
                    transparent 6px
                    /* Lebih rapat */
                );
            transform: scaleX(0.2);
            /* Mengecilkan width secara visual */
            opacity: 0.7;
            /* Biar tidak terlalu mencolok */
        }

        .separator {
            position: absolute;
            top: 0px;
            bottom: 10px;
            left: 68%;
            /* Menempatkan separator di tengah */
            width: 2px;
            background: repeating-linear-gradient(to bottom,
                    white 0px,
                    white 3px,
                    /* Lebih kecil */
                    transparent 3px,
                    transparent 6px
                    /* Lebih rapat */
                );

            opacity: 0.7;
            /* Biar tidak terlalu mencolok */
        }

        .info {
            margin-bottom: 15px;
        }

        .label {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            color: #555;
        }

        .input-box {
            border: 1px solid #ddd;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
            font-size: 14px;
        }

        .barcode-container {
            margin-top: 20px;
        }

        .ticket-id {
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
            color: #555;
        }

        .footer1 {
            background: #007bff;
            /* Warna biru custom */
            color: white;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 10px 10px;
            min-height: 50px;
            /* Biar ukurannya mirip header */
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
        }


        .bg-custom-blue {
            background: #268adb;
            /* Warna biru Bootstrap */
            color: white;
        }

        .stamp {
            position: absolute;
            top: 43%;
            /* Geser ke bawah */
            left: 20%;
            /* Geser ke kiri */
            transform: rotate(-15deg);
            /* Miring ke kanan atas */
            /* background-color: rgba(0, 123, 255, 0.2); */
            /* Biru transparan */
            color: rgb(197, 47, 47);
            border: 3px dashed rgb(192, 42, 42);
            /* Warna teks biru */
            font-weight: bold;
            font-size: 18px;
            text-transform: uppercase;
            padding: 8px 16px;

            /* Border biru */
            border-radius: 6px;
            display: inline-block;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
        }

        /* Supaya stamp bisa diposisikan relatif terhadap card-body */
        .card-body {
            position: relative;
            background: #c5cee9;
        }

        .card-body::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 35%;
            width: 200px;
            height: 200px;
            background: url("{{ asset('backend/assets/images/bus2.png') }}") no-repeat center;
            background-size: contain;
            /* Biar proporsional */
            transform: translate(-50%, -50%);
            filter: blur(2px);
            opacity: 0.6;
            /* Sesuaikan biar lebih samar */
            z-index: 0;
            pointer-events: none;
            /* Biar nggak ganggu input */
        }
    </style>
@endsection
