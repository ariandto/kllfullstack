@extends('admin.dashboard')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-center">
                    @if (session('facility_info'))
                    @foreach (session('facility_info') as $facility)
                    <h4 style="font-size: 1.5rem; font-weight: bold; text-align: center;">
                        REPORT THROUGHPUT {{ $facility['Name'] }}
                    </h4>
                    <div class="page-title-right">
                        {{-- <ol class="breadcrumb m-0"> 
                        </ol> --}}
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
                        <form  id="reportForm" method="POST" action="{{ route('admin.report.inventory.summary_throughput') }}"> 
                            @csrf
                            <div class="row"> 
                                <!-- Start Date -->
                                <div class="col-6 col-md-2 mb-3">
                                    <label for="start_date" class="form-label">Start Date</label> 
                                    <input type="date" id="start_date" required data-pristine-required-message="Please Choice Start Date"  
                                        name="start_date" class="form-control" 
                                        value="{{ old('start_date', session("input_data_" . Auth::guard('admin')->id() . ".start_date")) }}" />
                                </div>

                                <!-- End Date -->
                                <div class="col-6 col-md-2 mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" id="end_date" required data-pristine-required-message="Please Choice End Date" name="end_date" class="form-control"
                                    value="{{ old('end_date', session("input_data_" . Auth::guard('admin')->id() . ".end_date")) }}">
                                </div>

                                <!-- View By (Combo Box) -->
                                <div class="col-6 col-md-2 mb-3">
                                    <label for="view_by" class="form-label">View By</label>
                                    <select id="view_by" name="view_by" required class="form-control">
                                        <option value="daily" {{ session("input_data_" . Auth::guard('admin')->id() . ".view_by") == 'daily' ? 'selected' : '' }}>Daily</option>
                                        <option value="weekly" {{ session("input_data_" . Auth::guard('admin')->id() . ".view_by") == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="monthly" {{ session("input_data_" . Auth::guard('admin')->id() . ".view_by") == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    </select>
                                </div> 
                                
                                <!-- Owner Button -->
                                <div class="col-6 col-md-2 mb-3">
                                    <label class="form-label d-block">Owner</label>
                                    <div class="position-relative">
                                        <button type="button" class="btn btn-light w-100" onclick="toggleDropdown(event, 'owner-checkbox-list')" id="dropdown-owner-button">
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
                                                @if(session()->has("dataowner_" . Auth::guard('admin')->id()))
                                                    @foreach (session("dataowner_" . Auth::guard('admin')->id()) as $owner)
                                                        @if(strcasecmp($owner->Owner, 'All') !== 0)
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input owner-checkbox" name="selected_owners[]"
                                                                id="Owner_{{ $loop->index }}" value="{{ $owner->Owner }}">
                                                                <label class="form-check-label" for="Owner_{{ $loop->index }}">{{ $owner->Owner }}</label>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <p class="text-warning">Data owner tidak ditemukan. Silakan refresh data.</p>
                                                @endif
                                               
                                            </div>
                                            <div class="d-flex mt-2" style="display: flex; gap: 5px;">
                                                <button type="button" class="btn btn-primary" style="flex: 1; padding: 0.375rem 0.75rem; font-size: 0.875rem;" onclick="closeCheckboxList()">OK</button>
                                                <button type="button" class="btn btn-danger" style="flex: 1; padding: 0.375rem 0.75rem; font-size: 0.875rem;" onclick="clearCheckboxes()">Clear</button>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                {{-- @dd(session("input_data_" . Auth::guard('admin')->id() . ".selected_owners", [])); --}}
                                <input type="hidden" id="selected_owners" name="selected_owners" value="{{ implode(';', session("input_data_" . Auth::guard('admin')->id() . ".selected_owners", [])) }}">
                                {{-- @dd(session("input_data_" . Auth::guard('admin')->id() . ".selected_owners", [])); --}}
                                {{-- Awalnya col-md-2 --}}
                                <div class="col-6 col-md-2 mb-3">
                                </div> 
                            </div>
                        
                            <div class="row">
                                <div class="col-6 col-md-2 mb-3">
                                    <button type="submit" class="btn btn-info w-100 mt-2" >Show</button> 
                                </div>
                                <div class="col-6 col-md-2 mb-3">
                                    <button type="button"  id="exportButton" class="btn btn-success w-100 mt-2">Export</button> 
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
                        <h4 class="card-title mb-0">Report Throughput by Chart </h4>
                    </div>
                    <div class="card-body">
                        <!-- Column kedua untuk grafik -->
                        @if(isset($dataTable) && count($dataTable) > 0)
                            <div id="mix-line-bar" class="e-charts" style="width: 100%;"  ></div>
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
                        {{-- <h4 class="card-title">Report Throughput</h4> --}}
                        <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                            <thead class="table-light" >
                                <tr>
                                    <th class="text-center">Periode</th>
                                    <th class="text-center">Inbound</th>
                                    <th class="text-center">Outbound</th>
                                    <th class="text-center">Occupancy</th>
                                    <th class="text-center">% Occupancy</th>
                                    <th class="text-center">Capacity</th> 
                                </tr>
                            </thead> 
                            <tbody>
                                @if(isset($dataTable) && count($dataTable) > 0)
                                @foreach ($dataTable as $key => $item)
                                <tr>
                                    <td class="text-start">{{ $item->Periode }}</td>
                                    <td class="text-end">{{ number_format(floatval(str_replace(',', '', $item->Inbound)), 0, '.', ',') }}</td>
                                    <td class="text-end">{{ number_format(floatval(str_replace(',', '', $item->Outbound)), 0, '.', ',') }}</td>
                                    <td class="text-end">{{ number_format(floatval(str_replace(',', '', $item->Occupancy)), 0, '.', ',') }}</td> 
                                    <td class="text-end">{{ number_format(floatval(str_replace(',', '', $item->{'%Occupancy'})), 2, '.', ',') }}</td> 
                                    <td class="text-end">{{ number_format(floatval(str_replace(',', '', $item->Capacity)), 0, '.', ',') }}</td>
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
<script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script> 

<script>
    document.addEventListener('DOMContentLoaded', function () {
    @if(isset($dataTable) && count($dataTable) > 0)
    const dataTable = @json($dataTable);
    
    // Function to convert comma-separated strings to numbers
    const parseNumber = str => parseFloat(str.replace(/,/g, '')); 
    const dates = dataTable.map(item => item.Periode);
    const inbound = dataTable.map(item => parseNumber(item.Inbound)); // Convert to number
    const outbound = dataTable.map(item => parseNumber(item.Outbound)); // Convert to number
    const occupancy = dataTable.map(item => parseNumber(item['%Occupancy'])); // Convert to number

    const chart = echarts.init(document.getElementById('mix-line-bar'));

    const option = {
        grid: {
            left: '5%',   // Space on the left side of the chart
            right: '5%', // Space on the right side of the chart to prevent overlap
            bottom: '10%', // Space at the bottom of the chart
            top: '10%',   // Space at the top of the chart
            containLabel: true // Ensures that labels are contained within the grid
        },
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data: ['Inbound', 'Outbound', '% Occupancy'],
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
        yAxis: [
            {
                type: 'value',
                name: 'Inbound/Outbound',
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
                name: '% Occupancy',
                position: 'right',
                axisLine: {
                    lineStyle: {
                        color: '#000'
                    }
                }
            }
        ],
        series: [
            {
                name: 'Inbound',
                type: 'bar',
                data: inbound,
                itemStyle: {
                    color: '#5156be'
                }
                ,
                label: {
                    show: true,
                    position: 'top',
                   
                }
            },
            {
                name: 'Outbound',
                type: 'bar',
                data: outbound,
                itemStyle: {
                    color: '#fd625e'
                },
                label: {
                    show: true,
                    position: 'top',
                   
                }
            },
            {
                name: '% Occupancy',
                type: 'line',
                yAxisIndex: 1,
                data: occupancy,
                itemStyle: {
                    color: '#000'
                },
                lineStyle: {
                    type: 'solid'
                }
                ,
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
