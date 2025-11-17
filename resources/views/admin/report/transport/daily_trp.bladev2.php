@extends('admin.dashboard')
@section('title', 'Daily Report Transport')
@section('admin')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<style>
/* ===== Toast ===== */
.toast-container {
  position: fixed;
  top: 1rem;
  right: 1rem;
  z-index: 1055;
}
.toast {
  display: flex;
  align-items: center;
  gap: .5rem;
  min-width: 250px;
  max-width: 350px;
  background: linear-gradient(135deg, #489FB5 0%, #16697A 100%);
  color: #fff;
  padding: 12px 16px;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0,0,0,.2);
  opacity: 0;
  transform: translateX(100%);
  transition: all .5s;
}
.toast.show {
  opacity: 1;
  transform: translateX(0);
}
.toast .close-btn {
  margin-left: auto;
  cursor: pointer;
  font-weight: 700;
}

/* ===== Spinner & Tombol Loading ===== */
.spinner-border {
  width: 1.2rem;
  height: 1.2rem;
  border-width: 2px;
  vertical-align: middle;
}

.btn-loading {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  pointer-events: none;
  opacity: 0.9;
  height: 42px;
}

.btn-loading .spinner-border {
  position: absolute;
  inset: 0;
  margin: auto;
  display: block !important;
  z-index: 10;
}

.btn-loading #btnText {
  visibility: hidden;
}

/* ===== Layout & Table ===== */
.table-responsive-wrapper {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  margin-bottom: 20px;
}

.table-modern {
  background: #EDE7E3;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0,0,0,.08);
  border: 1px solid #ddd;
  min-width: 100%;
}

.table-modern table { 
  width: 100%; 
  margin: 0; 
}

.table-modern thead th {
  background: #16697A;
  color: #fff;
  font-weight: 600;
  border: 1px solid rgba(255,255,255,.1);
  padding: 12px 10px;
  font-size: 13px;
  white-space: nowrap;
  position: sticky;
  top: 0;
  z-index: 10;
}

.table-modern tbody td {
  padding: 10px;
  vertical-align: middle;
  font-size: 13px;
  border: 1px solid #dee2e6;
}

.table-modern tbody tr:hover {
  background: #82C0CC33;
}

/* DataTables sorting icons */
.table-modern thead th.sorting,
.table-modern thead th.sorting_asc,
.table-modern thead th.sorting_desc {
  cursor: pointer;
  position: relative;
  padding-right: 25px;
}

.table-modern thead th.sorting:after,
.table-modern thead th.sorting_asc:after,
.table-modern thead th.sorting_desc:after {
  position: absolute;
  right: 8px;
  top: 50%;
  transform: translateY(-50%);
  font-family: 'Font Awesome 5 Free';
  font-weight: 900;
  font-size: 10px;
  opacity: 0.7;
}

.table-modern thead th.sorting:after {
  content: "\f0dc";
}

.table-modern thead th.sorting_asc:after {
  content: "\f0de";
  opacity: 1;
}

.table-modern thead th.sorting_desc:after {
  content: "\f0dd";
  opacity: 1;
}

/* ===== Summary Card ===== */
.summary-card {
  background: linear-gradient(135deg, #489FB5 0%, #82C0CC 100%);
  color: #fff;
  padding: 15px;
  border-radius: 10px;
  margin-bottom: 15px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.summary-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}

.summary-card h6 {
  font-size: 13px;
  font-weight: 600;
  margin-bottom: 8px;
  opacity: .9;
}

.summary-card .summary-value {
  font-size: 24px;
  font-weight: 700;
  margin-bottom: 5px;
}

.summary-card .summary-label {
  font-size: 12px;
  opacity: .85;
}

.summary-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 15px;
  margin-bottom: 20px;
}

/* ===== Badges ===== */
.badge-success-custom {
  background: #489FB5;
  color: #fff;
  padding: 4px 10px;
  border-radius: 15px;
  font-weight: 600;
  font-size: 11px;
}

.badge-warning-custom {
  background: #FFA62B;
  color: #fff;
  padding: 4px 10px;
  border-radius: 15px;
  font-weight: 600;
  font-size: 11px;
}

.badge-danger-custom {
  background: #16697A;
  color: #fff;
  padding: 4px 10px;
  border-radius: 15px;
  font-weight: 600;
  font-size: 11px;
}

/* ===== Titles ===== */
.section-title {
  font-size: 16px;
  font-weight: 600;
  margin: 25px 0 15px;
  padding: 12px 15px;
  background: linear-gradient(135deg, #16697A 0%, #489FB5 100%);
  color: #fff;
  border-radius: 8px;
  display: flex;
  align-items: center;
}

.section-title i { 
  margin-right: 10px; 
}

.total-row {
  background: #EDE7E3 !important;
  font-weight: 700;
  border-top: 2px solid #16697A !important;
}

.text-right { text-align: right; }
.text-center { text-align: center; }

/* ===== Info Box ===== */
.info-box {
  background: #EDE7E3;
  border-left: 4px solid #489FB5;
  padding: 12px 15px;
  margin-bottom: 15px;
  border-radius: 5px;
  font-size: 13px;
}

.info-box strong { 
  color: #16697A; 
}

/* ===== Button & Header ===== */
.btn-primary {
  background: linear-gradient(135deg, #489FB5 0%, #16697A 100%) !important;
  border: none;
  color: #fff;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(72, 159, 181, 0.4);
}

.card-header {
  background: linear-gradient(135deg, #16697A 0%, #489FB5 100%) !important;
}

/* ===== Chart Container ===== */
.chart-container {
  position: relative;
  height: 320px;
  width: 100%;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0,0,0,.05);
  padding: 10px;
}

.chart-card {
  background: #fff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0,0,0,.08);
  margin-bottom: 20px;
}

.chart-header {
  padding: 15px 20px;
  background: linear-gradient(135deg, #82C0CC 0%, #489FB5 100%);
  color: #fff;
  font-weight: 600;
  display: flex;
  align-items: center;
}

.chart-header i {
  margin-right: 10px;
}

/* ===== Responsive Breakpoints ===== */
@media (max-width: 992px) {
  form#filterForm .col-lg-4, 
  form#filterForm .col-lg-3, 
  form#filterForm .col-lg-2 {
    flex: 0 0 100%;
    max-width: 100%;
  }
  #btnTampil { margin-top: 10px; }
}

@media (max-width: 768px) {
  .table-modern thead th,
  .table-modern tbody td {
    font-size: 11px;
    padding: 8px 6px;
  }
  .section-title {
    font-size: 14px;
    padding: 10px 12px;
  }
  .summary-card .summary-value { font-size: 20px; }
  .summary-grid {
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 10px;
  }
  .toast-container { 
    right: .5rem; 
    left: .5rem; 
  }
  .toast { max-width: 100%; }
  .chart-container { height: 260px; }
}

@media (max-width: 576px) {
  .card-body { padding: 12px !important; }
  .summary-card { padding: 12px; }
  .form-label, select.form-select, input.form-control {
    font-size: 13px;
  }
}

/* DataTables responsive styles */
.dataTables_wrapper {
  font-size: 13px;
}

.dataTables_filter input {
  border-radius: 5px;
  border: 1px solid #ddd;
  padding: 5px 10px;
  margin-left: 5px;
}

.dataTables_length select {
  border-radius: 5px;
  border: 1px solid #ddd;
  padding: 5px 10px;
  margin: 0 5px;
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
<div class="card-header text-white fw-bold justify-content-center d-flex" style="background:linear-gradient(135deg,#16697A 0%,#489FB5 100%)">
  DAILY REPORT TRANSPORT
</div>
<div class="card-body" style="background-color:#f8f9fa">
<form id="filterForm" class="row g-3 mb-4">
  <div class="col-lg-4 col-md-6 col-12">
    <label class="form-label fw-semibold">Facility</label>
    <select class="form-select" name="facility" id="facility" required>
      <option value="">-- Klik untuk memuat daftar facility --</option>
    </select>
  </div>
  <div class="col-lg-3 col-md-6 col-12">
    <label class="form-label fw-semibold">Tanggal Awal</label>
    <input type="date" class="form-control" name="start_date" id="start_date" value="{{$StartDate}}">
  </div>
  <div class="col-lg-3 col-md-6 col-12">
    <label class="form-label fw-semibold">Tanggal Akhir</label>
    <input type="date" class="form-control" name="end_date" id="end_date" value="{{$EndDate}}">
  </div>
  <div class="col-lg-2 col-md-6 col-12 d-flex align-items-end">
    <button 
      type="submit" 
      id="btnTampil" 
      class="btn btn-primary flex-grow-1"
      style="height:42px;"
    >
      <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
      <span id="btnText"><i class="fas fa-search me-1"></i> Tampilkan</span>
    </button>
  </div>
</form>
<div id="dataContainer" class="mt-3"></div>
</div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
const form = document.getElementById('filterForm');
const btn = document.getElementById('btnTampil');
const spinner = document.getElementById('loadingSpinner');
const btnText = document.getElementById('btnText');
const container = document.getElementById('dataContainer');
let tableCounter = 0;

function formatNumber(n) {
  return !n && n !== 0 ? '0' : new Intl.NumberFormat('id-ID').format(n);
}

function formatCurrency(n) {
  return !n && n !== 0 ? 'Rp 0' : 'Rp ' + new Intl.NumberFormat('id-ID').format(n);
}

function createSummary(data, type) {
  let cards = '';
  data.forEach(d => cards += `<div class="summary-card"><h6>${d.title}</h6><div class="summary-value">${d.value}</div><div class="summary-label">${d.label}</div></div>`);
  return `<div class="summary-grid">${cards}</div>`;
}

function createTable(title, icon, headers, rows, hasSummary = false, summaryData = null, enableSort = true) {
  const tableId = `dataTable_${tableCounter++}`;
  let html = `<div class="section-title"><i class="fas fa-${icon}"></i>${title}</div>`;
  if (hasSummary && summaryData) { html += createSummary(summaryData); }
  html += `<div class="table-responsive-wrapper"><div class="table-modern"><table id="${tableId}" class="table table-sm mb-0 ${enableSort ? 'sortable-table' : ''}"><thead><tr>`;
  headers.forEach(h => html += `<th class="${h.align || ''}">${h.label}</th>`);
  html += `</tr></thead><tbody>`;
  rows.forEach(r => {
    const isTotal = r.isTotal || false;
    html += `<tr class="${isTotal ? 'total-row' : ''}">`;
    r.cells.forEach(c => html += `<td class="${c.align || ''}">${c.value}</td>`);
    html += `</tr>`;
  });
  html += `</tbody></table></div></div>`;
  
  if (enableSort) {
    setTimeout(() => {
      if ($.fn.DataTable.isDataTable('#' + tableId)) {
        $('#' + tableId).DataTable().destroy();
      }
      $('#' + tableId).DataTable({
        paging: false,
        searching: false,
        info: false,
        order: [],
        language: {
          emptyTable: "Tidak ada data",
          zeroRecords: "Tidak ada data yang cocok"
        }
      });
    }, 100);
  }
  
  return html;
}

function buildSLATable(data) {
  const sla = data.slaCustomer || [];
  if (!sla.length) return '';
  
  const summary = [];
  const rows = sla.map(i => {
    const ach = parseFloat(i.Persentase || 0);
    const std = parseFloat(i.STD || 0);
    return {
      isTotal: i.SLA === 'TOTAL',
      cells: [
        { value: `<strong>${i.SLA}</strong>` },
        { value: i.STD ? i.STD + '%' : '-', align: 'text-center' },
        { value: formatNumber(i['Jumlah DO']), align: 'text-center' },
        { value: `<span class="${ach >= std ? 'badge-success-custom' : 'badge-danger-custom'}">${i.Persentase}%</span>`, align: 'text-center' }
      ]
    };
  });
  
  let html = createTable('SLA Customer Performance', 'award', [
    { label: 'SLA Metric' },
    { label: 'Standard', align: 'text-center' },
    { label: 'Jumlah DO', align: 'text-center' },
    { label: 'Persentase', align: 'text-center' }
  ], rows, true, summary);
  
  const labels = sla.filter(i => i.SLA !== 'TOTAL').map(i => i.SLA);
  const actualData = sla.filter(i => i.SLA !== 'TOTAL').map(i => parseFloat(i.Persentase || 0));
  const standardData = sla.filter(i => i.SLA !== 'TOTAL').map(i => parseFloat(i.STD || 0));
  
  html += `
    <div class="chart-card">
      <div class="chart-header">
        <i class="fas fa-chart-bar"></i>Grafik SLA Customer
      </div>
      <div class="p-3">
        <div class="chart-container">
          <canvas id="slaChart"></canvas>
        </div>
      </div>
    </div>`;
  
  setTimeout(() => {
    const ctx = document.getElementById('slaChart');
    if (!ctx) return;
    if (window.slaChartInstance) window.slaChartInstance.destroy();
    window.slaChartInstance = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [
          {
            label: 'Pencapaian (%)',
            data: actualData,
            borderRadius: 6,
            backgroundColor: actualData.map(v => v >= 90 ? '#489FB5' : v >= 70 ? '#FFA62B' : '#16697A')
          },
          {
            label: 'Standar (%)',
            data: standardData,
            borderRadius: 6,
            backgroundColor: 'rgba(130, 192, 204, 0.3)',
            borderColor: '#82C0CC',
            borderWidth: 2
          }
        ]
      },
      options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top',
            labels: {
              color: '#2c3e50',
              font: { size: 12, weight: '600' },
              padding: 15
            }
          },
          tooltip: {
            backgroundColor: '#fff',
            titleColor: '#333',
            bodyColor: '#333',
            borderColor: '#ddd',
            borderWidth: 1,
            callbacks: {
              label: ctx => ctx.dataset.label + ': ' + ctx.parsed.x + '%'
            }
          }
        },
        layout: { padding: 10 },
        scales: {
          x: {
            beginAtZero: true,
            max: 100,
            ticks: {
              color: '#2c3e50',
              callback: v => v + '%',
              font: { size: 11 }
            },
            grid: { color: '#eee' }
          },
          y: {
            ticks: {
              color: '#2c3e50',
              font: { weight: '600', size: 11 }
            },
            grid: { display: false }
          }
        }
      }
    });
  }, 300);
  
  return html;
}

