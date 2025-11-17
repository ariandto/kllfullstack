<?php

namespace App\Http\Controllers\Admin\Master\Public\Maintenance;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Module\PostUserView;
use App\Models\Admin\Module\GetValidasiUserSPV;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin\Master\Public\Maintenance\ModelMasterAssetBattery;
use App\Models\Admin\Module\GetDepartement5R;
use Illuminate\Support\Facades\Log;
use App\Models\Admin\Module\GetRandomData;
use App\Models\Admin\Module\GetSiteS2Support;
use App\Models\Admin\Module\GetOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Mews\Captcha\Facades\Captcha;
use PhpParser\Node\Stmt\TryCatch;

class MasterAssetBattery extends Controller
{
    public function ViewPageMasterAsset(Request $request)
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
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('Relasi', '');
            $Type = '2';
            // Jika tidak ada nilai yang diperlukan, set default atau tampilkan pesan error
            if (!$facilityID || !$Relasi) {
                return back()->with('error', 'Facility information not found.');
            }

            // Cek apakah hasilnya tidak kosong atau null
            try {
                PostUserView::PostUserViewPage($facilityID, $pagename, $pagenamedetail, $userid, $Relasi, $ipaddress);
            } catch (\Exception $e) {
                Log::error('Error logging user view: ' . $e->getMessage());
                return back()->with('error', 'Failed to log page view.');
            }

            if (session()->has("dataowner5r_{$id}_{$Relasi}")) {
                // Jika semua data sudah ada di session, ambil dari session
                $dataowner5r = session("dataowner5r_{$id}_{$Relasi}");
            } else {
                // Jika belum ada, query ke database dan simpan di session
                $dataowner5r = GetOwner::getOwnerData($facilityID, $Relasi, $Type);

                // Simpan data di session menggunakan ID pengguna
                session([" dataowner5r_{$id}_{$Relasi}" => $dataowner5r]);
            }
            $Owner = '';
            $dataTable = ModelMasterAssetBattery::getTypeUnit();

            $dataTable1 = ModelMasterAssetBattery::getDataView($facilityID, $Relasi, $Owner);

            $notification = [
                'message' => 'Data Ditemukan',
                'alert-type' => 'success',
            ];
            return view('admin.master.public.Maintenance.MasterAssetBattery', compact('dataTable', 'dataowner5r', 'dataTable1'))->with($notification);
        } catch (\Exception $e) {
            Log::error('Error logging user view: ' . $e->getMessage());
            return back()->with('error', 'Failed to log page view.');
        }
    }

    public function GetData(Request $request)
    {
        try {
            $id = Auth::guard('admin')->id();
            $data = Admin::find($id);
            $userid = $data->userid;

            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('Relasi', '');
            $Owner = $request->input('owner5r');
            $AssetNo = '';
            $UnitNo = $request->input('no_unit');
            // Ambil data dari model
            $result = ModelMasterAssetBattery::getData($facilityID, $Relasi, $Owner, $AssetNo, $UnitNo);
            //dd($result);
            if (!$result) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Data not found',
                    ],
                    404,
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Data found',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching data: ' . $e->getMessage());
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error fetching data',
                ],
                500,
            );
        }
    }

    public function SaveData(Request $request)
    {
        try {
            $id = Auth::guard('admin')->id();
            $data = Admin::find($id);
            $userid = $data->userid;

            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('Relasi', '');
            $Owner = $request->input('owner5r');
            $AssetNo = $request->input('aset_number');
            $UnitNo = $request->input('no_unit');
            $UnitName = $request->input('unit_name');
            $UnitType = $request->input('unit_type');
            $ReceiveDate = $request->input('receive_date');
            $LifeTime = $request->input('lifetime');
            $IsActive = $request->input('status');
            $AddUser = $userid;
            $Brand = $request->input('brand');
            $BatteryType = $request->input('battery_type');
            $RelatedUnitType = $request->input('related_unit_type');

            $notification = [
                'message' => 'Data Insert Successfully',
                'alert-type' => 'success',
            ];

            //dd($facilityID, $Relasi, $Owner, $AssetNo, $UnitNo, $UnitName, $UnitType, $ReceiveDate, $LifeTime, $IsActive, $AddUser, $Brand, $BatteryType, $RelatedUnitType);
            ModelMasterAssetBattery::insertDataView($facilityID, $Relasi, $Owner, $AssetNo, $UnitNo, $UnitName, $UnitType, $ReceiveDate, $LifeTime, $IsActive, $AddUser, $Brand, $BatteryType, $RelatedUnitType);
            return back()->with($notification);
        } catch (\Exception $e) {
            Log::error('Error logging user view: ' . $e->getMessage());
            return back()->with('error', 'Failed Insert Data, Area  Harus Huruf dan Angka.');
        }
    }

    public function deleteData(Request $request)
    {
        try {
            $id = Auth::guard('admin')->id();
            $data = Admin::find($id);
            $userid = $data->userid;

            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('Relasi', '');
            $Owner = $request->input('owner5r');
            $AssetNo = $request->input('aset_number');
            $UnitNo = $request->input('no_unit');
            $UnitName = $request->input('unit_name');
            $UnitType = $request->input('unit_type');
            $ReceiveDate = $request->input('receive_date');
            $LifeTime = $request->input('lifetime');
            $IsActive = $request->input('status');
            $AddUser = $userid;
            $Brand = $request->input('brand');
            $BatteryType = $request->input('battery_type');
            $RelatedUnitType = $request->input('related_unit_type');
            $Type = '2';
            $notification = [
                'message' => 'Data Delete Successfully',
                'alert-type' => 'success',
            ];

            // dd($facilityID, $Relasi, $Owner, $AssetNo, $UnitNo, $UnitName, $UnitType, $ReceiveDate, $LifeTime, $IsActive, $AddUser, $Brand, $BatteryType, $RelatedUnitType);
            ModelMasterAssetBattery::deleteDataView($facilityID, $Relasi, $Owner, $AssetNo, $UnitNo, $UnitName, $UnitType, $ReceiveDate, $LifeTime, $IsActive, $AddUser, $Brand, $BatteryType, $RelatedUnitType);
            return back()->with($notification);
        } catch (\Exception $e) {
            Log::error('Error logging user view: ' . $e->getMessage());
            return back()->with('error', 'Failed Insert Data, Area  Harus Huruf dan Angka.');
        }
    }
}
