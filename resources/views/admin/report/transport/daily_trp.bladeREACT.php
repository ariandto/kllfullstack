@extends('admin.dashboard')
@section('title', 'Daily Report Transport')
@section('admin')

<div class="page-content">
  <!-- React akan render di div ini -->
  <div id="react-root"></div>
</div>

@viteReactRefresh
@vite('resources/js/App.tsx')

@endsection
