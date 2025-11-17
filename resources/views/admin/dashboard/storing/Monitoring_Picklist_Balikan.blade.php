@extends('admin.dashboard')
@section('admin')
<div class="page-content">
    <div class="container-fluid mt-4">
        <div class="container-fluid">
            <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-center">
                            @if (session('facility_info'))
                            @foreach (session('facility_info') as $facility)
                            <h4 style="font-size: 1.5rem; font-weight: bold; text-align: center;">
                                MONITORING PICKLIST BARANG BALIKAN
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
            <!-- End page title -->
            <!-- start row pertama parameter -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
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
                                <form id="reportForm" method="get" action="{{ route('admin.dashboard.storing.picklist') }}">
                                @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-2">
                                                <div class="row">
                                                    <div class="col-md-3 mb-2">
                                                        <label class="form-label">Picklist ID</label>
                                                        <input type="text" class="form-control" id="picklistid" name="picklist_id"
                                                            value="{{ old('picklist_id') }}"
                                                            placeholder="Masukan nomor picklist ID">
                                                        </div>
                                                    <div class="col-md-3 mb-2">
                                                        <label class="form-label">Tanggal Kirim</label>
                                                        <div class="col-md-6 mb-2"> 
                                                            <input type="date" class="form-control" id="startDate" name="start_date" 
                                                                value="{{ old('start_date', session("input_databr_" . Auth::guard('admin')->id() . "_" . session('facility_info')[0]['Relasi'] . '.start_date')) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 col-md-2 mb-0">
                                            <button type="Show" class="btn btn-info w-100 mt-2">Show</button>
                                        </div>
                                        <div class="col-6 col-md-2 mb-0">
                                            <a href="{{ route('admin.dashboard.storing.picklist') }}" class="btn btn-danger w-100 mt-2">Cancel</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- end row pertama parameter -->
            <!-- Row kedua untuk tabel -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                        {{-- <h4 class="card-title">Monitoring Picklist</h4> --}}
                        <table class="table mb-0">
                                @if(isset($datatabel1) && !empty($datatabel1))
                                <thead class="table-light">
                                    <tr>
                                        <th></th> <!-- Tombol Expand -->
                                        <th>Picklist ID</th>
                                        <th>To Storer</th>
                                        <th>Delivery Date</th>
                                        <th>Picklist Status</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $groupedData = collect($datatabel1)->groupBy('picklistid'); // Mengelompokkan data berdasarkan picklistid
                                    @endphp

                                    @foreach ($groupedData as $picklistid => $items)
                                    @php
                                    $firstItem = $items->first(); // Mengambil data pertama untuk header
                                    $index = $loop->index; // Menentukan index unik untuk setiap grup
                                    @endphp

                                    <!-- Baris Utama (Header) -->
                                    <tr class="main-row">
                                        <td>
                                            <button class="btn btn-sm btn-primary toggle-detail" data-index="{{ $index }}">+</button>
                                        </td>
                                        <td>{{ $firstItem['picklistid'] }}</td>
                                        <td>{{ $firstItem['to_storer'] }}</td>
                                        <td>{{ $firstItem['deliverydate'] }}</td>
                                        <td>{{ $firstItem['PicklistStatus'] }}</td>
                                    </tr>

                                    <!-- Baris Detail (Hidden) -->
                                    <tr class="detail-row" id="detail-{{ $index }}" style="display: none;">
                                        <td colspan="4">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Article</th>
                                                        <th>DESCR</th>
                                                        <th>ID</th>
                                                        <th>Qty Request </th>
                                                        <th>Qty Pick</th>
                                                        <th>Storer Key</th>
                                                        <th>Add Who</th>
                                                        <th>Edit Who</th>
                                                        <th>Item Add Date</th>
                                                        <th>Edit Date</th>
                                                        <th>Item Status</th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($items as $item)
                                                    <tr>
                                                        <td>{{ $item['article'] }}</td>
                                                        <td>{{ $item['DESCR'] }}</td>
                                                        <td>{{ $item['ID'] }}</td>
                                                        <td>{{ $item['Qty_Request'] }}</td>
                                                        <td>{{ $item['Qty_Pick'] }}</td>
                                                        <td>{{ $item['storerkey'] }}</td>
                                                        <td>{{ $item['addwho'] }}</td>
                                                        <td>{{ $item['editwho'] }}</td>
                                                        <td>{{ $item['item_adddate'] }}</td>
                                                        <td>{{ $item['editdate'] }}</td>
                                                        <td>{{ $item['ItemStatus'] }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                @else
                                <thead class="table-light">
                                    <tr>
                                        <th>No Data Available</th>
                                    </tr>
                                </thead>
                                @endif
                            </table>

                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row kedua untuk tabel -->
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $(".toggle-detail").click(function() {
        let index = $(this).data("index").toString(); // Pastikan index dalam bentuk string
        let detailRow = $("#detail-" + index.replace(/\//g, "_")); // Ganti '/' dengan '_'

        detailRow.toggle();

        let btn = $(this);
        if (btn.text() === "+") {
            btn.text("-");
        } else {
            btn.text("+");
        }
    });
});

</script>
@endsection