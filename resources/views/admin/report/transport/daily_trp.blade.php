@extends('admin.dashboard')
@section('title', 'Daily Report Transport')
@section('admin')

<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
/* 🎨 ===== Root Color Palettes ===== */
:root {
  --blue: #69bef0ff;
  --teal: #14b8a6;
  --green: #22c55e;
  --orange: #f59e0b;
  --gray-bg: #f8fafc;
  --gray-card: #f1f5f9;
  --text-dark: #1e293b;
  --text-light: #fff;
  --cust-primary: #29cef7ff;
  --cust-secondary: #44c9ebff;
  --store-primary: #FF6663;
  --store-secondary: #F8858B;
}

/* ===== Buttons ===== */
.btn-primary {
  background: linear-gradient(135deg, var(--blue), var(--teal)) !important;
  border: none;
  color: var(--text-light);
  font-weight: 600;
  box-shadow: 0 2px 8px rgba(20, 201, 214, 0.4);
}
.btn-primary:hover { opacity: 0.9; }

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
  gap: 0.5rem;
  min-width: 250px;
  max-width: 350px;
  background: linear-gradient(135deg, var(--teal), var(--blue));
  color: #fff;
  padding: 12px 16px;
  border-radius: 8px;
  box-shadow: 0 3px 12px rgba(0, 0, 0, 0.25);
  opacity: 0;
  transform: translateX(100%);
  transition: all 0.5s ease;
}
.toast.show { opacity: 1; transform: translateX(0); }
.toast .close-btn {
  margin-left: auto; cursor: pointer; font-weight: 700; color: #fff;
}

/* ===== Table Modern ===== */
.table-modern {
  background: #fff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
  border: 1px solid #e5e7eb;
}
.table-modern thead th {
  color: #fff;
  font-weight: 600;
  padding: 12px 10px;
  font-size: 13px;
  white-space: nowrap;
}
.table-modern tbody td {
  padding: 10px;
  font-size: 13px;
  border: 1px solid #e2e8f0;
}
.table-modern tbody tr:hover {
  background: #f1f5f9;
  transition: background-color 0.3s ease;
}

