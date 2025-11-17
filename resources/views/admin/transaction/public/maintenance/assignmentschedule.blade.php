@extends('admin.dashboard')
@section('admin')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-center">
                        @if (session('facility_info'))
                            @foreach (session('facility_info') as $facility)
                                <h4 class="text-center fw-bold">
                                    ASSIGNMENT SCHEDULE {{ $facility['Name'] }}
                                </h4>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <form id="myFormShow" action="{{ route('admin.maintenance.public.assignmentschedule_submit') }}" method="POST">
                @csrf
                {{-- Header - Parameter Show Data --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm mb-3">
                            <div class="card-body">
                                <div class="row g-3 align-items-end">
                                    {{-- Bulan --}}
                                    <div class="col-md-3">
                                        <label for="bulan" class="form-label">Bulan</label>
                                        <select class="form-control" id="bulan" name="bulan">
                                            @php
                                                $months = [
                                                    '01' => 'Januari',
                                                    '02' => 'Februari',
                                                    '03' => 'Maret',
                                                    '04' => 'April',
                                                    '05' => 'Mei',
                                                    '06' => 'Juni',
                                                    '07' => 'Juli',
                                                    '08' => 'Agustus',
                                                    '09' => 'September',
                                                    '10' => 'Oktober',
                                                    '11' => 'November',
                                                    '12' => 'Desember',
                                                ];
                                                $currentMonth = date('m');
                                            @endphp
                                            @foreach ($months as $val => $name)
                                                <option value="{{ $val }}"
                                                    {{ $val == $currentMonth ? 'selected' : '' }}>{{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Tahun --}}
                                    <div class="col-md-3">
                                        <label for="tahun" class="form-label">Tahun</label>
                                        <select class="form-control" id="tahun" name="tahun">
                                            @php
                                                $thisYear = date('Y');
                                                $lastYear = $thisYear + 1;
                                            @endphp
                                            <option value="{{ $thisYear }}">{{ $thisYear }}</option>
                                            <option value="{{ $lastYear }}">{{ $lastYear }}</option>
                                        </select>
                                    </div>

                                    {{-- Unit Type --}}
                                    <div class="col-md-3">
                                        <label for="unit_type" class="form-label" style="font-style: italic;">Unit
                                            Type</label>
                                        <select class="form-control" id="unit_type" name="unit_type">
                                            @isset($getUnitType)
                                                @foreach ($getUnitType as $req)
                                                    <option value="{{ $req->UnitType }}"
                                                        {{ old('unit_type') == $req->UnitType ? 'selected' : '' }}>
                                                        {{ $req->UnitType }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="">Data tidak tersedia</option>
                                            @endisset
                                        </select>

                                    </div>

                                    {{-- Button Show --}}
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary">Show</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Form Insert Data --}}
            <form id="myFormInsert" action="{{ route('admin.maintenance.public.assignmentschedule_insert') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                @endif

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

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" value="" id="enableInsert">
                                    <label class="form-check-label" for="enableInsert">
                                        Input Assignment Schedule Data
                                    </label>
                                </div>

                                <div id="insertDataSection" style="display: none;">
                                    <div class="row g-3 align-items-end">
                                        {{-- Activity --}}
                                        <div class="col-md-3">
                                            <label for="activity" class="form-label">Activity</label>
                                            <select class="form-control" id="activity" name="activity">
                                                @isset($getActivityAssignment)
                                                    @foreach ($getActivityAssignment as $activity)
                                                        <option value="{{ $activity->Description }}">
                                                            {{ $activity->Description }}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    <option value="">Data tidak tersedia</option>
                                                @endisset
                                            </select>
                                        </div>
                                        {{-- PIC --}}
                                        <div class="col-md-3">
                                            <label for="pic" class="form-label">PIC</label>
                                            <select class="form-control" id="pic" name="pic">
                                                @foreach ($getEmployeeMaintenance ?? [] as $emp)
                                                    <option value="{{ $emp->EmployeeName }}">{{ $emp->EmployeeName }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Teammate + Checkbox --}}
                                        <div class="col-md-3">
                                            <div class="form-check mb-1">
                                                <input class="form-check-input" type="checkbox" id="enableTeammate">
                                                <label class="form-check-label" for="enableTeammate">
                                                    Enable Teammate
                                                </label>
                                            </div>
                                            <select class="form-control" id="teammate" name="teammate" disabled>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            {{-- <button type="submit" class="btn btn-success">Save</button> --}}
                                            <button type="button" id="btnSaveInsert" class="btn btn-success">Save</button>

                                        </div>

                                    </div>


                                </div> <!-- end insertDataSection -->
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="assignment_data" id="assignment_data">
                <div style="color: red; font-style: italic;">
                    Jika data H-1 dan seterusnya, maka TANGGAL sudah tidak berlaku dan data tidak dapat disimpan.
                </div>
            </form>



            <!-- Data Table -->
            <div class="row mt-3 mb-3">
                <!-- Isi kontennya -->
                <div class="col-12">
                    @if (isset($dataTable1) && count($dataTable1) > 0)
                        @php

                            $daysInMonth = cal_days_in_month(
                                CAL_GREGORIAN,
                                request('bulan') ?? date('m'),
                                request('tahun') ?? date('Y'),
                            );
                        @endphp
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="filterTable" class="table table-bordered nowrap " style="width: 100%;">
                                        <thead class="text-center align-middle">
                                            <tr>
                                                <th>Unit No</th>
                                                <th style="white-space: nowrap; width: 1%;">Unit Name</th>
                                                <th>Status</th>
                                                <th>Scheduled</th>
                                                @for ($day = 1; $day <= $daysInMonth; $day++)
                                                    <th class="text-center align-middle">
                                                        {{ str_pad($day, 2, '0', STR_PAD_LEFT) }}</th>
                                                @endfor

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dataTable1 as $row)
                                                <tr>
                                                    <td>{{ $row->{'Unit No'} }}</td>
                                                    <td style="white-space: nowrap; width: 1%;">{{ $row->{'Unit Name'} }}
                                                    </td>

                                                    <td>{{ $row->Status }}</td>
                                                    <td>{{ $row->Scheduled }}</td>
                                                    @for ($day = 1; $day <= $daysInMonth; $day++)
                                                        @php
                                                            $dayKey = str_pad($day, 2, '0', STR_PAD_LEFT);
                                                            $checked = $row->{$dayKey} == 1 ? 'checked' : '';
                                                            $tanggalLengkap = sprintf(
                                                                '%04d-%02d-%02d',
                                                                request('tahun') ?? date('Y'),
                                                                request('bulan') ?? date('m'),
                                                                $day,
                                                            );
                                                        @endphp
                                                        <td class="text-center align-middle">
                                                            <input type="checkbox"
                                                                name="schedule[{{ $row->{'Unit No'} }}][{{ $dayKey }}]"
                                                                value="1" {{ $checked }} class="day-checkbox"
                                                                data-date="{{ $tanggalLengkap }}" disabled>
                                                        </td>
                                                    @endfor

                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                    @endif
                </div>
            </div>
        </div>



    </div>
    </div>


    <script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>
    {{-- Script untuk mengatur tampilan dan filtering teammate --}}

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const checkboxInsert = document.getElementById('enableInsert');
            const insertSection = document.getElementById('insertDataSection');
            const enableTeammate = document.getElementById('enableTeammate');
            const teammateSelect = document.getElementById('teammate');
            const picSelect = document.getElementById('pic');

            // Data seluruh employee
            const allEmployees = @json($getEmployeeMaintenance);

            // Tampilkan/hilangkan insert section
            checkboxInsert.addEventListener('change', function() {
                insertSection.style.display = this.checked ? 'block' : 'none';
            });

            // Aktifkan/nonaktifkan select teammate
            enableTeammate.addEventListener('change', function() {
                teammateSelect.disabled = !this.checked;
                if (this.checked) {
                    updateTeammateOptions();
                } else {
                    teammateSelect.innerHTML = '<option value="">-- Pilih Teammate --</option>';
                }
            });

            // Update options saat PIC berubah
            picSelect.addEventListener('change', function() {
                if (enableTeammate.checked) {
                    updateTeammateOptions();
                }
            });

            function updateTeammateOptions() {
                const selectedPIC = picSelect.value;
                teammateSelect.innerHTML = '<option value="">-- Pilih Teammate --</option>';

                allEmployees.forEach(emp => {
                    if (emp.EmployeeName !== selectedPIC) {
                        const option = document.createElement('option');
                        option.value = emp.EmployeeName;
                        option.textContent = emp.EmployeeName;
                        teammateSelect.appendChild(option);
                    }
                });
            }

            const enableInsert = document.getElementById('enableInsert');

            enableInsert.addEventListener('change', function() {
                const today = new Date().toISOString().split('T')[0]; // "YYYY-MM-DD"
                const allCheckboxes = document.querySelectorAll('.day-checkbox');

                allCheckboxes.forEach(cb => {
                    const cbDate = cb.dataset.date;

                    if (cbDate >= today) {
                        cb.disabled = !enableInsert.checked; // Bisa enable/disable sesuai toggle
                    } else {
                        cb.disabled = true; // Permanen disable kalau tanggal sudah lewat
                    }
                });
            });


        });

        // document.getElementById('myFormInsert').addEventListener('submit', function(e) {
        //     const assignmentData = [];
        //     const year = document.getElementById('tahun').value;
        //     const month = document.getElementById('bulan').value;

        //     const rows = document.querySelectorAll('#filterTable tbody tr');

        //     rows.forEach(row => {
        //         const unitNo = row.children[0].innerText.trim();
        //         const checkboxes = row.querySelectorAll('input.day-checkbox');

        //         checkboxes.forEach(cb => {
        //             const tanggal = cb.dataset.date; // yyyy-MM-dd
        //             const status = cb.checked ? 1 : 0;
        //             assignmentData.push(`${unitNo}_${tanggal}_${status}`);
        //         });
        //     });

        //     // Gabungkan dengan `;` dan simpan ke input hidden
        //     document.getElementById('assignment_data').value = assignmentData.join(';') + ';';
        //     console.log("Assignment Data: ", assignmentData);
        //     console.log("Gabungan: ", assignmentData.join(';') + ';');


        // });
    </script>
    <script>
        // Simpan nilai saat form disubmit
        document.getElementById('myFormShow').addEventListener('submit', function() {
            localStorage.setItem('unit_type', document.getElementById('unit_type').value);
            localStorage.setItem('bulan', document.getElementById('bulan').value);
            localStorage.setItem('tahun', document.getElementById('tahun').value);
        });

        // Saat halaman diload, kembalikan nilai dari localStorage
        window.addEventListener('DOMContentLoaded', function() {
            const unitType = localStorage.getItem('unit_type');
            const bulan = localStorage.getItem('bulan');
            const tahun = localStorage.getItem('tahun');

            if (unitType) document.getElementById('unit_type').value = unitType;
            if (bulan) document.getElementById('bulan').value = bulan;
            if (tahun) document.getElementById('tahun').value = tahun;
        });
    </script>


    <script>
        document.getElementById('btnSaveInsert').addEventListener('click', function(e) {
            e.preventDefault();

            const assignmentData = [];
            const year = document.getElementById('tahun').value;
            const month = document.getElementById('bulan').value;
            const form = document.getElementById('myFormInsert');

            const rows = document.querySelectorAll('#filterTable tbody tr');
            rows.forEach(row => {
                const unitNo = row.children[0].innerText.trim();
                const checkboxes = row.querySelectorAll('input.day-checkbox');
                checkboxes.forEach(cb => {
                    const tanggal = cb.dataset.date;
                    const status = cb.checked ? 1 : 0;
                    assignmentData.push(`${unitNo}_${tanggal}_${status}`);
                });
            });

            const datastring = assignmentData.join(';') + ';';

            // Buat objek FormData dan append semua data form + assignment_data
            const formData = new FormData(form);
            formData.set('assignment_data', datastring);

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    },
                    body: formData
                })
                .then(response => response.text())
                .then(responseHtml => {
                    // Jika kamu redirect/return view, tampilkan di modal atau alert
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Data berhasil disimpan!',
                    });

                    // Optional: Reset form, reload data table, dsb.
                    // form.reset();
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat menyimpan data.',
                    });
                });
        });
    </script>


    <script>
        $(document).ready(function() {
            let table = $('#filterTable').DataTable({
                scrollX: true,
                scrollCollapse: true,
                paging: true,
                searching: true,
                info: true,
                fixedHeader: true, // Header tetap di atas
                fixedColumns: {
                    leftColumns: 1 // Kolom pertama tetap beku
                },
                columnDefs: [{
                    targets: 0,
                    className: 'dt-left freeze-column'
                }],
                order: [],
                // dom: 'lBfrtip',
                // buttons: [{
                //         extend: 'copy',
                //         text: 'Copy',
                //         className: 'btn btn-success'
                //     },
                //     {
                //         extend: 'csv',
                //         text: 'CSV',
                //         className: 'btn btn-success'
                //     },
                //     {
                //         extend: 'excel',
                //         text: 'Excel',
                //         className: 'btn btn-success'
                //     },

                //     {
                //         extend: 'print',
                //         text: 'Print',
                //         className: 'btn btn-success'
                //     }
                // ],
                language: {
                    search: "Filter data:",
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

            // Pastikan header juga memiliki freeze column
            $('.dataTables_scrollHeadInner table thead th:first-child').addClass('freeze-header');

        });
    </script>

    <style>
        /* Header tetap di atas */
        #filterTable_wrapper .dataTables_scrollHead {
            position: sticky;
            top: 0;
            background: white;
            z-index: 1000;
        }

        /* Kolom pertama di dalam tbody tetap freeze */
        #filterTable_wrapper .dataTables_scrollBody .freeze-column {
            position: sticky !important;
            left: 0 !important;
            background: white;
            z-index: 1000;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        /* Kolom pertama di dalam thead juga harus freeze */
        #filterTable_wrapper .dataTables_scrollHeadInner table thead th:first-child {
            position: sticky !important;
            left: 0 !important;
            background: white;
            z-index: 1050;
            /* Lebih tinggi agar tidak tertutup */
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        #filterTable tbody tr:hover td {
            background-color: rgba(0, 123, 255, 0.2);
            /* Warna biru transparan saat hover */
            cursor: pointer;
        }
    </style>

@endsection