function buildProdTable(data, type) {
  const items = data[type] || [];
  if (!items.length) return '';
  
  const totalTrip = items.reduce((s, i) => i.Jalur !== 'Total' ? s + (parseInt(i.Trip) || 0) : s, 0);
  const avgTAT = items.filter(i => i.Jalur !== 'Total').reduce((s, i, _, a) => s + (parseFloat(i['Actual TAT']) || 0) / a.length, 0);
  const avgUJP = items.filter(i => i.Jalur !== 'Total').reduce((s, i, _, a) => s + (parseFloat(i['UJP/Trip']) || 0) / a.length, 0);
  
  const summary = [
    { title: 'Total Trip', value: formatNumber(totalTrip), label: type === 'prodCustomer' ? 'Seluruh Jalur' : 'Pengiriman Store' },
    { title: 'Rata-rata TAT', value: avgTAT.toFixed(0) + ' min', label: 'Waktu Aktual' },
    { title: 'Rata-rata UJP/Trip', value: formatCurrency(avgUJP.toFixed(0)), label: 'Rata-rata UJP' }
  ];
  
  const rows = items.map(i => ({
    isTotal: i.Jalur === 'Total',
    cells: [
      { value: i.No },
      { value: `<strong>${i.Jalur}</strong>` },
      { value: formatNumber(i.Trip), align: 'text-center' },
      { value: i[type === 'prodCustomer' ? 'DP/Trip' : 'CBM/Trip'], align: 'text-center' },
      { value: formatCurrency(i['Total UJP']), align: 'text-right' },
      { value: formatCurrency(i['UJP/Trip']), align: 'text-right' },
      { value: i['Actual TAT'], align: 'text-center' }
    ]
  }));
  
  return createTable(
    type === 'prodCustomer' ? 'Produktivitas Customer' : 'Produktivitas Store',
    type === 'prodCustomer' ? 'users' : 'store',
    [
      { label: 'No' },
      { label: 'Jalur' },
      { label: 'Trip', align: 'text-center' },
      { label: type === 'prodCustomer' ? 'DP/Trip' : 'CBM/Trip', align: 'text-center' },
      { label: 'Total UJP', align: 'text-right' },
      { label: 'UJP/Trip', align: 'text-right' },
      { label: 'Actual TAT', align: 'text-center' }
    ],
    rows, true, summary
  );
}

