@extends('admin.dashboard')
@section('admin')

<div class="page-content">
    <!-- Judul Dashboard (Compact) -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 fw-bold text-gray-800 mb-0">Transport Dashboard 
            <small class="text-primary" id="selectedSite"></small>
        </h2>
        <button id="refreshBtn" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-sync-alt"></i>
        </button>
    </div>

    <!-- Form Filter (Compact) -->
    <form id="dashboardForm" method="POST" action="{{ route('transport.dashboard.data') }}">
        @csrf
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light py-2">
                <h5 class="card-title mb-0 small fw-bold">Filter Data</h5>
            </div>
            <div class="card-body py-2">
                <div class="row g-2 align-items-center">
                    <!-- Pilih Site -->
                    <div class="col-md-3">
                        <label for="site" class="form-label small fw-bold">Site</label>
                        <select name="site" id="site" class="form-select form-select-sm">
                            <option value="">-- Pilih Site --</option>
                            @foreach($dropdownData as $data)
                                <option value="{{ $data->NAME }}">{{ $data->NAME }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pilih Tanggal -->
                    <div class="col-md-3">
                        <label for="start_date" class="form-label small fw-bold">Tanggal</label>
                        <input type="date" name="start_date" id="start_date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <!-- Tombol Show -->
                    <div class="col-md-3">
                    <label for="start_date" class="form-label small fw-bold"></label>
                        <button type="submit" id="btnShow" class="btn btn-info btn-sm w-100">
                            <span id="btnText">Show</span>
                            <span id="btnLoader" class="spinner-border spinner-border-sm d-none"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
 
    <!-- Main Dashboard Content -->
    <div class="container-fluid">
        <!-- Row 1: Summary Cards -->
        <div class="row g-2 mb-3">       
            <!-- Data Armada -->
            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-2">
                        <h5 class="card-title text-center small fw-bold mb-2">Data Armada</h5>
                        <div class="row g-1">
                            @php
                                $summaryItems = [
                                    ['id' => 'asset', 'label' => 'Asset', 'icon' => 'fa-truck'],
                                    ['id' => 'totaltrip', 'label' => 'Total Trip', 'icon' => 'fa-route'],
                                    ['id' => 'ratio', 'label' => 'Ratio', 'icon' => 'fa-percentage'],
                                    ['id' => 'available', 'label' => 'Available', 'icon' => 'fa-check-circle'],
                                    ['id' => 'not_available', 'label' => 'Not Available', 'icon' => 'fa-times-circle'],
                                    ['id' => 'idle', 'label' => 'Idle', 'icon' => 'fa-pause-circle'],
                                ];
                            @endphp
                            @foreach($summaryItems as $item)
                                <div class="col-6">
                                    <div class="card text-center h-100 shadow-none border">
                                        <div class="card-body p-1">
                                            <h6 class="card-title text-secondary mb-0 small">
                                                <i class="fas {{ $item['icon'] }} me-1"></i> {{ $item['label'] }}
                                            </h6>
                                            <h4 class="card-text fw-bold mt-1 mb-0" id="{{ $item['id'] }}">-</h4>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- UJP Data -->
            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-2">
                        <h5 class="card-title text-center small fw-bold mb-2">UJP Data</h5>
                        <div class="row g-1">
                            @php
                                $ujpItems = [
                                    ['id' => 'ujptotal', 'label' => 'UJP Total', 'icon' => 'fa-money-bill-wave'],
                                    ['id' => 'ujpcust', 'label' => 'UJP Customer', 'icon' => 'fa-user-tie'],
                                    ['id' => 'ujpstore', 'label' => 'UJP Store', 'icon' => 'fa-store'],
                                    ['id' => 'ujphub', 'label' => 'UJP Hub', 'icon' => 'fa-warehouse'],
                                    ['id' => 'ujpother', 'label' => 'UJP Other', 'icon' => 'fa-ellipsis-h'],
                                    ['id' => 'ujptripcust', 'label' => 'UJP/Trip Customer', 'icon' => 'fa-divide'],
                                    ['id' => 'ujptripstore', 'label' => 'UJP/Trip Store', 'icon' => 'fa-divide'],
                                    ['id' => 'ujptriphub', 'label' => 'UJP/Trip Hub', 'icon' => 'fa-divide'],
                                ];
                            @endphp
                            @foreach($ujpItems as $item)
                                <div class="col-6">
                                    <div class="card text-center h-100 shadow-none border">
                                        <div class="card-body p-1">
                                            <h6 class="card-title text-secondary mb-0 small">
                                                <i class="fas {{ $item['icon'] }} me-1"></i> {{ $item['label'] }}
                                            </h6>
                                            <h4 class="card-text fw-bold mt-1 mb-0" id="{{ $item['id'] }}">-</h4>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>  

            <!-- Order Delivery -->
            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-2">
                        <h5 class="card-title text-center small fw-bold mb-2">Order Delivery</h5>
                        <div class="row g-1">
                            @php
                                $pendingitems = [
                                    ['id' => 'totaldo', 'label' => 'Total DO', 'icon' => 'fa-clipboard-list'],
                                    ['id' => 'pendingint', 'label' => 'Pending Internal', 'icon' => 'fa-clock'],
                                    ['id' => 'pendingext', 'label' => 'Pending External', 'icon' => 'fa-clock'],
                                    ['id' => 'dokirim', 'label' => 'DO Terkirim', 'icon' => 'fa-check'],
                                    ['id' => 'dokirimberhasil', 'label' => 'Success%', 'icon' => 'fa-percent'],
                                    ['id' => 'internal', 'label' => 'Internal%', 'icon' => 'fa-percent'],
                                    ['id' => 'external', 'label' => 'External%', 'icon' => 'fa-percent'],
                                ];
                            @endphp
                            @foreach($pendingitems as $item)
                                <div class="col-6">
                                    <div class="card text-center h-100 shadow-none border">
                                        <div class="card-body p-1">
                                            <h6 class="card-title text-secondary mb-0 small">
                                                <i class="fas {{ $item['icon'] }} me-1"></i> {{ $item['label'] }}
                                            </h6>
                                            <h4 class="card-text fw-bold mt-1 mb-0" id="{{ $item['id'] }}">-</h4>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>  

            <!-- Fulfillment Charts -->
            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-2">
                        <h5 class="card-title text-center small fw-bold mb-2">Fulfillment</h5>
                        <div class="row g-1">
                            <!-- Chart: Fulfillment Internal Customer -->
                            <div class="col-4">
                                <p class="text-center text-muted small mb-1">Customer</p>
                                <div class="chart-container" style="height: 200px;">
                                    <canvas id="customerChart"></canvas>
                                </div>
                            </div>

                            <!-- Chart: Fulfillment Internal Store -->
                            <div class="col-4">
                                <p class="text-center text-muted small mb-1">Store</p>
                                <div class="chart-container" style="height: 200px;">
                                    <canvas id="storeChart"></canvas>
                                </div>
                            </div>

                            <!-- Chart: Fulfillment External -->
                            <div class="col-4">
                                <p class="text-center text-muted small mb-1">External</p>
                                <div class="chart-container" style="height: 200px;">
                                    <canvas id="externalChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Comparison Charts -->
        <div class="row g-2 mb-3">
            <!-- Comparison Charts -->
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-2">
                        <h5 class="card-title text-center small fw-bold mb-2">Comparison</h5>
                        <div class="row g-1">
                            <!-- Chart: Internal vs External -->
                            <div class="col-4">
                                <p class="text-center text-muted small mb-1">Internal vs External</p>
                                <div class="chart-container" style="height: 200px;">
                                    <canvas id="barChart1"></canvas>
                                </div>
                            </div>

                            <!-- Chart: Type Kiriman -->
                            <div class="col-4">
                                <p class="text-center text-muted small mb-1">Type Kiriman(Internal)</p>
                                <div class="chart-container" style="height: 200px;">
                                    <canvas id="barChart2"></canvas>
                                </div>
                            </div>

                            <!-- Chart: Moda Kiriman -->
                            <div class="col-4">
                                <p class="text-center text-muted small mb-1">Moda Kiriman(External)</p>
                                <div class="chart-container" style="height: 200px;">
                                    <canvas id="barChart3"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trip Charts -->
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-2">
                        <h5 class="card-title text-center small fw-bold mb-2">Trip Overview</h5>
                        <div class="chart-container" style="height: 180px;">
                            <canvas id="tripExtChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 3: Top 10 Trips -->
        <div class="row g-2">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body p-2">
                        <h5 class="card-title text-center small fw-bold mb-2">Top 10 Trips Customer</h5>
                        <div class="chart-container" style="height: 180px;">
                            <canvas id="top10Chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS -->
<style>
    .chart-container {
        width: 100%;
        position: relative;
    }

    .card {
        border: none;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 6px 12px rgba(0,0,0,0.1);
    }

    .card-title {
        font-size: 0.85rem;
    }

    .card-body h4 {
        font-size: 1.25rem;
    }

    .small {
        font-size: 0.75rem !important;
    }

    .shadow-sm {
        box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let customerChart, storeChart, externalChart, barChart1, barChart2, barChart3,tripExtChart, top10Chart;
    let userClickedShow = false; // Cegah toastr error saat load pertama
    let selectedSite = document.getElementById("selectedSite");

    document.getElementById("dashboardForm").addEventListener("submit", function (e) {
        e.preventDefault();
        userClickedShow = true; // Tandai bahwa user sudah klik "Show"
        let site = document.getElementById("site").value;

        if (!site) {
            toastr.warning("Pilih site terlebih dahulu!", "Peringatan", { timeOut: 3000 });
            return;
        }    
        selectedSite.textContent =  site;

        let btnShow = document.getElementById("btnShow");                                                                                                         
        let btnText = document.getElementById("btnText");
        let btnLoader = document.getElementById("btnLoader");

        btnShow.disabled = true;
        btnText.classList.add("d-none");
        btnLoader.classList.remove("d-none");

        fetchDashboardData().finally(() => {
            btnShow.disabled = false;
            btnText.classList.remove("d-none");
            btnLoader.classList.add("d-none");
        });
    });

    function fetchDashboardData() {
        let form = document.getElementById("dashboardForm");
        let formData = new FormData(form);

        return fetch(form.action, {
            method: "POST",
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Server response not OK");
            }
            return response.json();
        })
        .then(data => {
            console.log("Data dari Server:", data);

            if (!data || !data.labels || !data.charts || !data.barCharts) {
                throw new Error("Data format tidak valid");
            }

            // Update angka-angka utama
            document.getElementById("asset").innerText = data.labels.asset;
            document.getElementById("totaltrip").innerText = data.labels.totaltrip;
            document.getElementById("ratio").innerText = data.labels.ratio;
            document.getElementById("available").innerText = data.labels.available;
            document.getElementById("not_available").innerText = data.labels.not_available;
            document.getElementById("idle").innerText = data.labels.idle;

          //  Labels2
            document.getElementById("ujptotal").innerText = formatMoney(data.label2.ujptotal);
            document.getElementById("ujpcust").innerText = formatMoney(data.label2.ujpcust);
            document.getElementById("ujpstore").innerText = formatMoney(data.label2.ujpstore);
            document.getElementById("ujphub").innerText = formatMoney(data.label2.ujphub);
            document.getElementById("ujpother").innerText = formatMoney(data.label2.ujpother);
            document.getElementById("ujptripcust").innerText = formatMoney(data.label2.ujptripcust);
            document.getElementById("ujptripstore").innerText = formatMoney(data.label2.ujptripstore);
            document.getElementById("ujptriphub").innerText = formatMoney(data.label2.ujptriphub);
            //  Labels3
            document.getElementById("totaldo").innerText = data.label3.totaldo;
            document.getElementById("pendingint").innerText = data.label3.pendingint;
            document.getElementById("pendingext").innerText = data.label3.pendingext;
            document.getElementById("dokirim").innerText = data.label3.dokirim;
            document.getElementById("dokirimberhasil").innerText = data.label3.dokirimberhasil;
            document.getElementById("internal").innerText = data.label3.internal;
            document.getElementById("external").innerText = data.label3.external;
            function formatMoney(amount) {
                return amount.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 });
            }

            // Update doughnut charts
            customerChart = updateDoughnutChart(customerChart, "customerChart", data.charts.customer);
            storeChart = updateDoughnutChart(storeChart, "storeChart", data.charts.store);
            externalChart = updateDoughnutChart(externalChart, "externalChart", data.charts.external);

            // Update bar charts (stacked)
            barChart1 = updateBarChart(barChart1, "barChart1", data.barCharts.chart1);
            barChart2 = updateBarChart(barChart2, "barChart2", data.barCharts.chart2);
            barChart3 = updateBarChart(barChart3, "barChart3", data.barCharts.chart3);
            // Update bar chart terbaru
            tripExtChart = updateBar2Chart(tripExtChart, "tripExtChart", data.barTripData.tripExt);
            top10Chart = updateBar2Chart(top10Chart, "top10Chart", data.barTripData.top10);

        })
        .catch(error => {
            console.error("Error fetching data:", error);

            if (userClickedShow) {
                toastr.error("Gagal mengambil data. Periksa koneksi atau hubungi admin!", "Error", { timeOut: 5000 });
            }
        });
    }
    function updateDoughnutChart(chartInstance, canvasId, chartData) {
        let ctx = document.getElementById(canvasId).getContext("2d");

        if (!chartInstance) {
            return new Chart(ctx, {
                type: "doughnut",
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: "Jumlah",
                        data: chartData.data,
                        backgroundColor: ["#36A2EB", "#FF6384"],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: "top" },
                        datalabels: { // Menampilkan angka di dalam chart
                            color: "#fff",
                            font: { weight: "bold", size: 14 },
                            formatter: (value, ctx) => value // Menampilkan angka saja
                        }
                    }
                },
                plugins: [ChartDataLabels] // Aktifkan plugin
            });
        } else {
            chartInstance.data.labels = chartData.labels;
            chartInstance.data.label3 = chartData.label3;
            chartInstance.data.datasets[0].data = chartData.data;
            chartInstance.update();
            return chartInstance;
        }
    }

