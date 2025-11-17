@extends('admin.dashboard')
@section('admin')

<div class="card">
    <div class="card-header">
        <h4>Monitoring Driver Report</h4>
    </div>
    <div class="card-body">
        
        {{-- Filter Form --}}
        <form method="GET" action="{{ route('admin.report.transport.monitoring-driver') }}" class="row g-3 mb-3">
            <div class="col-md-3">
                <label class="form-label">Site</label>
                <input type="text" name="site" value="{{ old('site', $site) }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Facility</label>
                <input type="text" name="facility" value="{{ old('facility', $facility) }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Date</label>
                <input type="date" name="date" value="{{ old('date', $date) }}" class="form-control">
            </div>
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>

        {{-- Data Table --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        @if(!empty($data) && count($data) > 0)
                            @foreach(array_keys((array)$data[0]) as $col)
                                <th>{{ $col }}</th>
                            @endforeach
                        @else
                            <th>No columns</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                        <tr>
                            @foreach((array)$row as $val)
                                <td>{{ $val }}</td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ !empty($data) ? count((array)$data[0]) : 1 }}" class="text-center">
                                No data found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection
