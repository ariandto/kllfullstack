<?php

namespace App\Http\Controllers\Admin\Master\Inventory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Module\PostUserView;
use App\Models\Admin\Module\GetValidasiUserSPV;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin\Master\Inventory\Master_damage_from;
use App\Models\Admin\Module\GetDepartementholdlpn;
use Illuminate\Support\Facades\Log; 
use App\Models\Admin\Module\GetRandomData;
use App\Models\Admin\Module\GetSiteS2Support;
use App\Models\Admin\Module\GetOwner;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Validator; 
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Mews\Captcha\Facades\Captcha;
use PhpParser\Node\Stmt\TryCatch;

class Master_damage_from_controller extends Controller
{
    public function ViewPageLPNBarangRusak(Request $request)
    { 
        try {
            //Mengambil nilai id yang sedang login
            $id=Auth::guard('admin')->id();
            $data= Admin::find($id); 
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
            //Cek apakah data sudah ada di session berdasarkan ID pengguna
            if (session()->has("datasites2_{$id}_{$Relasi}")  && session()->has("dataownerholdlpn_{$id}_{$Relasi}")  ) {
                // Jika semua data sudah ada di session, ambil dari session
                $datasites2 = session("datasites2_{$id}_{$Relasi}"); 
                $dataownerholdlpn = session("dataownerholdlpn_{$id}_{$Relasi}");    
               
            } else {
                // Jika belum ada, query ke database dan simpan di session
                $datasites2 = GetSiteS2Support::getSiteDataS2($facilityID, $Relasi, $Type);  
                $dataownerholdlpn = GetOwner::getOwnerData($facilityID, $Relasi, $Type); 
                // Simpan data di session menggunakan ID pengguna
                session(["datasites2_{$id}_{$Relasi}" => $datasites2]);  
                session(["dataownerholdlpn_{$id}_{$Relasi}" =>  $dataownerholdlpn]);  
            }   

            // Jika tidak ada nilai yang diperlukan, set default atau tampilkan pesan error
            if (!$facilityID || !$Relasi) {
                return back()->with('error', 'Facility information not found.');
            }   
                try {

                    PostUserView::PostUserViewPage($facilityID, $pagename, $pagenamedetail, $userid,$Relasi,$ipaddress); 
                } catch (\Exception $e) {
                    Log::error('Error logging user view: ' . $e->getMessage());
                    return back()->with('error', 'Failed to load page view.');
            }
            
          
            $dataTable= Master_damage_from::getDataViewLPNBarangRusak($facilityID,$Relasi);
            //dd($dataTable); 
            $notification = array(
                'message' => 'Data Insert Successfully',
                'alert-type' => 'success'
            );

            return view('admin.master.inventory.Master_damage_from', compact('dataTable', 'dataownerholdlpn' , 'datasites2'))
            ->with($notification); 
            
        } catch (\Exception $e) {
            Log::error('Error logging user view: ' . $e->getMessage());
            return back()->with('error', 'Failed to log page view.');
        } 
       
    }

    public function ViewPageSaveLPNBarangRusak(Request $request)
    { 
        try {
            // dd($request);
            $id = Auth::guard('admin')->id();  
            // Ambil nilai Facility_ID, Relasi, dan Type dari session atau request
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', ''); 
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('Relasi', '');   
            $validator = Validator::make($request->all(), [
               'codelpn' => 'required|regex:/^[a-zA-Z0-9]{1,2}$/', 
                'holdlpndesc' => 'required|regex:/^[a-zA-Z0-9,\\.]+$/', 
            ]);
    
            if ($validator->fails()) {
                // Ambil pesan kesalahan
                $errors = $validator->errors()->all(); // Mendapatkan semua pesan kesalahan sebagai array
                $errorMessage = implode(', ', $errors); // Menggabungkan pesan kesalahan menjadi string
        
                return back()->with('error', "Failed Insert Data: $errorMessage, Kode LPN Max 2 Karakter");
            }  
            $Owner = $request->input('ownerholdlpn'); 
            $Site = $request->input('sites2'); 
            $CodeLPN = $request->input('codelpn'); 
            $LPNDesc = $request->input('holdlpndesc'); 
         

            //Panggil stored procedure untuk mendapatkan data tabel 
            Master_damage_from::insertDataViewLPNBarangRusak($facilityID,$Relasi,$Site, $Owner,$CodeLPN, $LPNDesc); 

            // Redirect ke halaman master setelah berhasil menyimpan data, ini mereset ulang semua
            return back()->with('success', 'Data Insert Successfully')->withInput();
               
        } catch (\Exception $e) {
            Log::error('Error logging user view: ' . $e->getMessage());
            return back()->with('error', 'Failed Insert Data, Area  Harus Huruf dan Angka.');
        } 
       
    }

    public function updateLPNBarangRusak(Request $request)
    {
        try {
             
            $validator = Validator::make($request->all(), [
                'code' => 'required|regex:/^[a-zA-Z0-9]{1,2}$/', 
               'damagedesc' => 'required|regex:/^[a-zA-Z0-9,\\.\\s]+$/', 
            ]);
    

            if ($validator->fails()) {
                // Ambil pesan kesalahan
                $errors = $validator->errors()->all(); // Mendapatkan semua pesan kesalahan sebagai array
                $errorMessage = implode(', ', $errors); // Menggabungkan pesan kesalahan menjadi string
        
                return back()->with('error', "Failed Insert Data : $errorMessage , Kode LPN Max 2 Karakter");
            } 
            
            $facilityInfo = session('facility_info', []);
            // Ambil data yang telah divalidasi
            $facilityID =$facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', ''); 
            $Relasi =$facilityInfo[0]['Relasi'] ?? $request->input('Relasi', '');    
            $Owner = $request->input('owner'); 
            $Site = $request->input('site'); 
            $CodeLPN = $request->input('code'); 
            $LPNDesc = $request->input('damagedesc'); 
         

             //Panggil stored procedure untuk mendapatkan data tabel 
             Master_damage_from::updateDataViewLPNBarangRusak($facilityID,$Relasi,$Site, $Owner,$CodeLPN, $LPNDesc); 

            // Menambahkan notifikasi sukses
            return response()->json([
                'success' => true,
                'message' => 'Data Updated Successfully',
            ]);
            
            //admin.master.public.checklistholdlpn.masterpointholdlpn_update
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function deleteLPNBarangRusak(Request $request)
    {
        try {
             
            $facilityInfo = session('facility_info', []);
            // Ambil data yang telah divalidasi
            $facilityID =$facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', ''); 
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('Relasi', '');   
            $Owner = $request->input('owner'); 
            $Site = $request->input('site'); 
            $CodeLPN = $request->input('code'); 
            $LPNDesc = $request->input('damagedesc');
         
            // Eksekusi stored procedure untuk menghapus data
            Master_damage_from::deleteDataViewLPNBarangRusak($facilityID,$Relasi,$Site, $Owner,$CodeLPN, $LPNDesc); 

            // Menambahkan notifikasi sukses
            return response()->json([
                'success' => true,
                'message' => 'Data Deleted Successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


}