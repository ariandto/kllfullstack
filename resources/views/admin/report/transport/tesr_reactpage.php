@extends('admin.dashboard')

@section('admin')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>

    /* Toast container di pojok kanan atas */
.toast-container {
    position: fixed;
    top: 1rem;
    right: 1rem;
    z-index: 1055;
}

/* Toast box */
.toast {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    min-width: 250px;
    max-width: 350px;
    background-color: #28a745;
    color: white;
    padding: 12px 16px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    opacity: 0;
    transform: translateX(100%);
    transition: all 0.5s ease;
}

/* Toast visible */
.toast.show {
    opacity: 1;
    transform: translateX(0);
}

/* Close button */
.toast .close-btn {
    margin-left: auto;
    cursor: pointer;
    font-weight: bold;
}
    .spinner-border {
        width: 1.2rem;
        height: 1.2rem;
        border-width: 2px;
    }
    
    .table-modern {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        margin-bottom: 20px;
        border: 1px solid #dee2e6;
    }
    
    .table-modern thead th {
        background: #667eea;
        color: white;
        font-weight: 600;
        border: 1px solid rgba(255,255,255,0.1);
        padding: 12px 15px;
        font-size: 14px;
    }
    
    .table-modern tbody td {
        padding: 10px 15px;
        vertical-align: middle;
        font-size: 14px;
        border: 1px solid #dee2e6;
    }
    
    .table-modern tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .badge-success-custom {
        background: #28a745;
        color: white;
        padding: 4px 10px;
        border-radius: 15px;
        font-weight: 600;
        font-size: 12px;
    }
    
    .badge-warning-custom {
        background: #ffc107;
        color: #333;
        padding: 4px 10px;
        border-radius: 15px;
        font-weight: 600;
        font-size: 12px;
    }
    
    .badge-danger-custom {
        background: #dc3545;
        color: white;
        padding: 4px 10px;
        border-radius: 15px;
        font-weight: 600;
        font-size: 12px;
    }
    
    .progress-modern {
        height: 6px;
        border-radius: 10px;
        background-color: #e9ecef;
    }
    
    .progress-bar-modern {
        border-radius: 10px;
        background: #667eea;
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 600;
        margin: 25px 0 15px 0;
        padding-bottom: 10px;
        border-bottom: 2px solid #667eea;
        color: #2c3e50;
    }
    
    .total-row {
        background-color: #f8f9fa !important;
        font-weight: 600;
    }
    
    .text-right {
        text-align: right;
    }
    
    .text-center {
        text-align: center;
    }
</style>

@php
    $StartDate = $StartDate ?? now()->format('Y-m-d');
    $EndDate = $EndDate ?? now()->format('Y-m-d');
    $siteList = $siteList ?? [];
@endphp

<div class="page-content">
    <title>DAILY REPORT TRANSPORT</title>
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header text-white fw-bold justify-content-center d-flex" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
             DAILY REPORT TRANSPORT
        </div>

        <div class="card-body" style="background-color: #f8f9fa;">
            {{-- 🔹 Form Filter --}}
            <form id="filterForm" class="row g-3 mb-4">
                <div class="col-lg-4 col-md-6">
                    <label class="form-label fw-semibold">Facility / Site</label>
                    <select class="form-select" name="facility" id="facility" required>
                        @forelse($siteList as $s)
                            @php
                                $facilityName = is_object($s)
                                    ? ($s->NAME ?? $s->FacilityName ?? $s->Facility ?? $s->Site ?? reset((array)$s))
                                    : ($s['NAME'] ?? $s['FacilityName'] ?? $s['Facility'] ?? $s['Site'] ?? reset($s));
                            @endphp
                            @if(!empty($facilityName))
                                <option value="{{ $facilityName }}" {{ ($facilityID ?? '') === $facilityName ? 'selected' : '' }}>
                                    {{ $facilityName }}
                                </option>
                            @endif
                        @empty
                            <option value="">-- Tidak ada data site --</option>
                        @endforelse
                    </select>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-semibold">Tanggal Awal</label>
                    <input type="date" class="form-control" name="start_date" id="start_date" value="{{ $StartDate }}">
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-semibold">Tanggal Akhir</label>
                    <input type="date" class="form-control" name="end_date" id="end_date" value="{{ $EndDate }}">
                </div>

                <div class="col-lg-2 col-md-6 d-flex align-items-end">
                    <button type="submit" id="btnTampil" class="btn btn-primary w-100 position-relative" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none position-absolute top-50 start-50 translate-middle"></span>
                        <span id="btnText"><i class="fas fa-search me-1"></i> Tampilkan</span>
                    </button>
                </div>
            </form>

            {{-- 🔹 Container hasil data --}}
            <div id="dataContainer" class="mt-3"></div>
        </div>
    </div>
