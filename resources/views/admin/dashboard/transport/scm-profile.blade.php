@extends('admin.dashboard')
@section('admin')

<div class="container-fluid" style="padding-top: 80px;"  {{-- tambah jarak dari header --}}>
    <h4 class="mb-3">SCM Transport Profile</h4>
    {{-- Dropdown dan Tombol --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <label for="facilitySelect" class="form-label">Select Facility</label>
            <select id="facilitySelect" class="form-select">
                <option value="">-- Select Facility --</option>
                @foreach($facilities as $f)
                    <option value="{{ $f->Facility }}">{{ $f->Facility }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button id="btnLoad" class="btn btn-primary w-100">
                Load Data
            </button>
        </div>
    </div>
    {{-- Area hasil / konten nanti --}}
    <div id="resultArea">
        <p class="text-muted">Silakan pilih facility lalu tekan tombol Load Data.</p>
    </div>
</div>

@endsection
