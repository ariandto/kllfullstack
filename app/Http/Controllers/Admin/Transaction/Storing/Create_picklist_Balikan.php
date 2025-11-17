<?php

namespace App\Http\Controllers\Admin\Transaction\Storing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use App\Models\Admin\Transaction\Storing\ModelCreatePicklistBalikan;
use App\Models\Admin\Module\GetRandomData;
use App\Models\Admin\Module\GetSiteS2Support;
use App\Models\Admin\Module\PostUserView;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Module\GetOwner;
use Illuminate\Support\Facades\DB;

class Create_picklist_Balikan extends Controller
{
    public function ViewPagePicklistkbalikan(Request $request)
    {
        try {
            // Start Section Untuk Insert Ke Log User Ok
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
            $RELASI = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');
            $Type = $request->input('Type', ''); // Ambil parameter Type dari request atau default kosong 

            // Jika tidak ada nilai yang diperlukan, set default atau tampilkan pesan error
            if (!$facilityID || !$RELASI) {
                return back()->with('error', 'Facility information not found.');
            }

            // Panggil stored procedure untuk insert log
            try {
                PostUserView::PostUserViewPage($facilityID, $pagename, $pagenamedetail, $userid, $RELASI, $ipaddress);
            } catch (\Exception $e) {
                Log::error('Error logging user view: ' . $e->getMessage());
                return back()->with('error', 'Failed to log page view.');
            }

            // End Section Untuk Insert Ke Log User Ok

            // Cek apakah data sudah ada di session berdasarkan ID pengguna
            if (session()->has("dataowner_$id")) {
                // Jika data sudah ada di session, ambil dari session
                $dataowner = session("dataowner_$id");
            } else {
                // Jika belum ada, query ke database dan simpan di session
                $dataowner = GetOwner::getOwnerData($facilityID, $RELASI, $Type);
                // Simpan data di session menggunakan ID pengguna
                session(["dataowner_$id" => $dataowner]);
            }
        } catch (\Exception $e) {
            Log::error('Error load page : ' . $e->getMessage());
            return back()->with([
                'message' => 'Failed to load page .',
                'alert-type' => 'warning'
            ]);
        }

        // Ambil data dari stored procedure menggunakan nilai dari session
        //Ini Menampilkan Data Owner 
        // Cek apakah data sudah ada di session berdasarkan ID pengguna
        if (session()->has("dataowner_$id")) {
            // Jika data sudah ada di session, ambil dari session
            $dataowner = session("dataowner_$id");
        } else {
            // Jika belum ada, query ke database dan simpan di session
            $dataowner = GetOwner::getOwnerData($facilityID, $RELASI, $Type);
            // Simpan data di session menggunakan ID pengguna
            session(["dataowner_$id" => $dataowner]);
        }

        logger()->info('Request masuk:', $request->all()); // Log data request

        $facilityInfo = session('facility_info', []);
        $type = ('getdatapicklist');
        $sku = $request->input('nomor_sku_picklist_show') ?? '';
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
        Log::info('owner:', [$ownerString]); // owner

        $lpn = $request->input('idlpn') ?? '';
        $param1 = '';
        $param2 = '';
        $param3 = '';

        \DB::enableQueryLog();

        $datatabel = ModelCreatePicklistBalikan::getstockeData1($type, $sku, $whseid, $relasi, $loc, $ownerString, $lpn, $param1, $param2, $param3);
        Log::info(\DB::getQueryLog());
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'tabelheaders1' => $datatabel['headers1'],
                'datatabel1' => array_map(function ($row) {
                    return [
                        'Article' => $row['Article'] ?? '',
                        'DESCR' => $row['DESCR'] ?? '',
                        'Reference_no' => $row['Reference_no'] ?? '',
                        'Available Qty' => $row['Available Qty'] ?? '',
                        'Foto1_base64' => $row['Foto1_base64'] ?? null,
                        'Foto2_base64' => $row['Foto2_base64'] ?? null,
                        'Foto3_base64' => $row['Foto3_base64'] ?? null,
                    ];
                }, $datatabel['tabel1'] ?? [])
            ]);
        }

        // Jika request langsung (buka halaman biasa)
        return view('admin.transaction.storing.Create_Picklist_balikan', [
            'dataowner' => $dataowner,
            'datatabel1' => $datatabel['tabel1'],
            'tabelheaders1' => $datatabel['headers1']
        ]);
    }


    public function ViewPageEditPicklistkbalikan(Request $request)
    {
        Log::info('Request Data:', $request->all());
        $facilityInfo = session('facility_info', []);
        $type = ('getdatapicklistedit');
        $sku = $request->input('nomor_sku_picklist_show') ?? '';
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


        $lpn = $request->input('idlpn') ?? '';
        $param1 = $request->input('picklist_id') ?? '';
        Log::info('Picklist ID:', [$param1]); // owner
        $param2 = '';
        $param3 = '';



        $datatabe2 = ModelCreatePicklistBalikan::getstockeData2($type, $sku, $whseid, $relasi, $loc, $ownerString, $lpn, $param1, $param2, $param3);
        Log::info('Executing Stored Procedure:', [
            'query' => "EXEC [GETDATA_STOCK_BALIKAN] ?,?,?,?,?,?,?,?,?,?",
            'params' => [$type, $sku, $whseid, $relasi, $loc, $ownerString, $lpn, $param1, $param2, $param3]
        ]);
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'tabelheaders2' => $datatabe2['headers2'],
                'datatabel2' => array_map(function ($row) {
                    return [
                        'picklistid' => $row['picklistid'] ?? '', 
                        'to_storer' => $row['to_storer'] ?? '',
                        'deliverydate' => $row['deliverydate'] ?? '',
                        'ID' => $row['ID'] ?? '',
                        'storerkey' => $row['storerkey'] ?? '',
                        'article' => $row['article'] ?? '',
                        'DESCR' => $row['DESCR'] ?? '',
                        'Available_Qty' => $row['Available_Qty'] ?? '',
                        'Request_Qty' => $row['Request_Qty'] ?? '',
                        'Foto1_base64' => $row['Foto1_base64'] ?? null,
                        'Foto2_base64' => $row['Foto2_base64'] ?? null,
                        'Foto3_base64' => $row['Foto3_base64'] ?? null,
                    ];
                }, $datatabe2['tabel2'] ?? [])
            ]);
        }

        // Jika request langsung (buka halaman biasa)
        return view('admin.transaction.storing.Create_Picklist_balikan', [
            'datatabel2' => $datatabe2['tabel2'],
            'tabelheaders2' => $datatabe2['headers2']
        ]);
    }

    public function deletePicklistItem(Request $request)
    {
        try {
             // Log data yang diterima
            Log::info('Data yang diterima di deletePicklistItem:', [
                'picklistid' => $request->input('picklistid'),
                'article' => $request->input('article'),
                'reference_no' => $request->input('reference_no'),
                'qtyrequest' => $request->input('qtyrequest'),
            ]);

            $picklistid = $request->input('picklistid');
            $article = $request->input('article');
            $reference_no = $request->input('reference_no'); 
            $qtyrequest = $request->input('qtyrequest'); 
            // Jika qtyrequest kosong atau null, set ke 0
            $qtyrequest = empty($qtyrequest) ? 0 : intval($qtyrequest); 
            // Hapus item dari tabel picklist_item_balikan
            DB::statement("EXEC sp_UpdateAndDeletePicklistItem ?, ?, ?, ?", [
                $picklistid,
                $article,
                $reference_no,
                $qtyrequest
            ]);

            return response()->json(['status' => 'success', 'message' => 'Item berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function ViewPageSaveEditPicklistkbalikan(Request $request)
    {
        try {
            Log::info('Data diterima dari AJAX:', $request->all());
            // 🔹 Ambil data `updatedData` dari request
            $updatedData = $request->input('data', []);
            // 🔹 Pastikan data tidak kosong
            if (empty($updatedData)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada data yang diterima!'
                ]);
            }
            // 🔹 Debug: Lihat isi data setelah diambil
            Log::info('Data setelah diproses:', ['data' => $updatedData]);
            // Ambil user yang sedang login untuk addwho
            Log::info('SEBELUM AMBIL USER');
            //Mengambil nilai id yang sedang login
            $id = Auth::guard('admin')->id();
            $data = Admin::find($id);
            $addwho = $data->userid;
            Log::info('Mengambil ADDWHO untuk:', ['ADDWHO' => $addwho]);
            $facilityInfo = session('facility_info', []);
            $whseid = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
            $relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');
            $storerkey = $request->input('selected_owners', ['ALL']);
            if (is_array($storerkey)) {
                // Jika array, gabungkan menjadi string dengan delimiter ";"
                $ownerString = implode(';', $storerkey);
            } else {
                // Jika bukan array, langsung gunakan sebagai string
                $ownerString = $storerkey;
            }
            Log::info('owner:', [$ownerString]); // owner
            Log::info('WHSEID yang didapat:', ['WHSEID' => $whseid]);
            Log::info('RELASI yang didapat:', ['RELASI' => $relasi]);
            Log::info("Cek isi items sebelum validasi:", ['items' => $request->input('items')]);
            // Ambil data items dengan default array kosong jika tidak ada
            if (empty($updatedData)) {
                Log::error("Gagal: Tidak ada barang yang disimpan!", ['items' => $updatedData]);
                return back()->with('error', 'Tidak ada barang yang disimpan.');
            }
            try {
                Log::info('INI SEBELUM MASUK KE FOREACH ITEM');

                if (!is_array($updatedData) || empty($updatedData)) {
                    Log::error("Error: Items tidak valid!", ['items' => json_encode($updatedData)]);
                    return back()->with('error', 'Data barang tidak valid.');
                }
                Log::info('Items valid, masuk ke foreach');

                foreach ($updatedData as $item) {
                    Log::info("Menyimpan item ke DB", [
                        'picklistid' => $item['picklistid'] ?? 'NULL',
                        'to_storer' => $item['to_storer'] ?? 'NULL',
                        'deliverydate' => $item['deliverydate'] ?? 'NULL',
                        'article' => $item['article'] ?? 'NULL',
                        'request_qty' => $item['request_qty'] ?? 'NULL',
                        'whseid' => $whseid,
                        'relasi' => $relasi,
                        'addwho' => $addwho,
                        'storerkey' => $item['storerkey'] ?? 'NULL',
                        'Reference_no' => $item['ID'] ?? 'NULL',
                        'qty' => $item['qty'] ?? 'NULL',
                        'DESCR' => $item['DESCR'] ?? 'NULL',
                        'Available_Qty' => $item['Available_Qty'] ?? 'NULL',
                    ]);

                    DB::statement("EXEC sp_InsertPicklistAndItem_balikan ?, ?, ?, ?, ?, ?, ?, ?, ?, ?", [
                        $item['picklistid'] ?? null,
                        $item['to_storer'] ?? null,
                        $item['deliverydate'] ?? null,
                        $item['article'] ?? null,
                        $item['request_qty'] ?? null,
                        $whseid,
                        $relasi,
                        $addwho,
                        $item['storerkey'] ?? null,
                        $item['ID'] ?? null
                    ]);
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil disimpan!',
                    'redirect' => route('admin.transaction.storing.saveeditpicklist') // Sesuaikan dengan route Blade tujuan
                ]);
            } catch (\Exception $e) {
                Log::error("Terjadi error di foreach", [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Gagal menyimpan data! Terjadi kesalahan: ' . $e->getMessage()
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }


    public function ViewPageSummaryPicklistbalikan(Request $request)
    {
        try {
            Log::info('Data Diterima di Controller:', $request->all());
            // Ambil user yang sedang login untuk addwho
            Log::info('SEBELUM AMBIL USER');
            //Mengambil nilai id yang sedang login
            $id = Auth::guard('admin')->id();
            $data = Admin::find($id);
            $addwho = $data->userid;

            Log::info('Mengambil ADDWHO untuk:', ['ADDWHO' => $addwho]);
            $to_storer = $request->input('to_storer');

            // Ambil picklistid dari SP

            Log::info('Mengambil Picklist ID untuk:', ['to_storer' => $to_storer]);

            $picklistData = DB::select("EXEC GetPickID_balikan ?", [$to_storer]);
            $picklistid = $picklistData[0]->{'picklistid'} ?? null; // Ganti 'column_name' dengan nama yang benar dari hasil SP
            $deliverydate = $request->input('deliverydate');
            $facilityInfo = session('facility_info', []);
            $whseid = $facilityInfo[0]['Facility_ID'] ?? $request->input('WHSEID', '');
            $relasi = $facilityInfo[0]['Relasi'] ?? $request->input('RELASI', '');
            $storerkey = $request->input('selected_owners', ['ALL']);
            if (is_array($storerkey)) {
                // Jika array, gabungkan menjadi string dengan delimiter ";"
                $ownerString = implode(';', $storerkey);
            } else {
                // Jika bukan array, langsung gunakan sebagai string
                $ownerString = $storerkey;
            }

            Log::info('owner:', [$ownerString]); // owner
            Log::info('WHSEID yang didapat:', ['WHSEID' => $whseid]);
            Log::info('RELASI yang didapat:', ['RELASI' => $relasi]);
            Log::info("Cek isi items sebelum validasi:", ['items' => $request->input('items')]);

            // Ambil data items dengan default array kosong jika tidak ada
            $items = $request->input('items', []);

            if (empty($items)) {
                Log::error("Gagal: Tidak ada barang yang disimpan!", ['items' => $items]);
                return back()->with('error', 'Tidak ada barang yang disimpan.');
            }

            try {
                Log::info('INI SEBELUM MASUK KE FOREACH ITEM');

                if (!is_array($items) || empty($items)) {
                    Log::error("Error: Items tidak valid!", ['items' => json_encode($items)]);
                    return back()->with('error', 'Data barang tidak valid.');
                }

                Log::info('Items valid, masuk ke foreach');

                foreach ($items as $item) {
                    Log::info("Menyimpan item ke DB", [
                        'picklistid' => json_encode($picklistid),
                        'to_storer' => $to_storer,
                        'deliverydate' => $deliverydate,
                        'article' => $item['article'] ?? 'NULL',
                        'qty' => $item['qty'] ?? 'NULL',
                        'whseid' => $whseid,
                        'relasi' => $relasi,
                        'addwho' => $addwho,
                        'owner' => $ownerString,
                        'Reference_no' => $item['Reference_no'] ?? 'NULL',
                    ]);

                    DB::statement("EXEC sp_InsertPicklistAndItem_balikan ?, ?, ?, ?, ?, ?, ?, ?, ?, ?", [
                        $picklistid,
                        $to_storer,
                        $deliverydate,
                        $item['article'] ?? null,
                        $item['qty'] ?? null,
                        $whseid,
                        $relasi,
                        $addwho,
                        $ownerString,
                        $item['Reference_no'] ?? null // Cek apakah NULL atau ada isinya
                    ]);
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil disimpan!',
                    'redirect' => route('admin.transaction.storing.picklistbalikan') // Sesuaikan dengan route Blade tujuan
                ]);
            } catch (\Exception $e) {
                Log::error("Terjadi error di foreach", [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan Picklist: ' . $e->getMessage());
        }
    }
}
