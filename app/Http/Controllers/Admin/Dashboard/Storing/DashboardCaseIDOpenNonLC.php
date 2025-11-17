<?php

namespace App\Http\Controllers\Admin\Dashboard\Storing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use App\Models\Admin\Dashboard\Storing\ModelDashboardCaseIDNonLC;
use App\Models\Admin\Module\PostUserView;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Module\GetOwner;
use App\Models\Admin\Module\GetTypeOrder;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Str;


class DashboardCaseIDOpenNonLC extends Controller
{
    public function ViewPageDashboardCaseIDNonLC(Request $request)
    {
        try {
            //Mengambil nilai id yang sedang login
            $id = Auth::guard('admin')->id();
            $data = Admin::find($id);
            $userid = $data->userid;

            // Ambil PageName dari request
            $pagename = $request->query('PageName', '');
            $pagenamedetail = $pagename;
            $ipaddress = $request->ip();

            // Ambil nilai Facility_ID, Relasi, dan Type dari session atau request
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
            $RELASI = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');
            $Type = $request->input('Type', ''); // Ambil parameter Type dari request atau default kosong 

            // // Validasi: Jika Facility_ID adalah WMWHSE2 dan Relasi diakhiri dengan 'IND', blok akses
            // if ($facilityID === 'WMWHSE2' && Str::endsWith($RELASI, 'IND')) {
            //     return redirect()->back()->with('pagerestricted', 'Page Ini Tidak Tersedia Buat Site Ini');
            // }  

            if (session()->has("dataowner_$id") && session()->has("datatypeorder_$id")) {
                // Jika data sudah ada di session, ambil dari session
                $dataowner = session("dataowner_$id");
                $datatypeorder = session("datatypeorder_$id");
            } else {
                // Jika belum ada, query ke database dan simpan di session
                $dataowner = GetOwner::getOwnerData($facilityID, $RELASI, $Type);
                $datatypeorder = GetTypeOrder::GetTypeOrder($facilityID, $RELASI);

                // Simpan data di session menggunakan ID pengguna
                session(["dataowner_$id" => $dataowner]);
                session(["datatypeorder_$id" => $datatypeorder]);
            }

            // Jika tidak ada nilai yang diperlukan, set default atau tampilkan pesan error
            if (!$facilityID || !$RELASI) {
                return back()->with('error', 'Facility information not found.');
            }

            // Panggil stored procedure untuk insert log
            try {
                PostUserView::PostUserViewPage($facilityID, $pagename, $pagenamedetail, $userid, $RELASI, $ipaddress);
            } catch (\Exception $e) {
                Log::error('Error logging user view: ' . $e->getMessage());
                return back()->with('error', 'Failed to log page view.');
            }

            //dd($datadamage_from);
            $tableHeaders1 = [];
            // Kirim data ke view
            return view('admin.dashboard.storing.dashboard_caseid_open_nonlc', compact('dataowner', 'datatypeorder'));
        } catch (\Exception $e) {
            Log::error('Error load page : ' . $e->getMessage());
            return back()->with([
                'message' => 'Failed to load page .',
                'alert-type' => 'warning'
            ]);
        }
    }

    public function ViewPageSummaryDashboardCaseIDNonLC(Request $request)
    {
        try {
            // Ambil ID admin yang sedang login
            $id = Auth::guard('admin')->id();
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');

            $Owner = $request->input('selected_owners', ['ALL']);
            if (is_array($Owner)) {
                // Jika array, gabungkan menjadi string dengan delimiter ";"
                $ownerString = implode(';', $Owner);
            } else {
                // Jika bukan array, langsung gunakan sebagai string
                $ownerString = $Owner;
            }

            $TypeOrder = $request->input('selected_typeorders', ['ALL']);
            if (is_array($TypeOrder)) {
                // Jika array, gabungkan menjadi string dengan delimiter ";"
                $TypeOrderString = implode(';', $TypeOrder);
            } else {
                // Jika bukan array, langsung gunakan sebagai string
                $TypeOrderString = $TypeOrder;
            }

            // Validasi input owner
            $validator = Validator::make($request->all(), [
                'selected_owners' => 'required',
                'selected_typeorders' => 'required',
            ]);

            if ($validator->fails()) {
                // Mengarahkan kembali dengan pesan error
                return redirect()->back()->with([
                    'message' => 'Owner atau Type Order Belum Di Pilih',
                    'alert-type' => 'warning'
                ]);;
            }

            $id = Auth::guard('admin')->id();

            // Simpan data ke session dengan key yang termasuk ID admin
            session()->put("input_data_caseidopennonlc_$id", [
                'selected_owners' => is_array($Owner) ? $Owner : [$Owner],
                'selected_typeorders' => is_array($TypeOrder) ? $TypeOrder : [$TypeOrder],
            ]);

            $StartDate = $request->input('start_date');
            $EndDate = $request->input('end_date');
            $Key1 = $request->input('tanggal');
            $Key2 = $request->input('status');
            // Panggil stored procedure untuk mendapatkan data tabel 
            $dataTables = ModelDashboardCaseIDNonLC::getSummaryData($facilityID, $Relasi, $ownerString, $TypeOrderString, $StartDate, $EndDate, $Key1, $Key2);
            // Kirim data ke view

            //dd($request);
            return view('admin.dashboard.storing.dashboard_caseid_open_nonlc', [
                'dataTable1' => $dataTables['tabel1'],
                'tableHeaders1' => $dataTables['headers1'],
                'dataTable2' => $dataTables['tabel2'],
                'tableHeaders2' => $dataTables['headers2'],
            ])

                ->with([
                    'message' => 'Data berhasil diambil',
                    'alert-type' => 'success'
                ]);
        } catch (\Exception $e) {

            // Redirect ke halaman report_throughput dengan pesan error
            return redirect()->route('admin.dashboard.storing.summary_dashboardcaseidopennonlc')
                ->with([
                    'message' => 'Database Sedang Restore, Tunggu Beberapa Saat',
                    'alert-type' => 'warning'
                ]);
        }
    }

    public function ViewPageDetailDashboardCaseIDNonLC(Request $request)
    {
        try {
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');

            $id = Auth::guard('admin')->id();
            $sessionData = session()->get("input_data_caseidopennonlc_$id", []);
            $Owner = $sessionData['selected_owners'] ?? [];
            if (is_array($Owner)) {
                // Jika array, gabungkan menjadi string dengan delimiter ";"
                $ownerString = implode(';', $Owner);
            } else {
                // Jika bukan array, langsung gunakan sebagai string
                $ownerString = $Owner;
            }
            $TypeOrder = $sessionData['selected_typeorders'] ?? [];
            if (is_array($TypeOrder)) {
                // Jika array, gabungkan menjadi string dengan delimiter ";"
                $TypeOrderString = implode(';', $TypeOrder);
            } else {
                // Jika bukan array, langsung gunakan sebagai string
                $TypeOrderString = $TypeOrder;
            }

            $StartDate = $request->input('start_date');
            $EndDate = $request->input('end_date');

            $Key1 = $request->input('tanggal');
            $Key2 = $request->input('status');

            // dd($StartDate, $EndDate, $Key1, $Key2, $TypeOrder);
            // Eksekusi stored procedure
            $dataTables = ModelDashboardCaseIDNonLC::getDetailData($facilityID, $Relasi, $ownerString, $TypeOrderString, $StartDate, $EndDate, $Key1, $Key2);
            // dd($dataTables);
            return response()->json([
                'success' => true,
                'data' => $dataTables['tabel3'],
                'headers' => $dataTables['headers3']
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan, silakan coba lagi'
            ]);
        }
    }
}
