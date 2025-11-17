<?php

namespace App\Http\Controllers\Admin\Report\Transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Report\Transport\ModelMonitoringPengiriman;
use Illuminate\Support\Facades\Log;

class MonitoringPengirimanController extends Controller
{
    public function index()
    {
        return view('admin.report.transport.monitoring_pengiriman');
    }

    public function getData(Request $request)
{
    $startDate = $request->input('start_date', date('Y-m-d'));
    $endDate   = $request->input('end_date', date('Y-m-d'));
    $site      = $request->input('site', 'WMWHSE2');
    $owner     = $request->input('owner', 'AHI');

    \Log::info('MonitoringPengirimanController Parameters:', [
        'start_date' => $startDate,
        'end_date' => $endDate,
        'site' => $site,
        'owner' => $owner
    ]);

    try {
        $data = ModelMonitoringPengiriman::getMonitoringPengiriman(
            $startDate,
            $endDate,
            $site,
            $owner
        );

        \Log::info('Data from SP:', ['count' => count($data)]);

        return response()->json([
            'success' => true,
            'data'    => $data,
            'count'   => count($data)
        ]);
    } catch (\Exception $e) {
        \Log::error("MonitoringPengirimanController@getData error", [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'site' => $site,
            'owner' => $owner,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}


    public function getDataFlexible(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $site      = $request->input('site');
        $owner     = $request->input('owner');

        try {
            $data = ModelMonitoringPengiriman::getMonitoringPengiriman(
                $startDate,
                $endDate,
                $site,
                $owner
            );

            return response()->json([
                'success' => true,
                'data'    => $data
            ]);
        } catch (\Exception $e) {
            Log::error("MonitoringPengirimanController@getDataFlexible error", [
                'startDate' => $startDate,
                'endDate'   => $endDate,
                'site'      => $site,
                'owner'     => $owner,
                'error'     => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}