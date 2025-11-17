<?php

namespace App\Http\Controllers\Admin\Report\Transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use App\Models\Admin\Report\Transport\ModelMonitoringTimeStampProsesDC;
use App\Models\Admin\Module\PostUserView;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Str;

class MonitoringTimeStampProsesDC extends Controller
{
    public function ViewPageReportTimeStampLC(Request $request)
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

            //dd($datadamage_from);
            $tableHeaders1 = [];
            // Kirim data ke view
            return view('admin.report.transport.reportimestampprosesdclc');
        } catch (\Exception $e) {
            Log::error('Error load page : ' . $e->getMessage());
            return back()->with([
                'message' => 'Failed to load page .',
                'alert-type' => 'warning'
            ]);
        }
    }

    public function ViewPageSummaryReportTimeStampLC(Request $request)
    {
        try {
            // Ambil ID admin yang sedang login
            $id = Auth::guard('admin')->id();
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');
            $StartDate = $request->input('start_date');
            $EndDate = $request->input('end_date');
            $Key1 = $request->input('type_lc');
            // Panggil stored procedure untuk mendapatkan data tabel 
            $dataTables = ModelMonitoringTimeStampProsesDC::getSummaryData($facilityID, $Relasi,  $StartDate, $EndDate, $Key1);
            // Kirim data ke view 
            //dd($dataTables);
            return view('admin.report.transport.reportimestampprosesdclc', [
                'dataTable1' => $dataTables['tabel1'],
                'tableHeaders1' => $dataTables['headers1'],
                // 'dataTable2' => $dataTables['tabel2'],
                // 'tableHeaders2' => $dataTables['headers2'],
            ])

                ->with([
                    'message' => 'Data berhasil diambil',
                    'alert-type' => 'success'
                ]);
        } catch (\Exception $e) {

            // Redirect ke halaman report_throughput dengan pesan error
            return redirect()->route('admin.report.transport.summary_reportimestampprosesdclc')
                ->with([
                    'message' => 'Database Sedang Restore, Tunggu Beberapa Saat',
                    'alert-type' => 'warning'
                ]);
        }
    }

    public function ViewPageDetailReportTimeStampLC(Request $request)
    {
        try {
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');
            $StartDate = $request->input('start_date');
            $EndDate = $request->input('end_date');
            $Key1 = $request->input('type_lc');

            //dd($StartDate, $EndDate, $Key1, $Key2);
            // Eksekusi stored procedure
            $dataTables = ModelMonitoringTimeStampProsesDC::getDetailData($facilityID, $Relasi,  $StartDate, $EndDate, $Key1);
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
