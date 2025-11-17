<?php

namespace App\Http\Controllers\Admin\Master\Public\Config;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin\Master\Config\ModelMasterActivityMaintenance;
use Illuminate\Support\Facades\Log;

class MasterActivityMaintenance extends Controller
{
    public function ViewPageMasterActivityMaintenance(Request $request)
    {
        try {
            $id = Auth::guard('admin')->id();
            $data = Admin::find($id);
            $userid = $data->userid;

            $pagename = $request->query('PageName', '');
            $ipaddress = $request->ip();

            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('Relasi', '');

            if (!$facilityID || !$Relasi) {
                return back()->with('error', 'Facility information not found.');
            }

            $dataTable1 = ModelMasterActivityMaintenance::getDataView();
            $taskCategory = ModelMasterActivityMaintenance::getTaskCategory();
            $relatedUnitType = ModelMasterActivityMaintenance::getRelatedUnitType();

            return view('admin.master.public.Config.MasterActivityMaintenance', compact('dataTable1', 'taskCategory', 'relatedUnitType'))->with([
                'message' => 'Data ditemukan',
                'alert-type' => 'success',
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ViewPageMasterActivity: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat halaman.');
        }
    }

    public function SaveData(Request $request)
    {
        try {
            $id = Auth::guard('admin')->id();
            $data = Admin::find($id);
            $userid = $data->userid;

            ModelMasterActivityMaintenance::insertDataView($request->input('description'), $request->input('unit_type'), $request->input('task_category'), $request->input('lama_pengerjaan'), $request->input('uom'), $request->input('status'), $userid);

            return back()->with([
                'message' => 'Data berhasil disimpan',
                'alert-type' => 'success',
            ]);
        } catch (\Exception $e) {
            Log::error('Error inserting data: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan data.');
        }
    }

    public function deleteData(Request $request)
    {
        try {
            $id = Auth::guard('admin')->id();
            $data = Admin::find($id);
            $userid = $data->userid;

            ModelMasterActivityMaintenance::deleteDataView($request->input('description'), $request->input('unit_type'), $request->input('task_category'), $request->input('lama_pengerjaan'), $request->input('uom'), $request->input('status'), $userid);

            return back()->with([
                'message' => 'Data berhasil dihapus',
                'alert-type' => 'success',
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting data: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus data.');
        }
    }
}
