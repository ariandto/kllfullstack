<?php

namespace App\Http\Controllers\Admin\Report\Public\Maintenance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use App\Models\Admin\Report\Public\Maintenance\ModelReportTaskMHE;
use App\Models\Admin\Module\PostUserView;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ReportTaskMHE extends Controller
{
    public function ViewPageReportTaskMHE(Request $request)
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
            $Type = $request->input('Type', '');


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

            // Kirim data ke view
            return view('admin.report.public.maintenance.report_task_mhe');
        } catch (\Exception $e) {
            Log::error('Error load page : ' . $e->getMessage());
            return back()->with([
                'message' => 'Failed to load page .',
                'alert-type' => 'warning'
            ]);
        }
    }

    public function ViewPageSummaryReportTaskMHE(Request $request)
    {
        try {
            // Ambil ID admin yang sedang login
            $id = Auth::guard('admin')->id();
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');
            $StartDate = $request->input('start_date');
            $EndDate = $request->input('end_date');
            // Panggil stored procedure untuk mendapatkan data tabel 
            $dataTables = ModelReportTaskMHE::getSummaryData($facilityID, $Relasi, $StartDate, $EndDate);
            // Kirim data ke view
            //dd(Storage::disk('ftp')->files('/'));
            //dd($dataTables);
            return view('admin.report.public.maintenance.report_task_mhe', [
                'dataTable1' => $dataTables['tabel1'],
                'tableHeaders1' => $dataTables['headers1'],
                'dataTable2' => $dataTables['tabel2'],
                'tableHeaders2' => $dataTables['headers2'],
                'dataTable3' => $dataTables['tabel3'],
                'tableHeaders3' => $dataTables['headers3'],
            ])->with([
                'message' => 'Data berhasil diambil',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {

            // Redirect ke halaman report_throughput dengan pesan error
            return redirect()->route('admin.report.public.summary_reporttaskmhe')
                ->with([
                    'message' => 'Database Sedang Restore, Tunggu Beberapa Saat',
                    'alert-type' => 'warning'
                ]);
        }
    }
}
