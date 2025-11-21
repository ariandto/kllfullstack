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
     * 🧭 Halaman utama
     */
    public function index(Request $request)
    {
        ini_set('max_execution_time', 360);
        set_time_limit(120);

        try {
            $facilityID = $request->input('facility', 'DC INFORMA JABABEKA');

            // Frontend hanya kirim 1 tanggal
            $Tanggal = $request->input('date', now()->format('Y-m-d'));
            $Key1    = $request->input('key1', 'WMWHSE4RTL');

            // Cache key baru
            $cacheKey = "dailyreport_index_{$Tanggal}_{$Key1}_{$facilityID}";

            $data = Cache::remember($cacheKey, 60, function () use ($Tanggal, $Key1, $facilityID) {
                return [
                    'reportData' => DailyReportTransportModel::getReport($Tanggal, $Key1, $facilityID),
                    'armadaUtil' => DailyReportTransportModel::getArmadaUtilization($facilityID, $Tanggal),
                    'driverUtil' => DailyReportTransportModel::getDriverUtilization($facilityID, $Tanggal),
                    'siteList'   => DailyReportTransportModel::getSiteList(),
                ];
            });

            return view('admin.report.transport.daily_trp', [
                'Tanggal'    => $Tanggal,
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
     * ⚡ Endpoint API (AJAX)
     */
    public function getData(Request $request)
    {
        ini_set('max_execution_time', 360);
        set_time_limit(120);

        try {
            $facilityID = $request->input('facility', 'DC INFORMA JABABEKA');
            $Tanggal    = $request->input('date', now()->format('Y-m-d'));
            $Key1       = $request->input('key1', 'WMWHSE4RTL');

            $cacheKey = "dailyreport_data_{$Tanggal}_{$Key1}_{$facilityID}";

            $data = Cache::remember($cacheKey, 300, function () use ($Tanggal, $Key1, $facilityID) {

                $reportData = DailyReportTransportModel::getReport($Tanggal, $Key1, $facilityID);

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
                    'armadaUtil'         => DailyReportTransportModel::getArmadaUtilization($facilityID, $Tanggal),
                    'driverUtil'         => DailyReportTransportModel::getDriverUtilization($facilityID, $Tanggal),
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
     * 📍 Ambil daftar facility
     */
    public function getSiteListDaily()
    {
        try {
            $sites = DailyReportTransportModel::getSiteList();

            return response()->json([
                'status' => 'success',
                'data'   => $sites
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal mengambil daftar facility: ' . $e->getMessage()
            ], 500);
        }
    }
}
