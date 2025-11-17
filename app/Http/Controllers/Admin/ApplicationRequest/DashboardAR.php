<?php

namespace App\Http\Controllers\Admin\ApplicationRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use App\Models\Admin\Module\PostUserView;
use App\Models\Admin\ApplicationRequest\ModelDashboardAR;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class DashboardAR extends Controller
{

    public function ViewPageDashboardAR(Request $request)
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

            $getKLIP = ModelDashboardAR::getKLIP($UserID, $Key1, $Key2, $Key3);


            //End Section Menampilkan Site Detail
            // Kirim data ke view
            return view(
                'admin.applicationrequest.dashboard_ar',
                compact('getKLIP')
            );
        } catch (\Exception $e) {
            Log::error('Error load page : ' . $e->getMessage());
            return back()->with([
                'message' => 'Failed to load page .',
                'alert-type' => 'warning'
            ]);
        }
    }
}
