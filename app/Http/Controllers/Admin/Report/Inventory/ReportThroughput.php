<?php

namespace App\Http\Controllers\Admin\Report\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Admin\Module\GetOwner;
use App\Models\Admin;
use App\Models\Admin\Module\PostUserView;
use App\Models\Admin\Report\Inventory\Throughput;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class ReportThroughput extends Controller
{
    public function ViewPageThroughput(Request $request)
    {
        try {
            // Start Section Untuk Insert Ke Log User Ok
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

            // End Section Untuk Insert Ke Log User Ok

            // Ambil data dari stored procedure menggunakan nilai dari session
            //Ini Menampilkan Data Owner 
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

            //End Section Menampilkan Data Owner  

            // Kirim data ke view
            return view('admin.report.inventory.report_throughput', compact('dataowner'));
        } catch (\Exception $e) {
            Log::error('Error load page : ' . $e->getMessage());
            return back()->with([
                'message' => 'Failed to load page .',
                'alert-type' => 'warning'
            ]);
        }
    }

    public function ViewSummaryThroughput(Request $request)
    {
        try {

            // Ambil nilai Facility_ID, Relasi, dan Type dari session atau request
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');
            $StartDate = $request->input('start_date');
            $EndDate = $request->input('end_date');
            $ViewBy = $request->input('view_by');
            $Owner = $request->input('selected_owners', ['ALL']);
            if (is_array($Owner)) {
                // Jika array, gabungkan menjadi string dengan delimiter ";"
                $ownerString = implode(';', $Owner);
            } else {
                // Jika bukan array, langsung gunakan sebagai string
                $ownerString = $Owner;
            }


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

            // Ambil ID admin yang sedang login
            $id = Auth::guard('admin')->id();
            // Simpan data ke session dengan key yang termasuk ID admin
            session()->put("input_data_$id", [
                'start_date' => $StartDate,
                'end_date' => $EndDate,
                'selected_owners' => is_array($Owner) ? $Owner : [$Owner],
                'view_by' => $ViewBy
            ]);
            // Panggil stored procedure untuk mendapatkan data tabel
            $dataTable = Throughput::getSummaryData($facilityID, $Relasi, $StartDate, $EndDate, $ownerString, $ViewBy);
            // Kirim data ke view
            return view('admin.report.inventory.report_throughput', compact('dataTable'))
                ->with([
                    'message' => 'Data berhasil diambil',
                    'alert-type' => 'success'
                ]);
        } catch (\Exception $e) {

            // Redirect ke halaman report_throughput dengan pesan error
            return redirect()->route('admin.report.inventory.throughput')
                ->with([
                    'message' => 'Database Sedang Restore, Tunggu Beberapa Saat',
                    'alert-type' => 'warning'
                ]);
        }
    }
}
