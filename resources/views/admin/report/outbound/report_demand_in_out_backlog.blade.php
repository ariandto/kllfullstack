@extends('admin.dashboard')
@section('admin')

    <div class="page-content">
        <div class="container-fluid">
            <!-- resources/views/admin/report/Shipment Out/report_demand_in_out_backlog.blade.php -->

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-center">
                        @if (session('facility_info'))
                            @foreach (session('facility_info') as $facility)
                                <h4 style="font-size: 1.5rem; font-weight: bold; text-align: center;">
                                    REPORT DEMAND IN OUT BACKLOG {{ $facility['Name'] }}
                                </h4>
                                <div class="page-title-right">
                                </div>
                            @endforeach
                        @else
                        @endif
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <!-- Row pertama untuk parameter -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Ini Eror Message -->
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            @endif

                            {{-- Ini Sesssion Buat Eror Penting --}}
                            @if (Session::has('error'))
                                <li>{{ Session::get('error') }}</li>
                            @endif

                            @if (Session::has('success'))
                                <li>{{ Session::get('success') }}</li>
                            @endif
                            <!-- Ini Eror Message -->

                            <form id="reportForm" method="POST"
                                action="{{ route('admin.report.outbound.summary_demandinoutbacklog') }}">
                                @csrf

                                <div class="row">
                                    <!-- Start Date -->
                                    <div class="col-6 col-md-2 mb-3">
                                        <label for="start_date" class="form-label">Start Date</label>
                                        <input type="date" id="start_date" required
                                            data-pristine-required-message="Please Choice Start Date" name="start_date"
                                            class="form-control"
                                            value="{{ old('start_date', session('input_data_demand_' . Auth::guard('admin')->id() . '.start_date')) }}" />
                                    </div>

                                    <!-- End Date -->
                                    <div class="col-6 col-md-2 mb-3">
                                        <label for="end_date" class="form-label">End Date</label>
                                        <input type="date" id="end_date" required
                                            data-pristine-required-message="Please Choice End Date" name="end_date"
                                            class="form-control"
                                            value="{{ old('end_date', session('input_data_demand_' . Auth::guard('admin')->id() . '.end_date')) }}">
                                    </div>

                                    <!-- View By (Combo Box) -->
                                    <div class="col-6 col-md-2 mb-3">
                                        <label for="view_by" class="form-label">View By</label>
                                        <select id="view_by" name="view_by" required class="form-control">
                                            <option value="daily"
                                                {{ session('input_data_demand_' . Auth::guard('admin')->id() . '.view_by') == 'daily' ? 'selected' : '' }}>
                                                Daily</option>
                                            <option value="weekly"
                                                {{ session('input_data_demand_' . Auth::guard('admin')->id() . '.view_by') == 'weekly' ? 'selected' : '' }}>
                                                Weekly</option>
                                            <option value="monthly"
                                                {{ session('input_data_demand_' . Auth::guard('admin')->id() . '.view_by') == 'monthly' ? 'selected' : '' }}>
                                                Monthly</option>
                                        </select>
                                    </div>

                                    <!-- Filter By (Combo Box) -->
                                    <div class="col-6 col-md-2 mb-3">
                                        <label for="filter_by" class="form-label">Filter By</label>
                                        <select id="filter_by" name="filter_by" required class="form-control">
                                            <option value="cbm"
                                                {{ session('input_data_demand_' . Auth::guard('admin')->id() . '.filter_by') == 'cbm' ? 'selected' : '' }}>
                                                CBM</option>
                                            <option value="order"
                                                {{ session('input_data_demand_' . Auth::guard('admin')->id() . '.filter_by') == 'order' ? 'selected' : '' }}>
                                                Order</option>
                                        </select>
                                    </div>

                                    <!-- Owner Button Isinya Check List Box-->
                                    <div class="col-6 col-md-2 mb-3">
                                        <label class="form-label d-block">Owner</label>
                                        <div class="position-relative">
                                            <button type="button" class="btn btn-light w-100"
                                                onclick="toggleDropdown(event, 'owner-checkbox-list')"
                                                id="dropdown-owner-button">
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

                                                    @if (session()->has('dataowner_' . Auth::guard('admin')->id()))
                                                        @foreach (session('dataowner_' . Auth::guard('admin')->id()) as $owner)
                                                            @if (strcasecmp($owner->Owner, 'All') !== 0)
                                                                <div class="form-check">
                                                                    <input type="checkbox"
                                                                        class="form-check-input owner-checkbox"
                                                                        name="selected_owners[]"
                                                                        id="Owner_{{ $loop->index }}"
                                                                        value="{{ $owner->Owner }}">
                                                                    <label class="form-check-label"
                                                                        for="Owner_{{ $loop->index }}">{{ $owner->Owner }}</label>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <p class="text-warning">Data owner tidak ditemukan. Silakan refresh
                                                            data.</p>
                                                    @endif

                                                    {{-- @if (isset($dataowner) && count($dataowner) > 0) 
                                                    @foreach ($dataowner as $owner)
                                                        @if (strcasecmp($owner->Owner, 'All') !== 0) 
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input owner-checkbox" name="selected_owners[]" 
                                                                id="Owner_{{ $loop->index }}" value="{{ $owner->Owner }}">
                                                                <label class="form-check-label" for="Owner_{{ $loop->index }}">{{ $owner->Owner }}</label>
                                                            </div>
                                                        @endif
                                                    @endforeach 
                                                @else
                                                    <p class="text-warning">Data owner kosong. Silakan refresh data.</p>
                                                @endif --}}
                                                </div>
                                                <div class="d-flex mt-2" style="display: flex; gap: 5px;">
                                                    <button type="button" class="btn btn-primary"
                                                        style="flex: 1; padding: 0.375rem 0.75rem; font-size: 0.875rem;"
                                                        onclick="closeCheckboxList()">OK</button>
                                                    <button type="button" class="btn btn-danger"
                                                        style="flex: 1; padding: 0.375rem 0.75rem; font-size: 0.875rem;"
                                                        onclick="clearCheckboxes()">Clear</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="selected_owners" name="selected_owners"
                                        value="{{ implode(';', session('input_data_demand_' . Auth::guard('admin')->id() . '.selected_owners', [])) }}">


                                    {{-- Type Order Button Isinya Check List Box --}}
                                    <div class="col-6 col-md-2 mb-3">
                                        <label class="form-label d-block">Order Type</label>
                                        <div class="position-relative">
                                            <button type="button" class="btn btn-light w-100"
                                                onclick="toggleDropdownTypeOrder(event, 'typeorder-checkbox-list')"
                                                id="dropdown-typeorder-button">
                                                Orders Type
                                            </button>
                                            <!-- Daftar Checkbox Dinamis -->
                                            <div id="typeorder-checkbox-list" class="dropdown-menu"
                                                style="display: none; position: absolute; top: 100%; left: 0; width: 100%; z-index: 1000;">
                                                <!-- Kontainer Scroll untuk Checkbox -->
                                                <div style="max-height: 100px; overflow-y: auto;">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="check_all_typeorder">
                                                        <label class="form-check-label"
                                                            for="check_all_typeorder">All</label>
                                                    </div>
                                                    <!-- Checkbox yang diisi dinamis dari Blade -->
                                                    @if (session()->has('datatypeorder_' . Auth::guard('admin')->id()))
                                                        @foreach (session('datatypeorder_' . Auth::guard('admin')->id()) as $typeorder)
                                                            @if (strcasecmp($typeorder->TYPE, 'All') !== 0)
                                                                <div class="form-check">
                                                                    <input type="checkbox"
                                                                        class="form-check-input typeorder-checkbox"
                                                                        name="selected_typeorders[]"
                                                                        id="typeorder_{{ $loop->index }}"
                                                                        value="{{ $typeorder->TYPE }}">
                                                                    <label class="form-check-label"
                                                                        for="typeorder_{{ $loop->index }}">{{ $typeorder->TYPE }}</label>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <p class="text-warning">Data type order tidak ditemukan. Silakan
                                                            refresh data.</p>
                                                    @endif

                                                    {{-- @if (isset($datatypeorder) && count($datatypeorder) > 0) 
                                                    @foreach ($datatypeorder as $typeorder)
                                                        @if (strcasecmp($typeorder->TYPE, 'All') !== 0) 
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input typeorder-checkbox" name="selected_typeorders[]" 
                                                                id="typeorder_{{ $loop->index }}" value="{{ $typeorder->TYPE }}">
                                                                <label class="form-check-label" for="typeorder_{{ $loop->index }}">{{ $typeorder->TYPE }}</label>
                                                            </div>
                                                        @endif
                                                    @endforeach 
                                                @else
                                                    <p class="text-warning">Data typeorder kosong. Silakan refresh data.</p>
                                                @endif --}}
                                                </div>
                                                <div class="d-flex mt-2" style="display: flex; gap: 5px;">
                                                    <button type="button" class="btn btn-primary"
                                                        style="flex: 1; padding: 0.375rem 0.75rem; font-size: 0.875rem;"
                                                        onclick="closeCheckboxListTypeOrder()">OK</button>
                                                    <button type="button" class="btn btn-danger"
                                                        style="flex: 1; padding: 0.375rem 0.75rem; font-size: 0.875rem;"
                                                        onclick="clearCheckboxesTypeOrder()">Clear</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="selected_typeorders" name="selected_typeorders"
                                        value="{{ implode(';', session('input_data_demand_' . Auth::guard('admin')->id() . '.selected_typeorders', [])) }}">

                                </div>

                                <div class="row">
                                    <div class="col-6 col-md-2 mb-3">
                                        <button type="submit" class="btn btn-info w-100 mt-2">Show</button>
                                    </div>
                                    <div class="col-6 col-md-2 mb-3">
                                        <button type="button" id="exportButton"
                                            class="btn btn-success w-100 mt-2">Export</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- end row pertama -->

            <div class="row">
                <!-- Column kedua untuk grafik -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Report Demand In Out by Chart </h4>
                        </div>
                        <div class="card-body">
                            <!-- Column kedua untuk grafik -->
                            @if (isset($dataTable) && count($dataTable) > 0)
                                <div id="mix-line-bar" class="e-charts" style="width: 100%;"></div>
                                {{-- style="width: 600px; height: 400px;" --}}
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            <!-- end row ketiga -->

            <!-- Row kedua untuk tabel data dan grafik -->
            <div class="row">
                <!-- Column pertama untuk tabel data -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            {{-- <h4 class="card-title">Report Demand In Out Backlog</h4> --}}
                            <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">Periode</th>
                                        <th class="text-center">Demand In</th>
                                        <th class="text-center">Shipment Out</th>
                                        <th class="text-center">Backlog</th>
                                        <th class="text-center">Shipment Nol</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($dataTable) && count($dataTable) > 0)
                                        @foreach ($dataTable as $key => $item)
                                            <tr>
                                                <td class="text-start">{{ $item->Periode }}</td>
                                                <td class="text-end">
                                                    {{ number_format(floatval(str_replace(',', '', $item->{'Demand In'})), 0, '.', ',') }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format(floatval(str_replace(',', '', $item->{'Shipment Out'})), 0, '.', ',') }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format(floatval(str_replace(',', '', $item->Backlog)), 0, '.', ',') }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format(floatval(str_replace(',', '', $item->{'Shipment Nol'})), 0, '.', ',') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        {{-- <p>No data available. Please submit the form to view the results.</p> --}}
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row kedua -->



        </div> <!-- container-fluid -->
    </div>
    <!-- Load the custom script -->
    <script src="{{ asset('backend/assets/js/owner-dropdown.js') }}"></script>
    <script src="{{ asset('backend/assets/js/typeorder-dropdown.js') }}"></script>
    <script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (isset($dataTable) && count($dataTable) > 0)
                const dataTable = @json($dataTable);

                // Function to convert comma-separated strings to numbers
                const parseNumber = str => parseFloat(str.replace(/,/g, ''));
                const dates = dataTable.map(item => item.Periode);
                const DemandIn = dataTable.map(item => parseNumber(item['Demand In'])); // Convert to number
                const ShipmentOut = dataTable.map(item => parseNumber(item['Shipment Out'])); // Convert to number
                const Backlog = dataTable.map(item => parseNumber(item['Backlog'])); // Convert to number

                const chart = echarts.init(document.getElementById('mix-line-bar'));

                const option = {
                    grid: {
                        left: '5%', // Space on the left side of the chart
                        right: '5%', // Space on the right side of the chart to prevent overlap
                        bottom: '10%', // Space at the bottom of the chart
                        top: '10%', // Space at the top of the chart
                        containLabel: true // Ensures that labels are contained within the grid
                    },
                    tooltip: {
                        trigger: 'axis'
                    },
                    legend: {
                        data: ['Demand In', 'Shipment Out', 'Backlog'],
                        top: 0
                    },
                    xAxis: {
                        type: 'category',
                        data: dates,
                        axisPointer: {
                            type: 'shadow'
                        },
                        axisLabel: {
                            // rotate: 45, // Rotate labels to prevent overlap
                            // interval: 0, // Display all labels
                            // formatter: function (value) {
                            //     // Format date if needed
                            //     return value;
                            // }
                        },
                        axisTick: {
                            show: false
                        }
                    },
                    yAxis: [{
                            type: 'value',
                            name: 'Demand In/Shipment Out',
                            position: 'left',
                            axisLine: {
                                lineStyle: {
                                    color: '#000'
                                }
                            },
                            splitLine: {
                                show: false
                            },
                            // max: 3000, // Atur nilai maksimum sesuai kebutuhan
                        },
                        {
                            type: 'value',
                            name: 'Backlog',
                            position: 'right',
                            axisLine: {
                                lineStyle: {
                                    color: '#000'
                                }
                            }
                        }
                    ],
                    series: [{
                            name: 'Demand In',
                            type: 'bar',
                            data: DemandIn, //Ini isi datanya
                            itemStyle: {
                                color: '#5156be'
                            },
                            label: {
                                show: true,
                                position: 'top',
                            }
                        },
                        {
                            name: 'Shipment Out',
                            type: 'bar',
                            data: ShipmentOut, //Ini isi datanya
                            itemStyle: {
                                color: '#fd625e'
                            },
                            label: {
                                show: true,
                                position: 'top',
                            }
                        },
                        {
                            name: 'Backlog',
                            type: 'line',
                            yAxisIndex: 1,
                            data: Backlog, //Ini isi datanya
                            itemStyle: {
                                color: '#000'
                            },
                            lineStyle: {
                                type: 'solid'
                            },
                            label: {
                                show: true,
                                position: 'top',
                            }
                        }
                    ]
                };

                chart.setOption(option);
                // Ini untuk biar bisa di resize
                window.addEventListener('resize', function() {
                    chart.resize();
                });
                // Ini untuk biar bisa di resize
            @endif
        });
    </script>


    <style>

    </style>

@endsection
