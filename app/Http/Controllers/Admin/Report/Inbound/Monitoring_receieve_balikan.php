<?php

namespace App\Http\Controllers\Admin\Report\Inbound;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use App\Models\Admin;
use App\Models\Admin\Report\Inbound\ModelMonitoringReceieveBalikan;
use App\Models\Admin\Module\GetRandomData;
use App\Models\Admin\Module\GetSiteS2Support;
use App\Models\Admin\Module\PostUserView;
use App\Models\Admin\Report\Inventory\Throughput;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class Monitoring_receieve_balikan extends Controller
{
    public function ViewPagemonitoringreceievebalikan(Request $request)
    {
        return view('admin.Report.inbound.report_receieve_balikan');
    }
    public function ViewPageSummaryreceivebalikan(Request $request)
    {

        $facilityInfo = session('facility_info', []);
        $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
        $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');
        $StartDate = $request->input('start_date');
        $EndDate = $request->input('end_date');
        $notto = $request->input('nomor_tto');
        $sku = $request->input('nomor_sku');
        $loc = $request->input('Loc');
        $lpn = $request->input('noLPN');

        $datatabel = ModelMonitoringReceieveBalikan::getReceieveData($facilityID, $StartDate, $EndDate, $Relasi, $notto, $sku, $loc, $lpn);


        return view('admin.report.inbound.Report_receieve_balikan', [
            'datatabel1' => $datatabel['tabel1'],
            'tabelheaders1' => $datatabel['headers1']
        ]);
    }
    
}