</div>

{{-- 🔹 Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filterForm');
    const btn = document.getElementById('btnTampil');
    const spinner = document.getElementById('loadingSpinner');
    const btnText = document.getElementById('btnText');
    const container = document.getElementById('dataContainer');

    function formatNumber(num) {
        if (!num) return '0';
        return new Intl.NumberFormat('id-ID').format(num);
    }

    function formatCurrency(num) {
        if (!num) return 'Rp 0';
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
    }

    function createProdCustomerTable(data) {
        const prodCustomer = data.prodCustomer || [];
        
        let html = `
            <div class="section-title">
                <i class="fas fa-users me-2"></i>Produktivitas Customer
            </div>
            <div class="table-modern">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jalur</th>
                            <th class="text-center">Trip</th>
                            <th class="text-center">DP/Trip</th>
                            <th class="text-right">Total UJP</th>
                            <th class="text-right">UJP/Trip</th>
                            <th class="text-center">Actual TAT (min)</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        prodCustomer.forEach(item => {
            const isTotal = item.Jalur === 'Total';
            const rowClass = isTotal ? 'total-row' : '';
            
            html += `
                <tr class="${rowClass}">
                    <td>${item.No}</td>
                    <td><strong>${item.Jalur}</strong></td>
                    <td class="text-center">${formatNumber(item.Trip)}</td>
                    <td class="text-center">${item['DP/Trip']}</td>
                    <td class="text-right">${formatCurrency(item['Total UJP'])}</td>
                    <td class="text-right">${formatCurrency(item['UJP/Trip'])}</td>
                    <td class="text-center">${item['Actual TAT']}</td>
                </tr>
            `;
        });
        
        html += `
                    </tbody>
                </table>
            </div>
        `;
        
        return html;
    }

    function createProdStoreTable(data) {
        const prodStore = data.prodStore || [];
        
        let html = `
            <div class="section-title">
                <i class="fas fa-store me-2"></i>Produktivitas Store
            </div>
            <div class="table-modern">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jalur</th>
                            <th class="text-center">Trip</th>
                            <th class="text-center">CBM/Trip</th>
                            <th class="text-right">Total UJP</th>
                            <th class="text-right">UJP/Trip</th>
                            <th class="text-center">Actual TAT (min)</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        prodStore.forEach(item => {
            const isTotal = item.Jalur === 'Total';
            const rowClass = isTotal ? 'total-row' : '';
            
            html += `
                <tr class="${rowClass}">
                    <td>${item.No}</td>
                    <td><strong>${item.Jalur}</strong></td>
                    <td class="text-center">${formatNumber(item.Trip)}</td>
                    <td class="text-center">${item['CBM/Trip']}</td>
                    <td class="text-right">${formatCurrency(item['Total UJP'])}</td>
                    <td class="text-right">${formatCurrency(item['UJP/Trip'])}</td>
                    <td class="text-center">${item['Actual TAT']}</td>
                </tr>
            `;
        });
        
        html += `
                    </tbody>
                </table>
            </div>
        `;
        
        return html;
    }

    function createDeliveryCustomerTable(data) {
        const deliveryCustomer = data.deliveryCustomer || [];
        
        let html = `
            <div class="section-title">
                <i class="fas fa-shipping-fast me-2"></i>Summary DO Customer
            </div>
            <div class="table-modern">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Order Type</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        deliveryCustomer.forEach(item => {
            const isTotal = item.Order === 'TOTAL';
            const rowClass = isTotal ? 'total-row' : '';
            
            html += `
                <tr class="${rowClass}">
                    <td><strong>${item.Order}</strong></td>
                    <td class="text-center">${item.Total ? formatNumber(item.Total) : '-'}</td>
                    <td class="text-center">${item.Persentase ? item.Persentase + '%' : '-'}</td>
                </tr>
            `;
        });
        
        html += `
                    </tbody>
                </table>
            </div>
        `;
        
        return html;
    }

    function createDeliveryStoreTable(data) {
        const deliveryStore = data.deliveryStore || [];
        
        let html = `
            <div class="section-title">
                <i class="fas fa-warehouse me-2"></i>Delivery to Store
            </div>
            <div class="table-modern">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Area</th>
                            <th class="text-center">CBM Armada</th>
                            <th class="text-center">CBM Actual</th>
                            <th class="text-center">Standar OLF (%)</th>
                            <th class="text-center">Persentase (%)</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        deliveryStore.forEach(item => {
            const standardOLF = parseFloat(item['Standar OLF (%)'] || 0);
            const actualOLF = parseFloat(item['Persentase (%)'] || 0);
            const status = actualOLF >= standardOLF ? 'badge-success-custom' : 'badge-danger-custom';
            
            html += `
                <tr>
                    <td><strong>${item.Area}</strong></td>
                    <td class="text-center">${item['CBM Armada']}</td>
                    <td class="text-center">${item['CBM Actual']}</td>
                    <td class="text-center">${item['Standar OLF (%)']}%</td>
                    <td class="text-center"><span class="${status}">${item['Persentase (%)']}%</span></td>
                </tr>
            `;
        });
        
        html += `
                    </tbody>
                </table>
            </div>
        `;
        
        return html;
    }

    function createMonitoringExternalTable(data) {
        const monitoringExternal = data.monitoringExternal || [];
        
        let html = `
            <div class="section-title">
                <i class="fas fa-clipboard-check me-2"></i>Monitoring Armada External
            </div>
            <div class="table-modern">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tipe Kiriman</th>
                            <th class="text-center">Plan</th>
                            <th class="text-center">Fulfill</th>
                            <th class="text-center">Not Fulfill</th>
                            <th class="text-center">Ontime</th>
                            <th class="text-center">Late</th>
                            <th class="text-center">Kembali</th>
                            <th class="text-center">Belum Kembali</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        monitoringExternal.forEach(item => {
            const isTotal = item.TipeKiriman === 'Total';
            const rowClass = isTotal ? 'total-row' : '';
            
            html += `
                <tr class="${rowClass}">
                    <td>${item.No}</td>
                    <td><strong>${item.TipeKiriman}</strong></td>
                    <td class="text-center">${formatNumber(item.Plan)}</td>
                    <td class="text-center">${formatNumber(item.Fulfill)}</td>
                    <td class="text-center">${formatNumber(item['Not Fullfil'])}</td>
                    <td class="text-center">${formatNumber(item.Ontime)}</td>
                    <td class="text-center">${formatNumber(item.Late)}</td>
                    <td class="text-center">${formatNumber(item.Kembali)}</td>
                    <td class="text-center">${formatNumber(item['Belum Kembali'])}</td>
                </tr>
            `;
        });
        
        html += `
                    </tbody>
                </table>
            </div>
        `;
        
        return html;
    }

    function createMonitoringInternalTable(data) {
        const monitoringInternal = data.monitoringInternal || [];
        
        let html = `
            <div class="section-title">
                <i class="fas fa-clipboard-list me-2"></i>Monitoring Armada Internal
            </div>
            <div class="table-modern">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tipe Kiriman</th>
                            <th class="text-center">Plan</th>
                            <th class="text-center">Fulfill</th>
                            <th class="text-center">Not Fulfill</th>
                            <th class="text-center">Ontime</th>
                            <th class="text-center">Late</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        monitoringInternal.forEach(item => {
            const isTotal = item.TipeKiriman === 'Total';
            const rowClass = isTotal ? 'total-row' : '';
            
            html += `
                <tr class="${rowClass}">
                    <td>${item.No}</td>
                    <td><strong>${item.TipeKiriman}</strong></td>
                    <td class="text-center">${formatNumber(item.Plan)}</td>
                    <td class="text-center">${formatNumber(item.Fulfill)}</td>
                    <td class="text-center">${formatNumber(item['Not Fullfil'])}</td>
                    <td class="text-center">${formatNumber(item.Ontime)}</td>
                    <td class="text-center">${formatNumber(item.Late)}</td>
                </tr>
            `;
        });
        
        html += `
                    </tbody>
                </table>
            </div>
        `;
        
        return html;
    }

    function createSLATable(data) {
    const slaCustomer = data.slaCustomer || [];

    const labels = slaCustomer.map(i => i.SLA);
    const actualData = slaCustomer.map(i => parseFloat(i.Persentase || 0));
    const standardData = slaCustomer.map(i => parseFloat(i.STD || 0));

    let html = `
        <div class="section-title">
            <i class="fas fa-award me-2"></i>SLA Customer Performance
        </div>

        <div class="table-modern mb-4">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>SLA Metric</th>
                        <th class="text-center">Standard</th>
                        <th class="text-center">Jumlah DO</th>
                        <th class="text-center">Persentase</th>
                    </tr>
                </thead>
                <tbody>
    `;

    slaCustomer.forEach(item => {
        const isTotal = item.SLA === 'TOTAL';
        const rowClass = isTotal ? 'total-row' : '';
        const achievement = parseFloat(item.Persentase || 0);
        const standard = parseFloat(item.STD || 0);
        const status = achievement >= standard ? 'badge-success-custom' : 'badge-danger-custom';
        
        html += `
            <tr class="${rowClass}">
                <td><strong>${item.SLA}</strong></td>
                <td class="text-center">${item.STD ? item.STD + '%' : '-'}</td>
                <td class="text-center">${formatNumber(item['Jumlah DO'])}</td>
                <td class="text-center"><span class="${status}">${item.Persentase}%</span></td>
            </tr>
        `;
    });

    html += `
                </tbody>
            </table>
        </div>

        <!-- 🔹 Chart Container -->
        <div class="card shadow-sm border-0 rounded-3 mb-4" style="background:white;">
            <div class="p-3 pb-2 fw-semibold" style="color:#2c3e50;">
                <i class="fas fa-chart-bar me-2"></i>Grafik SLA Customer
            </div>
            <div class="p-3" style="height:auto; min-height:300px;">
                <canvas id="slaChart"></canvas>
            </div>
        </div>
    `;

    // 🔹 Render chart responsif setelah HTML dimasukkan
    setTimeout(() => {
        const ctx = document.getElementById('slaChart');
        if (!ctx) return;

        // Hapus chart lama jika ada
        if (window.slaChartInstance) {
            window.slaChartInstance.destroy();
        }

        window.slaChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pencapaian (%)',
                        data: actualData,
                        borderRadius: 6,
                        backgroundColor: actualData.map(v =>
                            v >= 90 ? '#28a745' :
                            v >= 70 ? '#ffc107' : '#dc3545'
                        ),
                    },
                    {
                        label: 'Standar (%)',
                        data: standardData,
                        borderRadius: 6,
                        backgroundColor: 'rgba(102,126,234,0.2)',
                        borderColor: '#667eea',
                        borderWidth: 1.5,
                    }
                ]
            },
            options: {
                indexAxis: 'y', // Horizontal bar
                responsive: true,
                maintainAspectRatio: false,
                aspectRatio: 2, // 🔹 jaga proporsinya di berbagai ukuran layar
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#2c3e50',
                            font: { size: 13, weight: '600' }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#fff',
                        titleColor: '#333',
                        bodyColor: '#333',
                        borderColor: '#ddd',
                        borderWidth: 1,
                        callbacks: {
                            label: (ctx) => ctx.dataset.label + ': ' + ctx.parsed.x + '%'
                        }
                    }
                },
                layout: { padding: { left: 10, right: 10, top: 10, bottom: 10 } },
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            color: '#2c3e50',
                            callback: (v) => v + '%'
                        },
                        grid: { color: '#eee' }
                    },
                    y: {
                        ticks: {
                            color: '#2c3e50',
                            font: { weight: '600' }
                        },
                        grid: { display: false }
                    }
                }
            }
        });
    }, 300);

    return html;
}



    function createProdArmadaTable(data) {
        const prodArmada = data.prodArmada || [];
        
        let html = `
            <div class="section-title">
                <i class="fas fa-truck me-2"></i>Produktivitas Armada
            </div>
            <div class="table-modern">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Jenis Armada</th>
                            <th class="text-center">Trip</th>
                            <th class="text-center">CBM</th>
                            <th class="text-center">AVG CBM</th>
                            <th class="text-center">Olf</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        prodArmada.forEach(item => {
            html += `
                <tr>
                    <td><strong>${item.JENISARMADA}</strong></td>
                    <td class="text-center">${formatNumber(item.Trip)}</td>
                    <td class="text-center">${item.CBM}</td>
                    <td class="text-center">${item['AVG CBM']}</td>
                    <td class="text-center">${item.Olf}</td>
                </tr>
            `;
        });
        
        html += `
                    </tbody>
                </table>
            </div>
        `;
        
        return html;
    }

    function createArmadaUtilTable(data) {
        const armadaUtil = data.armadaUtil || [];
        
        let html = `
            <div class="section-title">
                <i class="fas fa-chart-pie me-2"></i>Utilisasi Armada
            </div>
            <div class="table-modern">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>BU</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Total Armada</th>
                            <th class="text-center">Available</th>
                            <th class="text-center">Utilize</th>
                            <th class="text-center">Not Available</th>
                            <th class="text-center">Idle</th>
                            <th class="text-center">% Available</th>
                            <th class="text-center">% Utilization</th>
                            <th class="text-center">% Not Available</th>
                            <th class="text-center">% Idle</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        armadaUtil.forEach(item => {
            html += `
                <tr>
                    <td><strong>${item.BU}</strong></td>
                    <td class="text-center">${item.Tanggal}</td>
                    <td class="text-center">${formatNumber(item.TotalArmada)}</td>
                    <td class="text-center">${formatNumber(item.Available)}</td>
                    <td class="text-center">${formatNumber(item.Utilize)}</td>
                    <td class="text-center">${formatNumber(item.NotAvailable)}</td>
                    <td class="text-center">${formatNumber(item.Idle)}</td>
                    <td class="text-center">${item.PctAvailable}%</td>
                    <td class="text-center">${item.PctUtilization}%</td>
                    <td class="text-center">${item.PctNotAvailable}%</td>
                    <td class="text-center">${item.PctIdle}%</td>
                </tr>
            `;
        });
        
        html += `
                    </tbody>
                </table>
            </div>
        `;
        
        return html;
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        spinner.classList.remove('d-none');
        btnText.classList.add('invisible');
        btn.disabled = true;
        container.innerHTML = '';

        const payload = {
            facility: document.getElementById('facility').value,
            start_date: document.getElementById('start_date').value,
            end_date: document.getElementById('end_date').value,
            key1: 'WMWHSE4RTL',
        };

        const startTime = performance.now(); 

        try {
            const res = await fetch("{{ route('transport.dailyreport.data') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(payload)
            });

            if (!res.ok) throw new Error(`HTTP ${res.status} ${res.statusText}`);
            const result = await res.json();

            const endTime = performance.now(); // 🔹 waktu selesai
            const execTime = ((endTime - startTime) / 1000).toFixed(2); // dalam detik

            if (result && result.data) {
    const data = result.data;

    // tampilkan toast
    showToast(`Data berhasil dimuat untuk DC ${payload.facility} (${payload.start_date} s/d ${payload.end_date})`);
    showToast(`Waktu eksekusi data: ${execTime} detik`, 7000);
    let html = `
        ${createSLATable(data)}
        ${createProdCustomerTable(data)}
        ${createProdStoreTable(data)}
        ${createDeliveryCustomerTable(data)}
        ${createDeliveryStoreTable(data)}
        ${createMonitoringExternalTable(data)}
        ${createMonitoringInternalTable(data)}
        ${createProdArmadaTable(data)}
        ${createArmadaUtilTable(data)}
    `;

    container.innerHTML = html;
} 


        } catch (err) {
            console.error(err);
            container.innerHTML = `<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Terjadi kesalahan: ${err.message || String(err)}</div>`;
        } finally {
            spinner.classList.add('d-none');
            btnText.classList.remove('invisible');
            btn.disabled = false;
        }
    });
});
function showToast(message, duration = 5000) {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.innerHTML = `
        <span>${message}</span>
        <span class="close-btn">&times;</span>
    `;
    
    container.appendChild(toast);

    // klik tombol close untuk dismiss manual
    toast.querySelector('.close-btn').addEventListener('click', () => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 500);
    });

    // tampilkan toast
    setTimeout(() => toast.classList.add('show'), 50);

    // auto hide setelah duration
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 500);
    }, duration);
}



</script>
<div class="toast-container" id="toastContainer"></div>
@endsection