function buildDeliveryCustomer(data) {
  const items = data.deliveryCustomer || [];
  if (!items.length) return '';
  const totalDO = items.find(i => i.Order === 'TOTAL')?.Total || 0;
  const rows = items.map(i => ({
    isTotal: i.Order === 'TOTAL',
    cells: [
      { value: `<strong>${i.Order}</strong>` },
      { value: i.Total ? formatNumber(i.Total) : '-', align: 'text-center' },
      { value: i.Persentase ? i.Persentase + '%' : '-', align: 'text-center' }
    ]
  }));
  return `<div class="section-title"><i class="fas fa-shipping-fast"></i>Summary DO Customer</div><div class="info-box"><strong>Total Delivery Order:</strong> ${formatNumber(totalDO)} DO</div>` + createTable('', 'shipping-fast', [{ label: 'Order Type' }, { label: 'Total', align: 'text-center' }, { label: 'Persentase', align: 'text-center' }], rows).replace('<div class="section-title"><i class="fas fa-shipping-fast"></i></div>', '');
}

function buildDeliveryStore(data) {
  const items = data.deliveryStore || [];
  if (!items.length) return '';
  const totalCBMA = items.reduce((s, i) => s + (parseFloat(i['CBM Armada']) || 0), 0);
  const totalCBMAct = items.reduce((s, i) => s + (parseFloat(i['CBM Actual']) || 0), 0);
  const avgOLF = items.reduce((s, i, _, a) => s + (parseFloat(i['Persentase (%)']) || 0) / a.length, 0);
  const summary = [
    { title: 'Total CBM Armada', value: totalCBMA.toFixed(2), label: 'Kapasitas Tersedia' },
    { title: 'Total CBM Actual', value: totalCBMAct.toFixed(2), label: 'Terpakai' },
    { title: 'Rata-rata OLF', value: avgOLF.toFixed(1) + '%', label: 'Utilisasi' }
  ];
  const rows = items.map(i => {
    const std = parseFloat(i['Standar OLF (%)'] || 0);
    const act = parseFloat(i['Persentase (%)'] || 0);
    return {
      cells: [
        { value: `<strong>${i.Area}</strong>` },
        { value: i['CBM Armada'], align: 'text-center' },
        { value: i['CBM Actual'], align: 'text-center' },
        { value: i['Standar OLF (%)'] + '%', align: 'text-center' },
        { value: `<span class="${act >= std ? 'badge-success-custom' : 'badge-danger-custom'}">${i['Persentase (%)']}%</span>`, align: 'text-center' }
      ]
    };
  });
  return createTable('Delivery to Store', 'warehouse', [{ label: 'Area' }, { label: 'CBM Armada', align: 'text-center' }, { label: 'CBM Actual', align: 'text-center' }, { label: 'Standar OLF', align: 'text-center' }, { label: 'Persentase', align: 'text-center' }], rows, true, summary);
}

