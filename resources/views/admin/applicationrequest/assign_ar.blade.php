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
                                    ASSIGN AR {{ $facility['Name'] }}
                                </h4>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            {{-- Data Parameter --}}
            <div class="row">
                <div class="card">
                    <div class="card-body">


                    </div>

                </div>

            </div>

        </div>
    </div>


    <script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>

    <script></script>



    <style>

    </style>
@endsection
