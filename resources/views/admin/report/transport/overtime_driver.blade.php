@extends('admin.dashboard')

@section('admin')
  <!-- Remix icons CDN -->
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

  <style>
    /* --- Layout compatibility with admin dashboard (sidebar & topbar) --- */
    .page-content {
      padding: 1.25rem;
      margin-top: 70px;
      transition: margin-left .12s ease;
      background-color: #f8f9fa;
    }

    @media (min-width: 992px) {
      .page-content {
        margin-left: 250px;
      }
    }

    /* --- Modern card/table styling (Bootstrap-friendly) --- */
    .card-modern {
      border-radius: .8rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      border: 0;
      background: #fff;
    }

    .card-header-modern {
      background: transparent;
      border-bottom: none;
      padding-bottom: .5rem;
    }

    .icon-circle {
      width: 44px;
      height: 44px;
      border-radius: 10px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .bg-icon-primary {
      background-color: rgba(13, 110, 253, 0.1);
      color: #0d6efd;
    }

    .bg-icon-success {
      background-color: rgba(25, 135, 84, 0.1);
      color: #198754;
    }

    .bg-icon-warning {
      background-color: rgba(255, 193, 7, 0.1);
      color: #ffc107;
    }

    .bg-icon-info {
      background-color: rgba(13, 202, 240, 0.1);
      color: #0dcaf0;
    }

    /* total overtime card */
    .total-overtime-card {
      border-radius: 12px;
      padding: 1.5rem;
      color: #fff;
      background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
      box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
      height: 100%;
    }

    /* badge tweaks */
    .badge-pill {
      border-radius: 999px;
      padding: .35rem .7rem;
      font-size: .8rem;
      font-weight: 500;
    }

    /* responsive: desktop table / mobile cards */
    @media (max-width: 767.98px) {
      .desktop-table-view {
        display: none !important;
      }

      .mobile-card-view {
        display: block !important;
      }
    }

    @media (min-width: 768px) {
      .desktop-table-view {
        display: block !important;
      }

      .mobile-card-view {
        display: none !important;
      }
    }

    /* small helpers */
    .muted-sm {
      color: #6c757d;
      font-size: .9rem;
    }

    .text-muted-sm {
      color: #6c757d;
      font-size: .85rem;
    }

    .overtime-card {
      border-radius: .6rem;
      border: 1px solid #e9ecef;
      padding: 1rem;
      background: #fff;
      transition: all 0.2s ease;
    }

    .overtime-card:hover {
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      border-color: #dee2e6;
    }

    .overtime-card .title {
      font-weight: 600;
      font-size: 1rem;
      color: #1f2937;
    }

    /* loading spinner */
    .loading-spinner {
      width: 1.05rem;
      height: 1.05rem;
      border: 2px solid rgba(255, 255, 255, 0.9);
      border-top-color: rgba(255, 255, 255, 0.25);
      border-radius: 50%;
      animation: spin .7s linear infinite;
      display: inline-block;
      margin-right: .5rem;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(360deg);
      }
    }

    /* table improvements */
    .table thead th {
      border-bottom: 2px solid #e9ecef;
      color: #495057;
      font-weight: 600;
      vertical-align: middle;
      background-color: #f8f9fa;
    }

    .table td,
    .table th {
      vertical-align: middle;
      padding: 0.85rem;
    }

    .table-hover tbody tr:hover {
      background-color: #f8f9fa;
    }

    /* Alert styling */
    .alert-info {
      background-color: rgba(13, 202, 240, 0.1);
      border: 1px solid rgba(13, 202, 240, 0.2);
      color: #055160;
    }

    /* Button improvements */
    .btn-primary {
      background-color: #0d6efd;
      border-color: #0d6efd;
    }

    .btn-primary:hover {
      background-color: #0b5ed7;
      border-color: #0a58ca;
    }

    .btn-outline-secondary:hover {
      background-color: #6c757d;
      border-color: #6c757d;
      color: #fff;
    }

    /* Form improvements */
    .form-control:focus,
    .form-control-sm:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    /* Mobile optimizations */
    @media (max-width: 575.98px) {
      .page-content {
        padding: 0.75rem;
      }

      .icon-circle {
        width: 38px;
        height: 38px;
      }

      .total-overtime-card {
        padding: 1rem;
      }

      h4 {
        font-size: 1.25rem;
      }
    }

    /* Durasi badge */
    .durasi-badge {
      background-color: #e7f3ff;
      color: #0d6efd;
      padding: 0.4rem 0.8rem;
      border-radius: 6px;
      font-weight: 600;
      display: inline-block;
    }
  </style>

  <div class="page-content mb-5">
    <div class="container-fluid mt-3 mb-5">

      <!-- Page Header -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
              <div class="icon-circle bg-white shadow-sm">
                <i class="ri-time-line fs-4 text-primary"></i>
              </div>
              <div>
                <h4 class="mb-1">Data Lembur Driver</h4>
                <div class="muted-sm">Ringkasan dan riwayat lembur</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Driver info + alert -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card card-modern p-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
              <div class="d-flex gap-3 align-items-center">
                <div class="icon-circle bg-icon-info">
                  <i class="ri-user-line fs-5"></i>
                </div>
                <div>
                  <div class="text-muted-sm">Informasi Driver</div>
                  <div class="fw-semibold fs-5">{{ Auth::guard('admin')->user()->name ?? '-' }}</div>
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
    // total menit lembur
    $sumMinutes = 0;

    foreach ($dataLembur as $item) {
        if ($item->Jam_Lembur_Roster_Out && $item->Jam_Selesai_Lembur) {
            try {
                $start = Carbon\Carbon::parse($item->Jam_Lembur_Roster_Out);
                $end   = Carbon\Carbon::parse($item->Jam_Selesai_Lembur);

                $diffInMinutes = $start->diffInMinutes($end, false);

                if ($diffInMinutes > 0) {
                    $sumMinutes += $diffInMinutes;
                }
            } catch (\Exception $e) {
                continue;
            }
        }
    }

    // konversi total menit ke jam + menit
    $totalHours   = floor($sumMinutes / 60);
    $totalMinutes = $sumMinutes % 60;

    if ($totalHours > 0 && $totalMinutes > 0) {
        $totalDurasi = "{$totalHours} jam {$totalMinutes} menit";
    } elseif ($totalHours > 0) {
        $totalDurasi = "{$totalHours} jam";
    } else {
        $totalDurasi = "{$totalMinutes} menit";
    }
