<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GetController;
use App\Http\Controllers\Api\PostController;
use App\Http\Middleware\ApiKeyMiddleware;
use Illuminate\Support\Facades\Response;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//test Ai
Route::apiResource('/posts', App\Http\Controllers\Api\PostController::class);
Route::apiResource('/get', App\Http\Controllers\Api\GetController::class);

//Android Dede
// Grup route yang menggunakan API Key Middleware
Route::middleware(ApiKeyMiddleware::class)->group(function () {
    //Public
    Route::get('/getownerlogin/{param1?}/{param2?}/{param3?}', [PostController::class, 'getOwnerLogin']);
    //Public

    //Android Checklist 5R
    Route::get('/getfacilitylogin/{param1?}/{param2?}/{param3?}', [PostController::class, 'getFacilityLogin']);
    Route::get('/getvalidasilogin/{param1?}/{param2?}/{param3?}', [PostController::class, 'getValidasiUserLogin']);
    Route::get('/getversilogin/{param1?}/{param2?}/{param3?}', [PostController::class, 'getVersiAndroidLogin']);
    Route::get('/getownerlogin/{param1?}/{param2?}/{param3?}/{param4?}/{param5?}', [PostController::class, 'getOwnerAndroidLogin']);
    Route::get('/getarealogin/{param1?}/{param2?}/{param3?}/{param4?}/{param5?}', [PostController::class, 'getAreaAndroidLogin']);
    Route::get('/getdeptlogin/{param1?}/{param2?}/{param3?}/{param4?}/{param5?}', [PostController::class, 'getDeptAndroidLogin']);
    Route::get('/getmastershiftlogin/{param1?}/{param2?}/{param3?}', [PostController::class, 'getMasterShiftAndroidLogin']);
    Route::get('/gettransaksilogin/{param1?}/{param2?}/{param3?}/{param4?}/{param5?}', [PostController::class, 'getTransaksiAndroidLogin']);
    Route::get('/getftplogin/{param1?}/{param2?}/{param3?}', [PostController::class, 'getFTPAndroidLogin']);
    Route::post('/insertChecklist5R', [PostController::class, 'postChecklist5R']);
    Route::post('/uploadFotoChecklist5R', [PostController::class, 'postFotoChecklist5R']);
    Route::get('/getreportchecklist5r/{param1?}/{param2?}/{param3?}/{param4?}/{param5?}', [PostController::class, 'getReportChecklist5RLogin']);
    //Android Checklist 5R

    //Android CICO ASET
    Route::get('/getandroidasetdetail/{param1?}/{param2?}/{param3?}/{param4?}/{param5?}', [PostController::class, 'getAndroidAsetDetailLogin']);
    Route::post('/postLogPageAndroid/{param1}/{param2}/{param3}/{param4}', [PostController::class, 'postLogPageLogin']);
    Route::post('/postInsertAssetMobile/{param1}/{param2}/{param3}/{param4}/{param5}/{param6}/{param7}/{param8}', [PostController::class, 'postInsertAssetMobileLogin']);
    Route::get('/getChecklistCico/{param1}/{param2}/{param3}/{param4}/{param5}/{param6}/{param7}/{param8}', [PostController::class, 'getChecklistCicoLogin']);
    Route::get('/getLogUserCico/{param1}/{param2}/{param3}/{param4}/{param5}/{param6}/{param7}/{param8}', [PostController::class, 'getLogUserCicoLogin']);
    Route::get('/getUnitTypeCico/{param1}/{param2}/{param3}', [PostController::class, 'getUnitTypeCicoLogin']);
    Route::get('/getDashboardCico/{param1}/{param2}/{param3}', [PostController::class, 'getDashboardCicoLogin']);
    Route::get('/getDetDashboardCico/{param1}/{param2}/{param3}/{param4}', [PostController::class, 'getDetDashboardCicoLogin']);
    //Android CICO ASET

    //Ini Untuk Empty Loc
    Route::get('/getReasonEmptyLoc/{param1}/{param2}/{param3}', [PostController::class, 'getReasonEmptyLocLogin']);
    Route::get('/getValidasiReasonEmptyLoc/{param1}/{param2}/{param3}', [PostController::class, 'getValidasiReasonEmptyLocLogin']);
    Route::post('/postInsertReasonEmptyLoc/{param1}/{param2}/{param3}/{param4}/{param5}', [PostController::class, 'PostInsertReasonEmptyLocLogin']);
    //Ini Untuk Empty Loc

    //Ini Untuk Balikan Store
    Route::get('/getStoreName/{param1}/{param2}/{param3}/{param4}/{param5}/{param6}/{param7}/{param8}/{param9}/{param10}/{param11}/{param12}', [PostController::class, 'getStoreBalikanStoreLogin']);
    Route::get('/getDescr/{param1}/{param2}/{param3}/{param4}/{param5}/{param6}/{param7}/{param8}/{param9}/{param10}/{param11}/{param12}', [PostController::class, 'getDescrBalikanStoreLogin']);
    Route::get('/getMove/{param1}/{param2}/{param3}/{param4}/{param5}/{param6}/{param7}/{param8}', [PostController::class, 'getMoveBalikanStoreLogin']);


    Route::post('/insertReceiveBarangBalikanStore', [PostController::class, 'postInsertReceiveBalikanStoreLogin']);
    Route::post('/uploadFotoReceiveBarangBalikanStore', [PostController::class, 'postFotoReceiveBarangBalikanStoreLogin']);
    Route::post('/insertMoveBarangBalikanStore/{param1}/{param2}/{param3}/{param4}/{param5}/{param6}/{param7}/{param8}/{param9}/{param10}/{param11}/{param12}/{param13}/{param14}', [PostController::class, 'postInsertMoveBalikanStoreLogin']);
    //Ini Untuk Balikan Store

    //Ini Untuk MHE
    Route::get('/getTaskMHE/{param1}/{param2}/{param3}/{param4}/{param5}/{param6}', [PostController::class, 'getDataTaskMHELogin']);
    Route::post('/uploadFotoPreventive', [PostController::class, 'postFotoPreventive']);
    Route::post('/UpdateDailyAssignmentSchedule', [PostController::class, 'postDailyAssignmentSchedule']);
    Route::post('/InsertFormHelpdeskMHE', [PostController::class, 'postFormHelpdeskMHE']);
    Route::post('/uploadfoto', [PostController::class, 'postFoto']);
    Route::post('/InsertAssignmentScheduleMobile', [PostController::class, 'postAssignmentScheduleMobile']);
    Route::post('/insertservice', [PostController::class, 'postinsertservice']);
    Route::post('/insertmheemp', [PostController::class, 'postinsertmheemp']);


    //Ini Untuk MHE

    /// Ini general untuk get data mobile
    Route::get('/getDataMobile/{param1}/{param2}/{param3}/{param4}/{param5}', [PostController::class, 'getDataMobileLogin']);
    Route::get('/getDataMobilearray/{param1?}/{param2?}/{param3?}/{param4?}/{param5?}', [PostController::class, 'getDataMobilearrayLogin']);
    /// Ini general untuk get data mobile

    /// ini untuk serah terima dokumen
    Route::post('/uploadfotoserahterima', [PostController::class, 'postFotoSerahTerima']);
    Route::post('/insertserahterima', [PostController::class, 'postInsertSerahTerima']);
    /// ini untuk serah terima dokumen

    // ini untuk mendapatkan image dari ftp
    Route::get('/getfoto/{folder}/{sub}/{filename}', function ($folder, $sub, $filename) {
        $ftpPath = "ftp://anonymous:your_email@example.com@10.1.100.119:2121/Attachment/{$folder}/{$sub}/{$filename}";
    
        // Coba ambil konten dari file FTP utama
        $imageContent = @file_get_contents($ftpPath);
    
        if ($imageContent === false) {
            // Jika gagal, ambil gambar fallback dari FTP
            $fallbackFtpPath = "ftp://anonymous:your_email@example.com@10.1.100.119:2121/Attachment/NoImage.jpg";
            $imageContent = @file_get_contents($fallbackFtpPath);
    
            // Jika fallback juga gagal, kirim error
            if ($imageContent === false) {
                return response()->json(['message' => 'File and fallback not found'], 404);
            }
    
            $filename = 'NoImage.jpg';
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($imageContent);
        } else {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($imageContent);
        }
    
        return Response::make($imageContent, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    });    
    // ini untuk mendapatkan image dari ftp

    // ini untuk update master user
    Route::post('/updatepasslogin', [PostController::class, 'postupdatepasslogin']);
    // ini untuk update master user

      //Ini Untuk Replensih
    Route::get('/getDataReplensih/{param1}/{param2}/{param3}/{param4}/{param5}/{param6}/{param7}', [PostController::class, 'getDataReplenishLogin']);
     //Ini Untuk Replensih

        //INI TAMBAHAN BELUM DI SYNC
    Route::post('/InsertRatingHelpdeskMHE', [PostController::class, 'postRatingHelpdeskMHE']);
        //INI TAMBAHAN BELUM DI SYNC

    // Ini Untuk Return ODI
    //Route::post('/insertRetunODILogin/{param1}/{param2}/{param3}/{param4}/{param5}/{param6}/{param7}/{param8}/{param9}/{param10}', [PostController::class, 'postInsertReturnODILogin']);
     Route::post('/insertRetunODILogin', [PostController::class, 'postInsertRetunODILogin']);

    // Ini Untuk Return ODI SELECT LOC
     Route::get('/getLocationByNoResi/{no_resi}', [PostController::class, 'getLocation']);

     // Ini Untuk Return ODI SELECT LOC
     Route::get('/getLocationByNoResi1/{no_resi}', [PostController::class, 'getLocation1']);

});