function buildMonitoring(data, type) {
  const items = data[type] || [];
  if (!items.length) return '';
  
  const totalData = items.find(i => i.TipeKiriman === 'Total');
  const fulfillRate = totalData ? ((parseInt(totalData.Fulfill) || 0) / (parseInt(totalData.Plan) || 1) * 100).toFixed(1) : 0;
  const ontimeRate = totalData ? ((parseInt(totalData.Ontime) || 0) / (parseInt(totalData.Fulfill) || 1) * 100).toFixed(1) : 0;
  
  const summary = [
    { title: 'Tingkat Pemenuhan', value: fulfillRate + '%', label: 'Fulfill Rate' },
    { title: 'Ketepatan Waktu', value: ontimeRate + '%', label: 'Ontime Delivery' },
    { title: 'Total Armada', value: formatNumber(totalData?.Plan || 0), label: 'Direncanakan' }
  ];
  
  const headers = [
    { label: 'No' },
    { label: 'Tipe Kiriman' },
    { label: 'Plan', align: 'text-center' },
    { label: 'Fulfill', align: 'text-center' },
    { label: 'Not Fulfill', align: 'text-center' },
    { label: 'Ontime', align: 'text-center' },
    { label: 'Late', align: 'text-center' }
  ];
  
  if (type === 'monitoringExternal') {
    headers.push({ label: 'Kembali', align: 'text-center' }, { label: 'Belum', align: 'text-center' });
  }
  
  const rows = items.map(i => {
    const cells = [
      { value: i.No },
      { value: `<strong>${i.TipeKiriman}</strong>` },
      { value: formatNumber(i.Plan), align: 'text-center' },
      { value: formatNumber(i.Fulfill), align: 'text-center' },
      { value: formatNumber(i['Not Fullfil']), align: 'text-center' },
      { value: formatNumber(i.Ontime), align: 'text-center' },
      { value: formatNumber(i.Late), align: 'text-center' }
    ];
    if (type === 'monitoringExternal') {
      cells.push(
        { value: formatNumber(i.Kembali), align: 'text-center' },
        { value: formatNumber(i['Belum Kembali']), align: 'text-center' }
      );
    }
    return { isTotal: i.TipeKiriman === 'Total', cells };
  });
  
  let html = createTable(
    type === 'monitoringExternal' ? 'Monitoring Armada External' : 'Monitoring Armada Internal',
    type === 'monitoringExternal' ? 'clipboard-check' : 'clipboard-list',
    headers, rows, true, summary
  );
  
  // Tambahkan grafik untuk Monitoring Armada Internal
  if (type === 'monitoringInternal' && items.length > 0) {
    const labels = items.filter(i => i.TipeKiriman !== 'Total').map(i => i.TipeKiriman);
    const planData = items.filter(i => i.TipeKiriman !== 'Total').map(i => parseInt(i.Plan) || 0);
    const fulfillData = items.filter(i => i.TipeKiriman !== 'Total').map(i => parseInt(i.Fulfill) || 0);
    const ontimeData = items.filter(i => i.TipeKiriman !== 'Total').map(i => parseInt(i.Ontime) || 0);
    
    html += `
      <div class="chart-card">
        <div class="chart-header">
          <i class="fas fa-chart-line"></i>Grafik Fulfillment Monitoring Armada Internal
        </div>
        <div class="p-3">
          <div class="chart-container">
            <canvas id="monitoringInternalChart"></canvas>
          </div>
        </div>
      </div>`;
    
    setTimeout(() => {
      const ctx = document.getElementById('monitoringInternalChart');
      if (!ctx) return;
      if (window.monitoringInternalChartInstance) window.monitoringInternalChartInstance.destroy();
      window.monitoringInternalChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [
            {
              label: 'Plan',
              data: planData,
              backgroundColor: '#82C0CC',
              borderRadius: 6,
              borderWidth: 0
            },
            {
              label: 'Fulfill',
              data: fulfillData,
              backgroundColor: '#489FB5',
              borderRadius: 6,
              borderWidth: 0
            },
            {
              label: 'Ontime',
              data: ontimeData,
              backgroundColor: '#16697A',
              borderRadius: 6,
              borderWidth: 0
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'top',
              labels: {
                color: '#2c3e50',
                font: { size: 12, weight: '600' },
                padding: 15,
                usePointStyle: true,
                pointStyle: 'circle'
              }
            },
            tooltip: {
              backgroundColor: '#fff',
              titleColor: '#333',
              bodyColor: '#333',
              borderColor: '#ddd',
              borderWidth: 1,
              padding: 12,
              displayColors: true,
              callbacks: {
                label: ctx => ctx.dataset.label + ': ' + ctx.parsed.y + ' unit'
              }
            }
          },
          layout: { padding: 10 },
          scales: {
            x: {
              ticks: {
                color: '#2c3e50',
                font: { size: 11, weight: '600' }
              },
              grid: { display: false }
            },
            y: {
              beginAtZero: true,
              ticks: {
                color: '#2c3e50',
                font: { size: 11 }
              },
              grid: { color: '#eee' }
            }
          }
        }
      });
    }, 300);
  }
  
  return html;
}