@endphp

      <div class="row mb-4">
        <div class="col-12 col-lg-5 mb-3 mb-lg-0">
          <div class="total-overtime-card">
            <div class="d-flex align-items-center gap-3 mb-3">
              <div class="icon-circle" style="background: rgba(255,255,255,0.2);">
                <i class="ri-time-line fs-3 text-white"></i>
              </div>
              <div>
                <div class="small opacity-90">Total Jam Lembur</div>
                <h3 class="mb-0 fw-bold">{{ $totalDurasi }}</h3>
              </div>
            </div>
            <div class="d-flex align-items-center gap-2 text-white-50 small">
              <i class="ri-calendar-event-line"></i>
              <span>Periode: {{ $defaultStartDate }} s/d {{ $defaultEndDate }}</span>
            </div>
          </div>
        </div>

        <!-- Filter Form -->
        <div class="col-12 col-lg-7">
          <div class="card card-modern p-3 h-100">
            <div class="card-header-modern d-flex align-items-center gap-2 mb-3">
              <div class="icon-circle bg-icon-warning">
                <i class="ri-filter-line"></i>
              </div>
              <div>
                <div class="fw-semibold">Filter Data Lembur</div>
                <div class="text-muted-sm">Pilih tanggal untuk menampilkan data</div>
              </div>
            </div>
            <form id="filterForm" method="GET" action="#" class="row g-3 align-items-end">
              <div class="col-12 col-md-5">
                <label class="form-label small mb-1 fw-semibold">Tanggal Mulai</label>
                <input type="date" name="start_date_lembur" class="form-control"
                  value="{{ $defaultStartDate }}">
              </div>
              <div class="col-12 col-md-5">
                <label class="form-label small mb-1 fw-semibold">Tanggal Selesai</label>
                <input type="date" name="end_date_lembur" class="form-control"
                  value="{{ $defaultEndDate }}">
              </div>
              <div class="col-12 col-md-2">
                <button id="btnTampilkan" type="submit"
                  class="btn btn-primary w-100 d-flex align-items-center justify-content-center">
                  <span id="btnSpinner" class="spinner-border spinner-border-sm me-2 d-none" role="status"
                    aria-hidden="true"></span>
                  <i class="ri-search-line me-1"></i> <span>Tampilkan</span>
                </button>
              </div>
              <div class="col-12">
                <a href="{{ route('driver.overtime') }}"
                  class="btn btn-outline-secondary btn-sm">
                  <i class="ri-refresh-line me-1"></i> Reset Filter
                </a>
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
                <div class="text-muted-sm">Total: <strong>{{ count($dataLembur) }}</strong> data</div>
              </div>

              <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                  <thead>
                    <tr>
                      <th>Tanggal</th>
                      <th>Jam Mulai</th>
                      <th>Jam Selesai</th>
                      <th>Durasi</th>
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
                        $durasi = 0;
                        $durasiDetail = '-';

                        if ($item->Jam_Lembur_Roster_Out && $item->Jam_Selesai_Lembur) {
                          try {
                            $start = Carbon\Carbon::parse($item->Jam_Lembur_Roster_Out);
                            $end = Carbon\Carbon::parse($item->Jam_Selesai_Lembur);

                            $diffInMinutes = $start->diffInMinutes($end, false);

                            if ($diffInMinutes <= 0) {
                              continue;
                            }

                            $hours = floor($diffInMinutes / 60);
                            $minutes = $diffInMinutes % 60;

                            if ($hours > 0 && $minutes > 0)
                              $durasiDetail = "{$hours}j {$minutes}m";
                            elseif ($hours > 0)
                              $durasiDetail = "{$hours} jam";
                            else
                              $durasiDetail = "{$minutes} menit";

                          } catch (\Exception $e) {
                            continue;
                          }
                        } else {
                          continue;
                        }
                      @endphp

                      <tr>
                        <td>
                          <i class="ri-calendar-line text-muted me-1"></i>
                          {{ $item->Tanggal ?? '-' }}
                        </td>
                        <td>
                          <i class="ri-play-circle-line text-success me-1"></i>
                          {{ $item->Jam_Lembur_Roster_Out ?? '-' }}
                        </td>
                        <td>
                          <i class="ri-stop-circle-line text-danger me-1"></i>
                          {{ $item->Jam_Selesai_Lembur ?? '-' }}
                        </td>
                        <td>
                          <span class="durasi-badge">
                            <i class="ri-timer-line me-1"></i>{{ $durasiDetail }}
                          </span>
                        </td>
                        <td>{{ $item->Keterangan_Lembur ?? '-' }}</td>
                        <td>{{ $item->Facility ?? '-' }}</td>
                        <td>{{ $item->Jobdesc_Lembur ?? '-' }}</td>
                        <td>{{ $item->Approval_By_System ?? '-' }}</td>
                        <td>
                          @if($item->Status_Approval === 'Disetujui')
                            <span class="badge bg-success badge-pill">
                              <i class="ri-checkbox-circle-line me-1"></i>Disetujui
                            </span>
                          @elseif($item->Status_Approval === 'Menunggu')
                            <span class="badge bg-warning text-dark badge-pill">
                              <i class="ri-time-line me-1"></i>Menunggu
                            </span>
                          @else
                            <span class="badge bg-secondary badge-pill">
                              <i class="ri-question-line me-1"></i>{{ $item->Status_Approval ?? 'N/A' }}
                            </span>
                          @endif
                        </td>
                        <td>
                          <div class="fw-semibold">{{ $item->Approved_By_Name ?? '-' }}</div>
                          @if($item->Approve_Time)
                            <div class="text-muted-sm">
                              <i class="ri-time-line me-1"></i>{{ $item->Approve_Time }}
                            </div>
                          @endif
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="10" class="text-center text-muted py-5">
                          <i class="ri-inbox-line fs-1 mb-2 d-block"></i>
                          <div>Tidak ada data lembur untuk periode yang dipilih</div>
                        </td>
                      </tr>
                    @endforelse
                  </tbody>

                  @if(count($dataLembur) > 0)
                    <tfoot class="table-light">
                      <tr>
                        <td colspan="3" class="text-end fw-semibold">
                          <i class="ri-file-list-3-line me-1"></i> Total Jam Lembur:
                        </td>
                        <td>
                          <span class="durasi-badge">
                            <i class="ri-time-line me-1"></i>{{ $totalDurasi }}
                          </span>
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
              <div class="icon-circle bg-icon-warning">
                <i class="ri-smartphone-line"></i>
              </div>
              <h5 class="mb-0">Data Lembur</h5>
            </div>

            @forelse($dataLembur as $item)
              @php
                $durasi = 0;
                $durasiDetail = '-';

                if ($item->Jam_Lembur_Roster_Out && $item->Jam_Selesai_Lembur) {
                    try {
                        $start = Carbon\Carbon::parse($item->Jam_Lembur_Roster_Out);
                        $end = Carbon\Carbon::parse($item->Jam_Selesai_Lembur);

                        $diffInMinutes = $start->diffInMinutes($end, false);

                        if($diffInMinutes <= 0) {
                            continue;
                        }

                        $hours = floor($diffInMinutes / 60);
                        $minutes = $diffInMinutes % 60;

                        if ($hours > 0 && $minutes > 0) $durasiDetail = "{$hours}j {$minutes}m";
                        elseif ($hours > 0) $durasiDetail = "{$hours} jam";
                        else $durasiDetail = "{$minutes} menit";

                    } catch(\Exception $e) {
                        continue;
                    }
                } else {
                    continue;
                }
              @endphp

              <article class="overtime-card mb-3">
                <div class="d-flex justify-content-between align-items-start mb-3">
                  <div>
                    <div class="title">
                      <i class="ri-calendar-line me-1 text-primary"></i>
                      {{ $item->Tanggal ?? '-' }}
                    </div>
                    <div class="text-muted-sm mt-1">
                      <i class="ri-play-circle-line text-success"></i>
                      {{ $item->Jam_Lembur_Roster_Out ?? '-' }}
                      <span class="mx-1">•</span>
                      <i class="ri-stop-circle-line text-danger"></i>
                      {{ $item->Jam_Selesai_Lembur ?? '-' }}
                    </div>
                  </div>
                  <div class="text-end">
                    <span class="durasi-badge mb-2 d-inline-block">
                      <i class="ri-timer-line me-1"></i>{{ $durasiDetail }}
                    </span>
                    <div>
                      @if($item->Status_Approval === 'Disetujui')
                        <span class="badge bg-success badge-pill">
                          <i class="ri-checkbox-circle-line me-1"></i>Disetujui
                        </span>
                      @elseif($item->Status_Approval === 'Menunggu')
                        <span class="badge bg-warning text-dark badge-pill">
                          <i class="ri-time-line me-1"></i>Menunggu
                        </span>
                      @else
                        <span class="badge bg-secondary badge-pill">
                          <i class="ri-question-line me-1"></i>{{ $item->Status_Approval ?? 'N/A' }}
                        </span>
                      @endif
                    </div>
                  </div>
                </div>

                <div class="text-sm text-muted">
                  <div class="mb-2">
                    <i class="ri-building-line me-1"></i>
                    <strong>Facility:</strong> {{ $item->Facility ?? '-' }}
                  </div>
                  <div class="mb-2">
                    <i class="ri-task-line me-1"></i>
                    <strong>Jobdesc:</strong> {{ $item->Jobdesc_Lembur ?? '-' }}
                  </div>
                  <div class="mb-2">
                    <i class="ri-shield-check-line me-1"></i>
                    <strong>Sts. People Pro:</strong> {{ $item->Approval_By_System ?? '-' }}
                  </div>
                  <div class="mb-2">
                    <i class="ri-file-text-line me-1"></i>
                    <strong>Keterangan:</strong> {{ $item->Keterangan_Lembur ?? '-' }}
                  </div>
                  <div>
                    <i class="ri-user-star-line me-1"></i>
                    <strong>Disetujui Oleh:</strong> {{ $item->Approved_By_Name ?? '-' }}
                    @if($item->Approve_Time)
                      <div class="text-muted-sm mt-1 ms-4">
                        <i class="ri-time-line me-1"></i>{{ $item->Approve_Time }}
                      </div>
                    @endif
                  </div>
                </div>
              </article>
            @empty
              <div class="text-center text-muted py-5">
                <i class="ri-inbox-line fs-1 mb-2 d-block"></i>
                <div>Tidak ada data lembur untuk periode yang dipilih</div>
              </div>
            @endforelse

            @if(count($dataLembur) > 0)
              <div class="border rounded p-3 bg-light mt-3">
                <div class="text-center">
                  <div class="small text-muted mb-2">Total Jam Lembur</div>
                  <div class="fs-4 fw-bold text-primary">
                    <i class="ri-time-line me-1"></i>{{ $totalDurasi }}
                  </div>
                  <div class="small text-muted mt-2">
                    <i class="ri-calendar-event-line me-1"></i>
                    Periode: {{ $defaultStartDate }} s/d {{ $defaultEndDate }}
                  </div>
                </div>
              </div>
            @endif
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Scripts -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Spinner behavior
      const form = document.getElementById('filterForm');
      const btn = document.getElementById('btnTampilkan');
      const spinner = document.getElementById('btnSpinner');

      if (form && btn) {
        form.addEventListener('submit', function () {
          if (spinner) spinner.classList.remove('d-none');
          btn.classList.add('disabled');
        });
      }

      // Remix icons fallback if not loaded
      const icons = document.querySelectorAll('i[class^="ri-"]');
      icons.forEach(icon => {
        const style = window.getComputedStyle(icon, '::before');
        const content = style && style.content;
        if (!content || content === 'none' || content === '""') {
          const cls = icon.className;
          let fb = '';
          if (cls.includes('ri-time-line')) fb = '⏰';
          else if (cls.includes('ri-user-line')) fb = '👤';
          else if (cls.includes('ri-filter-line')) fb = '🔍';
          else if (cls.includes('ri-table-line')) fb = '📊';
          else if (cls.includes('ri-smartphone-line')) fb = '📱';
          else if (cls.includes('ri-calendar-line')) fb = '📅';
          else if (cls.includes('ri-play-circle-line')) fb = '▶️';
          else if (cls.includes('ri-stop-circle-line')) fb = '⏹️';
          else if (cls.includes('ri-timer-line')) fb = '⏱️';
          else if (cls.includes('ri-file-text-line')) fb = '📄';
          else if (cls.includes('ri-building-line')) fb = '🏢';
          else if (cls.includes('ri-task-line')) fb = '✅';
          else if (cls.includes('ri-user-star-line')) fb = '⭐';
          else if (cls.includes('ri-checkbox-circle-line')) fb = '✅';
          else if (cls.includes('ri-question-line')) fb = '❓';
          else if (cls.includes('ri-inbox-line')) fb = '📥';
          else if (cls.includes('ri-shield-check-line')) fb = '🛡️';
          if (fb) {
            icon.textContent = fb + ' ';
            icon.style.fontStyle = 'normal';
          }
        }
      });
    });
  </script>

@endsection