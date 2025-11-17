<?php

namespace App\Http\Controllers\Admin\Dashboard\Transport;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Admin\Dashboard\Transport\ModelInlinePlan;
use App\Models\Admin\Module\PostUserView;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class DashboardInLinePlan extends Controller
{
    public function inlinePlan(Request $request)
    {
        try {

            $id = Auth::guard('admin')->id();
            $data = Admin::find($id);
            $userid = $data->userid ?? null;
            $pagename = 'InLine Plan';
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
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $owners = $request->input('owners', []);
            $dataGrid = ($startDate && $endDate) ? ModelInlinePlan::getInLinePlanning($startDate, $endDate, $Name, $owners, $RELASI) : [];
            $facilities = ModelInlinePlan::getFacilities($Name);
            $columns = $dataGrid ? array_keys(get_object_vars($dataGrid[0])) : [];

            return view('admin.dashboard.transport.inline-plan', compact('facilities', 'dataGrid', 'columns', 'Name'));
        } catch (\Exception $e) {
            Log::error('Error in inlinePlan: ' . $e->getMessage());
            return back()->with([
                'message' => 'Database Sedang Restore, Tunggu Beberapa Saat',
                'alert-type' => 'warning'
            ]);
        }
    }
    public function getDetail(Request $request)
    {
        $noLc = $request->query('no_lc');

        if (!$noLc) {
            return response()->json(['error' => 'No LC is required'], 400);
        }

        try {
            // Panggil SP untuk mengambil data detail LC
            $result = ModelInlinePlan::getLcDetail($noLc);

            return response()->json($result);
        } catch (\Exception $e) {
            // Log error jika ada
            Log::error('Error fetching LC detail: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching data: ' . $e->getMessage()], 500);
        }
    }
}
