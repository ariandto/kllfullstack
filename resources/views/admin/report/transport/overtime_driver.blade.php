@extends('admin.dashboard')

@section('admin')
<!-- Remix icons CDN -->
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

<style>
  /* --- Layout compatibility with admin dashboard (sidebar & topbar) --- */
  .page-content {
    padding: 1.25rem; /* default padding */
    margin-top: 70px; /* ensure below topbar (adjust if topbar height different) */
    transition: margin-left .12s ease;
  }
  @media (min-width: 992px) {
    /* assume sidebar width 250px on desktop */
    .page-content { margin-left: 250px; }
  }

  /* --- Modern card/table styling (Bootstrap-friendly) --- */
  .card-modern { border-radius: .8rem; box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06); border: 0; }
  .card-header-modern { background: transparent; border-bottom: none; padding-bottom: .5rem; }
  .icon-circle { width:44px;height:44px;border-radius:10px;display:inline-flex;align-items:center;justify-content:center; }
  .bg-icon-primary { background-color: rgba(13,110,253,0.08); color:#0d6efd; }
  .bg-icon-success { background-color: rgba(25,135,84,0.08); color:#198754; }
  .bg-icon-warning { background-color: rgba(255,193,7,0.08); color:#ffc107; }
  .bg-icon-info { background-color: rgba(13,202,240,0.08); color:#0dcaf0; }

  /* total overtime card */
  .total-overtime-card { border-radius: 12px; padding: 1.25rem; color: #fff; background: linear-gradient(135deg,#3b82f6 0%,#06b6d4 100%); box-shadow: 0 10px 30px rgba(59,130,246,0.15); }

  /* badge tweaks */
  .badge-pill { border-radius: 999px; padding:.35rem .6rem; font-size:.8rem; }

  /* responsive: desktop table / mobile cards */
  @media (max-width: 767.98px) {
    .desktop-table-view { display: none !important; }
    .mobile-card-view { display: block !important; }
  }
  @media (min-width: 768px) {
    .desktop-table-view { display: block !important; }
    .mobile-card-view { display: none !important; }
  }

  /* small helpers */
  .muted-sm { color: #6c757d; font-size:.9rem; }
  .text-muted-sm { color: #6c757d; font-size:.85rem; }
  .overtime-card { border-radius: .6rem; border: 1px solid #eef2f7; padding: .9rem; background: #fff; }
  .overtime-card .title { font-weight:600; font-size:1rem; color:#1f2937; }

  /* loading spinner */
  .loading-spinner {
    width: 1.05rem; height: 1.05rem;
    border: 2px solid rgba(255,255,255,0.9);
    border-top-color: rgba(255,255,255,0.25);
    border-radius: 50%;
    -webkit-animation: spin .7s linear infinite;
            animation: spin .7s linear infinite;
    display:inline-block; margin-right:.5rem;
  }
  @-webkit-keyframes spin { 0%{ -webkit-transform: rotate(0deg);} 100%{-webkit-transform: rotate(360deg);} }
  @keyframes spin { 0%{ transform: rotate(0deg);} 100%{ transform: rotate(360deg);} }

  /* table improvements */
  .table thead th { border-bottom: 1px solid #eef2f7; color:#475569; font-weight:600; vertical-align:middle; }
  .table td, .table th { vertical-align: middle; }
</style>

<div class="content mb-5">
  <div class="container-fluid mt-5 mb-5">

    <!-- Page Header -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="d-flex align-items-start justify-content-between">
          <div class="d-flex align-items-center gap-3">
            <div class="icon-circle bg-white shadow-sm">
              <i class="ri-time-line fs-4 text-primary"></i>
            </div>
            <div>
              <h4 class="mb-0">Data Lembur Driver</h4>
              <div class="muted-sm">Ringkasan dan riwayat lembur</div>
            </div>
          </div>
          <!-- right area (optional actions) -->
          <div class="d-none d-md-flex align-items-center gap-2">
            {{-- tempat action button jika perlu --}}
          </div>
        </div>
      </div>
    </div>

    <!-- Driver info + alert -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="card card-modern p-3">
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div class="d-flex gap-3 align-items-center mb-3 mb-md-0">
              <div class="icon-circle bg-icon-info">
                <i class="ri-user-line fs-5"></i>
              </div>
              <div>
                <div class="text-muted-sm">Informasi Driver</div>
                <div class="fw-semibold">{{ Auth::guard('admin')->user()->name ?? '-' }}</div>
                <div class="text-muted-sm"><strong>NIK:</strong> {{ $nik ?? '-' }}</div>
              </div>
            </div>

            <div class="alert alert-info mb-0 d-flex align-items-center gap-2 py-2 px-3">
              <i class="ri-information-line"></i>
              <div class="mb-0 small">Maksimal periode pencarian: <strong>1 bulan</strong></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Total Overtime Summary + Filter -->
    @php
      $totalJamLembur = 0;
      foreach($dataLembur as $item){
        if($item->Jam_Lembur_Roster_Out && $item->Jam_Selesai_Lembur){
          try {
            $start = Carbon\Carbon::parse($item->Jam_Lembur_Roster_Out);
            $end = Carbon\Carbon::parse($item->Jam_Selesai_Lembur);
            $diffInMinutes = $start->diffInMinutes($end);
            $durasiJam = round($diffInMinutes / 60, 1);
            $totalJamLembur += $durasiJam;
          } catch(\Exception $e) { continue; }
        }
      }
      $totalJamBulat = round($totalJamLembur);
      $totalJamDesimal = number_format($totalJamLembur,1);
    @endphp

    <div class="row mb-4">
      <div class="col-12 col-md-6 mb-3 mb-md-0">
        <div class="total-overtime-card">
          <div class="d-flex align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
              <div class="icon-circle bg-white/10">
                <i class="ri-time-line fs-3"></i>
              </div>
              <div>
                <div class="h4 mb-0">{{ $totalJamBulat }} Jam</div>
                <div class="muted-sm">Total Jam Lembur</div>
                @if($totalJamBulat != $totalJamLembur)
                  <div class="small opacity-90 mt-1">Detail: {{ $totalJamDesimal }} Jam</div>
                @endif
              </div>
            </div>
            <div class="text-end text-white-50 d-none d-sm-block">
              <div class="small"><i class="ri-calendar-event-line"></i> Periode</div>
              <div class="fw-medium">{{ $defaultStartDate }} s/d {{ $defaultEndDate }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Filter Form -->
      <div class="col-12 col-md-6">
        <div class="card card-modern p-3 h-100">
          <div class="card-header-modern d-flex align-items-center gap-2 mb-2">
            <div class="icon-circle bg-icon-warning">
              <i class="ri-filter-line"></i>
            </div>
            <div>
              <div class="fw-semibold">Filter Data Lembur</div>
              <div class="text-muted-sm">Pilih tanggal untuk menampilkan data</div>
            </div>
          </div>
          <form id="filterForm" method="GET" action="#" class="row g-2 align-items-end">
            <div class="col-12 col-sm-5">
              <label class="form-label small mb-1">Tanggal Mulai</label>
              <input type="date" name="start_date_lembur" class="form-control form-control-sm" value="{{ $defaultStartDate }}">
            </div>
            <div class="col-12 col-sm-5">
              <label class="form-label small mb-1">Tanggal Selesai</label>
              <input type="date" name="end_date_lembur" class="form-control form-control-sm" value="{{ $defaultEndDate }}">
            </div>
            <div class="col-12 col-sm-2 d-flex gap-2">
              <button id="btnTampilkan" type="submit" class="btn btn-primary btn-sm w-100 d-flex align-items-center justify-content-center">
                <span id="btnSpinner" class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                <i class="ri-search-line me-1"></i> <span class="d-none d-sm-inline">Tampilkan</span>
              </button>
              <a href="{{ route('driver.overtime') }}" class="btn btn-outline-secondary btn-sm d-none d-sm-inline">Reset</a>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Desktop Table -->
    <div class="row desktop-table-view mb-4">
      <div class="col-12">
        <div class="card card-modern">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="d-flex align-items-center gap-2">
                <div class="icon-circle bg-icon-success"><i class="ri-table-line"></i></div>
                <h5 class="mb-0">Data Lembur</h5>
              </div>
              <div class="text-muted-sm">Total: {{ count($dataLembur) }}</div>
            </div>

            <div class="table-responsive">
              <table class="table align-middle table-hover">
                <thead class="table-light">
                  <tr>
                    <th>Tanggal</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th class="text-center">Durasi (Jam)</th>
                    <th>Keterangan</th>
                    <th>Facility</th>
                    <th>Jobdesc</th>
                    <th>Sts. People Pro</th>
                    <th>Sts. Appr. MGR</th>
                    <th>Disetujui Oleh</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($dataLembur as $item)
                    @php
                      $durasi = 0; $durasiDetail = '-'; $durasiBulat = 0;
                      if ($item->Jam_Lembur_Roster_Out && $item->Jam_Selesai_Lembur) {
                        try {
                          $start = Carbon\Carbon::parse($item->Jam_Lembur_Roster_Out);
                          $end = Carbon\Carbon::parse($item->Jam_Selesai_Lembur);
                          $diffInMinutes = $start->diffInMinutes($end);
                          $hours = floor($diffInMinutes / 60);
                          $minutes = $diffInMinutes % 60;
                          $durasi = round($diffInMinutes / 60, 1);
                          $durasiBulat = round($durasi);
                          if ($hours > 0 && $minutes > 0) $durasiDetail = "{$hours} jam {$minutes} menit";
                          elseif ($hours > 0) $durasiDetail = "{$hours} jam";
                          else $durasiDetail = "{$minutes} menit";
                        } catch(\Exception $e) { $durasi = 0; $durasiBulat = 0; $durasiDetail = '-'; }
                      }
                    @endphp
                    <tr>
                      <td><i class="ri-calendar-line text-muted me-1"></i> {{ $item->Tanggal ?? '-' }}</td>
                      <td><i class="ri-play-circle-line text-muted me-1"></i> {{ $item->Jam_Lembur_Roster_Out ?? '-' }}</td>
                      <td><i class="ri-stop-circle-line text-muted me-1"></i> {{ $item->Jam_Selesai_Lembur ?? '-' }}</td>
                      <td class="text-center">
                        <span class="badge bg-primary badge-pill"><i class="ri-timer-line me-1"></i> {{ $durasiBulat }} Jam</span>
                        @if($durasiBulat != $durasi)
                          <div class="text-muted-sm mt-1">{{ $durasi }} jam</div>
                        @endif
                      </td>
                      <td>{{ $item->Keterangan_Lembur ?? '-' }}</td>
                      <td>{{ $item->Facility ?? '-' }}</td>
                      <td>{{ $item->Jobdesc_Lembur ?? '-' }}</td>
                      <td>{{ $item->Approval_By_System ?? '-' }}</td>
                      <td>
                        @if($item->Status_Approval === 'Disetujui')
                          <span class="badge bg-success badge-pill"><i class="ri-checkbox-circle-line me-1"></i> Disetujui</span>
                        @elseif($item->Status_Approval === 'Menunggu')
                          <span class="badge bg-warning text-dark badge-pill"><i class="ri-time-line me-1"></i> Menunggu</span>
                        @else
                          <span class="badge bg-secondary badge-pill"><i class="ri-question-line me-1"></i> {{ $item->Status_Approval ?? 'N/A' }}</span>
                        @endif
                      </td>
                      <td>
                        <div>{{ $item->Approved_By_Name ?? '-' }}</div>
                        @if($item->Approve_Time)
                          <div class="text-muted-sm"><i class="ri-time-line me-1"></i> {{ $item->Approve_Time }}</div>
                        @endif
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="10" class="text-center text-muted py-5">
                        <i class="ri-inbox-line fs-2 mb-2 d-block"></i>
                        Tidak ada data lembur
                      </td>
                    </tr>
                  @endforelse
                </tbody>

                @if(count($dataLembur) > 0)
                  <tfoot class="table-light">
                    <tr>
                      <td colspan="3" class="text-end"><strong><i class="ri-file-list-3-line me-1"></i> Total Jam Lembur:</strong></td>
                      <td class="text-center"><strong class="text-primary"><i class="ri-time-line me-1"></i> {{ $totalJamBulat }} Jam</strong>
                        @if($totalJamBulat != $totalJamLembur)
                          <div class="text-muted-sm">{{ $totalJamDesimal }} jam</div>
                        @endif
                      </td>
                      <td colspan="6"></td>
                    </tr>
                  </tfoot>
                @endif

              </table>
            </div>

          </div>
        </div>
      </div>
    </div>

    <!-- Mobile Cards -->
    <div class="row mobile-card-view">
      <div class="col-12">
        <div class="card card-modern p-3">
          <div class="d-flex align-items-center gap-2 mb-3">
            <div class="icon-circle bg-icon-warning"><i class="ri-smartphone-line"></i></div>
            <h5 class="mb-0">Data Lembur</h5>
          </div>

          @forelse($dataLembur as $item)
            @php
              $durasi = 0; $durasiDetail = '-'; $durasiBulat = 0;
              if ($item->Jam_Lembur_Roster_Out && $item->Jam_Selesai_Lembur) {
                try {
                  $start = Carbon\Carbon::parse($item->Jam_Lembur_Roster_Out);
                  $end = Carbon\Carbon::parse($item->Jam_Selesai_Lembur);
                  $diffInMinutes = $start->diffInMinutes($end);
                  $hours = floor($diffInMinutes / 60);
                  $minutes = $diffInMinutes % 60;
                  $durasi = round($diffInMinutes / 60, 1);
                  $durasiBulat = round($durasi);
                  if ($hours > 0 && $minutes > 0) $durasiDetail = "{$hours} jam {$minutes} menit";
                  elseif ($hours > 0) $durasiDetail = "{$hours} jam";
                  else $durasiDetail = "{$minutes} menit";
                } catch(\Exception $e) { $durasi = 0; $durasiBulat = 0; $durasiDetail = '-'; }
              }
            @endphp

            <article class="overtime-card mb-3">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="title"><i class="ri-calendar-line me-1 text-muted"></i> {{ $item->Tanggal ?? '-' }}</div>
                  <div class="text-muted-sm">{{ $item->Jam_Lembur_Roster_Out ?? '-' }} &nbsp; • &nbsp; {{ $item->Jam_Selesai_Lembur ?? '-' }}</div>
                </div>
                <div class="text-end">
                  <div><span class="badge bg-primary badge-pill"><i class="ri-timer-line me-1"></i> {{ $durasiBulat }} Jam</span></div>
                  <div class="mt-2">
                    @if($item->Status_Approval === 'Disetujui')
                      <span class="badge bg-success badge-pill"><i class="ri-checkbox-circle-line me-1"></i> Disetujui</span>
                    @elseif($item->Status_Approval === 'Menunggu')
                      <span class="badge bg-warning text-dark badge-pill"><i class="ri-time-line me-1"></i> Menunggu</span>
                    @else
                      <span class="badge bg-secondary badge-pill"><i class="ri-question-line me-1"></i> {{ $item->Status_Approval ?? 'N/A' }}</span>
                    @endif
                  </div>
                </div>
              </div>

              @if($durasiBulat != $durasi)
                <div class="mt-2 text-muted-sm"><i class="ri-bar-chart-line me-1"></i> {{ $durasiDetail }} ({{ $durasi }} jam)</div>
              @endif

              <div class="mt-2 text-sm text-muted">
                <div><i class="ri-building-line me-1"></i> <strong>Facility:</strong> {{ $item->Facility ?? '-' }}</div>
                <div class="mt-1"><i class="ri-task-line me-1"></i> <strong>Jobdesc:</strong> {{ $item->Jobdesc_Lembur ?? '-' }}</div>
                <!-- <td>{{ $item->Approval_By_System ?? '-' }}</td> -->
                 <div class="mt-1"><i class="ri-task-line me-1"></i> <strong>Sts.People Pro</strong> {{ $item->Approval_By_System ?? '-' }}</div>
                <div class="mt-1"><i class="ri-file-text-line me-1"></i> <strong>Keterangan:</strong> {{ $item->Keterangan_Lembur ?? '-' }}</div>
                <div class="mt-1"><i class="ri-user-star-line me-1"></i> <strong>Disetujui Oleh:</strong> {{ $item->Approved_By_Name ?? '-' }}
                  @if($item->Approve_Time) <div class="text-muted-sm mt-1"><i class="ri-time-line me-1"></i> {{ $item->Approve_Time }}</div> @endif
                </div>
              </div>
            </article>

          @empty
            <div class="text-center text-muted py-5">
              <i class="ri-inbox-line fs-2 mb-2 d-block"></i>
              Tidak ada data lembur
            </div>
          @endforelse

          @if(count($dataLembur) > 0)
            <div class="border rounded p-3 bg-light mt-3">
              <div class="text-center">
                <div class="small text-muted">Total Jam Lembur</div>
                <div class="h5 mb-0 text-primary"><i class="ri-time-line me-1"></i> {{ $totalJamBulat }} Jam</div>
                @if($totalJamBulat != $totalJamLembur) <div class="small text-muted mt-1">Detail: {{ $totalJamDesimal }} jam</div> @endif
                <div class="small text-muted mt-1"><i class="ri-calendar-event-line me-1"></i> Periode: {{ $defaultStartDate }} s/d {{ $defaultEndDate }}</div>
              </div>
            </div>
          @endif

        </div>
      </div>
    </div>

  </div> {{-- container-fluid --}}
</div> {{-- page-content --}}

<!-- Scripts: spinner + icon fallback -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Spinner behavior
    const form = document.getElementById('filterForm');
    const btn = document.getElementById('btnTampilkan');
    const spinner = document.getElementById('btnSpinner');

    if(form && btn){
      form.addEventListener('submit', function(){
        if(spinner) spinner.classList.remove('d-none');
        btn.classList.add('disabled');
        // replace inner text for small screens
        // keep default submit behavior (page reload)
      });
    }

    // Remix icons fallback if not loaded
    const icons = document.querySelectorAll('i[class^="ri-"]');
    icons.forEach(icon => {
      const style = window.getComputedStyle(icon, '::before');
      const content = style && style.content;
      if(!content || content === 'none' || content === '""') {
        const cls = icon.className;
        let fb = '';
        if (cls.includes('ri-time-line')) fb='⏰';
        else if (cls.includes('ri-user-line')) fb='👤';
        else if (cls.includes('ri-filter-line')) fb='🔍';
        else if (cls.includes('ri-table-line')) fb='📊';
        else if (cls.includes('ri-smartphone-line')) fb='📱';
        else if (cls.includes('ri-calendar-line')) fb='';
        else if (cls.includes('ri-play-circle-line')) fb='▶️';
        else if (cls.includes('ri-stop-circle-line')) fb='⏹️';
        else if (cls.includes('ri-timer-line')) fb='⏱️';
        else if (cls.includes('ri-file-text-line')) fb='📄';
        else if (cls.includes('ri-building-line')) fb='🏢';
        else if (cls.includes('ri-task-line')) fb='✅';
        else if (cls.includes('ri-user-star-line')) fb='👤⭐';
        else if (cls.includes('ri-checkbox-circle-line')) fb='✅';
        else if (cls.includes('ri-question-line')) fb='❓';
        else if (cls.includes('ri-inbox-line')) fb='📥';
        if(fb){ icon.textContent = fb + ' '; icon.style.fontStyle='normal'; }
      }
    });
  });
</script>

@endsection
