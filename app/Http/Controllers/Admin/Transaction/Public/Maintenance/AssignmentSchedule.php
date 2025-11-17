<?php

namespace App\Http\Controllers\Admin\Transaction\Public\Maintenance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use App\Models\Admin\Module\PostUserView;
use App\Models\Admin\Transaction\Public\Maintenance\ModelAssignmentSchedule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class AssignmentSchedule extends Controller
{

    public function ViewPageAssignmentSchedule(Request $request)
    {
        try {

            $id = Auth::guard('admin')->id();
            $data = Admin::find($id);
            $UserID = $data->userid;
            // Ambil PageName dari request
            $pagename = $request->query('PageName', '');
            $pagenamedetail = $pagename;
            $ipaddress = $request->ip();

            // Ambil nilai Facility_ID, Relasi, dan Type dari session atau request
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');
            $Type = $request->input('Type', ''); // Ambil parameter Type dari request atau default kosong 

            // Jika tidak ada nilai yang diperlukan, set default atau tampilkan pesan error
            if (!$facilityID || !$Relasi) {
                return back()->with('error', 'Facility information not found.');
            }

            // Panggil stored procedure untuk insert log
            try {
                PostUserView::PostUserViewPage($facilityID, $pagename, $pagenamedetail, $UserID, $Relasi, $ipaddress);
            } catch (\Exception $e) {
                Log::error('Error logging user view: ' . $e->getMessage());
                return back()->with('error', 'Failed to log page view.');
            }
            $Key1 = '';
            $Key2 = '';

            $getUnitType = ModelAssignmentSchedule::GetUnitTypeAssetDC($facilityID, $Relasi, $Key1, $Key2);
            $getActivityAssignment = ModelAssignmentSchedule::GetActivityAssignment($facilityID, $Relasi, $Key1, $Key2);
            $getEmployeeMaintenance = ModelAssignmentSchedule::GetEmployeeMaintenance($facilityID, $Relasi, $Key1, $Key2);

            //dd($getTaskView);
            //End Section Menampilkan Site Detail
            // Kirim data ke view
            return view(
                'admin.transaction.public.maintenance.assignmentschedule',
                compact('getUnitType', 'getActivityAssignment', 'getEmployeeMaintenance')
            );
        } catch (\Exception $e) {
            Log::error('Error load page : ' . $e->getMessage());
            return back()->with([
                'message' => 'Failed to load page .',
                'alert-type' => 'warning'
            ]);
        }
    }

    public function ViewPageSubmitAssignmentSchedule(Request $request)
    {
        try {
            //dd($request->all());
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');
            $bulan = $request->input('bulan', '');
            $tahun = $request->input('tahun', '');
            $Key2 = $tahun . '-' . $bulan;
            $Key1 = '';
            $selectedUnitType = $request->input('unit_type', ''); // string dari form

            $getUnitType = ModelAssignmentSchedule::GetUnitTypeAssetDC($facilityID, $Relasi, $Key1, $Key2);
            $getActivityAssignment = ModelAssignmentSchedule::GetActivityAssignment($facilityID, $Relasi, $Key1, $Key2);
            $getEmployeeMaintenance = ModelAssignmentSchedule::GetEmployeeMaintenance($facilityID, $Relasi, $Key1, $Key2);
            // dd($facilityID, $Relasi, $getUnitType, $Key2);

            $dataTable1 = ModelAssignmentSchedule::GetDailyAssignment($facilityID, $Relasi, $selectedUnitType, $Key2);


            return view(
                'admin.transaction.public.maintenance.assignmentschedule',
                compact('dataTable1', 'getActivityAssignment', 'getEmployeeMaintenance', 'getUnitType')
            );
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function ViewPageInsertAssignmentSchedule(Request $request)
    {
        try {
            $id = Auth::guard('admin')->id();
            $data = Admin::find($id);
            $userID = $data->userid;

            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');
            $bulan = $request->input('bulan', '');
            $tahun = $request->input('tahun', '');
            $Key2 = $tahun . '-' . $bulan;
            $Key1 = '';
            $selectedUnitType = $request->input('unit_type', '');
            $Datastring = $request->assignment_data;
            $Pic = $request->input('pic', '');
            $Teammate = $request->input('teammate', '');
            $Activity = $request->input('activity', '');

            // Simpan data ke database melalui model
            ModelAssignmentSchedule::InsertDailyAssignment(
                $facilityID,
                $Relasi,
                $Pic,
                $Teammate,
                $Activity,
                $Datastring,
                $userID
            );

            // Kalau request-nya AJAX, kirim JSON
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data berhasil disimpan.'
                ]);
            }

            // Kalau bukan AJAX, fallback ke redirect biasa
            return back()->with([
                'message' => 'Data Insert Successfully',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {


            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal menyimpan data. ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed Insert Data');
        }
    }
}
