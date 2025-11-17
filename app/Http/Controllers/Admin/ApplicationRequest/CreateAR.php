<?php

namespace App\Http\Controllers\Admin\ApplicationRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use App\Models\Admin\Dashboard\Inventory\ModelBarangRusak;
use App\Models\Admin\Module\GetRandomData;
use App\Models\Admin\Module\GetSiteS2Support;
use App\Models\Admin\Module\PostUserView;
use App\Models\Admin\ApplicationRequest\ModelCreateAR;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class CreateAR extends Controller
{

    public function ViewPageCreateAR(Request $request)
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

            $getKLIP = ModelCreateAR::getKLIP($UserID, $Key1, $Key2, $Key3);
            $getEmpInfo = ModelCreateAR::getEmpInfo($UserID, $Key1, $Key2, $Key3);
            $getDCPilot = ModelCreateAR::getDCPilot($UserID, $Key1, $Key2, $Key3);
            $getListApp = ModelCreateAR::getlistApp($UserID, $Key1, $Key2, $Key3);
            $getCompanyImpact = ModelCreateAR::getCompanyImpact($UserID, $Key1, $Key2, $Key3);
            $getSuperior = ModelCreateAR::getSuperior($UserID, $Key1, $Key2, $Key3);
            $getReqType = ModelCreateAR::getReqType($UserID, $Key1, $Key2, $Key3);

            //End Section Menampilkan Site Detail
            // Kirim data ke view
            return view(
                'admin.applicationrequest.create_ar',
                compact('getKLIP', 'getEmpInfo', 'getDCPilot', 'getListApp', 'getCompanyImpact', 'getSuperior', 'getReqType')
            );
        } catch (\Exception $e) {
            Log::error('Error load page : ' . $e->getMessage());
            return back()->with([
                'message' => 'Failed to load page .',
                'alert-type' => 'warning'
            ]);
        }
    }

    public function getSuperior(Request $request)
    {
        $ownerID = $request->owner_id;

        // Panggil stored procedure atau query untuk mengambil data
        $result = ModelCreateAR::getSuperior($ownerID, 'Key1', 'Key2', 'Key3');

        if (!empty($result)) {
            return response()->json([
                'success' => true,
                'data' => $result[0] // Ambil baris pertama
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    public function getValidasiUser(Request $request)
    {
        $UserID = $request->userID;

        // Panggil stored procedure atau query untuk mengambil data
        $result = ModelCreateAR::getEmpInfo($UserID, 'Key1', 'Key2', 'Key3');

        if (!empty($result)) {
            return response()->json([
                'success' => true,
                'data' => $result[0] // Ambil baris pertama
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    public function ViewPageSaveCreateAR(Request $request)
    {
        // Ambil data dari request tanpa validasi
        $ApplicationRequest = $request->input('requestNo') ?? '';
        $UserID = $request->input('userId') ?? '';
        $Company = $request->input('companyName') ?? '';
        $JobTtlName = $request->input('jobTitle') ?? '';
        $OrganizationName = $request->input('organizationName') ?? '';
        $Email = $request->input('email') ?? '';
        $ProjectName = $request->input('project_name') ?? '';
        $PilotProject = $request->input('pilot_project') ?? '';
        $CompanyImpact = str_replace(', ', ';', $request->input('company_impact', '')) . ';';
        $ProjectOwner = $request->input('owner_id') ?? '';
        $Superior = $request->input('superior') ?? '';
        $RequestType = $request->input('request_type') ?? '';
        $ApplicationType = $request->input('application_type') ?? '';
        $GotoKLIP = $request->input('go_to_klip') ?? '';
        $KLIPNumber = $request->input('klip_number') ?? '';
        $ExpectedGolive = $request->input('expected_go_live') ?? '';
        $Point1 = $request->input('latar_belakang') ?? '';
        $Point2 = $request->input('kondisi_dicapai') ?? '';
        $Point3 = $request->input('kondisi_saat_ini') ?? '';
        $Point4 = $request->input('cara_selesaikan') ?? '';
        $Attachment = $request->input('attachmentName') ?? '';
        $ButtonName = "Save";
        $Comment = $request->input('comment') ?? '';
        $Raci = $request->input('raci_data') ?? '';

        //dd($Raci);
        $result = ModelCreateAR::getARNumber(
            $ApplicationRequest,
            $UserID,
            $Company,
            $JobTtlName,
            $OrganizationName,
            $Email,
            $ProjectName,
            $PilotProject,
            $CompanyImpact,
            $ProjectOwner,
            $Superior,
            $RequestType,
            $ApplicationType,
            $GotoKLIP,
            $KLIPNumber,
            $ExpectedGolive,
            $Point1,
            $Point2,
            $Point3,
            $Point4,
            $Attachment,
            $ButtonName,
            $Comment,
            $Raci
        );

        // dd($result);
        if (!empty($result)) {
            return response()->json([
                'success' => true,
                'data' => $result[0] // Ambil baris pertama
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    public function ViewPageCreateARDraft(Request $request)
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

            $getKLIP = ModelCreateAR::getKLIP($UserID, $Key1, $Key2, $Key3);
            $getEmpInfo = ModelCreateAR::getEmpInfo($UserID, $Key1, $Key2, $Key3);
            $getDCPilot = ModelCreateAR::getDCPilot($UserID, $Key1, $Key2, $Key3);
            $getListApp = ModelCreateAR::getlistApp($UserID, $Key1, $Key2, $Key3);
            $getCompanyImpact = ModelCreateAR::getCompanyImpact($UserID, $Key1, $Key2, $Key3);
            $getSuperior = ModelCreateAR::getSuperior($UserID, $Key1, $Key2, $Key3);
            $getReqType = ModelCreateAR::getReqType($UserID, $Key1, $Key2, $Key3);

            $applicationNo = $request->query('applicationNo'); // Ambil applicationNo dari URL
            $getCurrentDataAR = ModelCreateAR::getARDraft($applicationNo, $Key1, $Key2, $Key3);
            $tabel2 = $getCurrentDataAR['tabel2'] ?? []; // Kalau null, set array kosong
            // Kirim data ke view
            return view(
                'admin.applicationrequest.create_ar_draft',
                compact('getKLIP', 'getEmpInfo', 'getDCPilot', 'getListApp', 'getCompanyImpact', 'getSuperior', 'getReqType', 'getCurrentDataAR', 'tabel2')
            );
        } catch (\Exception $e) {
            Log::error('Error load page : ' . $e->getMessage());
            return back()->with([
                'message' => 'Failed to load page .',
                'alert-type' => 'warning'
            ]);
        }
    }



    public function ViewPageSubmitCreateAR(Request $request)
    {
        $ApplicationRequest = $request->input('requestNo') ?? '';
        $UserID = $request->input('userId') ?? '';
        $Company = $request->input('companyName') ?? '';
        $JobTtlName = $request->input('jobTitle') ?? '';
        $OrganizationName = $request->input('organizationName') ?? '';
        $Email = $request->input('email') ?? '';
        $ProjectName = $request->input('project_name') ?? '';
        $PilotProject = $request->input('pilot_project') ?? '';
        $CompanyImpact = str_replace(', ', ';', $request->input('company_impact', '')) . ';';
        $ProjectOwner = $request->input('owner_id') ?? '';
        $Superior = $request->input('superior') ?? '';
        $RequestType = $request->input('request_type') ?? '';
        $ApplicationType = $request->input('application_type') ?? '';
        $GotoKLIP = $request->input('go_to_klip') ?? '';
        $KLIPNumber = $request->input('klip_number') ?? '';
        $ExpectedGolive = $request->input('expected_go_live') ?? '';
        $Point1 = $request->input('latar_belakang') ?? '';
        $Point2 = $request->input('kondisi_dicapai') ?? '';
        $Point3 = $request->input('kondisi_saat_ini') ?? '';
        $Point4 = $request->input('cara_selesaikan') ?? '';
        $Attachment1 = $request->input('attachmentName') ?? '';
        $Attachment = $Attachment1;
        $Raci = $request->input('raci_data') ?? '';
        $ButtonName = "Submit";
        $Comment = $request->input('comment') ?? '';


        $result = ModelCreateAR::getSubmitAR(
            $ApplicationRequest,
            $UserID,
            $Company,
            $JobTtlName,
            $OrganizationName,
            $Email,
            $ProjectName,
            $PilotProject,
            $CompanyImpact,
            $ProjectOwner,
            $Superior,
            $RequestType,
            $ApplicationType,
            $GotoKLIP,
            $KLIPNumber,
            $ExpectedGolive,
            $Point1,
            $Point2,
            $Point3,
            $Point4,
            $Attachment,
            $ButtonName,
            $Comment,
            $Raci
        );

        // dd($result);
        if (!empty($result)) {
            return response()->json([
                'success' => true,
                'data' => $result[0] // Ambil baris pertama
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    public function uploadAttachment(Request $request)
    {
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            // Ambil Nomor AR dari request
            $ApplicationRequest = $request->input('requestNo') ?? '';
            // Format nama file: NomorAR - NamaFileAsli
            $filename = $ApplicationRequest . ' - ' . $file->getClientOriginalName();

            // dd($filename);
            try {
                // Simpan file ke FTP pada folder /Attachment/AR/
                Storage::disk('ftp')->put('/Attachment/ApplicationRequest/' . $filename, fopen($file->getPathname(), 'r+'));

                return response()->json(['success' => true, 'filename' => $filename]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Gagal upload ke FTP!']);
            }
        }

        return response()->json(['success' => false, 'message' => 'Tidak ada file untuk diupload!']);
    }
}
