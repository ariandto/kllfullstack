<?php

namespace App\Http\Controllers\Admin\Report\Transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Report\Transport\Overtime;
use Carbon\Carbon;

class OvertimeDriverController extends Controller
{
    /**
     * Halaman utama laporan lembur driver
     */
    public function index()
    {
        // Ambil user login
        $nik = Auth::guard('admin')->user()->userid ?? null;

        // Tentukan default periode (7 hari terakhir)
        $defaultStartDate = Carbon::now()->subDays(7)->format('Y-m-d');
        $defaultEndDate   = Carbon::now()->format('Y-m-d');

        /**
         * ⚠️ Tidak ada pemanggilan data SP di sini
         * Data akan dikosongkan agar tidak ada query berat dijalankan saat refresh
         */
        $dataLembur = collect([]);
        $totalJamLembur = 0;

        // Kirim ke view
        return view('admin.report.transport.overtime_driver', compact(
            'dataLembur', 'defaultStartDate', 'defaultEndDate', 'nik', 'totalJamLembur'
        ));
    }

    /**
     * Ambil data lembur driver (via AJAX)
     */
    public function getOvertimeData(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $nik       = Auth::guard('admin')->user()->userid ?? null;
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        // Jika user belum login atau tidak punya nik valid
        if (!$nik) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak terautentikasi.'
            ], 401);
        }

        try {
            // 🔹 Ambil data dari Stored Procedure via Model
            $data = Overtime::getData($nik, $startDate, $endDate);

            // 🔹 Hitung total jam lembur (opsional, untuk statistik)
            $totalJam = 0;
            foreach ($data as $row) {
                if (!empty($row->Jam_Lembur_Roster_Out) && !empty($row->Jam_Selesai_Lembur)) {
                    $start = Carbon::parse($row->Jam_Lembur_Roster_Out);
                    $end = Carbon::parse($row->Jam_Selesai_Lembur);
                    $totalJam += round($start->diffInMinutes($end) / 60, 1);
                }
            }

            // 🔹 Return dalam format JSON
            return response()->json([
                'success' => true,
                'data' => $data,
                'meta' => [
                    'total' => $data->count(),
                    'total_jam' => round($totalJam, 1),
                    'periode' => "{$startDate} s/d {$endDate}",
                ]
            ]);
        } catch (\Exception $e) {
            // 🔹 Tangani error (misal timeout SP atau koneksi)
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data lembur.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
