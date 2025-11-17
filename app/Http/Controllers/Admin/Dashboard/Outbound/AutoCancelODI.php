<?php

namespace App\Http\Controllers\Admin\Dashboard\Outbound;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use App\Models\Admin\Dashboard\Outbound\ModelAutoCancelODI;
use App\Models\Admin\Module\GetOwner;
use App\Models\Admin\Module\PostUserView;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class AutoCancelODI extends Controller
{
    public function ViewPageDashboardAutoCancelODI(Request $request)
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

            // Cek apakah data sudah ada di session berdasarkan ID pengguna
            if (session()->has("dataowner_$id")) {
                // Jika data sudah ada di session, ambil dari session
                $dataowner = session("dataowner_$id");
            } else {
                // Jika belum ada, query ke database dan simpan di session
                $dataowner = GetOwner::getOwnerData($facilityID, $RELASI, $Type);
                // Simpan data di session menggunakan ID pengguna
                session(["dataowner_$id" => $dataowner]);
            }

            //dd($datadamage_from);
            $tableHeaders1 = [];
            // Kirim data ke view
            return view('admin.dashboard.outbound.dashboard_auto_cancel_odi', compact('dataowner'));
        } catch (\Exception $e) {
            Log::error('Error load page : ' . $e->getMessage());
            return back()->with([
                'message' => 'Failed to load page .',
                'alert-type' => 'warning'
            ]);
        }
    }

    public function ViewPageSummaryDashboardAutoCancelODI(Request $request)
    {
        try {
            // Ambil ID admin yang sedang login
            $id = Auth::guard('admin')->id();
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');
            $StartDate = $request->input('start_date');
            $Owner = $request->input('selected_owners', ['ALL']);
            if (is_array($Owner)) {
                // Jika array, gabungkan menjadi string dengan delimiter ";"
                $ownerString = implode(';', $Owner);
            } else {
                // Jika bukan array, langsung gunakan sebagai string
                $ownerString = $Owner;
            }

            $Key1 = $request->input('kurir');
            $Key2 = $request->input('jam');
            // Validasi input owner
            $validator = Validator::make($request->all(), [
                'selected_owners' => 'required',
            ]);

            if ($validator->fails()) {
                // Mengarahkan kembali dengan pesan error
                return redirect()->back()->with([
                    'message' => 'Owner Belum Di Pilih',
                    'alert-type' => 'warning'
                ]);;
            }


            // Simpan data ke session dengan key yang termasuk ID admin
            session()->put("input_dataac_{$id}_{$Relasi}", [
                'start_date' => $StartDate,
                'selected_owners' => is_array($Owner) ? $Owner : [$Owner],

            ]);


            //dd(session("input_databr_{$id}_{$Relasi}"));
            // Panggil stored procedure untuk mendapatkan data tabel 
            $dataTables = ModelAutoCancelODI::getSummaryData($facilityID, $Relasi, $ownerString, $StartDate,  $Key1, $Key2);

            //dd($dataTables);
            // dd($dataTables );
            // Kirim data ke view
            return view('admin.dashboard.outbound.dashboard_auto_cancel_odi', [
                'dataTable1' => $dataTables['tabel1'],
                'tableHeaders1' => $dataTables['headers1'],
            ])
                ->with([
                    'message' => 'Data berhasil diambil',
                    'alert-type' => 'success'
                ]);
        } catch (\Exception $e) {

            // Redirect ke halaman report_throughput dengan pesan error
            return redirect()->route('admin.dashboard.outbound.summary_autocancelodi')
                ->with([
                    'message' => 'Database Sedang Restore, Tunggu Beberapa Saat',
                    'alert-type' => 'warning'
                ]);
        }
    }

    public function ViewPageDetailDashboardAutoCancelODI(Request $request)
    {
        try {
            $id = Auth::guard('admin')->id();
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');

            // Ambil Start Date dan Owner dari session berdasarkan Facility_ID & Relasi
            $sessionData = session("input_dataac_{$id}_{$Relasi}", []);

            $StartDate = $sessionData['start_date'] ?? null;
            $Owner = $sessionData['selected_owners'] ?? null;

            if (is_array($Owner)) {
                $ownerString = implode(';', $Owner);
            } else {
                $ownerString = $Owner;
            }

            if (!$StartDate || !$ownerString) {
                return response()->json([
                    'success' => false,
                    'message' => 'Start Date atau Owner tidak ditemukan dalam session!'
                ]);
            }

            // Ambil Key1 & Key2 dari request (dikirim dari AJAX)
            $Key1 = $request->input('kurir');
            $Key2 = $request->input('jam');

            // Eksekusi stored procedure
            $dataTables = ModelAutoCancelODI::getDetailData($facilityID, $Relasi, $ownerString, $StartDate, $Key1, $Key2);

            return response()->json([
                'success' => true,
                'data' => $dataTables['tabel2'],
                'headers' => $dataTables['headers2']
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan, silakan coba lagi'
            ]);
        }
    }
}