/* ===== Section Titles ===== */
.section-title {
  font-size: 16px;
  font-weight: 600;
  margin: 25px 0 15px;
  padding: 12px 15px;
  color: #fff;
  border-radius: 8px;
  display: flex;
  align-items: center;
}
.section-title i { margin-right: 10px; }
.section-title.customer { background: linear-gradient(135deg, var(--cust-primary), var(--cust-secondary)); }
.section-title.store { background: linear-gradient(135deg, var(--store-primary), var(--store-secondary)); }
.section-title.monitor { background: linear-gradient(135deg, var(--teal), #5eead4); }

/* ===== Spinner overlay ===== */
#btnTampil { position: relative; display: inline-flex; align-items: center; justify-content: center; overflow: hidden; }
.spinner-overlay {
  position: absolute;
  inset: 0;
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 3;
}
#btnTampil.btn-loading .spinner-overlay { display: flex; }
.spinner-overlay .spinner-border {
  width: 1.3rem; height: 1.3rem; color: #fff;
}
#btnTampil .btn-text-hidden { visibility: hidden; }

/* ===== Badge ===== */
.badge-success-custom { background: var(--green); color: #fff; }
.badge-danger-custom { background: #dc2626; color: #fff; }
.badge-warning-custom { background: var(--orange); color: #fff; }

/* ===== Summary Cards ===== */
.summary-card {
  color: var(--text-light);
  padding: 15px;
  border-radius: 10px;
  margin-bottom: 15px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  background: linear-gradient(135deg, var(--teal), var(--blue));
}
.summary-card h6 { font-size: 13px; font-weight: 600; margin-bottom: 8px; }
.summary-card .summary-value { font-size: 24px; font-weight: 700; margin-bottom: 5px; }
.summary-card .summary-label { font-size: 12px; opacity: 0.85; }
.summary-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 15px;
}

/* ===== Responsive ===== */
@media (max-width: 768px) {
  .summary-card .summary-value { font-size: 20px; }
}
</style>

@php
  $StartDate = $StartDate ?? now()->format('Y-m-d');
  $EndDate   = $EndDate ?? now()->format('Y-m-d');
@endphp

<div class="page-content">
  <div class="card shadow-sm border-0 rounded-3">
    <div class="card-header text-white fw-bold text-center" style="background:linear-gradient(135deg,var(--teal),var(--blue))">
      DAILY REPORT TRANSPORT
    </div>
    <div class="card-body" style="background-color:#f8f9fa justify-content-center" >
      <form id="filterForm" class="row g-3 mb-4 justify-content-center">

  <div class="col-lg-4 col-md-6 col-12">
    <label class="form-label fw-semibold">Facility</label>
    <select class="form-select" id="facility" required>
      <option value="">-- Klik untuk memuat daftar facility --</option>
    </select>
  </div>

  <div class="col-lg-3 col-md-6 col-12">
    <label class="form-label fw-semibold">Tanggal</label>
    <input type="date" class="form-control" id="date" value="{{ $Tanggal ?? now()->format('Y-m-d') }}">
  </div>

  <div class="col-lg-2 col-md-6 col-12 d-flex align-items-end">
    <button type="submit" id="btnTampil" class="btn btn-primary w-100" style="height:42px;">
      <div id="spinnerOverlay" class="spinner-overlay d-none">
        <div class="spinner-border spinner-border-sm" role="status"></div>
      </div>
      <span id="btnText"><i class="fas fa-search me-1"></i> Tampilkan</span>
    </button>
  </div>

</form>

      <div id="summaryPieContainer" class="mb-4"></div>
      <div id="dataContainer" class="mt-3"></div>
    </div>
  </div>
</div>

<!-- ===== BAGIAN 2: Fungsi builder (taruh di bawah Bagian 1) ===== -->
<script>
/* =========================
   Helper formatting
   ========================= */
function formatNumberLocal(n){
  if (n === null || n === undefined || n === '') return '0';
  const num = Number(n);
  if (isNaN(num)) return n;
  return new Intl.NumberFormat('id-ID').format(num);
}

function formatCurrencyLocal(n){
  if (n === null || n === undefined || n === '') return 'Rp 0';
  const num = Number(n);
  if (isNaN(num)) return n;
  return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
}

/* expose helpers */
window.formatNumberLocal = formatNumberLocal;
window.formatCurrencyLocal = formatCurrencyLocal;

/* =========================
   createSummary - generate summary cards HTML
   expects array of {title, value, label}
   ========================= */
function createSummaryHtml(data) {
  if (!Array.isArray(data) || data.length === 0) return '';
  let cards = '';
  data.forEach(d => {
    cards += `<div class="summary-card">
      <h6>${d.title}</h6>
      <div class="summary-value">${d.value}</div>
      <div class="summary-label">${d.label}</div>
    </div>`;
  });
  return `<div class="summary-grid">${cards}</div>`;
}
window.createSummaryHtml = createSummaryHtml;

/* =========================
   createTable - generic table renderer
   headers: [{label, align?}]
   rows: [{isTotal?, cells:[{value, align?}]}]
   theme: default/customer/store/monitor/util
   ========================= */
function createTable(title, icon, headers, rows, hasSummary = false, summaryData = null, theme = 'default') {
  const themes = {
    default: { headBg: '#0ea5e9', hover: '#bae6fd55', card: 'default' },
    customer: { headBg: 'var(--cust-primary)', hover: '#dcfce755', card: 'customer' },
    store: { headBg: 'var(--store-primary)', hover: '#fed7aa55', card: 'store' },
    delivery: { headBg: '#5cb8f6ff', hover: '#ddd6fe55', card: 'default' },
    monitor: { headBg: 'var(--teal)', hover: '#99f6e455', card: 'default' },
    util: { headBg: 'var(--blue)', hover: '#bfdbfe55', card: 'default' },
  };
  const t = themes[theme] || themes.default;

  let html = '';
  if (title && title.trim() !== '') {
    html += `<div class="section-title ${theme}"><i class="fas fa-${icon}"></i>${title}</div>`;
  }

  if (hasSummary && Array.isArray(summaryData) && summaryData.length) {
    html += `<div class="summary-grid">` + summaryData.map(d => `
      <div class="summary-card ${t.card}">
        <h6>${d.title}</h6>
        <div class="summary-value">${d.value}</div>
        <div class="summary-label">${d.label}</div>
      </div>`).join('') + `</div>`;
  }

  html += `<div class="table-responsive-wrapper"><div class="table-modern"><table class="table table-sm mb-0">`;
  // header
  html += '<thead><tr>';
  headers.forEach(h => {
    const align = h.align ? `class="${h.align}"` : '';
    html += `<th ${align} style="background:${t.headBg};color:#fff">${h.label}</th>`;
  });
  html += '</tr></thead><tbody>';
  // rows
  rows.forEach(r => {
    const rowClass = r.isTotal ? 'total-row' : '';
    const rowStyle = r.isTotal ? `style="background:${t.hover}"` : '';
    html += `<tr class="${rowClass}" ${rowStyle}>`;
    r.cells.forEach(cel => {
      const a = cel.align ? `class="${cel.align}"` : '';
      html += `<td ${a}>${cel.value}</td>`;
    });
    html += '</tr>';
  });
  html += '</tbody></table></div></div>';
  return html;
}
window.createTable = createTable;

/* =========================
   buildSLATable - render SLA section + charts placeholder
   expects data.slaCustomer = [{SLA, STD, 'Jumlah DO', Persentase}, ...]
   Will render canvas #slaChart and #monitoringInternalChart (chart code executed separately)
   ========================= */
function buildSLATable(data) {
  const sla = (data && data.slaCustomer) || [];
  if (!sla.length) return '';

  // rows
  const rows = sla.map(i => {
    const ach = parseFloat(i.Persentase || 0);
    const std = parseFloat(i.STD || 0);
    return {
      isTotal: (i.SLA || '').toString().toUpperCase() === 'TOTAL',
      cells: [
        { value: `<strong>${i.SLA || ''}</strong>` },
        { value: i.STD ? (i.STD + '%') : '-', align: 'text-center' },
        { value: formatNumberLocal(i['Jumlah DO']), align: 'text-center' },
        { value: `<span class="${ach >= std ? 'badge-success-custom' : 'badge-danger-custom'}">${i.Persentase || 0}%</span>`, align: 'text-center' },
      ],
    };
  });

  // create table HTML
  let html = createTable(
    'SLA Customer',
    'award',
    [
      { label: 'SLA Metric' },
      { label: 'Standard', align: 'text-center' },
      { label: 'Jumlah DO', align: 'text-center' },
      { label: 'Persentase', align: 'text-center' },
    ],
    rows,
    false,
    null,
    'customer'
  );

  // add chart card placeholders
  html += `
    <div class="card shadow-sm border-0 rounded-3 mb-4" style="background:#fff">
      <div class="p-3 pb-2 fw-semibold" style="color:#2c3e50"><i class="fas fa-chart-bar me-2"></i>Grafik SLA Customer</div>
      <div class="p-3"><div class="chart-container fade-in"><canvas id="slaChart" style="height:220px"></canvas></div></div>
    </div>
  `;

  html += `
    <div class="card shadow-sm border-0 rounded-3 mb-4" style="background:#fff">
      <div class="p-3 pb-2 fw-semibold" style="color:#2c3e50"><i class="fas fa-truck me-2"></i>Grafik Monitoring Armada Internal</div>
      <div class="p-3"><div class="chart-container fade-in"><canvas id="monitoringInternalChart" style="height:220px"></canvas></div></div>
    </div>
  `;

  // make chart render function available (will be called from main event handler)
  window.__sla_chart_data = {
    labels: sla.filter(i => (i.SLA || '').toString().toUpperCase() !== 'TOTAL').map(i => i.SLA || ''),
    actualData: sla.filter(i => (i.SLA || '').toString().toUpperCase() !== 'TOTAL').map(i => parseFloat(i.Persentase || 0)),
    standardData: sla.filter(i => (i.SLA || '').toString().toUpperCase() !== 'TOTAL').map(i => parseFloat(i.STD || 0))
  };

  return html;
}
window.buildSLATable = buildSLATable;

/* =========================
   renderSLAChart - Chart.js rendering for SLA & internal monitoring
   call this after DOM inserted (e.g. after container.innerHTML = buildSLATable(data))
   expects window.__sla_chart_data to be set
   ========================= */
function renderSLAChartAndMonitoring(data) {
  try {
    // SLA chart
    const slaData = window.__sla_chart_data || { labels: [], actualData: [], standardData: [] };
    const ctx = document.getElementById('slaChart');
    if (ctx) {
      if (window.slaChartInstance) window.slaChartInstance.destroy();
      window.slaChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: slaData.labels,
          datasets: [
            {
              label: 'Pencapaian (%)',
              data: slaData.actualData,
              borderRadius: 6,
              backgroundColor: slaData.actualData.map(v => v >= 90 ? '#28a745' : v >= 70 ? '#ffc107' : '#dc3545'),
            },
            {
              label: 'Standar (%)',
              data: slaData.standardData,
              borderRadius: 6,
              backgroundColor: 'rgba(102,126,234,0.2)',
              borderColor: '#667eea',
              borderWidth: 1.5,
            },
          ],
        },
        options: {
          indexAxis: 'y',
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { position: 'top', labels: { color: '#2c3e50', font: { size: 12, weight: '600' }, padding: 15 } }
          },
          scales: {
            x: { beginAtZero: true, max: 100, ticks: { color: '#2c3e50', callback: v => v + '%', font: { size: 11 } }, grid: { color: '#eee' } },
            y: { ticks: { color: '#2c3e50', font: { weight: '600', size: 11 } }, grid: { display: false } }
          }
        }
      });
    }

    // Monitoring Internal chart: data should come from 'data.monitoringInternal' passed into this function
    const ctx2 = document.getElementById('monitoringInternalChart');
    if (ctx2 && data && data.monitoringInternal) {
      if (window.monitoringInternalChartInstance) window.monitoringInternalChartInstance.destroy();
      const internal = (data.monitoringInternal || []).filter(i => (i.TipeKiriman || '').toString().toLowerCase() !== 'total');
      const labels2 = internal.map(i => i.TipeKiriman || '');
      const planData = internal.map(i => parseInt(i.Plan || 0));
      const fulfillData = internal.map(i => parseInt(i.Fulfill || 0));
      const lateData = internal.map(i => parseInt(i.Late || 0));

      window.monitoringInternalChartInstance = new Chart(ctx2, {
        type: 'bar',
        data: {
          labels: labels2,
          datasets: [
            { label: 'Plan', data: planData, backgroundColor: '#82C0CC' },
            { label: 'Fulfill', data: fulfillData, backgroundColor: '#489FB5' },
            { label: 'Late', data: lateData, backgroundColor: '#FFA62B' },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { position: 'top', labels: { color: '#333', font: { size: 12 } } } },
          scales: { x: { ticks: { color: '#333', font: { weight: '600' } } }, y: { beginAtZero: true, ticks: { color: '#333' } } },
        },
      });
    }
  } catch (e) {
    console.error('renderSLAChartAndMonitoring error', e);
  }
}
window.renderSLAChartAndMonitoring = renderSLAChartAndMonitoring;

