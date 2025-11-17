<?php

namespace App\Http\Controllers\Api;

//import model Post
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Api\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;



//import resource PostResource
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    // public function index()
    // {
    //     //get all posts
    //     $posts = Post::latest()->paginate(5);
    //     //return collection of posts as a resource
    //     return new PostResource(true, 'List Data Posts', $posts);
    // }

    //Public
    public function getOwnerLogin($param1 = '-', $param2 = '-', $param3 = '')
    {

        //dd($param1, $param2, $param3);
        // Paksa penggantian parameter kosong (null) menjadi string '-'
        // $param1 = is_null($param1) || $param1 === '' ? '-' : $param1;
        // $param2 = is_null($param2) || $param2 === '' ? '-' : $param2;
        // $param3 = is_null($param3) || $param3 === '' ? '-' : $param3;

        // Mengeksekusi stored procedure dengan parameter yang diterima
        // $results = DB::select('EXEC [udsp_Get_Data] ?, ?, ?, ?', [
        //     'Get Data Relasi',$param1, $param2, $param3
        // ]);

        $results = Post::getOwner($param1, $param2, $param3);
        //return $results;
        // Mengembalikan data sebagai resource
        return new PostResource(true, 'List Data Retrieved from SP', $results);
    }
    //Public



    // Ini Checklist 5R
    public function postFotoChecklist5R(Request $request)
    {

        // Validasi input
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png', // Memastikan hanya file gambar yang diterima
        ]);

        // Ambil file dari request
        $file = $request->file('file');

        // Ambil nama file dan ekstensi
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        // Tentukan folder berdasarkan nama file
        // $prefix = substr($filename, 0, strpos($filename, '_'));

        // Extract the alphabetic prefix before any digits (e.g., "Rapi" from "Rapi1", "Rapi2", etc.)
        $prefix = preg_match('/^[a-zA-Z]+/', $filename, $matches) ? $matches[0] : 'Others';

        $folder = match ($prefix) {
            'Rapi' => 'Rapi',
            'Resik' => 'Resik',
            'Ringkas' => 'Ringkas',
            'Rawat' => 'Rawat',
            default => 'Others',
        };

        // Tentukan path di FTP, misalnya di dalam folder Attachment/Checklist5R/
        $ftpFolderPath = 'Attachment/Checklist5R/' . $folder . '/' . $filename;

        // dd([
        //     'filename' => $filename,
        //     'extension' => $extension,
        //     'prefix' => $prefix,
        //     'folder' => $folder,
        //     'ftpFolderPath' => $ftpFolderPath,
        // ]);


        // Upload ke FTP
        $fileContents = file_get_contents($file);

        // Menggunakan Storage untuk upload file ke FTP
        if (Storage::disk('ftp')->put($ftpFolderPath, $fileContents)) {
            return response()->json(['message' => 'Upload Successful'], 200);
        }

        return response()->json(['message' => 'Failed to upload'], 500);
    }

    public function getFacilityLogin($param1 = '-', $param2 = '-', $param3 = '')
    {

        //dd($param1, $param2, $param3);
        // Paksa penggantian parameter kosong (null) menjadi string '-'
        // $param1 = is_null($param1) || $param1 === '' ? '-' : $param1;
        // $param2 = is_null($param2) || $param2 === '' ? '-' : $param2;
        // $param3 = is_null($param3) || $param3 === '' ? '-' : $param3;

        // Mengeksekusi stored procedure dengan parameter yang diterima
        // $results = DB::select('EXEC [udsp_Get_Data] ?, ?, ?, ?', [
        //     'Get Data Relasi',$param1, $param2, $param3
        // ]);

        $results = Post::getFacility($param1, $param2, $param3);
        //return $results;
        // Mengembalikan data sebagai resource
        return new PostResource(true, 'List Data Retrieved from SP', $results);
    }

    public function getValidasiUserLogin($param1, $param2, $param3)
    {
        // Mengeksekusi stored procedure dengan parameter yang diterima
        //dd($param1, $param2, $param3);
        $results = Post::getValidasiUser($param1, $param2, $param3);
        return $results;
    }

    public function getVersiAndroidLogin($param1, $param2, $param3)
    {
        // Mengeksekusi stored procedure dengan parameter yang diterima
        //dd($param1, $param2, $param3);
        $results = Post::getVersiAndroid($param1, $param2, $param3);
        return $results;
    }

    public function getOwnerAndroidLogin($param1, $param2, $param3, $param4, $param5)
    {
        // Mengeksekusi stored procedure dengan parameter yang diterima
        //dd($param1, $param2, $param3);
        $results = Post::getOwnerAndroid($param1, $param2, $param3, $param4, $param5);
        return $results;
    }

    public function getDeptAndroidLogin($param1, $param2, $param3, $param4, $param5)
    {
        // Mengeksekusi stored procedure dengan parameter yang diterima
        //dd($param1, $param2, $param3);
        $results = Post::getDeptAndroid($param1, $param2, $param3, $param4, $param5);
        return $results;
    }

    public function getAreaAndroidLogin($param1, $param2, $param3, $param4, $param5)
    {
        // Mengeksekusi stored procedure dengan parameter yang diterima
        //dd($param1, $param2, $param3);
        $results = Post::getAreaAndroid($param1, $param2, $param3, $param4, $param5);
        return $results;
    }

    public function getMasterShiftAndroidLogin($param1, $param2, $param3)
    {
        // Mengeksekusi stored procedure dengan parameter yang diterima
        //dd($param1, $param2, $param3);
        $results = Post::getMasterShiftAndroid($param1, $param2, $param3);
        return $results;
    }

    public function getTransaksiAndroidLogin($param1, $param2, $param3, $param4, $param5)
    {
        // Mengeksekusi stored procedure dengan parameter yang diterima
        //dd($param1, $param2, $param3);
        $results = Post::getTransaksiAndroid($param1, $param2, $param3, $param4, $param5);
        return $results;
    }

    public function getFTPAndroidLogin($param1, $param2, $param3)
    {
        // Mengeksekusi stored procedure dengan parameter yang diterima
        //dd($param1, $param2, $param3);
        $results = Post::getFTPAndroid($param1, $param2, $param3);
        return $results;
    }


    public function postChecklist5R(Request $request)
    {
        try {
            // Ambil data dari request
            $params = [
                $request->input('whse'),
                $request->input('relasi'),
                $request->input('hci'),
                $request->input('outbound'),
                $request->input('area'),
                $request->input('deskripsi_rapi'),
                $request->input('deskripsi_rawat'),
                $request->input('deskripsi_resik'),
                $request->input('deskripsi_ringkas'),
                $request->input('rapi1'),
                $request->input('rapi2'),
                $request->input('rapi3'),
                $request->input('rawat1'),
                $request->input('rawat2'),
                $request->input('rawat3'),
                $request->input('resik1'),
                $request->input('resik2'),
                $request->input('resik3'),
                $request->input('ringkas1'),
                $request->input('ringkas2'),
                $request->input('ringkas3'),
                $request->input('id_user'),
            ];

            // Eksekusi stored procedure
            $result = Post::insertChecklist($params);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil di-insert',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal insert data',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: Database sedang restore coba lagi dalam beberapa menit' . $e->getMessage(),
            ], 500);
        }
    }


    public function getReportChecklist5RLogin($param1, $param2, $param3, $param4, $param5)
    {
        // Mengeksekusi stored procedure dengan parameter yang diterima
        //dd($param1, $param2, $param3);
        $results = Post::getReportChecklist5R($param1, $param2, $param3, $param4, $param5);
        return $results;
    }



    // Ini CICO ASET
    public function getAndroidAsetDetailLogin($param1, $param2, $param3, $param4, $param5)
    {
        // Mengeksekusi stored procedure dengan parameter yang diterima
        //dd($param1, $param2, $param3);
        $results = Post::getAndroidAsetDetail($param1, $param2, $param3, $param4, $param5);
        return $results;
    }

    public function postLogPageLogin($param1, $param2, $param3, $param4)
    {
        try {
            // Panggil metode dari model
            $results = Post::PostLogPage($param1, $param2, $param3, $param4);

            // Kirim respon berhasil
            return response()->json([
                'status' => 'success',
                'message' => 'Log page inserted successfully',
                'data' => $results,
            ], 200);
        } catch (\Exception $e) {
            // Kirim respon error
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function postInsertAssetMobileLogin($param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8)
    {
        try {
            // Panggil metode dari model
            $results = Post::PostInsertAssetMobile($param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8);

            // Kirim respon berhasil
            return response()->json([
                'status' => 'success',
                'message' => 'Log page inserted successfully',
                'data' => $results,
            ], 200);
        } catch (\Exception $e) {
            // Kirim respon error
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getChecklistCicoLogin($param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8)
    {
        // Mengeksekusi stored procedure dengan parameter yang diterima
        //dd($param1, $param2, $param3);
        $results = Post::getChecklistCico($param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8);
        return $results;
    }

    public function getLogUserCicoLogin($param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8)
    {
        // Mengeksekusi stored procedure dengan parameter yang diterima
        //dd($param1, $param2, $param3);
        $results = Post::getLogUserCico($param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8);
        return $results;
    }

    public function getUnitTypeCicoLogin($param1, $param2, $param3)
    {
        // Mengeksekusi stored procedure dengan parameter yang diterima
        //dd($param1, $param2, $param3);
        $results = Post::getUnitTypeCico($param1, $param2, $param3);
        return $results;
    }

    public function getDashboardCicoLogin($param1, $param2, $param3)
    {
        // Mengeksekusi stored procedure dengan parameter yang diterima
        //dd($param1, $param2, $param3);
        $results = Post::getDashboardCico($param1, $param2, $param3);
        return $results;
    }

    public function getDetDashboardCicoLogin($param1, $param2, $param3, $param4)
    {
        // Mengeksekusi stored procedure dengan parameter yang diterima
        //dd($param1, $param2, $param3);
        $results = Post::getDetDashboardCico($param1, $param2, $param3, $param4);
        return $results;
    }
    /////////
    // Ini CICO ASET

    ///Ini Untuk Empty Loc
    public function getReasonEmptyLocLogin($param1, $param2, $param3)
    {
        // Mengeksekusi stored procedure dengan parameter yang diterima
        //dd($param1, $param2, $param3);
        $results = Post::getReasonEmptyLoc($param1, $param2, $param3);
        return $results;
    }

    public function getValidasiReasonEmptyLocLogin($param1, $param2, $param3)
    {
        // Mengeksekusi stored procedure dengan parameter yang diterima
        //dd($param1, $param2, $param3);
        $results = Post::getValidasiReasonEmptyLoc($param1, $param2, $param3);
        return $results;
    }

    public function PostInsertReasonEmptyLocLogin($param1, $param2, $param3, $param4, $param5)
    {
        try {
            // Panggil metode dari model
            $results = Post::PostInsertReasonEmptyLoc($param1, $param2, $param3, $param4, $param5);
            // Kirim respon berhasil
            return response()->json([
                'status' => 'success',
                'message' => 'Log page inserted successfully',
                'data' => $results,
            ], 200);
        } catch (\Exception $e) {
            // Kirim respon error
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    ///Ini Untuk Empty Loc

    // Ini Untuk Balikan Store
    public function getStoreBalikanStoreLogin($param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8, $param9, $param10, $param11, $param12)
    {
        // Mengeksekusi stored procedure dengan parameter yang diterima
        //dd($param1, $param2, $param3);
        $results = Post::getStoreBalikanStore($param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8, $param9, $param10, $param11, $param12);
        return $results;
    }


    public function getDescrBalikanStoreLogin($param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8, $param9, $param10, $param11, $param12)
    {
        // Mengeksekusi stored procedure dengan parameter yang diterima
        //dd($param1, $param2, $param3);
        $results = Post::getDescrBalikanStore($param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8, $param9, $param10, $param11, $param12);
        return $results;
    }

    public function getMoveBalikanStoreLogin($param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8)
    {
        // Mengeksekusi stored procedure dengan parameter yang diterima
        //dd($param1, $param2, $param3);
        $results = Post::getMoveBalikanStore($param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8);
        return $results;
    }

    public function postInsertReceiveBalikanStoreLogin(Request $request)
    {
        try {
            // Ambil data dari request
            $params = [
                $request->input('notto'),
                $request->input('fromstore'),
                $request->input('fromstorename'),
                $request->input('receivedate'),
                $request->input('notes'),
                $request->input('whseid'),
                $request->input('relasi'),
                $request->input('article'),
                $request->input('articledescr'),
                $request->input('qtytto'),
                $request->input('qtyreceive'),
                $request->input('nolpn'),
                $request->input('status'),
                $request->input('toloc'),
                $request->input('fotobarangbalikan1'),
                $request->input('fotobarangbalikan2'),
                $request->input('fotobarangbalikan3'),
                $request->input('referenceno'),
                $request->input('addwho'),
                $request->input('parameter1'),
                $request->input('parameter2')
            ];

            // Eksekusi stored procedure
            $result = Post::insertReceiveBarangBalikanStore($params);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil di-insert',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal insert data',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function postFotoReceiveBarangBalikanStoreLogin(Request $request)
    {
        // Validasi input
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png', // Memastikan hanya file gambar yang diterima
        ]);

        // Ambil file dari request
        $file = $request->file('file');

        // Ambil nama file dan ekstensi
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        //$ftpFolderPath = 'Attachment/BarangBalikan/' . $folder . '/' . $filename;
        $ftpFolderPath = 'Attachment/BarangBalikan/' . $filename;
        //dd($ftpFolderPath); // Debug to check the path

        // Upload ke FTP
        $fileContents = file_get_contents($file);

        // Menggunakan Storage untuk upload file ke FTP
        if (Storage::disk('ftp')->put($ftpFolderPath, $fileContents)) {
            return response()->json(['message' => 'Upload Successful'], 200);
        }

        return response()->json(['message' => 'Failed to upload'], 500);
    }

    public function postInsertMoveBalikanStoreLogin($param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8, $param9, $param10, $param11, $param12, $param13, $param14)
    {
        try {
            // Panggil metode dari model
            $results = Post::postInsertMoveBalikanStore($param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8, $param9, $param10, $param11, $param12, $param13, $param14);
            // Kirim respon berhasil
            return response()->json([
                'status' => 'success',
                'message' => 'Log page inserted successfully',
                'data' => $results,
            ], 200);
        } catch (\Exception $e) {
            // Kirim respon error
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    // Ini Untuk Balikan Store 













    // Ini Untuk MHE
    public function getDataTaskMHELogin($param1, $param2, $param3, $param4, $param5, $param6)
    {
        try {
            $results = Post::getDataTaskMHE($param1, $param2, $param3, $param4, $param5, $param6);
            return $results;
        } catch (\Exception $e) {
        }
    }

    public function postFotoPreventive(Request $request)
    {

        // Validasi input
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png', // Memastikan hanya file gambar yang diterima
        ]);

        // Ambil file dari request
        $file = $request->file('file');

        // Ambil nama file dan ekstensi
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        // Tentukan folder berdasarkan nama file
        // $prefix = substr($filename, 0, strpos($filename, '_'));

        // Extract the alphabetic prefix before any digits (e.g., "Rapi" from "Rapi1", "Rapi2", etc.)
        $prefix = preg_match('/^[a-zA-Z]+/', $filename, $matches) ? $matches[0] : 'Others';

        $folder = match ($prefix) {
            'Preventive' => 'Preventive',
            default => 'Others',
        };

        // Tentukan path di FTP, misalnya di dalam folder Attachment/Checklist5R/
        $ftpFolderPath = 'Attachment/Maintainance/' . $folder . '/' . $filename;

        // Upload ke FTP
        $fileContents = file_get_contents($file);

        // Menggunakan Storage untuk upload file ke FTP
        if (Storage::disk('ftp')->put($ftpFolderPath, $fileContents)) {
            return response()->json(['message' => 'Upload Successful'], 200);
        }

        return response()->json(['message' => 'Failed to upload'], 500);
    }

    public function postFoto(Request $request)
    {
        // Validasi input
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png',
            'folder' => 'nullable|string',  // Opsional, nama folder
            'filename' => 'nullable|string' // Opsional, nama file
        ]);

        // Ambil file dari request
        $file = $request->file('file');

        // Ambil ekstensi file
        $extension = $file->getClientOriginalExtension();

        // Gunakan nama file dari request atau default ke nama asli
        $filename = $request->input('filename', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $filename = preg_replace('/[^a-zA-Z0-9_-]/', '', $filename); // Bersihkan karakter tidak valid

        // Tentukan folder berdasarkan parameter atau default berdasarkan nama file
        $defaultFolder = preg_match('/^[a-zA-Z]+/', $filename, $matches) ? $matches[0] : 'Others';
        $folder = $request->input('folder', $defaultFolder);

        // Tentukan path di FTP
        $ftpFolderPath = "Attachment/{$folder}/{$filename}.{$extension}";

        // Upload ke FTP
        $fileContents = file_get_contents($file);

        if (Storage::disk('ftp')->put($ftpFolderPath, $fileContents)) {
            return response()->json(['message' => 'Upload Successful', 'path' => $ftpFolderPath], 200);
        }

        return response()->json(['message' => 'Failed to upload'], 500);
    }

    public function postDailyAssignmentSchedule(Request $request)
    {
        try {
            // Ambil data dari request
            $params = [
                $request->input('state'),
                $request->input('whse'),
                $request->input('relasi'),
                $request->input('taskid'),
                $request->input('unitno'),
                $request->input('pic'),
                $request->input('temmate'),
                $request->input('attach1'),
                $request->input('attach2'),
                $request->input('attach3'),
                $request->input('checklist'),
                $request->input('note'),
            ];

            // Eksekusi stored procedure
            $result = Post::updateDailyAssignmentSchedule($params);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil di-insert',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal insert data',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function postFormHelpdeskMHE(Request $request)
    {
        try {
            // Ambil data dari request
            $params = [
                $request->input('state'),
                $request->input('whse'),
                $request->input('relasi'),
                $request->input('requester'),
                $request->input('unitno'),
                $request->input('location'),
                $request->input('issuecategory'),
                $request->input('issue'),
                $request->input('adduser'),
                $request->input('attach'),
            ];

            // Eksekusi stored procedure
            $result = Post::InsertFormHelpdeskMHE($params);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil di-insert',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal insert data',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function postAssignmentScheduleMobile(Request $request)
    {
        try {
            // Ambil data dari request
            $params = [
                $request->input('whse'),
                $request->input('relasi'),
                $request->input('unitno'),
                $request->input('pic'),
                $request->input('temmate'),
                $request->input('taskaname'),
                $request->input('assignmentdate'),
                $request->input('requestid'),
                $request->input('adduser'),
                $request->input('state'),
                $request->input('note'),
            ];

            // Eksekusi stored procedure
            $result = Post::InsertAssignmentScheduleMobile($params);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil di-insert',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal insert data',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function postinsertservice(Request $request)
    {
        try {
            // Ambil data dari request
            $params = [
                $request->input('state'),
                $request->input('whse'),
                $request->input('relasi'),
                $request->input('unitno'),
                $request->input('pic'),
                $request->input('temmate'),
                $request->input('taskname'),
                $request->input('taskid'),
                $request->input('activityid'),
                $request->input('attach1'),
                $request->input('attach2'),
                $request->input('attach3'),
                $request->input('note'),
                $request->input('addwho'),
                $request->input('articlecode'),

            ];

            // Eksekusi stored procedure
            $result = Post::InsertService($params);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil di-insert',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal insert data',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function postinsertmheemp(Request $request)
    {
        try {
            // Ambil data dari request
            $params = [
                $request->input('state'),
                $request->input('whse'),
                $request->input('relasi'),
                $request->input('empid'),
                $request->input('assignment'),
                $request->input('executor'),
            ];

            // Eksekusi stored procedure
            $result = Post::InsertMHEEmp($params);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil di-insert',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal insert data',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
    // Ini Untuk MHE

    /// Ini general untuk get data mobile
    public function getDataMobileLogin($param1, $param2, $param3, $param4, $param5)
    {
        try {
            $results = Post::getDataMobile($param1, $param2, $param3, $param4, $param5);
            return $results;
        } catch (\Exception $e) {
        }
    }

    public function getDataMobilearrayLogin($param1, $param2, $param3, $param4, $param5)
    {
        // Mengeksekusi stored procedure dengan parameter yang diterima
        //dd($param1, $param2, $param3);
        $results = Post::getDataMobileArray($param1, $param2, $param3, $param4, $param5);
        return $results;
    }

    /// Ini general untuk get data mobile

    /// ini untuk serah terima dokumen

    public function postFotoSerahTerima(Request $request)
    {

        // Validasi input
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png', // Memastikan hanya file gambar yang diterima
        ]);

        // Ambil file dari request
        $file = $request->file('file');

        // Ambil nama file dan ekstensi
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        // Tentukan folder berdasarkan nama file
        // $prefix = substr($filename, 0, strpos($filename, '_'));

        // Extract the alphabetic prefix before any digits (e.g., "Rapi" from "Rapi1", "Rapi2", etc.)
        $prefix = preg_match('/^[a-zA-Z]+/', $filename, $matches) ? $matches[0] : 'Others';

        $folder = match ($prefix) {
            'SerahTerima' => 'SerahTerima',
            default => 'Others',
        };

        // Tentukan path di FTP, misalnya di dalam folder Attachment/Checklist5R/
        $ftpFolderPath = 'Attachment/SerahTerima/Dokumen/' . $filename;

        // Upload ke FTP
        $fileContents = file_get_contents($file);

        // Menggunakan Storage untuk upload file ke FTP
        if (Storage::disk('ftp')->put($ftpFolderPath, $fileContents)) {
            return response()->json(['message' => 'Upload Successful'], 200);
        }

        return response()->json(['message' => 'Failed to upload'], 500);
    }


    public function postInsertSerahTerima(Request $request)
    {
        try {
            // Ambil data dari request
            $params = [
                $request->input('whse'),
                $request->input('relasi'),
                $request->input('transno'),
                $request->input('owner'),
                $request->input('nopol'),
                $request->input('serahterima'),
                $request->input('attach'),
                $request->input('receiver'),
                $request->input('submitter'),
                $request->input('addwho'),
            ];
            //dd($params);
            // Eksekusi stored procedure
            $result = Post::InsertSerahTerima($params);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil di-insert',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal insert data',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
    /// ini untuk serah terima dokumen


    // ini untuk update master user
    public function postupdatepasslogin(Request $request)
    {
        try {
            // Ambil data dari request
            $params = [
                $request->input('state'),
                $request->input('userid'),
                $request->input('pass'),
                $request->input('site'),
            ];

            // Eksekusi stored procedure
            $result = Post::UpdatePassLogin($params);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil di-insert',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal insert data',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
    // ini untuk update master user
    /// Ini replenish
    public function getDataReplenishLogin($param1, $param2, $param3, $param4, $param5, $param6, $param7)
    {
        try {
            $results = Post::getDataReplensih($param1, $param2, $param3, $param4, $param5, $param6, $param7);
            return $results;
        } catch (\Exception $e) {
        }
    }
    /// Ini replenish

    //INI TAMBAHAN BELUM DI SYNC
    public function postRatingHelpdeskMHE(Request $request)
    {
        try {
            // Ambil data dari request
            $params = [
                $request->input('whse'),
                $request->input('relasi'),
                $request->input('requestid'),
                $request->input('rating'),
                $request->input('comment'),
                $request->input('adduser'),
            ];

            // Eksekusi stored procedure
            $result = Post::InsertRatingHelpdeskMHE($params);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil di-insert',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal insert data',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getDataAttachment5RLogin($param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8)
    {
        try {
            $results = Post::getDataAttachment5R($param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8);
            return $results;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching data: ' . $e->getMessage(),
            ], 500);
        }
    }

    //INI TAMBAHAN BELUM DI SYNC

}
