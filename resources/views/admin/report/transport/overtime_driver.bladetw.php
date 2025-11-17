@extends('admin.dashboard')

@section('admin')
<!-- Tailwind CDN (browser build as requested) -->
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

<!-- Compatibility CSS: mimic a few Bootstrap helpers so admin.dashboard tetap jalan -->
<style>
  /* ruang untuk topbar fixed (sesuaikan jika topbar tingginya beda) */
  .admin-content-wrapper { padding-top: 56px; } /* ganti 56px sesuai topbar */

  /* container / grid compatibility */
  .container-fluid { width: 100%; padding-left: 1rem; padding-right: 1rem; margin-left: auto; margin-right: auto; }
  .container { max-width: 1140px; margin-left: auto; margin-right: auto; padding-left: 1rem; padding-right: 1rem; }

  .row { display: flex; flex-wrap: wrap; margin-left: -0.5rem; margin-right: -0.5rem; }
  .col-12, .col-lg-12 { padding-left: 0.5rem; padding-right: 0.5rem; flex: 0 0 100%; max-width: 100%; }

  /* card helpers */
  .card { background: white; border-radius: 0.5rem; box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06); overflow: hidden; }
  .card-body { padding: 1rem; }

  /* small badge helper */
  .badge { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem; }

  /* table responsiveness */
  .table { width: 100%; border-collapse: collapse; }
  .table th, .table td { padding: 0.75rem; border-bottom: 1px solid #eef2f7; }

  /* ensure images/icons align with previous layout */
  .page-title-box { display:flex; align-items:center; justify-content:space-between; gap:1rem; }

  /* fallback jika admin layout mengandalkan bootstrap spacing classes */
  .mb-3 { margin-bottom: 0.75rem; }
  .mb-6 { margin-bottom: 1.5rem; }
  .mt-2 { margin-top: 0.5rem; }
  .d-flex { display:flex; }
  .justify-content-between { justify-content:space-between; }
  .align-items-center { align-items:center; }
</style>

<div class="page-content mt-[70px] ml-[250px] px-4 md:px-6 py-6">
<div class="admin-content-wrapper container-fluid">
    <!-- Header -->
    <div class="flex items-start justify-between mb-6">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-white/60 backdrop-blur-sm flex items-center justify-center shadow">
          <i class="ri-time-line text-2xl text-sky-600"></i>
        </div>
        <div>
          <h1 class="text-lg font-semibold text-slate-800">Data Lembur Driver</h1>
          <p class="text-sm text-slate-500">Ringkasan dan riwayat lembur</p>
        </div>
      </div>
      <!-- empty right slot, keep for actions if needed -->
      <div></div>
    </div>

    <!-- Driver info + alert -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <div class="md:col-span-2 bg-white rounded-xl shadow p-4 flex flex-col sm:flex-row gap-4 items-start">
        <div class="w-12 h-12 rounded-lg bg-sky-50 flex items-center justify-center text-sky-600">
          <i class="ri-user-line text-xl"></i>
        </div>
        <div class="flex-1">
          <h3 class="text-sm font-medium text-slate-700">Informasi Driver</h3>
          <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-slate-600">
            <div class="flex items-center gap-2"><i class="ri-id-card-line"></i> <span><strong>NIK:</strong> {{ $nik ?? '-' }}</span></div>
            <div class="flex items-center gap-2"><i class="ri-user-3-line"></i> <span><strong>Nama:</strong> {{ Auth::guard('admin')->user()->name ?? '-' }}</span></div>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow p-4 flex items-center gap-3">
        <i class="ri-information-line text-sky-500 text-xl"></i>
        <div class="text-sm text-slate-600">Maksimal periode pencarian: <span class="font-medium">1 bulan</span></div>
      </div>
    </div>

    @php
        // Hitung total jam lembur yang dibulatkan (dipertahankan dari versi awal)
        $totalJamLembur = 0;
        $totalMenitLembur = 0;
        
        foreach ($dataLembur as $item) {
            if ($item->Jam_Lembur_Roster_Out && $item->Jam_Selesai_Lembur) {
                try {
                    $start = Carbon\Carbon::parse($item->Jam_Lembur_Roster_Out);
                    $end = Carbon\Carbon::parse($item->Jam_Selesai_Lembur);
                    $diffInMinutes = $start->diffInMinutes($end);
                    
                    // Bulatkan ke jam terdekat (30 menit = 0.5 jam, dibulatkan)
                    $durasiJam = round($diffInMinutes / 60, 1);
                    $totalJamLembur += $durasiJam;
                    
                } catch (\Exception $e) {
                    continue;
                }
            }
        }
        
        $totalJamBulat = round($totalJamLembur);
        $totalJamDesimal = number_format($totalJamLembur, 1);
    @endphp

    <!-- Total Overtime Summary (prominent card) -->
    <div class="bg-gradient-to-tr from-sky-600 to-teal-500 text-white rounded-2xl p-5 mb-6 shadow-lg">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
          <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center">
            <i class="ri-time-line text-2xl"></i>
          </div>
          <div>
            <div class="text-2xl font-bold">{{ $totalJamBulat }} Jam</div>
            <div class="text-sm opacity-90">Total Jam Lembur</div>
            @if($totalJamBulat != $totalJamLembur)
              <div class="text-xs opacity-80 mt-1">Detail: {{ $totalJamDesimal }} Jam</div>
            @endif
          </div>
        </div>

        <div class="text-sm text-white/90">
          <div class="flex items-center gap-2"><i class="ri-calendar-event-line"></i> Periode: <span class="font-semibold ml-1">{{ $defaultStartDate }} s/d {{ $defaultEndDate }}</span></div>
        </div>
      </div>
    </div>

    <!-- Filter form -->
    <div class="bg-white rounded-xl shadow p-4 mb-6">
      <div class="flex items-center gap-3 mb-3">
        <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
          <i class="ri-filter-line"></i>
        </div>
        <h3 class="text-sm font-medium text-slate-700">Filter Data Lembur</h3>
      </div>

      <form id="filterForm" method="GET" action="#" class="flex flex-col sm:flex-row sm:items-end gap-3">
        <div class="flex-1 min-w-0">
          <label class="block text-xs text-slate-600 mb-1 flex items-center gap-2"><i class="ri-calendar-2-line"></i> Tanggal Mulai</label>
          <input type="date" name="start_date_lembur" value="{{ $defaultStartDate }}" class="w-full bg-slate-50 border border-slate-200 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-300" />
        </div>

        <div class="flex-1 min-w-0">
          <label class="block text-xs text-slate-600 mb-1 flex items-center gap-2"><i class="ri-calendar-check-line"></i> Tanggal Selesai</label>
          <input type="date" name="end_date_lembur" value="{{ $defaultEndDate }}" class="w-full bg-slate-50 border border-slate-200 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-300" />
        </div>

        <div class="flex gap-2">
          <button id="btnTampilkan" type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-sky-300">
            <span class="btn-text inline-flex items-center">
              <span class="w-4 h-4 border-2 border-white border-t-transparent rounded-full mr-2 hidden" id="btnSpinner"></span>
              <i class="ri-search-line mr-1"></i> <span>Tampilkan Data</span>
            </span>
          </button>

          <a href="{{ route('driver.overtime') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-md text-sm">
            <i class="ri-refresh-line mr-1"></i> Reset
          </a>
        </div>
      </form>
    </div>

    <!-- DATA: Desktop table (md+) -->
    <div class="bg-white rounded-xl shadow p-4 mb-6 hidden md:block">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
          <i class="ri-table-line"></i>
        </div>
        <h3 class="text-sm font-medium text-slate-700">Data Lembur</h3>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
          <thead class="bg-slate-50">
            <tr class="text-slate-600">
              <th class="py-3 px-3 border-b">Tanggal</th>
              <th class="py-3 px-3 border-b">Jam Mulai</th>
              <th class="py-3 px-3 border-b">Jam Selesai</th>
              <th class="py-3 px-3 border-b text-center">Durasi (Jam)</th>
              <th class="py-3 px-3 border-b">Keterangan</th>
              <th class="py-3 px-3 border-b">Facility</th>
              <th class="py-3 px-3 border-b">Jobdesc</th>
              <th class="py-3 px-3 border-b">Sts. People Pro</th>
              <th class="py-3 px-3 border-b">Sts. Appr. MGR</th>
              <th class="py-3 px-3 border-b">Disetujui Oleh</th>
            </tr>
          </thead>
          <tbody class="text-slate-700">
            @forelse ($dataLembur as $item)
              @php
                  $durasi = 0;
                  $durasiDetail = '';
                  if ($item->Jam_Lembur_Roster_Out && $item->Jam_Selesai_Lembur) {
                      try {
                          $start = Carbon\Carbon::parse($item->Jam_Lembur_Roster_Out);
                          $end = Carbon\Carbon::parse($item->Jam_Selesai_Lembur);
                          $diffInMinutes = $start->diffInMinutes($end);
                          
                          $hours = floor($diffInMinutes / 60);
                          $minutes = $diffInMinutes % 60;
                          
                          $durasi = round($diffInMinutes / 60, 1);
                          $durasiBulat = round($durasi);
                          
                          if ($hours > 0 && $minutes > 0) {
                              $durasiDetail = "{$hours} jam {$minutes} menit";
                          } elseif ($hours > 0) {
                              $durasiDetail = "{$hours} jam";
                          } else {
                              $durasiDetail = "{$minutes} menit";
                          }
                          
                      } catch (\Exception $e) {
                          $durasi = 0;
                          $durasiBulat = 0;
                          $durasiDetail = '-';
                      }
                  } else {
                      $durasi = 0;
                      $durasiBulat = 0;
                      $durasiDetail = '-';
                  }
              @endphp
              <tr class="odd:bg-white even:bg-slate-50">
                <td class="py-3 px-3 border-b align-middle">
                  <div class="flex items-center gap-2">
                    <i class="ri-calendar-line text-slate-400"></i>
                    <span>{{ $item->Tanggal ?? '-' }}</span>
                  </div>
                </td>
                <td class="py-3 px-3 border-b">
                  <div class="flex items-center gap-2">
                    <i class="ri-play-circle-line text-slate-400"></i>
                    <span>{{ $item->Jam_Lembur_Roster_Out ?? '-' }}</span>
                  </div>
                </td>
                <td class="py-3 px-3 border-b">
                  <div class="flex items-center gap-2">
                    <i class="ri-stop-circle-line text-slate-400"></i>
                    <span>{{ $item->Jam_Selesai_Lembur ?? '-' }}</span>
                  </div>
                </td>
                <td class="py-3 px-3 border-b text-center align-middle">
                  <div class="inline-flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-1 rounded text-white bg-sky-600 text-xs">
                      <i class="ri-timer-line mr-1"></i> {{ $durasiBulat }} Jam
                    </span>
                  </div>
                  @if($durasiBulat != $durasi)
                    <div class="text-xs text-slate-500 mt-1">{{ $durasi }} jam</div>
                  @endif
                </td>
                <td class="py-3 px-3 border-b">{{ $item->Keterangan_Lembur ?? '-' }}</td>
                <td class="py-3 px-3 border-b">{{ $item->Facility ?? '-' }}</td>
                <td class="py-3 px-3 border-b">{{ $item->Jobdesc_Lembur ?? '-' }}</td>
                <td class="py-3 px-3 border-b">{{ $item->Approval_By_System ?? '-' }}</td>
                <td class="py-3 px-3 border-b">
                  @if($item->Status_Approval === 'Disetujui')
                    <span class="inline-flex items-center gap-2 px-2 py-1 rounded text-sm bg-emerald-100 text-emerald-800">
                      <i class="ri-checkbox-circle-line"></i> Disetujui
                    </span>
                  @elseif($item->Status_Approval === 'Menunggu')
                    <span class="inline-flex items-center gap-2 px-2 py-1 rounded text-sm bg-amber-100 text-amber-800">
                      <i class="ri-time-line"></i> Menunggu
                    </span>
                  @else
                    <span class="inline-flex items-center gap-2 px-2 py-1 rounded text-sm bg-slate-100 text-slate-600">
                      <i class="ri-question-line"></i> {{ $item->Status_Approval ?? 'N/A' }}
                    </span>
                  @endif
                </td>
                <td class="py-3 px-3 border-b">
                  <div class="text-sm">{{ $item->Approved_By_Name ?? '-' }}</div>
                  @if($item->Approve_Time)
                    <div class="text-xs text-slate-500 mt-1 flex items-center gap-1"><i class="ri-time-line"></i>{{ $item->Approve_Time }}</div>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="10" class="py-12 text-center text-slate-400">
                  <i class="ri-inbox-line text-4xl mb-2"></i>
                  <div>Tidak ada data lembur</div>
                </td>
              </tr>
            @endforelse
          </tbody>

          @if(count($dataLembur) > 0)
            <tfoot class="bg-slate-50">
              <tr>
                <td colspan="3" class="py-3 px-3 text-right border-t">
                  <strong class="text-slate-700 inline-flex items-center gap-2"><i class="ri-file-list-3-line"></i> Total Jam Lembur:</strong>
                </td>
                <td class="py-3 px-3 text-center border-t">
                  <strong class="text-sky-600 inline-flex items-center gap-2"><i class="ri-time-line"></i> {{ $totalJamBulat }} Jam</strong>
                  @if($totalJamBulat != $totalJamLembur)
                    <div class="text-xs text-slate-500">{{ $totalJamDesimal }} jam</div>
                  @endif
                </td>
                <td colspan="6" class="border-t"></td>
              </tr>
            </tfoot>
          @endif
        </table>
      </div>
    </div>

    <!-- Mobile cards (smaller screens) -->
    <div class="md:hidden space-y-4">
      <div class="bg-white rounded-xl shadow p-4">
        <div class="flex items-center gap-3 mb-3">
          <div class="w-10 h-10 rounded-lg bg-violet-50 flex items-center justify-center text-violet-600">
            <i class="ri-smartphone-line"></i>
          </div>
          <h3 class="text-sm font-medium text-slate-700">Data Lembur</h3>
        </div>

        @forelse ($dataLembur as $item)
          @php
              $durasi = 0;
              $durasiDetail = '';
              if ($item->Jam_Lembur_Roster_Out && $item->Jam_Selesai_Lembur) {
                  try {
                      $start = Carbon\Carbon::parse($item->Jam_Lembur_Roster_Out);
                      $end = Carbon\Carbon::parse($item->Jam_Selesai_Lembur);
                      $diffInMinutes = $start->diffInMinutes($end);
                      
                      $hours = floor($diffInMinutes / 60);
                      $minutes = $diffInMinutes % 60;
                      
                      $durasi = round($diffInMinutes / 60, 1);
                      $durasiBulat = round($durasi);
                      
                      if ($hours > 0 && $minutes > 0) {
                          $durasiDetail = "{$hours} jam {$minutes} menit";
                      } elseif ($hours > 0) {
                          $durasiDetail = "{$hours} jam";
                      } else {
                          $durasiDetail = "{$minutes} menit";
                      }
                      
                  } catch (\Exception $e) {
                      $durasi = 0;
                      $durasiBulat = 0;
                      $durasiDetail = '-';
                  }
              } else {
                  $durasi = 0;
                  $durasiBulat = 0;
                  $durasiDetail = '-';
              }
          @endphp

          <article class="border border-slate-100 rounded-lg p-3 mb-3">
            <div class="flex items-start justify-between gap-3">
              <div>
                <div class="flex items-center gap-2">
                  <i class="ri-calendar-line text-slate-400"></i>
                  <h4 class="text-sm font-medium text-slate-700">{{ $item->Tanggal ?? '-' }}</h4>
                </div>
                <div class="mt-2 text-xs text-slate-500 flex gap-3">
                  <div class="flex items-center gap-1"><i class="ri-play-circle-line"></i> {{ $item->Jam_Lembur_Roster_Out ?? '-' }}</div>
                  <div class="flex items-center gap-1"><i class="ri-stop-circle-line"></i> {{ $item->Jam_Selesai_Lembur ?? '-' }}</div>
                </div>
              </div>

              <div class="text-right">
                <div class="inline-flex items-center gap-2 px-2 py-1 rounded text-white bg-sky-600 text-xs">
                  <i class="ri-timer-line"></i> {{ $durasiBulat }} Jam
                </div>
                <div class="mt-1">
                  @if($item->Status_Approval === 'Disetujui')
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-emerald-800 bg-emerald-100 text-xs mt-2"><i class="ri-checkbox-circle-line"></i> Disetujui</span>
                  @elseif($item->Status_Approval === 'Menunggu')
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-amber-800 bg-amber-100 text-xs mt-2"><i class="ri-time-line"></i> Menunggu</span>
                  @else
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-slate-600 bg-slate-100 text-xs mt-2"><i class="ri-question-line"></i> {{ $item->Status_Approval ?? 'N/A' }}</span>
                  @endif
                </div>
              </div>
            </div>

            @if($durasiBulat != $durasi)
              <div class="mt-3 text-xs text-slate-500"><i class="ri-bar-chart-line"></i> {{ $durasiDetail }} ({{ $durasi }} jam)</div>
            @endif

            <div class="mt-3 grid grid-cols-1 gap-2 text-sm text-slate-600">
              <div><i class="ri-building-line"></i> <strong>Facility:</strong> {{ $item->Facility ?? '-' }}</div>
              <div><i class="ri-task-line"></i> <strong>Jobdesc:</strong> {{ $item->Jobdesc_Lembur ?? '-' }}</div>
              <div><i class="ri-file-text-line"></i> <strong>Keterangan:</strong> {{ $item->Keterangan_Lembur ?? '-' }}</div>
              <div><i class="ri-user-star-line"></i> <strong>Disetujui Oleh:</strong> {{ $item->Approved_By_Name ?? '-' }}
                @if($item->Approve_Time)
                  <div class="text-xs text-slate-400 mt-1"><i class="ri-time-line"></i> {{ $item->Approve_Time }}</div>
                @endif
              </div>
            </div>
          </article>
        @empty
          <div class="text-center text-slate-400 py-8">
            <i class="ri-inbox-line text-4xl mb-2"></i>
            <div>Tidak ada data lembur</div>
          </div>
        @endforelse

        @if(count($dataLembur) > 0)
          <div class="border border-slate-100 rounded-lg p-3 mt-4 bg-slate-50">
            <div class="text-center">
              <div class="text-sm text-slate-600">Total Jam Lembur</div>
              <div class="text-xl font-bold text-sky-600 flex items-center justify-center gap-2"><i class="ri-time-line"></i> {{ $totalJamBulat }} Jam</div>
              @if($totalJamBulat != $totalJamLembur)
                <div class="text-xs text-slate-500 mt-1">Detail: {{ $totalJamDesimal }} jam</div>
              @endif
              <div class="text-xs text-slate-400 mt-1 flex items-center justify-center gap-1"><i class="ri-calendar-event-line"></i> Periode: {{ $defaultStartDate }} s/d {{ $defaultEndDate }}</div>
            </div>
          </div>
        @endif
      </div>
    </div>

  </div>
</div>

<!-- JS: spinner behavior + icon fallback -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('filterForm');
  const button = document.getElementById('btnTampilkan');
  const spinner = document.getElementById('btnSpinner');

  form.addEventListener('submit', function() {
    // show spinner element and disable button
    if (spinner) spinner.classList.remove('hidden');
    button.classList.add('opacity-70', 'pointer-events-none');
    // change text
    const textSpan = button.querySelector('.btn-text span');
    // safer update - find text node
    const btnText = button.querySelector('.btn-text');
    if (btnText) {
      btnText.innerHTML = '<span class="w-4 h-4 border-2 border-white border-t-transparent rounded-full mr-2 animate-spin inline-block"></span> Memuat...';
    }
  });

  // Fallback for remixicons: if not loaded, replace some icons with emoji/text
  const icons = document.querySelectorAll('i[class^="ri-"]');
  icons.forEach(icon => {
    const style = window.getComputedStyle(icon, '::before');
    const content = style && style.content;
    // content might return 'none' or '""' when icon set not loaded
    if (!content || content === 'none' || content === '""') {
      const cls = icon.className;
      let fallback = '';
      if (cls.includes('ri-time-line')) fallback = '⏰';
      else if (cls.includes('ri-user-line')) fallback = '👤';
      else if (cls.includes('ri-filter-line')) fallback = '🔍';
      else if (cls.includes('ri-table-line')) fallback = '📊';
      else if (cls.includes('ri-smartphone-line')) fallback = '📱';
      else if (cls.includes('ri-calendar-line')) fallback = '';
      else if (cls.includes('ri-play-circle-line')) fallback = '▶️';
      else if (cls.includes('ri-stop-circle-line')) fallback = '⏹️';
      else if (cls.includes('ri-timer-line')) fallback = '⏱️';
      else if (cls.includes('ri-file-text-line')) fallback = '📄';
      else if (cls.includes('ri-building-line')) fallback = '🏢';
      else if (cls.includes('ri-task-line')) fallback = '✅';
      else if (cls.includes('ri-computer-line')) fallback = '💻';
      else if (cls.includes('ri-user-check-line')) fallback = '👤✓';
      else if (cls.includes('ri-user-star-line')) fallback = '👤⭐';
      else if (cls.includes('ri-information-line')) fallback = 'ℹ️';
      else if (cls.includes('ri-search-line')) fallback = '🔍';
      else if (cls.includes('ri-refresh-line')) fallback = '🔄';
      else if (cls.includes('ri-calendar-2-line')) fallback = '';
      else if (cls.includes('ri-calendar-check-line')) fallback = '';
      else if (cls.includes('ri-checkbox-circle-line')) fallback = '✅';
      else if (cls.includes('ri-question-line')) fallback = '❓';
      else if (cls.includes('ri-inbox-line')) fallback = '📥';
      else if (cls.includes('ri-file-list-3-line')) fallback = '📋';
      else if (cls.includes('ri-bar-chart-line')) fallback = '📊';
      else if (cls.includes('ri-calendar-event-line')) fallback = '';
      else if (cls.includes('ri-id-card-line')) fallback = '🆔';
      else if (cls.includes('ri-user-3-line')) fallback = '👤';

      if (fallback) {
        icon.classList.add('ri-fallback');
        icon.textContent = fallback + ' ';
      }
    }
  });
});
</script>

@endsection