/* =========================
   buildMonitoring - internal/external monitoring table
   expects data.monitoringInternal or monitoringExternal = [{No, TipeKiriman, Plan, Fulfill, Not Fullfil, Ontime, Late, Kembali, 'Belum Kembali'}, ...]
   ========================= */
  function buildMonitoringHtml(data, type) {
  // 🧭 logika tetap, tapi labelnya dibalik
  const isInternal = type === 'monitoringInternal';
  const items = Array.isArray(data?.[type]) ? data[type] : [];

  if (!items.length) {
    return `<div class="alert alert-warning p-2 mt-2">
      <i class="fas fa-info-circle me-1"></i> Tidak ada data ${isInternal ? 'armada internal' : 'armada external'}.
    </div>`;
  }

  const totalData = items.find(i => (i.TipeKiriman || '').toLowerCase() === 'total') || {};
  const fulfillRate = ((+totalData.Fulfill || 0) / ((+totalData.Plan || 1))) * 100;
  const ontimeRate = ((+totalData.Ontime || 0) / ((+totalData.Fulfill || 1))) * 100;

  const summary = [
    { title: 'Tingkat Pemenuhan', value: fulfillRate.toFixed(1) + '%', label: 'Fulfill Rate' },
    { title: 'Ketepatan Waktu', value: ontimeRate.toFixed(1) + '%', label: 'Ontime Delivery' },
    { title: 'Total Armada', value: formatNumberLocal(totalData.Plan || 0), label: 'Direncanakan' }
  ];

  const headers = [
    { label: 'No' },
    { label: 'Tipe Kiriman' },
    { label: 'Plan', align: 'text-center' },
    { label: 'Fulfill', align: 'text-center' },
    { label: 'Not Fulfill', align: 'text-center' },
    { label: 'Ontime', align: 'text-center' },
    { label: 'Late', align: 'text-center' },
  ];

  if (!isInternal) {
    headers.push({ label: 'Kembali', align: 'text-center' });
    headers.push({ label: 'Belum Kembali', align: 'text-center' });
  }

  const rows = items.map(i => {
    const cells = [
      { value: i.No },
      { value: `<strong>${i.TipeKiriman}</strong>` },
      { value: formatNumberLocal(i.Plan), align: 'text-center' },
      { value: formatNumberLocal(i.Fulfill), align: 'text-center' },
      { value: formatNumberLocal(i['Not Fullfil'] || i['Not Fulfil'] || 0), align: 'text-center' },
      { value: formatNumberLocal(i.Ontime), align: 'text-center' },
      { value: formatNumberLocal(i.Late), align: 'text-center' },
    ];
    if (!isInternal) {
      cells.push(
        { value: formatNumberLocal(i.Kembali), align: 'text-center' },
        { value: formatNumberLocal(i['Belum Kembali']), align: 'text-center' }
      );
    }
    return { isTotal: (i.TipeKiriman || '').toLowerCase() === 'total', cells };
  });

  // 🏷️ Label & ikon ditukar:
  const title = isInternal
    ? 'Monitoring Armada External'  // 🔄 dibalik
    : 'Monitoring Armada Internal'; // 🔄 dibalik
  const icon = isInternal ? 'truck-moving' : 'truck-fast';

  return createTable(title, icon, headers, rows, true, summary, 'monitor');
}



   window.buildMonitoringHtml = buildMonitoringHtml;

