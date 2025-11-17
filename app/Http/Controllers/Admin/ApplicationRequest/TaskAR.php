<?php

namespace App\Http\Controllers\Admin\ApplicationRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use App\Models\Admin\Module\PostUserView;
use App\Models\Admin\ApplicationRequest\ModelTaskAR;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;

class TaskAR extends Controller
{

    public function ViewPageTaskAR(Request $request)
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
            $RELASI = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');
            $Type = $request->input('Type', ''); // Ambil parameter Type dari request atau default kosong 

            // Jika tidak ada nilai yang diperlukan, set default atau tampilkan pesan error
            if (!$facilityID || !$RELASI) {
                return back()->with('error', 'Facility information not found.');
            }

            // Panggil stored procedure untuk insert log
            try {
                PostUserView::PostUserViewPage($facilityID, $pagename, $pagenamedetail, $UserID, $RELASI, $ipaddress);
            } catch (\Exception $e) {
                Log::error('Error logging user view: ' . $e->getMessage());
                return back()->with('error', 'Failed to log page view.');
            }

            $id = Auth::guard('admin')->id();
            $data = Admin::find($id);
            $UserID = $data->userid;
            $Key1 = '';

            $getTaskView = ModelTaskAR::getTaskView($UserID, $Key1);

            //dd($getTaskView);
            //End Section Menampilkan Site Detail
            // Kirim data ke view
            return view(
                'admin.applicationrequest.task_ar',
                compact('getTaskView')
            );
        } catch (\Exception $e) {
            Log::error('Error load page : ' . $e->getMessage());
            return back()->with([
                'message' => 'Failed to load page .',
                'alert-type' => 'warning'
            ]);
        }
    }

    public function ViewPageSubmitTaskARDetail(Request $request)
    {
        try {
            $id = Auth::guard('admin')->id();
            $data = Admin::find($id);
            $UserID = $data->userid;
            $Key1 = $request->input('application_no', '');

            // Ambil data dari model
            $getDataViewDet = ModelTaskAR::getTaskViewDetail($UserID, $Key1);
            //dd($getDataViewDet);
            // Ambil statusAR dari tabel1
            $statusAR = $getDataViewDet['tabel1'][0]['Project Status'] ?? 'Unknown';
            $applicationNo = $getDataViewDet['tabel1'][0]['Application No'] ?? 'Unknown';

            //dd($getDataViewDet);
            // Return JSON response
            return response()->json([
                'success' => true,
                'statusAR' => $statusAR,
                'applicationNo' => $applicationNo,
                'tabel1' => $getDataViewDet['tabel1'] ?? [],
                'headers1' => $getDataViewDet['headers1'] ?? [],
                'tabel2' => $getDataViewDet['tabel2'] ?? [],
                'headers2' => $getDataViewDet['headers2'] ?? []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan, silakan coba lagi'
            ]);
        }
    }

    public function ViewPageSubmitSuperiordanSupernya(Request $request)
    {
        try {
            // dd($request);
            $id = Auth::guard('admin')->id();
            $data = Admin::find($id);
            $UserID = $data->userid;
            $ArNo = $request->input('application_no', '');
            $ButtonName = $request->input('button_name', ''); // Ambil dari request
            $Comment = $request->input('comment', ''); // Ambil komentar dari form

            // Insert Data
            ModelTaskAR::postSubmitSuperiordanSupernya($ArNo, $UserID, $Comment, $ButtonName);

            return response()->json([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan, silakan coba lagi'
            ]);
        }
    }


    public function ViewPageSubmitDeveloper(Request $request)
    {
        try {

            $id = Auth::guard('admin')->id();
            $data = Admin::find($id);
            $UserID = $data->userid;

            $applicationNo = $request->input('application_no');

            $datacompanyimpact = ModelTaskAR::getCompanyImpact($applicationNo);
            $data = ModelTaskAR::postGetSubmitDeveloper();

            $cleanedData = collect($data)->map(function ($item) {
                $item->Empphoto = base64_encode($item->Empphoto);
                return $item;
            });

            return response()->json([
                'success' => true,
                'developer' => $cleanedData,
                'company_impact' => $datacompanyimpact,
                'user_id' =>  $UserID
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }


    public function ViewPageSubmitDetailDeveloper(Request $request)
    {
        try {
            $nik = substr($request->nik, 0, 6); // Ambil 6 digit awal dari request

            $data = ModelTaskAR::postGetSubmitDetailDeveloper($nik);

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function ViewPageSubmitAssignAR(Request $request)
    {
        try {
            // Ambil dari request
            $ApplicationNo       = $request->input('application_no', '');
            $DataAnalyst         = $request->input('analyst', '');
            $DataDeveloper       = $request->input('developer', '');
            $GoLive              = $request->input('golive', '');
            $Comment             = $request->input('comment', '');
            $UserID              = $request->input('userid', '');
            $DataCompanyImpact   = $request->input('company_impact', '');
            //dd($ApplicationNo, $DataAnalyst, $DataDeveloper, $GoLive, $Comment, $UserID, $DataCompanyImpact);

            $data = ModelTaskAR::postAssignAR($ApplicationNo, $DataAnalyst, $DataDeveloper, $GoLive, $Comment, $UserID, $DataCompanyImpact);
            // Pastikan data yang diperlukan sudah tersedia dalam $data
            if ($data) {
                // Loop melalui data untuk mengirim email satu per satu
                foreach ($data as $entry) {
                    // Detail email yang ingin dikirim
                    $emailDetails = [
                        'title' => 'TESTING - ' . $entry->ApplicationNo,  // Subject
                        'no_ar' => $entry->ApplicationNo,
                        'project_name' => $entry->ProjectName,
                        'new_project_owner' => $entry->NewProjectOwner,
                        'parent_project_id' => $entry->ParentProjectID,
                        'original_project_owner' => $entry->OriginalProjectOwner,
                        'email_karyawan' => $entry->Email,
                        'keterangan' => 'Ini adalah Application Request yang terbentuk otomatis dari sistem', // Anda bisa mengganti ini dengan keterangan lainnya jika diperlukan
                    ];

                    // Mengirim email setelah insert data
                    Mail::to($entry->Email)->send(new SendEmail($emailDetails));
                }
            }

            return response()->json([
                'success' => true,  // atau false jika ada kesalahan
                'message' => 'Email berhasil dikirim.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
