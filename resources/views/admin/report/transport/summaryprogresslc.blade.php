@extends('admin.dashboard')
@section('admin')
<div class="page-content">
    <h1 class="mt-4">Summary Progress LC - {{ $Name }} </h1>
    <form method="POST" action="{{ route('transport.summary-progress-lc.show') }}">
        @csrf
        <div class="row mb-3">
            <!-- Dropdown View Type -->
            <div class="col-md-3">
                <label for="viewtype" class="form-label">View Type</label>
                <select name="viewtype" id="viewtype" class="form-select">
                    <option value="CBMINF" {{ request('viewtype') == 'CBMINF' ? 'selected' : '' }}>CBM</option>
                    <option value="CaseID" {{ request('viewtype') == 'CaseID' ? 'selected' : '' }}>Case ID</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date', now()->toDateString()) }}">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date', now()->toDateString()) }}">
            </div>
            
            <div class="col-md-3">
                <label for="owner" class="form-label">Owner</label>
                <select id="owner-dropdown" name="owner" class="form-select">
                    <option value="">Select Owner</option>
                    @if (!empty($ownersList))
                        @foreach ($ownersList as $ownerOption)
                            <option value="{{ $ownerOption->OWNER }}" 
                                {{ request('owner') == $ownerOption->OWNER ? 'selected' : '' }}>
                                {{ $ownerOption->OWNER }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <!-- Submit Button -->
            <div class="col-md-1 d-flex align-items-end mt-3">
                <button type="submit" class="btn btn-info w-100">Show</button>
            </div>
        </div>
    </form>

   <!-- Tabel Data -->
   @if (!empty($dataGrid))
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    @foreach (array_keys((array) $dataGrid[0]) as $column)
                        <th>{{ strtoupper(str_replace('_', ' ', $column)) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($dataGrid as $row)
                    <tr>
                        @foreach ((array) $row as $value)
                        <td class="{{ is_numeric($value) ? 'text-end' : '' }}">
                                {{ $value ?? '-' }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <p>No data Available</p>
@endif

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let form = document.querySelector("form");
        let ownerDropdown = document.getElementById("owner-dropdown");

        form.addEventListener("submit", function(e) {
            if (!ownerDropdown.value) {
                e.preventDefault(); 

                toastr.warning("Pilih Owner Terlebih Dahulu!", "Warning", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                });
            }
        });
    });
</script>
@endsection
