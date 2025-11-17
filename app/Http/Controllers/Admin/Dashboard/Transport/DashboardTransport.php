<?php

namespace App\Http\Controllers\Admin\Dashboard\Transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use App\Models\Admin\Module\PostUserView;
use App\Models\Admin\Dashboard\Transport\Modeltransportdashboard;
class DashboardTransport extends Controller
{
    public function dashboardtransport (Request $request )
    {
        try {
              
            $id = Auth::guard('admin')->id();
            $data = Admin::find($id); 
            $userid = $data->userid ?? null;             
            $pagename = 'Dashboard Transport'; 
            $pagenamedetail = $pagename;
            $ipaddress = $request->ip();                
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', ''); 
            $RELASI = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', ''); 
            $Name = $facilityInfo[0]['Name'] ?? $request->input('Name', '');                
         
            if (!$facilityID || !$RELASI) {
                return back()->with('error', 'Facility information not found.');
            }               
            try {
                PostUserView::PostUserViewPage($facilityID, $pagename, $pagenamedetail, $userid, $RELASI, $ipaddress);
            } catch (\Exception $e) {
                Log::error('Error logging user view: ' . $e->getMessage());
                return back()->with('error', 'Failed to log page view.');
            }   
            $dropdownData = Modeltransportdashboard::getFacilities();
            return view('admin.dashboard.transport.dashboard-transport', compact('dropdownData'));     
             } catch (\Exception $e) {
            Log::error('Error in inlinePlan: ' . $e->getMessage());
            return back()->with([
                'message' => 'Database Sedang Restore, Tunggu Beberapa Saat',
                'alert-type' => 'warning'
            ]);
            }
    }
    public function getDashboardData(Request $request)
    {
        $startDate = $request->input('start_date');
        $site = $request->input('site', '');
        $facilityInfo = session('facility_info', []);
        $RELASI = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');  
        $dashboardData = Modeltransportdashboard::getDashboardData($startDate, $site, $RELASI); 
        // dd($dashboardData); 

        $dataPertama = $dashboardData['armada'][0] ?? [];
        $dataKedua = $dashboardData['hitmiss'][0] ?? []; 
        $dataChart1   = $dashboardData['chart1'][0] ?? []; // Internal & External
        $dataChart2  = $dashboardData['chart2'][0] ?? []; // Customer, Store, Hub, Other
        $dataChart3  = $dashboardData['chart3'][0] ?? [];
        $datatrip = $dashboardData['tripext'] ?? [];
        $datatop10 = $dashboardData['top10'] ?? [];
        $dataujp = $dashboardData['ujp'] [0] ?? [];
        $datapending = $dashboardData['pending'] [0] ?? [];

        
        $labels = [
            'asset'         => intval($dataPertama['total_armada'] ?? 0),
            'totaltrip'     => intval($dataPertama['total_trip'] ?? 0),
            'ratio'         => floatval($dataPertama['trip_ratio'] ?? 0),
            'available'     => intval($dataPertama['total_available'] ?? 0),
            'not_available' => intval($dataPertama['total_not_available'] ?? 0),
            'idle'          => intval($dataPertama['total_idle_armada'] ?? 0),                
        ];
        $labels2 = [
            'ujptotal'      => intval($dataujp['Total UJP'] ?? 0),
            'ujpcust'       => intval($dataujp['UJP Costumer'] ?? 0),
            'ujpstore'      => intval($dataujp['UJP Store'] ?? 0),
            'ujphub'        => intval($dataujp['UJP HUB'] ?? 0),
            'ujpother'      => intval($dataujp['UJP Other'] ?? 0),
            'ujptripcust'   => intval($dataujp['UJP/Trip Cust'] ?? 0), 
            'ujptripstore'  => intval($dataujp['UJP/Trip Store'] ?? 0), 
            'ujptriphub'    => intval($dataujp['UJP/Trip HUB'] ?? 0), 
        ];
        $labels3 = [
            'totaldo'           => intval($datapending['Total DO Customer'] ?? 0),
            'pendingint'        => intval($datapending['Pending Internal'] ?? 0),
            'pendingext'        => intval($datapending['Pending External'] ?? 0),
            'dokirim'           => intval($datapending['Do Terkirim'] ?? 0),
            'dokirimberhasil'   => floatval($datapending['Kebehasilan Kirim'] ?? 0),
            'internal'          => floatval($datapending['%Internal'] ?? 0), 
            'external'          => floatval($datapending['%External'] ?? 0), 
        ];
        $charts = [
            'customer' => [
                'labels' => ['Customer Hit', 'Customer Miss'],
                'data' => [
                    intval($dataKedua['Customer Hit Int'] ?? 0), 
                    intval($dataKedua['Customer Miss Int'] ?? 0)
                ]
            ],
            'store' => [
                'labels' => ['Store Hit', 'Store Miss'],
                'data' => [
                    intval($dataKedua['Store Hit Int'] ?? 0), 
                    intval($dataKedua['Store Miss Int'] ?? 0)
                ]
            ],
            'external' => [
                'labels' => ['External Hit', 'External Miss'],
                'data' => [
                    intval($dataKedua['External Hit'] ?? 0), 
                    intval($dataKedua['External Miss'] ?? 0)
                ]
            ]
        ];
    
        $barCharts = [
            'chart1' => [
                'labels' => ['INTERNAL', 'EXTERNAL'],
                'data' => [
                    intval($dataChart1['INTERNAL'] ?? 0),
                    intval($dataChart1['EXTERNAL'] ?? 0)
                ]
            ],
            'chart2' => [
                'labels' => ['CUSTOMER', 'STORE', 'HUB', 'OTHER'],
                'data' => [
                    intval($dataChart2['CUSTOMER'] ?? 0),
                    intval($dataChart2['STORE'] ?? 0),
                    intval($dataChart2['HUB'] ?? 0),
                    intval($dataChart2['OTHER'] ?? 0)
                ]
            ],
            'chart3' => [
                'labels' => ['SEA', 'LAND', 'AIR'],
                'data' => [
                    intval($dataChart3['SEA'] ?? 0),
                    intval($dataChart3['LAND'] ?? 0),
                    intval($dataChart3['AIR'] ?? 0)
                ]
            ]        
        ];
        $barTripData = [
            'tripExt' => [
                'labels' => array_column($datatrip, 'JENISARMADA'), 
                'data' => array_map('intval', array_column($datatrip, 'Total'))
            ],
            'top10' => [
                'labels' => array_column($datatop10, 'Jalur'), 
                'data' => array_map('intval', array_column($datatop10, 'Total'))
            ]
        ];
        return response()->json([
            'labels' => $labels,
            'label2' =>  $labels2,
            'label3' =>  $labels3,
            'charts' => $charts,
            'barCharts' => $barCharts,
            'barTripData' => $barTripData        
        ]);
    }   
    
    
    
}