/* =========================
   buildDeliveryCustomer
   data.deliveryCustomer = [{Order, Total, Persentase}, ...]
   ========================= */
function buildDeliveryCustomerHtml(data) {
  const items = (data && data.deliveryCustomer) || [];
  if (!items.length) return '';

  const totalRow = items.find(i => (i.Order || '').toString().toUpperCase() === 'TOTAL') || {};
  const totalDO = parseInt(totalRow.Total || 0);
  const validOrders = items.filter(i => (i.Order || '').toString().toUpperCase() !== 'TOTAL');
  const orderCount = validOrders.length;
  const avgOrder = orderCount > 0 ? Math.round(totalDO / orderCount) : 0;
  const topOrders = validOrders.slice().sort((a, b) => (parseInt(b.Total || 0) - parseInt(a.Total || 0))).slice(0, 4);

  const summary = [
    { title: 'Total DO', value: formatNumberLocal(totalDO), label: 'Semua Order Type' },
    { title: 'Avg Order/Type', value: formatNumberLocal(avgOrder), label: 'Rata-rata per jenis' },
    ...topOrders.map(o => ({ title: o.Order, value: formatNumberLocal(o.Total || 0), label: `${o.Persentase || 0}%` }))
  ];

  const rows = items.map(i => ({
    isTotal: (i.Order || '').toString().toUpperCase() === 'TOTAL',
    cells: [
      { value: `<strong>${i.Order || ''}</strong>` },
      { value: i.Total ? formatNumberLocal(i.Total) : '-', align: 'text-center' },
      { value: i.Persentase ? `<span class="${parseFloat(i.Persentase) >= 50 ? 'badge-success-custom' : 'badge-danger-custom'}">${i.Persentase}%</span>` : '-', align: 'text-center' }
    ]
  }));

  let html = `<div class="section-title customer"><i class="fas fa-shipping-fast"></i> Summary DO Customer</div>`;
  html += `<div class="summary-grid">${summary.map(s => `<div class="summary-card customer"><h6>${s.title}</h6><div class="summary-value">${s.value}</div><div class="summary-label">${s.label}</div></div>`).join('')}</div>`;
  html += createTable('', 'shipping-fast', [{ label: 'Order Type' }, { label: 'Total', align: 'text-center' }, { label: 'Persentase', align: 'text-center' }], rows, false, null, 'customer');
  return html;
}
window.buildDeliveryCustomerHtml = buildDeliveryCustomerHtml;

/* =========================
   buildDeliveryStore
   data.deliveryStore = [{Area, 'CBM Armada', 'CBM Actual', 'Standar OLF (%)', 'Persentase (%)'}, ...]
   ========================= */
