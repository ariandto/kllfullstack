<?php

namespace App\Http\Controllers\Admin\Report\Transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Report\Transport\DailyReportTransportModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class DailyReportTransportController extends Controller
{
    /**
     * 🧭 Halaman utama (menampilkan filter + hasil awal)
     */
    public function index(Request $request)
    {
        
        ini_set('max_execution_time', 360);
        set_time_limit(120);

        try {
            $facilityID = $request->input('facility', 'DC INFORMA JABABEKA');
            $StartDate  = $request->input('start_date', now()->format('Y-m-d'));
            $EndDate    = $request->input('end_date', now()->format('Y-m-d'));
            $Key1       = $request->input('key1', 'WMWHSE4RTL');

            // 🔹 Cache 5 menit agar tidak selalu eksekusi SP berat
            $cacheKey = "dailyreport_index_{$StartDate}_{$EndDate}_{$Key1}_{$facilityID}";

            $data = Cache::remember($cacheKey, 60, function () use ($StartDate, $EndDate, $Key1, $facilityID) {
                return [
                    'reportData' => DailyReportTransportModel::getReport($StartDate, $EndDate, $Key1, $facilityID),
                    'armadaUtil' => DailyReportTransportModel::getArmadaUtilization($facilityID, $StartDate),
                    'driverUtil' => DailyReportTransportModel::getDriverUtilization($facilityID, $StartDate),
                    'siteList'   => DailyReportTransportModel::getSiteList(),
                ];
            });

            // 🔹 Kirim ke Blade
            return view('admin.report.transport.daily_trp', [
                'StartDate'  => $StartDate,
                'EndDate'    => $EndDate,
                'facilityID' => $facilityID,
                'Key1'       => $Key1,
                'siteList'   => $data['siteList'],
                'reportData' => $data['reportData'],
                'armadaUtil' => $data['armadaUtil'],
                'driverUtil' => $data['driverUtil'],
            ]);
        } catch (\Exception $e) {
            Log::error('Daily Report Transport - Index Error: ' . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memuat halaman utama: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * ⚡ Endpoint API (AJAX saat klik "Tampilkan Data")
     */
    public function getData(Request $request)
    {
        // 🕒 Tambahkan batas waktu eksekusi di sini juga
        ini_set('max_execution_time', 360);
        set_time_limit(120);

        try {
            $facilityID = $request->input('facility', 'DC INFORMA JABABEKA');
            $StartDate  = $request->input('start_date', now()->format('Y-m-d'));
            $EndDate    = $request->input('end_date', now()->format('Y-m-d'));
            $Key1       = $request->input('key1', 'WMWHSE4RTL');

            $cacheKey = "dailyreport_data_{$StartDate}_{$EndDate}_{$Key1}_{$facilityID}";

            // 🔹 Cache response selama 5 menit (SP berat hanya jalan sekali)
            $data = Cache::remember($cacheKey, 300, function () use ($StartDate, $EndDate, $Key1, $facilityID) {
                $reportData = DailyReportTransportModel::getReport($StartDate, $EndDate, $Key1, $facilityID);
                $armadaUtil = DailyReportTransportModel::getArmadaUtilization($facilityID, $StartDate);
                $driverUtil = DailyReportTransportModel::getDriverUtilization($facilityID, $StartDate);

                return [
                    'prodCustomer'       => $reportData['prodCustomer'] ?? [],
                    'prodStore'          => $reportData['prodStore'] ?? [],
                    'deliveryCustomer'   => $reportData['deliveryCustomer'] ?? [],
                    'deliveryStore'      => $reportData['deliveryStore'] ?? [],
                    'monitoringExternal' => $reportData['monitoringExternal'] ?? [],
                    'monitoringInternal' => $reportData['monitoringInternal'] ?? [],
                    'slaCustomer'        => $reportData['slaCustomer'] ?? [],
                    'prodArmadaCust'     => $reportData['prodArmadaCust'] ?? [],
                    'prodArmadaStore'    => $reportData['prodArmadaStore'] ?? [],
                    'armadaUtil'         => $armadaUtil,
                    'driverUtil'         => $driverUtil,
                ];
            });

            return response()->json([
                'status' => 'success',
                'data'   => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('Daily Report Transport - getData Error: ' . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal mengambil data laporan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 📍 Mendapatkan daftar site untuk filter (AJAX)
     */public function getSiteListDaily()
{
    try {
        $sites = DailyReportTransportModel::getSiteList();
        \Log::info('📦 Site list data:', is_array($sites) ? $sites : [$sites]);
        return response()->json([
            'status' => 'success',
            'data' => $sites
        ]);
    } catch (\Exception $e) {
        \Log::error('Daily Report Transport - getSiteList Error: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Gagal mengambil daftar facility: ' . $e->getMessage()
        ], 500);
    }
}



}