function buildProdArmadaCust(data) {
  const items = data.prodArmadaCust || [];
  if (!items.length) return '';
  
  const totalTrip = items.reduce((s, i) => s + (parseInt(i.Trip) || 0), 0);
  const totalDP = items.reduce((s, i) => s + (parseFloat(i.DP) || 0), 0);
  
  const summary = [
    { title: 'Total Trip', value: formatNumber(totalTrip), label: 'Semua Jenis Armada' },
    { title: 'Total DP', value: totalDP.toFixed(2), label: 'Total Drop Point' }
  ];
  
  const rows = items.map(i => ({
    cells: [
      { value: `<strong>${i.JENISARMADA}</strong>` },
      { value: formatNumber(i.Trip), align: 'text-center' },
      { value: i.DP, align: 'text-center' },
      { value: i['AVG DP'], align: 'text-center' }
    ]
  }));
  
  return createTable(
    'Produktivitas Armada Customer',
    'truck',
    [
      { label: 'Jenis Armada' },
      { label: 'Trip', align: 'text-center' },
      { label: 'DP', align: 'text-center' },
      { label: 'AVG DP', align: 'text-center' }
    ],
    rows, true, summary
  );
}

function buildProdArmadaStore(data) {
  const items = data.prodArmadaStore || [];
  if (!items.length) return '';
  const totalTrip = items.reduce((s, i) => s + (parseInt(i.Trip) || 0), 0);
  const totalCBM = items.reduce((s, i) => s + (parseFloat(i.CBM) || 0), 0);
  const avgOLF = items.reduce((s, i, _, a) => s + (parseFloat(i.Olf) || 0) / a.length, 0);
  const summary = [
    { title: 'Total Trip', value: formatNumber(totalTrip), label: 'Semua Jenis Armada' },
    { title: 'Total CBM', value: totalCBM.toFixed(2), label: 'Volume Terkirim' },
    { title: 'Rata-rata OLF', value: avgOLF.toFixed(1) + '%', label: 'Optimal Load Factor' }
  ];
  const rows = items.map(i => ({
    cells: [
      { value: `<strong>${i.JENISARMADA}</strong>` },
      { value: formatNumber(i.Trip), align: 'text-center' },
      { value: i.CBM, align: 'text-center' },
      { value: i['AVG CBM'], align: 'text-center' },
      { value: i.Olf, align: 'text-center' }
    ]
  }));
  return createTable('Produktivitas Armada Store', 'truck', [{ label: 'Jenis Armada' }, { label: 'Trip', align: 'text-center' }, { label: 'CBM', align: 'text-center' }, { label: 'AVG CBM', align: 'text-center' }, { label: 'OLF (%)', align: 'text-center' }], rows, true, summary);
}