function buildDeliveryStoreHtml(data) {
  const items = (data && data.deliveryStore) || [];
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
    return { cells: [
      { value: `<strong>${i.Area || ''}</strong>` },
      { value: i['CBM Armada'] ?? '-', align: 'text-center' },
      { value: i['CBM Actual'] ?? '-', align: 'text-center' },
      { value: (i['Standar OLF (%)'] !== undefined ? i['Standar OLF (%)'] + '%' : '-'), align: 'text-center' },
      { value: `<span class="${act >= std ? 'badge-success-custom' : 'badge-danger-custom'}">${i['Persentase (%)'] ?? 0}%</span>`, align: 'text-center' }
    ]};
  });

  return createTable('Delivery ke Store', 'warehouse',
    [{ label: 'Area' }, { label: 'CBM Armada', align: 'text-center' }, { label: 'CBM Actual', align: 'text-center' }, { label: 'Standar OLF', align: 'text-center' }, { label: 'Persentase', align: 'text-center' }],
    rows, true, summary, 'store');
}
window.buildDeliveryStoreHtml = buildDeliveryStoreHtml;

/* =========================
   buildProdTable (prodCustomer / prodStore)
   data.prodCustomer or data.prodStore
   ========================= */
function buildProdTableHtml(data, type) {
  const items = (data && data[type]) || [];
  if (!items.length) return '';

  const totalTrip = items.reduce((s, i) => s + (parseInt(i.Trip) || 0), 0);
  const avgTAT = items.filter(i => (i.Jalur || '').toString().toUpperCase() !== 'TOTAL').reduce((s, i, _, a) => s + (parseFloat(i['Actual TAT']) || 0) / a.length, 0);
  const avgUJP = items.filter(i => (i.Jalur || '').toString().toUpperCase() !== 'TOTAL').reduce((s, i, _, a) => s + (parseFloat(i['UJP/Trip']) || 0) / a.length, 0);

  const isCustomer = type === 'prodCustomer';
  const theme = isCustomer ? 'customer' : 'store';

  const summary = [
    { title: 'Total Trip', value: formatNumberLocal(totalTrip), label: isCustomer ? 'Seluruh Jalur Customer' : 'Pengiriman Store' },
    { title: 'Rata-rata TAT', value: Math.round(avgTAT) + ' min', label: 'Waktu Aktual' },
    { title: 'Rata-rata UJP/Trip', value: formatCurrencyLocal(Math.round(avgUJP)), label: 'Rata-rata UJP' }
  ];

  const rows = items.map(i => ({
    isTotal: (i.Jalur || '').toString().toUpperCase() === 'TOTAL',
    cells: [
      { value: i.No ?? '' },
      { value: `<strong>${i.Jalur ?? ''}</strong>` },
      { value: formatNumberLocal(i.Trip), align: 'text-center' },
      { value: (isCustomer ? i['DP/Trip'] : i['CBM/Trip']) ?? '-', align: 'text-center' },
      { value: formatCurrencyLocal(i['Total UJP']), align: 'text-right' },
      { value: formatCurrencyLocal(i['UJP/Trip']), align: 'text-right' },
      { value: i['Actual TAT'] ?? '-', align: 'text-center' }
    ]
  }));

  return createTable(
    isCustomer ? 'Produktivitas Customer berdasarkan Jalur' : 'Produktivitas Store berdasarkan Jalur',
    isCustomer ? 'users' : 'store',
    [
      { label: 'No' }, { label: 'Jalur' }, { label: 'Trip', align: 'text-center' },
      { label: isCustomer ? 'DP/Trip' : 'CBM/Trip', align: 'text-center' },
      { label: 'Total UJP', align: 'text-right' }, { label: 'UJP/Trip', align: 'text-right' }, { label: 'Actual TAT', align: 'text-center' }
    ],
    rows,
    true,
    summary,
    theme
  );
}
window.buildProdTableHtml = buildProdTableHtml;

/* =========================
   buildProdArmadaCust / buildProdArmadaStore
   ========================= */
function buildProdArmadaCustHtml(data) {
  const items = (data && data.prodArmadaCust) || [];
  if (!items.length) return '';
  const totalTrip = items.reduce((s, i) => s + (parseInt(i.Trip) || 0), 0);
  const totalDP = items.reduce((s, i) => s + (parseFloat(i.DP) || 0), 0);

  const summary = [{ title: 'Total Trip', value: formatNumberLocal(totalTrip), label: 'Semua Jenis Armada' }, { title: 'Total DP', value: (totalDP).toFixed(2), label: 'Total Drop Point' }];
  const rows = items.map(i => ({ cells: [{ value: `<strong>${i.JENISARMADA || ''}</strong>` }, { value: formatNumberLocal(i.Trip), align: 'text-center' }, { value: i.DP ?? '-', align: 'text-center' }, { value: i['AVG DP'] ?? '-', align: 'text-center' }] }));

  return createTable('Produktivitas Armada Customer', 'truck', [{ label: 'Jenis Armada' }, { label: 'Trip', align: 'text-center' }, { label: 'DP', align: 'text-center' }, { label: 'AVG DP', align: 'text-center' }], rows, true, summary,'customer');
}
window.buildProdArmadaCustHtml = buildProdArmadaCustHtml;

