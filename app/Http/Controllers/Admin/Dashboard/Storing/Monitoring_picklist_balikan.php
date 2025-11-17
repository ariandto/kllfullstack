<?php

namespace App\Http\Controllers\Admin\Dashboard\Storing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use App\Models\Admin;
use App\Models\Admin\Dashboard\Storing\ModelMonitoringPicklistBalikan;
use App\Models\Admin\Module\GetRandomData;
use App\Models\Admin\Module\GetSiteS2Support;
use App\Models\Admin\Module\PostUserView;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Module\GetOwner;

class Monitoring_picklist_balikan extends Controller
{
    
    public function ViewDataPicklist(Request $request)
    {
        try {
            $facilityInfo = session('facility_info', []);
            // Mengambil data dari form Parameter dari Blade
            $type =('getdatapicklistmonitoring');
            $sku = $request->input('nomor_sku') ?? '';
            $whseid = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', ''); 
            $relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');
            $loc = $request->input('loc') ?? ''; 
            $storerkey = $request->input('selected_owners', ['ALL']);

            if (is_array($storerkey)) {
                // Jika array, gabungkan menjadi string dengan delimiter ";"
                $ownerString = implode(';', $storerkey);
            } else {
                // Jika bukan array, langsung gunakan sebagai string
                $ownerString = $storerkey;
            }
            
            $lpn = $request->input('idlpn')?? '';
            $param1 = $request->input('picklistid')?? '';
            $param2 = $request->input('start_date');
            $param3 = '';
            
            // **Tambahkan pengecekan apakah user sudah mengisi form**
            if ($request->has('picklistid') || $request->has('start_date')) {
                // Jika ada input, jalankan query
                $datatabel = ModelMonitoringPicklistBalikan::getpicklistData($type, $sku, $whseid, $relasi, $loc, $ownerString, $lpn, $param1, $param2, $param3);
            } else {
                // Jika tidak ada input, kosongkan data tabel
                $datatabel = ['tabel1' => [], 'headers1' => []];
            }

            return view('admin.dashboard.storing.Monitoring_Picklist_Balikan', [
                'datatabel1' => $datatabel['tabel1'],
                'tabelheaders1' => $datatabel['headers1']
            ]);

        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard.storing.picklist')
                ->with([
                'message' => 'Database Sedang Restore, Tunggu Beberapa Saat',
                'alert-type' => 'warning'
            ]);
        }
    }
}
