@extends('admin.dashboard')

@section('admin')
    <div id="root"></div>

    @viteReactRefresh
    @vite(['resources/js/App.tsx', 'resources/css/app.css'])
@endsection