function buildProdArmadaStoreHtml(data) {
  const items = (data && data.prodArmadaStore) || [];
  if (!items.length) return '';
  const totalTrip = items.reduce((s, i) => s + (parseInt(i.Trip) || 0), 0);
  const totalCBM = items.reduce((s, i) => s + (parseFloat(i.CBM) || 0), 0);
  const avgOLF = items.reduce((s, i, _, a) => s + (parseFloat(i.Olf) || 0) / a.length, 0);

  const summary = [{ title: 'Total Trip', value: formatNumberLocal(totalTrip), label: 'Semua Jenis Armada' }, { title: 'Total CBM', value: totalCBM.toFixed(2), label: 'Volume Terkirim' }, { title: 'Rata-rata OLF', value: avgOLF.toFixed(1) + '%', label: 'Optimal Load Factor' }];

  const rows = items.map(i => ({ cells: [{ value: `<strong>${i.JENISARMADA || ''}</strong>` }, { value: formatNumberLocal(i.Trip), align: 'text-center' }, { value: i.CBM ?? '-', align: 'text-center' }, { value: i['AVG CBM'] ?? '-', align: 'text-center' }, { value: i.Olf ?? '-', align: 'text-center' }] }));

  return createTable('Produktivitas Armada Store', 'truck', [{ label: 'Jenis Armada' }, { label: 'Trip', align: 'text-center' }, { label: 'CBM', align: 'text-center' }, { label: 'AVG CBM', align: 'text-center' }, { label: 'OLF (%)', align: 'text-center' }], rows, true, summary, 'store');
}
window.buildProdArmadaStoreHtml = buildProdArmadaStoreHtml;

/* =========================
   buildArmadaUtil
   data.armadaUtil = [{BU, Tanggal, TotalArmada, Available, Utilize, NotAvailable, Idle, PctAvailable, PctUtilization, PctNotAvailable, PctIdle}, ...]
   ========================= */
function buildArmadaUtilHtml(data) {
  const items = (data && data.armadaUtil) || [];
  if (!items.length) return '';
  const totalArmada = items.reduce((s, i) => s + (parseInt(i.TotalArmada) || 0), 0);
  const totalUtilize = items.reduce((s, i) => s + (parseInt(i.Utilize) || 0), 0);
  const avgUtil = items.reduce((s, i, _, a) => s + (parseFloat(i.PctUtilization) || 0) / a.length, 0);
  const avgIdle = items.reduce((s, i, _, a) => s + (parseFloat(i.PctIdle) || 0) / a.length, 0);

  const summary = [
    { title: 'Total Armada', value: formatNumberLocal(totalArmada), label: 'Unit Tersedia' },
    { title: 'Armada Digunakan', value: formatNumberLocal(totalUtilize), label: 'Unit Aktif' },
    { title: 'Rata-rata Utilisasi', value: avgUtil.toFixed(1) + '%', label: 'Efisiensi Armada' },
    { title: 'Rata-rata Idle', value: avgIdle.toFixed(1) + '%', label: 'Armada Menganggur' }
  ];

  const rows = items.map(i => ({
    cells: [
      { value: `<strong>${i.BU || ''}</strong>` },
      { value: i.Tanggal || '-', align: 'text-center' },
      { value: formatNumberLocal(i.TotalArmada), align: 'text-center' },
      { value: formatNumberLocal(i.Available), align: 'text-center' },
      { value: formatNumberLocal(i.Utilize), align: 'text-center' },
      { value: formatNumberLocal(i.NotAvailable), align: 'text-center' },
      { value: formatNumberLocal(i.Idle), align: 'text-center' },
      { value: (i.PctAvailable ?? '-') + '%', align: 'text-center' },
      { value: `<span class="badge-success-custom">${i.PctUtilization ?? 0}%</span>`, align: 'text-center' },
      { value: (i.PctNotAvailable ?? '-') + '%', align: 'text-center' },
      { value: `<span class="badge-warning-custom">${i.PctIdle ?? 0}%</span>`, align: 'text-center' },
    ]
  }));

  return createTable('Utilisasi Armada', 'chart-pie',
    [{ label: 'BU' }, { label: 'Tanggal', align: 'text-center' }, { label: 'Total', align: 'text-center' }, { label: 'Available', align: 'text-center' }, { label: 'Utilize', align: 'text-center' }, { label: 'Not Avail', align: 'text-center' }, { label: 'Idle', align: 'text-center' }, { label: '% Avail', align: 'text-center' }, { label: '% Util', align: 'text-center' }, { label: '% Not Avail', align: 'text-center' }, { label: '% Idle', align: 'text-center' }],
    rows, true, summary, 'customer');
}
window.buildArmadaUtilHtml = buildArmadaUtilHtml;

/* =========================
   buildDriverUtil
   data.driverUtil = [{MPP, DRIVER, 'ASST TO DRIVER'}, ...]
   ========================= */
