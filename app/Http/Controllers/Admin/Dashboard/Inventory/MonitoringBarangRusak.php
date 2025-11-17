<?php

namespace App\Http\Controllers\Admin\Dashboard\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use App\Models\Admin;
use App\Models\Admin\Dashboard\Inventory\ModelBarangRusak;
use App\Models\Admin\Module\GetRandomData;
use App\Models\Admin\Module\GetSiteS2Support;
use App\Models\Admin\Module\PostUserView;
use App\Models\Admin\Report\Inventory\Throughput;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class MonitoringBarangRusak extends Controller
{
    public function ViewPageMonitoringBarangRusak(Request $request)
    {
        try{
            // Start Section Untuk Insert Ke Log User Ok
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
            $RELASI = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', ''); 
            $Type = $request->input('Type', ''); // Ambil parameter Type dari request atau default kosong 

            // Jika tidak ada nilai yang diperlukan, set default atau tampilkan pesan error
            if (!$facilityID || !$RELASI) {
                return back()->with('error', 'Facility information not found.');
            } 
           
            // Panggil stored procedure untuk insert log
            try {
                PostUserView::PostUserViewPage($facilityID, $pagename, $pagenamedetail, $userid,$RELASI,$ipaddress);
            } catch (\Exception $e) {
                Log::error('Error logging user view: ' . $e->getMessage());
                return back()->with('error', 'Failed to log page view.');
            }

        // End Section Untuk Insert Ke Log User Ok 
            //Cek apakah data sudah ada di session berdasarkan ID pengguna
            if (session()->has("datasites2_{$id}_{$RELASI}") && session()->has("datakategori_{$id}_{$RELASI}") && session()->has("damage_from_{$id}_{$RELASI}") && session()->has("damagetype_{$id}_{$RELASI}") ) {
                // Jika semua data sudah ada di session, ambil dari session
                $datasites2 = session("datasites2_{$id}_{$RELASI}");
                $datakategori = session("datakategori_{$id}_{$RELASI}");
                $datadamage_from = session("damage_from_{$id}_{$RELASI}");
                $datadamagetype =  session("damagetype_{$id}_{$RELASI}");
            } else {
                // Jika belum ada, query ke database dan simpan di session
                $datasites2 = GetSiteS2Support::getSiteDataS2($facilityID, $RELASI, $Type); 
                $datakategori =GetRandomData::getComboDamageKategori($facilityID, $RELASI);
                $datadamage_from = GetRandomData::getComboDamageFrom($facilityID, $RELASI);
                $datadamagetype = GetRandomData::getComboDamageType($facilityID, $RELASI);
                // Simpan data di session menggunakan ID pengguna
                session(["datasites2_{$id}_{$RELASI}" => $datasites2]); 
                session(["datakategori_{$id}_{$RELASI}" => $datakategori]); 
                session(["damage_from_{$id}_{$RELASI}" => $datadamage_from]);
                session(["damagetype_{$id}_{$RELASI}" => $datadamagetype]);
                
            }  
            //dd(session("damage_from_{$id}_{$RELASI}"));
        //dd($datadamage_from);
        $tableHeaders1 = []; 
        $tableHeaders2 = [];   
       
        //End Section Menampilkan Site Detail
        // Kirim data ke view
        return view('admin.dashboard.inventory.dashboard_monitoring_barang_rusak', compact('datasites2', 'datakategori', 'datadamage_from' ,'datadamagetype'));
        } catch (\Exception $e) {
            Log::error('Error load page : ' . $e->getMessage());
            return back()->with([
                'message' => 'Failed to load page .',
                'alert-type' => 'warning'
            ]);
        } 
    }

    public function ViewPageSummaryBarangRusak(Request $request)
    {
        try {

            //dd($request);
            // Ambil ID admin yang sedang login
            $id = Auth::guard('admin')->id();  
            $facilityInfo = session('facility_info', []);
            $facilityID = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', ''); 
            $Relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');  
            $StartDate = $request->input('start_date');
            $EndDate = $request->input('end_date');  
            $Owner = $request->input('owner_site'); 
            $Storerkey = $request->input('kode_site'); 
            $SiteName = $request->input('nama_site');
            $Key1 = $request->input('status_adf');  
            $Key2 = $request->input('selected_damage_from', ['ALL']); 
            if (is_array($Key2)) {
                // Jika array, gabungkan menjadi string dengan delimiter ";"
                $Key2 = implode(';', $Key2);
            } else {
                // Jika bukan array, langsung gunakan sebagai string
                $Key2 = $Key2;
            }
            $Key3 = $request->input('damage_kategori');  
            $Key4 = $request->input('damage_type');  
            $Key5 = $request->input('status_view'); 


            $validator = Validator::make($request->all(), [ 
                'selected_damage_from' => 'required', 
            ]);

            if ($validator->fails()) {
                // Mengarahkan kembali dengan pesan error
                return redirect()->back()->with([
                    'message' => 'Damage From Atau Site Belum Di Pilih',
                    'alert-type' => 'warning'
                ]);;
            }  

            $datasites2 = session("datasites2_{$id}_{$Relasi}");
            $datakategori = session("datakategori_{$id}_{$Relasi}");
            $datadamagetype = session("damagetype_{$id}_{$Relasi}");
            // $datadamagefrom = session("damagefrom_{$id}_{$Relasi}");

            // Simpan data ke session dengan key yang termasuk ID admin
            session()->put("input_databr_{$id}_{$Relasi}", [
                'start_date' => $StartDate,
                'end_date' => $EndDate,
                'owner_site' => $Owner,
                'kode_site' => $Storerkey,
                'nama_site' => $SiteName,
                'status_adf' => $Key1, 
                // 'damage_from' => $Key2,
                'selected_damage_from'=> is_array($Key2) ? $Key2 : [$Key2],
                'damage_kategori' => $Key3,
                'damage_type' => $Key4,
                'status_view' => $Key5,
            ]); 
            //dd(session("input_databr_{$id}_{$Relasi}"));
            // Panggil stored procedure untuk mendapatkan data tabel 
            $dataTables = ModelBarangRusak::getSummaryData($facilityID, $Relasi, $StartDate, $EndDate, $Owner, $Storerkey, $Key1, $Key2, $Key3, $Key4, $Key5);

           // dd($dataTables );
            // Kirim data ke view
            return view('admin.dashboard.inventory.dashboard_monitoring_barang_rusak', [
                'dataTable1' => $dataTables['tabel1'], 
                'dataTable2' => $dataTables['tabel2'],
                'dataTable3' => $dataTables['tabel3'],
                'tableHeaders1' => $dataTables['headers1'], // Header untuk tabel pertama
                'tableHeaders2' => $dataTables['headers2'], // Header untuk tabel kedua
                'tableHeaders3' => $dataTables['headers3'], // Header untuk tabel kedua
                'datakategori' => $datakategori, // Menyimpan data kategori supaya ketika di refresh muncul lagi
                'datadamagetype' => $datadamagetype, // Menyimpan data kategori
                // 'datadamagefrom' => $datadamagefrom, // Menyimpan data damage from
                'datasites2'=> $datasites2,  
            ])
            ->with([
                'message' => 'Data berhasil diambil',
                'alert-type' => 'success'
            ]);


        } catch (\Exception $e) {
          
            // Redirect ke halaman report_throughput dengan pesan error
            return redirect()->route('admin.dashboard.inventory.monitoringbarangrusak')
                             ->with([
                                'message' => 'Database Sedang Restore, Tunggu Beberapa Saat',
                                'alert-type' => 'warning'
                            ]);
        }
    }

}
          