function buildArmadaUtil(data) {
  const items = data.armadaUtil || [];
  if (!items.length) return '';
  const totalArmada = items.reduce((s, i) => s + (parseInt(i.TotalArmada) || 0), 0);
  const totalUtilize = items.reduce((s, i) => s + (parseInt(i.Utilize) || 0), 0);
  const avgUtil = items.reduce((s, i, _, a) => s + (parseFloat(i.PctUtilization) || 0) / a.length, 0);
  const avgIdle = items.reduce((s, i, _, a) => s + (parseFloat(i.PctIdle) || 0) / a.length, 0);
  const summary = [
    { title: 'Total Armada', value: formatNumber(totalArmada), label: 'Unit Tersedia' },
    { title: 'Armada Digunakan', value: formatNumber(totalUtilize), label: 'Unit Aktif' },
    { title: 'Rata-rata Utilisasi', value: avgUtil.toFixed(1) + '%', label: 'Efisiensi Armada' },
    { title: 'Rata-rata Idle', value: avgIdle.toFixed(1) + '%', label: 'Armada Menganggur' }
  ];
  const rows = items.map(i => ({
    cells: [
      { value: `<strong>${i.BU}</strong>` },
      { value: i.Tanggal, align: 'text-center' },
      { value: formatNumber(i.TotalArmada), align: 'text-center' },
      { value: formatNumber(i.Available), align: 'text-center' },
      { value: formatNumber(i.Utilize), align: 'text-center' },
      { value: formatNumber(i.NotAvailable), align: 'text-center' },
      { value: formatNumber(i.Idle), align: 'text-center' },
      { value: i.PctAvailable + '%', align: 'text-center' },
      { value: `<span class="badge-success-custom">${i.PctUtilization}%</span>`, align: 'text-center' },
      { value: i.PctNotAvailable + '%', align: 'text-center' },
      { value: `<span class="badge-warning-custom">${i.PctIdle}%</span>`, align: 'text-center' }
    ]
  }));
  return createTable('Utilisasi Armada', 'chart-pie', [{ label: 'BU' }, { label: 'Tanggal', align: 'text-center' }, { label: 'Total', align: 'text-center' }, { label: 'Available', align: 'text-center' }, { label: 'Utilize', align: 'text-center' }, { label: 'Not Avail', align: 'text-center' }, { label: 'Idle', align: 'text-center' }, { label: '% Avail', align: 'text-center' }, { label: '% Util', align: 'text-center' }, { label: '% Not Avail', align: 'text-center' }, { label: '% Idle', align: 'text-center' }], rows, true, summary);
}

function buildDriverUtil(data) {
  const items = data.driverUtil || [];
  if (!items.length) return '';

  const rows = [
    { label: 'TOTAL MPP', val1: items.find(r => r.MPP === 'TOTAL MPP')?.DRIVER || 0, val2: items.find(r => r.MPP === 'TOTAL MPP')?.['ASST TO DRIVER'] || 0 },
    { label: 'AVAILABILITY', val1: items.find(r => r.MPP === 'AVAILABILITY')?.DRIVER || 0, val2: items.find(r => r.MPP === 'AVAILABILITY')?.['ASST TO DRIVER'] || 0 },
    { label: 'NOT AVAILABILITY', val1: items.find(r => r.MPP === 'NOT AVAILABILITY')?.DRIVER || 0, val2: items.find(r => r.MPP === 'NOT AVAILABILITY')?.['ASST TO DRIVER'] || 0 },
    { label: 'UTILIZATION', val1: items.find(r => r.MPP === 'UTILIZATION')?.DRIVER || 0, val2: items.find(r => r.MPP === 'UTILIZATION')?.['ASST TO DRIVER'] || 0 },
    { label: 'IDLE', val1: items.find(r => r.MPP === 'IDLE')?.DRIVER || 0, val2: items.find(r => r.MPP === 'IDLE')?.['ASST TO DRIVER'] || 0 },
    { label: '% AVAILABILITY', val1: items.find(r => r.MPP === '% AVAILABILITY')?.DRIVER || 0, val2: items.find(r => r.MPP === '% AVAILABILITY')?.['ASST TO DRIVER'] || 0, isPercent: true },
    { label: '% UTILIZATION', val1: items.find(r => r.MPP === '% UTILIZATION')?.DRIVER || 0, val2: items.find(r => r.MPP === '% UTILIZATION')?.['ASST TO DRIVER'] || 0, isPercent: true }
  ];

  const formatValue = (value, isPercent = false) => {
    if (isPercent) {
      return `${formatNumber(value)}%`;
    }
    return formatNumber(value);
  };

  let html = `
    <div class="section-title"><i class="fas fa-id-card"></i>Utilisasi Driver</div>
    <div class="table-responsive-wrapper">
      <div class="table-modern">
        <table class="table table-sm mb-0">
          <thead>
            <tr class="text-center">
              <th>MPP</th>
              <th>DRIVER</th>
              <th>ASST TO DRIVER</th>
            </tr>
          </thead>
          <tbody>
            ${rows.map(r => `
              <tr class="${r.label.includes('%') ? 'total-row' : ''}">
                <td class="fw-bold">${r.label}</td>
                <td class="text-center ${r.label.includes('%') ? 'fw-semibold' : ''}">${formatValue(r.val1, r.isPercent)}</td>
                <td class="text-center ${r.label.includes('%') ? 'fw-semibold' : ''}">${formatValue(r.val2, r.isPercent)}</td>
              </tr>
            `).join('')}
          </tbody>
        </table>
      </div>
    </div>
  `;

  return html;
}

