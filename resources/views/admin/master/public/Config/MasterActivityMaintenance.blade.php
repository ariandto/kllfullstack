@extends('admin.dashboard')
@section('admin')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.css">
    <!-- DataTables CSS & Buttons -->

    <div class="page-content">
        <div class="container-fluid">
            <!-- Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-center">
                        @if (session('facility_info'))
                            @foreach (session('facility_info') as $facility)
                                <h4 class="text-center font-weight-bold">
                                    MASTER ACTIVITY MAINTENANCE
                                </h4>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form Input -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            {{-- Error & Session Alerts --}}
                            @if ($errors->any())
                                <ul class="text-danger">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                            {{-- @if (session('message'))
                                <div class="alert alert-{{ session('alert-type', 'info') }}">
                                    {{ session('message') }}
                                </div>
                            @endif --}}

                            <form method="POST"
                                action="{{ route('admin.master.public.config.masteractivitymaintenance_save') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label>Description</label>
                                        <input type="text" name="description" class="form-control" required
                                            value="{{ old('description') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Related Unit Type</label>
                                        <select name="unit_type" class="form-control" required>
                                            <option value="">-- Pilih --</option>
                                            @foreach ($relatedUnitType as $unit)
                                                <option value="{{ $unit->RelatedUnitType }}"
                                                    {{ old('unit_type') == $unit->RelatedUnitType ? 'selected' : '' }}>
                                                    {{ $unit->RelatedUnitType }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Task Category</label>
                                        <select name="task_category" class="form-control" required>
                                            <option value="">-- Pilih --</option>
                                            @foreach ($taskCategory as $task)
                                                <option value="{{ $task->TaskCategory }}"
                                                    {{ old('task_category') == $task->TaskCategory ? 'selected' : '' }}>
                                                    {{ $task->TaskCategory }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Lama Pengerjaan</label>
                                        <input type="number" step="0.1" name="lama_pengerjaan" class="form-control"
                                            required value="{{ old('lama_pengerjaan') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>UOM</label>
                                        <select name="uom" class="form-control" required>
                                            <option value="Menit" {{ old('uom') == 'Menit' ? 'selected' : '' }}>Menit
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Non Active
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100">Save</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="reset" class="btn btn-warning w-100">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DataTable -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body table-responsive">
                            <h5>Data Activity</h5>
                            <table id="filterableTable" class="table table-striped table-bordered display nowrap"
                                style="width:100%">
                                <thead class="table-info">
                                    <tr>
                                        <th>Description</th>
                                        <th>Unit Type</th>
                                        <th>Task Category</th>
                                        <th>Lama Pengerjaan</th>
                                        <th>UOM</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dataTable1 as $item)
                                        <tr>
                                            <td>{{ $item->Description }}</td>
                                            <td>{{ $item->UnitType }}</td>
                                            <td>{{ $item->TaskCategory }}</td>
                                            <td>{{ $item->LamaPengerjaan }}</td>
                                            <td>{{ $item->UOM }}</td>
                                            <td>{{ $item->IsActive ? 'Active' : 'Non Active' }}</td>
                                            <td>
                                                <form method="POST"
                                                    action="{{ route('admin.master.public.config.masteractivitymaintenance_delete') }}"
                                                    style="display:inline-block;">
                                                    @csrf
                                                    <input type="hidden" name="description"
                                                        value="{{ $item->Description }}">
                                                    <input type="hidden" name="unit_type" value="{{ $item->UnitType }}">
                                                    <input type="hidden" name="task_category"
                                                        value="{{ $item->TaskCategory }}">
                                                    <button type="submit" class="btn btn-danger btn-sm flex-fill"
                                                        onclick="return confirm('Yakin ingin hapus data ini?')">Hapus</button>
                                                </form>
                                                <button type="button" class="btn btn-warning btn-sm flex-fill"
                                                    onclick="editData(
                                                '{{ $item->Description }}', 
                                                '{{ $item->UnitType }}', 
                                                '{{ $item->TaskCategory }}', 
                                                '{{ $item->LamaPengerjaan }}', 
                                                '{{ $item->UOM }}', 
                                                '{{ $item->IsActive }}')">
                                                    Edit
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#filterableTable').DataTable({
                buttons: ['csv', 'excel', 'pdf', 'print'],
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, 'All']
                ],
                scrollX: true,
                order: [], // Nonaktifkan sorting default
                language: {
                    search: "Filter data:", // Label untuk input pencarian
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: {
                        previous: "Previous",
                        next: "Next"
                    },
                    zeroRecords: "Tidak ada data yang ditemukan",
                    infoEmpty: "Tidak ada data tersedia",
                    infoFiltered: "(difilter dari total _MAX_ data)"
                }
            });
        });
    </script>
    <script>
        function editData(description, unitType, taskCategory, lamaPengerjaan, uom, status) {
            // Isi field input dengan data yang dipilih
            $('input[name="description"]').val(description);
            $('select[name="unit_type"]').val(unitType);
            $('select[name="task_category"]').val(taskCategory);
            $('input[name="lama_pengerjaan"]').val(lamaPengerjaan);
            $('input[name="uom"]').val(uom);
            $('select[name="status"]').val(status);

            // Scroll ke form input
            window.scrollTo(0, 0);

        }
    </script>

@endsection
