<?php

namespace App\Http\Controllers\Admin\Report\Transport;

use Illuminate\Http\Request;
use App\Models\Admin\Report\Transport\ModelReportDaily;
use App\Http\Controllers\Controller;

class ReportDailyController extends Controller
{
    /**
     * Halaman laporan transport harian
     */
    public function transport(Request $request)
    {
        $state  = $request->input('state', '');
        $date   = $request->input('date', date('Y-m-d'));
        $whseid = $request->input('whseid', '');
        $dcname = $request->input('dcname', 'DC INFORMA JABABEKA');

        $data = ModelReportDaily::getTransportReport($state, $date, $date, $whseid, $dcname);

        return view('admin.report.transport.transport_daily', [
            'data'   => $data,
            'site'   => $whseid,
            'date'   => $date,
            'dcname' => $dcname,
            'state'  => $state,
        ]);
    }

    /**
     * Halaman monitoring driver
     */
    public function monitoring(Request $request)
    {
        $date     = $request->input('date', date('Y-m-d'));
        $site     = $request->input('site', 'DC INFORMA JABABEKA');
        $facility = $request->input('facility', 'ALL');

        $data = ModelReportDaily::getMonitoringDriver($date, $date, $site, $facility);

        return view('admin.report.transport.monitoring_driver', [
            'data'     => $data,
            'date'     => $date,
            'site'     => $site,
            'facility' => $facility,
        ]);
    }
}
