@extends('admin.dashboard')
@section('admin')
    <div class="page-content">
        <div class="container-fluid">
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

                            <form id="dashboardform" method="POST" action="{{ route('admin.export.checklist5rpdf.submit') }}">
                                @csrf

                                <div class="row">
                                    @php
                                        $selectedOwners = session(
                                            'input_datar5r_' .
                                                Auth::guard('admin')->id() .
                                                '_' .
                                                session('facility_info')[0]['Relasi'] .
                                                '.selected_owners',
                                            [],
                                        );
                                        if (is_string($selectedOwners)) {
                                            $selectedOwners = explode(';', $selectedOwners);
                                        }
                                    @endphp

                                    <input type="hidden" id="selected_owners" name="selected_owners"
                                        value="{{ implode(';', $selectedOwners) }}">

                                    <!-- Start Date -->
                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" class="form-control" id="startDate" name="start_date" required
                                            value="{{ old('start_date', session('input_datar5r_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.start_date')) }}">
                                    </div>

                                    <!-- Owner Button -->
                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <label class="form-label d-block">Owner</label>
                                        <div class="position-relative">
                                            <button type="button" class="btn btn-light w-100"
                                                onclick="toggleDropdown(event, 'owner-checkbox-list')"
                                                id="dropdown-owner-button">
                                                Owners
                                            </button>
                                            <div id="owner-checkbox-list" class="dropdown-menu"
                                                style="display: none; position: absolute; top: 100%; left: 0; width: 100%; z-index: 1000;">
                                                <div style="max-height: 100px; overflow-y: auto;">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="check_all">
                                                        <label class="form-check-label" for="check_all">All</label>
                                                    </div>
                                                    @if (session()->has('dataowner5rr_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi']))
                                                        @foreach (session('dataowner5rr_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi']) as $owner)
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
                                                </div>
                                                <div class="d-flex mt-2" style="gap: 5px;">
                                                    <button type="button" class="btn btn-primary" style="flex: 1;"
                                                        onclick="closeCheckboxList()">OK</button>
                                                    <button type="button" class="btn btn-danger" style="flex: 1;"
                                                        onclick="clearCheckboxes()">Clear</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <label class="form-label">Dept Name</label>
                                        <select class="form-select" id="dept5r" name="dept5r" required>
                                            {{-- <option value="All"
                                                {{ session('input_datad5r_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.dept5r') == 'All' ? 'selected' : (old('dept5r') == 'All' ? 'selected' : '') }}>
                                                All</option> --}}
                                            @if (isset($datedept5r))
                                                @foreach ($datedept5r as $deptname)
                                                    <option value="{{ $deptname->Departement }}"
                                                        {{ session('input_datad5r_' . Auth::guard('admin')->id() . '_' . session('facility_info')[0]['Relasi'] . '.dept5r') == $deptname->Departement ? 'selected' : (old('dept5r') == $deptname->Departement ? 'selected' : '') }}>
                                                        {{ $deptname->Departement }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option disabled>No data sources available</option>
                                            @endif
                                        </select>
                                    </div>



                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                        <button type="submit" class="btn btn-info w-100">Print</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Load the custom script -->
    <script src="{{ asset('backend/assets/js/owner-dropdown.js') }}"></script>

@endsection
