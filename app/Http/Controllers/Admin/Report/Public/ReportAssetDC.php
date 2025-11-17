<?php

namespace App\Http\Controllers\Admin\Report\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use App\Models\Admin\Report\Public\ModelReportAssetDC;
use App\Models\Admin\Module\PostUserView;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ReportAssetDC extends Controller
{
    public function ViewPageReportAssetDC(Request $request)
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
            $dcNames = ModelReportAssetDC::getDCName();
            session(['datadcname_' . $id => $dcNames]);

            // dd($unitTypes);
            return view('admin.report.public.report_asset_dc', compact('dcNames'));
        } catch (\Exception $e) {
            Log::error('Error load page : ' . $e->getMessage());
            return back()->with([
                'message' => 'Failed to load page.',
                'alert-type' => 'warning',
            ]);
        }
    }

    public function ViewPageSummaryReportAssetDC(Request $request)
    {
        try {
            // Ambil array DC name dari checkbox
            $selectedDCNames = $request->input('selected_dcnames', []);
            $dcNamesString = implode(';', $selectedDCNames);
            $selectedUnitTypes = $request->input('selected_unittypes', []);
            $unitTypesString = implode(';', $selectedUnitTypes);

            $dataTables = ModelReportAssetDC::getSummaryData($dcNamesString, $unitTypesString);

            return view('admin.report.public.report_asset_dc', [
                'dataTable1' => $dataTables['tabel1'],
                'tableHeaders1' => $dataTables['headers1'],
                'dataTable2' => $dataTables['tabel2'],
                'tableHeaders2' => $dataTables['headers2'],
                'dataTable3' => $dataTables['tabel3'],
                'tableHeaders3' => $dataTables['headers3'],
                'selectedDCNames' => $selectedDCNames,
                'selectedUnitTypes' => $selectedUnitTypes,
            ])->with([
                'message' => 'Data berhasil diambil',
                'alert-type' => 'success',
            ]);
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.report.public.summary_reportassetdc')
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
            $data = ModelReportAssetDC::getUnitType($dcNamesString);
            // Simpan ke session
            $adminId = Auth::guard('admin')->id(); // pastikan guard admin
            session(['dataunittype_' . $adminId => $data]);

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil data Unit Type: ' . $e->getMessage()], 500);
        }
    }
}