function buildDriverUtilHtml(data) {
  const items = (data && data.driverUtil) || [];
  if (!items.length) return '';

  const findVal = (key, col) => (items.find(r => (r.MPP || '').toString() === key) || {})[col] ?? 0;

  const rows = [
    { label: 'TOTAL MPP', val1: findVal('TOTAL MPP', 'DRIVER'), val2: findVal('TOTAL MPP', 'ASST TO DRIVER') },
    { label: 'AVAILABILITY', val1: findVal('AVAILABILITY', 'DRIVER'), val2: findVal('AVAILABILITY', 'ASST TO DRIVER') },
    { label: 'NOT AVAILABILITY', val1: findVal('NOT AVAILABILITY', 'DRIVER'), val2: findVal('NOT AVAILABILITY', 'ASST TO DRIVER') },
    { label: 'UTILIZATION', val1: findVal('UTILIZATION', 'DRIVER'), val2: findVal('UTILIZATION', 'ASST TO DRIVER') },
    { label: 'IDLE', val1: findVal('IDLE', 'DRIVER'), val2: findVal('IDLE', 'ASST TO DRIVER') },
    { label: '% AVAILABILITY', val1: findVal('% AVAILABILITY', 'DRIVER'), val2: findVal('% AVAILABILITY', 'ASST TO DRIVER'), isPercent: true },
    { label: '% UTILIZATION', val1: findVal('% UTILIZATION', 'DRIVER'), val2: findVal('% UTILIZATION', 'ASST TO DRIVER'), isPercent: true }
  ];

  const formatValue = (value, isPercent) => {
    if (value === null || value === undefined || value === '') return isPercent ? '0%' : '0';
    if (isPercent) return formatNumberLocal(value) + '%';
    return formatNumberLocal(value);
  };

  let html = `
    <div class="card mt-3 shadow-sm border-0">
      <div class="card-header text-white fw-bold" style="background:linear-gradient(135deg, var(--teal), var(--blue));">
        <i class="fa fa-id-card me-2"></i>Utilisasi Driver
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-bordered table-sm mb-0">
            <thead style="background:linear-gradient(135deg, var(--blue), var(--teal));color:#fff;">
              <tr class="text-center"><th>MPP</th><th>DRIVER</th><th>ASST TO DRIVER</th></tr>
            </thead>
            <tbody>
  `;
  html += rows.map(r => `
    <tr class="${r.label.includes('%') ? 'driver-highlight-row' : ''}">
      <td class="fw-bold ${r.label.includes('%') ? 'text-driver-highlight' : ''}">${r.label}</td>
      <td class="text-center ${r.label.includes('%') ? 'fw-semibold text-driver-highlight' : ''}">${formatValue(r.val1, r.isPercent)}</td>
      <td class="text-center ${r.label.includes('%') ? 'fw-semibold text-driver-highlight' : ''}">${formatValue(r.val2, r.isPercent)}</td>
    </tr>
  `).join('');
  html += `</tbody></table></div></div></div>`;
  return html;
}
window.buildDriverUtilHtml = buildDriverUtilHtml;

/* =========================
   small utility: safeJoinSections
   join array of HTML parts (ignore empty) with newline
   ========================= */
function safeJoinSections(parts) {
  if (!Array.isArray(parts)) return '';
  return parts.filter(Boolean).join('\n');
}
window.safeJoinSections = safeJoinSections;

/* =========================
   End of BAGIAN 2
   Semua fungsi di atas sudah diekspos ke window agar Bagian 3 (event handler / fetch) bisa memakainya.
   PENTING: Bagian 3 akan memanggil fungsi-fungsi ini dan melakukan render Chart.js (pie + bar).
   ========================= */
</script>

