<?php

namespace App\Http\Controllers\Admin\Report\Outbound;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use App\Models\Admin\Report\Outbound\ModelKontributorLPPB;
use App\Models\Admin\Module\PostUserView;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Str;

class ReportKontributorLPPB extends Controller
{
    public function ViewPageReportkontributorlppb(Request $request)
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

            // Validasi: Jika Facility_ID adalah WMWHSE2 dan Relasi diakhiri dengan 'IND', blok akses $facilityID !== 'WMWHSE2' && 
            if (Str::endsWith($RELASI, 'RTL')) {
                return redirect()->back()->with('pagerestricted', 'Page Ini Tidak Tersedia Buat Site Ini');
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
            $StartDate = '';
            $EndDate = '';
            $Key1 = '';
            $Key2 = '';
            $Key3 = '';
            $deptname = ModelKontributorLPPB::getDeptName($facilityID, $RELASI, $StartDate, $EndDate,  $Key1, $Key2, $Key3);
            // Kirim data ke view
            return view('admin.report.outbound.report_kontributor_lppb', compact('deptname'));
        } catch (\Exception $e) {
            Log::error('Error load page : ' . $e->getMessage());
            return back()->with([
                'message' => 'Failed to load page .',
                'alert-type' => 'warning'
            ]);
        }
    }

    public function ViewPageSummaryReportkontributorlppb(Request $request)
    {
        try {
            // Ambil ID admin yang sedang login
            $id = Auth::guard('admin')->id();
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');
            $StartDate = $request->input('start_date');
            $EndDate = $request->input('end_date');
            $Key1 = $request->input('deptname');
            $Key2 = '';
            $Key3 = '';

            $deptname = ModelKontributorLPPB::getDeptName($facilityID, $Relasi, $StartDate, $EndDate,  $Key1, $Key2, $Key3);
            // Panggil stored procedure untuk mendapatkan data tabel 
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'deptname' => 'required|string',
            ]);

            $dataTables = ModelKontributorLPPB::getSummaryData($facilityID, $Relasi, $StartDate, $EndDate,  $Key1, $Key2, $Key3);
            // Kirim data ke view

            //dd($dataTables);
            return view('admin.report.outbound.report_kontributor_lppb', [
                'dataTable1' => $dataTables['tabel1'],
                'tableHeaders1' => $dataTables['headers1'],
                'dataTable2' => $dataTables['tabel2'],
                'tableHeaders2' => $dataTables['headers2'],
                'deptname' => $deptname, // Kirim kembali daftar deptname
                'selectedDept' => $Key1, // Kirim pilihan yang terakhir dipilih
            ])

                ->with([
                    'message' => 'Data berhasil diambil',
                    'alert-type' => 'success'
                ]);
        } catch (\Exception $e) {

            // Redirect ke halaman report_throughput dengan pesan error
            return redirect()->route('admin.report.outbound.summary_reportkontributorlppb')
                ->with([
                    'message' => 'Database Sedang Restore, Tunggu Beberapa Saat',
                    'alert-type' => 'warning'
                ]);
        }
    }

    public function ViewPageDetailReportkontributorlppb(Request $request)
    {
        try {
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');
            $StartDate = $request->input('start_date');
            $EndDate = $request->input('end_date');

            $Key2 = $request->input('nik');
            $Key3 = $request->input('kategori');
            $Key1 = $request->input('deptname');
            //dd($StartDate, $EndDate, $Key1, $Key2);
            // Eksekusi stored procedure
            $dataTables = ModelKontributorLPPB::getDetailData($facilityID, $Relasi, $StartDate, $EndDate, $Key1, $Key2, $Key3);
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
