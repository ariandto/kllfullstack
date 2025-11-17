@extends('admin.dashboard')

@section('admin')
<div class="container-fluid">
    <h4 class="mb-4">📦 Monitoring Pengiriman</h4>

    {{-- Alert Container --}}
    <div id="alertContainer"></div>

    {{-- Filter --}}
    <div class="row mb-3">
        <div class="col-md-3">
            <label>Start Date</label>
            <input type="date" id="start_date" class="form-control" value="{{ date('Y-m-d') }}">
        </div>
        <div class="col-md-3">
            <label>End Date</label>
            <input type="date" id="end_date" class="form-control" value="{{ date('Y-m-d') }}">
        </div>
        <div class="col-md-3">
            <label>Owner</label>
            <select id="owner" class="form-control">
                <option value="AHI">AHI</option>
                <option value="FBI">FBI</option>
                <option value="HCI">HCI</option>
                <option value="WOI">WOI</option>
            </select>
        </div>
        <div class="col-md-3">
            <label>Site</label>
            <select id="site" class="form-control">
                <option value="WMWHSE2">WMWHSE2</option>
                <option value="WMWHSE4">WMWHSE4</option>
            </select>
        </div>
    </div>

    <button class="btn btn-primary mb-3" id="btnFilter">Filter</button>

    {{-- Loading overlay --}}
    <div id="loadingOverlay" 
         style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
                background:rgba(0,0,0,0.4); z-index:9999; text-align:center; padding-top:20%;">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="text-white mt-3">Sedang memuat data...</p>
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table id="monitoringTable" class="table table-bordered table-striped w-100">
            <thead class="bg-primary text-white">
                <tr>
                    <th>No. SI</th>
                    <th>Plan Delivery Date</th>
                    <th>Site</th>
                    <th>Type</th>
                    <th>No. Polisi</th>
                    <th>Owner</th>
                    <th>Type LC</th>
                    <th>Jalur</th>
                    <th>No. RDO</th>
                    <th>Driver ID</th>
                    <th>Driver Name</th>
                    <th>Crew 1 ID</th>
                    <th>Crew 1 Name</th>
                    <th>Kode Armada</th>
                    <th>Checkin 1</th>
                    <th>Checkin 2</th>
                    <th>Checkin 3</th>
                    <th>Checkin 4</th>
                    <th>Checkin 5</th>
                    <th>Checkin 6</th>
                    <th>Checkin 7</th>
                    <th>Checkin 8</th>
                    <th>Checkin 9</th>
                    <th>Checkin 10</th>
                    <th>Checkin 11</th>
                    <th>Checkin 12</th>
                    <th>Checkin 13</th>
                    <th>Checkin 14</th>
                    <th>Checkin 15</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    .table th {
        font-size: 12px;
        white-space: nowrap;
    }
    .table td {
        font-size: 11px;
    }
    
    /* Custom alert styles */
    .alert-custom {
        border-radius: 8px;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 15px;
    }
    
    .alert-error {
        background: linear-gradient(135deg, #ff6b6b, #ee5a52);
        color: white;
        border-left: 4px solid #d63031;
    }
    
    .alert-warning {
        background: linear-gradient(135deg, #fdcb6e, #f39c12);
        color: white;
        border-left: 4px solid #e17055;
    }
    
    .alert-success {
        background: linear-gradient(135deg, #00b894, #00a085);
        color: white;
        border-left: 4px solid #00b894;
    }
    
    .alert-info {
        background: linear-gradient(135deg, #74b9ff, #0984e3);
        color: white;
        border-left: 4px solid #2d3436;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    
    // Function untuk menampilkan alert
    function showAlert(type, message, title = '') {
        const alertId = 'alert-' + Date.now();
        const alertTitle = title ? `<h6 class="mb-1"><strong>${title}</strong></h6>` : '';
        
        const alertHtml = `
            <div id="${alertId}" class="alert alert-${type} alert-custom alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        ${getAlertIcon(type)}
                    </div>
                    <div class="flex-grow-1">
                        ${alertTitle}
                        <div>${message}</div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            </div>
        `;
        
        $('#alertContainer').prepend(alertHtml);
        
        // Auto remove after 5 seconds for success/info, 8 seconds for error/warning
        const timeout = (type === 'error' || type === 'warning') ? 8000 : 5000;
        setTimeout(() => {
            $(`#${alertId}`).fadeOut(500, function() {
                $(this).remove();
            });
        }, timeout);
    }
    
    // Function untuk mendapatkan icon alert
    function getAlertIcon(type) {
        const icons = {
            'error': '<i class="fas fa-exclamation-triangle fa-lg"></i>',
            'warning': '<i class="fas fa-exclamation-circle fa-lg"></i>',
            'success': '<i class="fas fa-check-circle fa-lg"></i>',
            'info': '<i class="fas fa-info-circle fa-lg"></i>'
        };
        return icons[type] || icons['info'];
    }
    
    // Function untuk validasi form
    function validateForm() {
        const startDate = $('#start_date').val();
        const endDate = $('#end_date').val();
        
        if (!startDate || !endDate) {
            showAlert('warning', 'Harap isi tanggal mulai dan tanggal akhir', 'Validasi Form');
            return false;
        }
        
        if (new Date(startDate) > new Date(endDate)) {
            showAlert('warning', 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir', 'Validasi Form');
            return false;
        }
        
        // Cek jika range tanggal terlalu lama (lebih dari 3 bulan)
        const diffTime = Math.abs(new Date(endDate) - new Date(startDate));
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        if (diffDays > 90) {
            showAlert('info', 'Range tanggal yang dipilih lebih dari 3 bulan. Proses loading mungkin membutuhkan waktu lebih lama.', 'Informasi');
        }
        
        return true;
    }

    function adjustSite() {
        let owner = $('#owner').val().trim().toUpperCase();
        if (owner === 'HCI') {
            $('#site').val('WMWHSE4').prop('disabled', true);
        } else {
            $('#site').val('WMWHSE2').prop('disabled', false);
        }
    }

    adjustSite();

    $('#owner').on('change', function() {
        adjustSite();
    });

    let table = $('#monitoringTable').DataTable({
        processing: true,
        serverSide: false,
        searching: true,
        ordering: true,
        lengthMenu: [10, 25, 50, 100],
        ajax: {
            url: "{{ route('monitoring.pengiriman.data') }}",
            type: "GET",
            beforeSend: function() {
                $("#loadingOverlay").show();
                console.log("🚀 Request Ajax dikirim ke server...");
            },
            complete: function() {
                $("#loadingOverlay").hide();
                console.log("✅ Request Ajax selesai");
            },
            data: function(d) {
                return {
                    start_date: $('#start_date').val(),
                    end_date: $('#end_date').val(),
                    owner: $('#owner').val(),
                    site: $('#site').val()
                };
            },
            dataSrc: function(json) {
                console.log('Response dari server:', json);

                if (json.hasOwnProperty('success')) {
                    if (json.success) {
                        // Tampilkan pesan sukses jika ada data
                        if (json.data && json.data.length > 0) {
                            showAlert('success', `Berhasil memuat ${json.data.length} data pengiriman`, 'Data Berhasil Dimuat');
                        } else {
                            showAlert('info', 'Tidak ada data ditemukan untuk filter yang dipilih', 'Tidak Ada Data');
                        }
                        return json.data;
                    } else {
                        // Error dari server
                        showAlert('error', json.message || 'Terjadi kesalahan saat memuat data', 'Error Server');
                        return [];
                    }
                } else {
                    // Response format tidak sesuai
                    if (Array.isArray(json)) {
                        if (json.length > 0) {
                            showAlert('success', `Berhasil memuat ${json.length} data pengiriman`, 'Data Berhasil Dimuat');
                        } else {
                            showAlert('info', 'Tidak ada data ditemukan untuk filter yang dipilih', 'Tidak Ada Data');
                        }
                        return json;
                    } else {
                        showAlert('error', 'Format response server tidak valid', 'Error Format Data');
                        return [];
                    }
                }
            },
            error: function(xhr, error, thrown) {
                console.error('AJAX Error:', xhr.responseText);
                
                let errorMessage = 'Gagal menghubungi server';
                let errorTitle = 'Error Koneksi';
                
                // Handle different types of errors
                if (xhr.status === 0) {
                    errorMessage = 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.';
                    errorTitle = 'Error Koneksi';
                } else if (xhr.status === 404) {
                    errorMessage = 'Endpoint tidak ditemukan (404). Periksa route server.';
                    errorTitle = 'Error 404';
                } else if (xhr.status === 500) {
                    errorMessage = 'Terjadi kesalahan internal server (500). Silakan hubungi administrator.';
                    errorTitle = 'Error Server';
                } else if (xhr.status === 403) {
                    errorMessage = 'Akses ditolak (403). Anda tidak memiliki izin untuk mengakses data ini.';
                    errorTitle = 'Error Akses';
                } else if (xhr.status === 401) {
                    errorMessage = 'Sesi Anda telah berakhir (401). Silakan login ulang.';
                    errorTitle = 'Error Autentikasi';
                } else if (xhr.status >= 400) {
                    errorMessage = `Error HTTP ${xhr.status}: ${xhr.statusText || 'Kesalahan tidak diketahui'}`;
                    errorTitle = 'Error HTTP';
                } else if (error === 'timeout') {
                    errorMessage = 'Request timeout. Server membutuhkan waktu terlalu lama untuk merespons.';
                    errorTitle = 'Error Timeout';
                } else if (error === 'parsererror') {
                    errorMessage = 'Gagal memproses data dari server. Format response tidak valid.';
                    errorTitle = 'Error Parsing';
                }
                
                showAlert('error', errorMessage, errorTitle);
            }
        },
        columns: [
            { data: 'TRANSNO', name: 'TRANSNO' },
            { data: 'PlanDeliveryDate', name: 'PlanDeliveryDate' },
            { data: 'site', name: 'site' },
            { data: 'type', name: 'type' },
            { data: 'nopol', name: 'nopol' },
            { data: 'Owner', name: 'Owner' },
            { data: 'TypeLC', name: 'TypeLC' },
            { data: 'jalur', name: 'jalur' },
            { data: 'rdo_no', name: 'rdo_no' },
            { data: 'driverid', name: 'driverid' },
            { data: 'drivername', name: 'drivername' },
            { data: 'crew1id', name: 'crew1id' },
            { data: 'crew1name', name: 'crew1name' },
            { data: 'kode_armada', name: 'kode_armada' },
            { data: 'Checkin1', name: 'Checkin1', defaultContent: '-', render: data => data || '-' },
            { data: 'Checkin2', name: 'Checkin2', defaultContent: '-', render: data => data || '-' },
            { data: 'Checkin3', name: 'Checkin3', defaultContent: '-', render: data => data || '-' },
            { data: 'Checkin4', name: 'Checkin4', defaultContent: '-', render: data => data || '-' },
            { data: 'Checkin5', name: 'Checkin5', defaultContent: '-', render: data => data || '-' },
            { data: 'Checkin6', name: 'Checkin6', defaultContent: '-', render: data => data || '-' },
            { data: 'Checkin7', name: 'Checkin7', defaultContent: '-', render: data => data || '-' },
            { data: 'Checkin8', name: 'Checkin8', defaultContent: '-', render: data => data || '-' },
            { data: 'Checkin9', name: 'Checkin9', defaultContent: '-', render: data => data || '-' },
            { data: 'Checkin10', name: 'Checkin10', defaultContent: '-', render: data => data || '-' },
            { data: 'Checkin11', name: 'Checkin11', defaultContent: '-', render: data => data || '-' },
            { data: 'Checkin12', name: 'Checkin12', defaultContent: '-', render: data => data || '-' },
            { data: 'Checkin13', name: 'Checkin13', defaultContent: '-', render: data => data || '-' },
            { data: 'Checkin14', name: 'Checkin14', defaultContent: '-', render: data => data || '-' },
            { data: 'Checkin15', name: 'Checkin15', defaultContent: '-', render: data => data || '-' }
        ]
    });

    $('#btnFilter').on('click', function() {
        // Clear previous alerts
        $('#alertContainer').empty();
        
        console.log('Filter parameters:', {
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val(),
            owner: $('#owner').val(),
            site: $('#site').val()
        });
        
        // Validasi form sebelum submit
        if (!validateForm()) {
            return;
        }
        
        // Tampilkan info bahwa filtering sedang berjalan
        showAlert('info', 'Memfilter data berdasarkan parameter yang dipilih...', 'Memproses Filter');
        
        // Reload table data
        table.ajax.reload(function(json) {
            // Callback setelah reload selesai
            console.log('Table reload completed');
        }, false); // false = reset paging to first page
    });

    // Load data pertama kali
    table.ajax.reload();
    
    // Event handler untuk error DataTable
    table.on('error.dt', function(e, settings, techNote, message) {
        console.error('DataTable Error:', message);
        showAlert('error', 'Terjadi kesalahan pada tabel data: ' + message, 'Error DataTable');
    });
    
    // Event handler untuk draw table (ketika data berhasil dimuat)
    table.on('draw.dt', function() {
        const info = table.page.info();
        console.log('Table drawn with', info.recordsDisplay, 'records');
    });
});
</script>

@endpush