<!-- ===== BAGIAN 3: Event handler & rendering utama ===== -->
<script>
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("filterForm");
  const btn = document.getElementById("btnTampil");
  const btnText = document.getElementById("btnText");
  const spinnerOverlay = document.getElementById("spinnerOverlay");
  const container = document.getElementById("dataContainer");
  const facilitySelect = document.getElementById("facility");

  /* =========================
     LOAD DAFTAR FACILITY
     ========================= */
  let siteLoaded = false;
  facilitySelect.addEventListener("focus", async () => {
    if (siteLoaded) return;
    facilitySelect.innerHTML = `<option value="">Memuat daftar facility...</option>`;
    try {
      const res = await fetch("{{ route('transport.dailyreport.sitelist') }}");
      if (!res.ok) throw new Error("Gagal mengambil data site");
      const result = await res.json();
      if (result.status === "success" && result.data.length > 0) {
        facilitySelect.innerHTML = `<option value="">-- Pilih Facility --</option>`;
        result.data.forEach((s) => {
          const name = s.NAME || s.Facility || s.FacilityName || "Unknown";
          const opt = document.createElement("option");
          opt.value = name;
          opt.textContent = name;
          facilitySelect.appendChild(opt);
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

  /* =========================
     FUNGSI PIE CHART RINGKASAN (REVISI SLA CUSTOMER)
     ========================= */
  function renderSummaryPieCharts(data) {
    const summaryContainer = document.getElementById("summaryPieContainer");
    if (!summaryContainer) return;

    summaryContainer.innerHTML = `
      <div class="pie-summary-wrapper" 
        style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;">
        
        <div class="pie-card" style="background:#fff;border-radius:10px;box-shadow:0 2px 6px rgba(0,0,0,0.1);padding:14px;">
          <h6 class="fw-semibold mb-2 text-center" style="color:#0f766e;"><i class="fas fa-clock me-2"></i>Ontime Armada Internal</h6>
          <div class="pie-chart-container" style="position:relative;height:200px;"><canvas id="pieOntime"></canvas></div>
        </div>

        <div class="pie-card" style="background:#fff;border-radius:10px;box-shadow:0 2px 6px rgba(0,0,0,0.1);padding:14px;">
          <h6 class="fw-semibold mb-2 text-center" style="color:#2563eb;"><i class="fas fa-award me-2"></i>SLA Customer</h6>
          <div class="pie-chart-container" style="position:relative;height:180px;"><canvas id="pieSLA"></canvas></div>
        </div>

        <div class="pie-card" style="background:#fff;border-radius:10px;box-shadow:0 2px 6px rgba(0,0,0,0.1);padding:14px;">
          <h6 class="fw-semibold mb-2 text-center" style="color:#14b8a6;"><i class="fas fa-truck me-2"></i>Utilisasi Armada</h6>
          <div class="pie-chart-container" style="position:relative;height:180px;"><canvas id="pieUtil"></canvas></div>
        </div>

      </div>
    `;

    /* ==== HITUNG SLA CUSTOMER SESUAI DEFINISI ==== */
    const slaCust = data.slaCustomer || [];

    const rowAntar = slaCust.find(i => i.SLA?.toLowerCase() === "do diantarkan");
    const rowKirim = slaCust.find(i => i.SLA?.toLowerCase() === "do terkirim");

    const doAntar = parseInt(rowAntar?.["Jumlah DO"] || 0);
    const doKirim = parseInt(rowKirim?.["Jumlah DO"] || 0);

    const slaPercent = doAntar > 0 ? ((doKirim / doAntar) * 100).toFixed(2) : 0;

    const stdSla = parseFloat(rowKirim?.STD || 0);
    const gap = Math.max(stdSla - slaPercent, 0).toFixed(2);

    /* ==== Ontime Armada Internal ==== */
    const monInt = data.monitoringInternal || [];
    const totalMon = monInt.find((i) => i.TipeKiriman === "Total") || {};
    const ontime = parseInt(totalMon.Ontime || 0);
    const fulfill = parseInt(totalMon.Fulfill || 0);
    const ontimePct = fulfill ? (ontime / fulfill * 100).toFixed(1) : 0;
    const latePct = (100 - ontimePct).toFixed(1);

    /* ==== Utilisasi Armada ==== */
    const util = data.armadaUtil || [];
    const avgUtil = util.reduce((s, i, _, a) => s + (parseFloat(i.PctUtilization) || 0) / a.length, 0);
    const avgIdle = util.reduce((s, i, _, a) => s + (parseFloat(i.PctIdle) || 0) / a.length, 0);

    /* ==== Konfig Pie ==== */
    const configPie = (ctx, labels, values, colors) =>
      new Chart(ctx, {
        type: "doughnut",
        data: { labels, datasets: [{ data: values, backgroundColor: colors }] },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: "65%",
          plugins: {
            legend: { position: "bottom", labels: { boxWidth: 12, font: { size: 11 } } }
          }
        }
      });

    /* ==== Render ==== */
    setTimeout(() => {
      if (document.getElementById("pieOntime")) 
        configPie(pieOntime, ["Ontime", "Late"], [ontimePct, latePct], ["#22c55e", "#f97316"]);

      if (document.getElementById("pieSLA")) 
        configPie(pieSLA, ["% SLA", "Gap terhadap STD"], [slaPercent, gap], ["#3b82f6", "#ef4444"]);

      if (document.getElementById("pieUtil")) 
        configPie(pieUtil, ["Utilisasi", "Idle"], [avgUtil.toFixed(1), avgIdle.toFixed(1)], ["#14b8a6", "#57dfd3ff"]);
    }, 200);
  }

  /* =========================
     SUBMIT FORM
     ========================= */
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    btn.classList.add("btn-loading");
    spinnerOverlay.classList.remove("d-none");
    btnText.classList.add("btn-text-hidden");
    btn.disabled = true;
    container.innerHTML = `<div class="text-center p-4"><div class="spinner-border"></div><p>Memuat data...</p></div>`;

    const payload = {
      facility: facilitySelect.value,
      date: document.getElementById("date").value,
      key1: "WMWHSE4RTL"
    };

    const startTime = performance.now();

    try {
      const res = await fetch("{{ route('transport.dailyreport.data') }}", {
        method: "POST",
        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
        body: JSON.stringify(payload)
      });

      if (!res.ok) throw new Error(`HTTP ${res.status}`);

      const result = await res.json();
      const execTime = ((performance.now() - startTime) / 1000).toFixed(2);

      if (result && result.data) {
        const d = result.data;

        renderSummaryPieCharts(d);

        container.innerHTML = safeJoinSections([
          buildSLATable(d),
          buildMonitoringHtml(d, "monitoringExternal"),
          buildMonitoringHtml(d, "monitoringInternal"),
          buildDeliveryCustomerHtml(d),
          buildProdTableHtml(d, "prodCustomer"),
          buildProdArmadaCustHtml(d),
          buildDeliveryStoreHtml(d),
          buildProdTableHtml(d, "prodStore"),
          buildProdArmadaStoreHtml(d),
          buildArmadaUtilHtml(d),
          buildDriverUtilHtml(d)
        ]);

        setTimeout(() => renderSLAChartAndMonitoring(d), 300);

        showToast(`✅ Data berhasil dimuat untuk ${payload.facility}`);
        showToast(`⏱️ Eksekusi: ${execTime} detik`, 7000);

      } else {
        container.innerHTML = `<div class="alert alert-warning">⚠️ Tidak ada data ditemukan.</div>`;
      }

    } catch (err) {
      container.innerHTML = `<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i> ${err.message}</div>`;
    }

    btn.classList.remove("btn-loading");
    spinnerOverlay.classList.add("d-none");
    btnText.classList.remove("btn-text-hidden");
    btn.disabled = false;
  });

  /* =========================
     TOAST
     ========================= */
  window.showToast = function (message, duration = 5000) {
    const container = document.getElementById("toastContainer");
    if (!container) return;
    const toast = document.createElement("div");
    toast.className = "toast";
    toast.innerHTML = `<span>${message}</span><span class="close-btn">&times;</span>`;
    container.appendChild(toast);
    toast.querySelector(".close-btn").addEventListener("click", () => {
      toast.classList.remove("show");
      setTimeout(() => toast.remove(), 500);
    });
    setTimeout(() => toast.classList.add("show"), 50);
    setTimeout(() => {
      toast.classList.remove("show");
      setTimeout(() => toast.remove(), 500);
    }, duration);
  };
});
</script>


<div class="toast-container" id="toastContainer"></div>
@endsection
