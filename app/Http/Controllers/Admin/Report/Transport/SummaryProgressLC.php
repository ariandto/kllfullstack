<?php

namespace App\Http\Controllers\Admin\Report\Transport;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Admin\Module\PostUserView;
use App\Models\Admin\Report\Transport\ModelSummaryProgressLC;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 


class SummaryProgressLC extends Controller
{
    public function loadPage(Request $request)
    {
        try {
            // Mendapatkan informasi user dan fasilitas
            $id = Auth::guard('admin')->id();
            $data = Admin::find($id);
            $userid = $data->userid ?? null;
            $pagename = 'Summary Progress LC';
            $pagenamedetail = $pagename;
            $ipaddress = $request->ip();
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
            $RELASI = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');
            $Name = $facilityInfo[0]['Name'] ?? $request->input('Name', '');        
            if (!$facilityID || !$RELASI) {
                return back()->with('error', 'Facility information not found.');
            }
            PostUserView::PostUserViewPage($facilityID, $pagename, $pagenamedetail, $userid, $RELASI, $ipaddress);           
            $ownersList = ModelSummaryProgressLC::getFacilities($Name);
            return view('admin.report.transport.summaryprogresslc', compact('ownersList','Name'));
        } catch (\Exception $e) {
            Log::error('Error in loadPage: ' . $e->getMessage());
            return back()->with('error', 'Failed to load page.');
        }
    }

   
    public function showData(Request $request)
    {
        try {
            $id = Auth::guard('admin')->id();
            $data = Admin::find($id);
            $userid = $data->userid ?? null;
            $pagename = 'Summary Progress LC';
            $pagenamedetail = $pagename;
            $ipaddress = $request->ip();
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
            $RELASI = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');
            $Name = $facilityInfo[0]['Name'] ?? $request->input('Name', '');     
            if (!$facilityID || !$RELASI) {
                return back()->with('error', 'Facility information not found.');
            }        
            $ownersList = ModelSummaryProgressLC::getFacilities($Name);
            $viewType = $request->input('viewtype', 'CBMINF');
            $startDate = $request->input('start_date', now()->toDateString());
            $endDate = $request->input('end_date', now()->toDateString());
            $owner = $request->input('owner', '');

            $dataGrid = ModelSummaryProgressLC::getSummaryProgressLC($viewType, $startDate, $endDate, $RELASI, [$owner], $Name);   
            
            return view('admin.report.transport.summaryprogresslc', compact('dataGrid' ,'ownersList','Name'));


        } catch (\Exception $e) {
            Log::error('Error in showData 3: ' . $e->getMessage());
            return back()->with('error', 'Failed to load data.');
        }
    }
    
}