let siteLoaded = false;
const facilitySelect = document.getElementById('facility');

facilitySelect.addEventListener('focus', async function() {
  if (siteLoaded) return;

  facilitySelect.innerHTML = `<option value="">Memuat daftar facility...</option>`;

  try {
    const res = await fetch("{{ route('transport.dailyreport.sitelist') }}");
    if (!res.ok) throw new Error('Gagal mengambil data site');
    const result = await res.json();

    if (result.status === 'success' && result.data.length > 0) {
      facilitySelect.innerHTML = `<option value="">-- Pilih Facility --</option>`;
      result.data.forEach(s => {
        const name = s.NAME || s.Facility || s.FacilityName || 'Unknown';
        if (name) {
          const opt = document.createElement('option');
          opt.value = name;
          opt.textContent = name;
          facilitySelect.appendChild(opt);
        }
      });
      siteLoaded = true;
    } else {
      facilitySelect.innerHTML = `<option value="">Gagal memuat data</option>`;
    }
  } catch (err) {
    console.error(err);
    facilitySelect.innerHTML = `<option value="">Error: ${err.message}</option>`;
  }
});

form.addEventListener('submit', async function(e) {
  e.preventDefault();

  btn.classList.add('btn-loading');
  spinner.classList.remove('d-none');
  btnText.classList.add('invisible');
  btn.disabled = true;
  container.innerHTML = '';
  
  const payload = {
    facility: document.getElementById('facility').value,
    start_date: document.getElementById('start_date').value,
    end_date: document.getElementById('end_date').value,
    key1: 'WMWHSE4RTL'
  };
  
  const startTime = performance.now();
  
  try {
    const res = await fetch("{{route('transport.dailyreport.data')}}", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "{{csrf_token()}}"
      },
      body: JSON.stringify(payload)
    });
    
    if (!res.ok) throw new Error(`HTTP ${res.status} ${res.statusText}`);
    const result = await res.json();
    const endTime = performance.now();
    const execTime = ((endTime - startTime) / 1000).toFixed(2);
    
    if (result && result.data) {
      const data = result.data;
      showToast(`Data berhasil dimuat untuk DC ${payload.facility} (${payload.start_date} s/d ${payload.end_date})`);
      showToast(`Waktu eksekusi: ${execTime} detik`, 7000);
      
      container.innerHTML = 
        buildSLATable(data) + 
        buildProdTable(data, 'prodCustomer') + 
        buildProdTable(data, 'prodStore') + 
        buildDeliveryCustomer(data) + 
        buildDeliveryStore(data) + 
        buildMonitoring(data, 'monitoringExternal') + 
        buildMonitoring(data, 'monitoringInternal') + 
        buildProdArmadaCust(data) + 
        buildProdArmadaStore(data) + 
        buildArmadaUtil(data) + 
        buildDriverUtil(data);
    }
  } catch (err) {
    console.error(err);
    container.innerHTML = `<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Terjadi kesalahan: ${err.message || String(err)}</div>`;
  } finally {
    btn.classList.remove('btn-loading');
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
  toast.innerHTML = `<span>${message}</span><span class="close-btn">&times;</span>`;
  container.appendChild(toast);
  toast.querySelector('.close-btn').addEventListener('click', () => {
    toast.classList.remove('show');
    setTimeout(() => toast.remove(), 500);
  });
  setTimeout(() => toast.classList.add('show'), 50);
  setTimeout(() => {
    toast.classList.remove('show');
    setTimeout(() => toast.remove(), 500);
  }, duration);
}
</script>
<div class="toast-container" id="toastContainer"></div>
@endsection