function updateBarChart(chartInstance, canvasId, chartData) {
    let ctx = document.getElementById(canvasId).getContext("2d");

    if (!chartInstance) {
        return new Chart(ctx, {
            type: "doughnut",
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: "Jumlah",
                    data: chartData.data,
                    backgroundColor: [
                        "#36A2EB", "#FF6384", "#4BC0C0", "#FFCE56", "#9966FF"
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: "top" },
                    // title: { display: true, text: "Polar Area Chart" },
                    datalabels: { 
                        color: "#fff", // Warna teks label
                        font: { weight: "bold", size: 14 },
                        formatter: (value, ctx) => value 
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    } else {
        chartInstance.data.labels = chartData.labels;
        chartInstance.data.label3 = chartData.label3;
        chartInstance.data.datasets[0].data = chartData.data;
        chartInstance.update();
        return chartInstance;
    }
}
function updateBar2Chart(chartInstance, canvasId, chartData) {
    let ctx = document.getElementById(canvasId).getContext("2d");

    if (!chartInstance) {
        return new Chart(ctx, {
            type: "bar", // Ini khusus untuk bar chart
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: "Jumlah",    
                    data: chartData.data,
                    backgroundColor: [
                        "#36A2EB", "#FF6384", "#4BC0C0", "#FFCE56", "#9966FF"
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: "top" },
                    datalabels: { 
                        color: "#fff",
                        font: { weight: "bold", size: 14 },
                        formatter: (value) => value
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    } else {
        chartInstance.data.labels = chartData.labels;
        chartInstance.data.label3 = chartData.label3;
        chartInstance.data.datasets[0].data = chartData.data;
        chartInstance.update();
        return chartInstance;
    }
}
});
</script>

@endsection