<?php

namespace App\Http\Controllers\Admin\Dashboard\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use App\Models\Admin\Dashboard\Public\ModelDashboardPerformanceMHE;
use App\Models\Admin\Module\PostUserView;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class DashboardPerformanceMHE extends Controller
{
    public function ViewPageDashboardPerformanceMHE(Request $request)
    {
        try {

            $id = Auth::guard('admin')->id();
            $data = Admin::find($id);
            $userid = $data->userid;

            $pagename = $request->query('PageName', '');
            $pagenamedetail = $pagename;
            $ipaddress = $request->ip();

            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
            $RELASI = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');

            if (!$facilityID || !$RELASI) {
                return back()->with('error', 'Facility information not found.');
            }

            // Simpan log view
            try {
                PostUserView::PostUserViewPage($facilityID, $pagename, $pagenamedetail, $userid, $RELASI, $ipaddress);
            } catch (\Exception $e) {
                Log::error('Error logging user view: ' . $e->getMessage());
                return back()->with('error', 'Failed to log page view.');
            }

            // Ambil data DC Name dan simpan ke session
            $dcNames = ModelDashboardPerformanceMHE::getDCName();
            session(['datadcname_' . $id => $dcNames]);

            // dd($unitTypes);
            return view('admin.dashboard.public.dashboardperformanceMHE', compact('dcNames'));
        } catch (\Exception $e) {
            Log::error('Error load page : ' . $e->getMessage());
            return back()->with([
                'message' => 'Failed to load page.',
                'alert-type' => 'warning',
            ]);
        }
    }
    public function ViewPageSummaryDashboardPerformanceMHE(Request $request)
    {
        try {
            // Ambil array DC name dari checkbox
            $selectedDCNames = $request->input('selected_dcnames', []);
            $dcNamesString = implode(';', $selectedDCNames);
            $selectedUnitTypes = $request->input('selected_unittypes', []);
            $unitTypesString = implode(';', $selectedUnitTypes);

            $dataTables = ModelDashboardPerformanceMHE::getSummaryData($dcNamesString, $unitTypesString);

            return view('admin.dashboard.public.dashboardperformancemhe', [
                'dataTable1' => $dataTables['tabel1'],
                'tableHeaders1' => $dataTables['headers1'],
                'dataTable2' => $dataTables['tabel2'],
                'tableHeaders2' => $dataTables['headers2'],
                'dataTable3' => $dataTables['tabel3'],
                'tableHeaders3' => $dataTables['headers3'],
                'dataTable4' => $dataTables['tabel4'],
                'tableHeaders4' => $dataTables['headers4'],
                'selectedDCNames' => $selectedDCNames,
                'selectedUnitTypes' => $selectedUnitTypes,
            ])->with([
                'message' => 'Data berhasil diambil',
                'alert-type' => 'success',
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching summary data: ' . $e->getMessage());
            return redirect()
                ->route('admin.dashboard.public.summary_dashboardperformancemhe')
                ->with([
                    'message' => 'Database Sedang Restore, Tunggu Beberapa Saat',
                    'alert-type' => 'warning',
                ]);
        }
    }
    public function ViewGetUnitType(Request $request)
    {
        try {
            $dcNames = $request->input('dcNames');

            if (!is_array($dcNames) || empty($dcNames)) {
                return response()->json(['error' => 'DC Name tidak boleh kosong.'], 422);
            }

            $dcNamesString = implode(';', $dcNames);
            $data = ModelDashboardPerformanceMHE::getUnitType($dcNamesString);
            // Simpan ke session
            $adminId = Auth::guard('admin')->id(); // pastikan guard admin
            session(['dataunittype_' . $adminId => $data]);

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil data Unit Type: ' . $e->getMessage()], 500);
        }
    }
}
