<?php

namespace App\Http\Controllers\Admin\ApplicationRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use App\Models\Admin\Module\PostUserView;
use App\Models\Admin\ApplicationRequest\ModelListAR;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class ListAR extends Controller
{

    public function ViewPageListAR(Request $request)
    {
        try {
            // Start Section Untuk Insert Ke Log User Ok
            //Mengambil nilai id yang sedang login
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

            $Key1 = '';
            $Key2 = '';
            $Key3 = '';

            $getListApp = ModelListAR::getlistApp($UserID, $Key1, $Key2, $Key3);
            $getStatusAR = ModelListAR::getStatusAR($UserID, $Key1, $Key2, $Key3);

            //End Section Menampilkan Site Detail
            // Kirim data ke view
            return view(
                'admin.applicationrequest.list_ar',
                compact('getListApp', 'getStatusAR')
            );
        } catch (\Exception $e) {
            Log::error('Error load page : ' . $e->getMessage());
            return back()->with([
                'message' => 'Failed to load page .',
                'alert-type' => 'warning'
            ]);
        }
    }

    public function ViewPageSubmitListAR(Request $request)
    {

        try {
            $id = Auth::guard('admin')->id();
            $data = Admin::find($id);
            $UserID = $data->userid;
            $StartDate = $request->input('start_date', '');
            $EndDate = $request->input('end_date', '');
            $AppType = $request->input('application_type', 'All');
            $AppStatus = $request->input('application_status', 'All');

            $Key1 = '';
            $Key2 = '';
            $Key3 = '';
            $getListApp = ModelListAR::getlistApp($UserID, $Key1, $Key2, $Key3);
            $getStatusAR = ModelListAR::getStatusAR($UserID, $Key1, $Key2, $Key3);

            // Panggil model untuk mengambil data
            $getDataView = ModelListAR::getDataView($StartDate, $EndDate, $AppType, $AppStatus);

            // dd($getDataView);
            return view('admin.applicationrequest.list_ar', compact('getDataView', 'getListApp', 'getStatusAR'));
        } catch (\Exception $e) {
            // Jika terjadi error, tangkap dan tampilkan pesan error
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function ViewPageSubmitListARDetail(Request $request)
    {
        try {
            $applicationNo = $request->input('application_no', '');
            $EndDate = '';
            $AppType = '';
            $AppStatus = '';

            // Ambil data dari model
            $getDataViewDet = ModelListAR::getDataViewDetail($applicationNo, $EndDate, $AppType, $AppStatus);

            // // **Konversi kolom "Project Owner Profile" ke Base64 jika ada**
            // foreach ($getDataViewDet['tabel3'] as &$row) {
            //     if (!empty($row['Project Owner Profile'])) {
            //         $row['Project Owner Profile'] = base64_encode($row['Project Owner Profile']);
            //     }
            // }

            // Return JSON response
            return response()->json([
                'success' => true,
                'data' => $getDataViewDet['tabel3'] ?? [],
                'headers' => $getDataViewDet['headers3'] ?? []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan, silakan coba lagi'
            ]);
        }
    }
}
