<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReportDaily;

class ReportDailyController extends Controller
{
    public function transport(Request $request)
    {
        $site = $request->input('site', 'WMWHSE4RTL');
        $date = $request->input('date', date('Y-m-d'));

        $data = ReportDaily::getTransportReport($site, $date, $date);

        return response()->json($data);
    }

    public function monitoring(Request $request)
    {
        $site     = $request->input('site', 'DC INFORMA JABABEKA');
        $facility = $request->input('facility', 'ALL');
        $date     = $request->input('date', date('Y-m-d'));

        $data = ReportDaily::getMonitoringDriver($date, $date, $site, $facility);

        return response()->json($data);
    }
}
