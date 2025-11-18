<?php

// Panggil Controller Yang Dibuat Disini Pake Use 


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\Admin\Report\Inventory\ReportThroughput;
use App\Http\Controllers\Admin\Report\Outbound\ReportDemandInOutBacklog;
use App\Http\Controllers\Admin\Dashboard\Inventory\MonitoringBarangRusak;
use App\Http\Controllers\Admin\Master\Inventory\Master_damage_from_controller;
use App\Http\Controllers\Admin\Master\Public\Checklist5R\MasterChecklist5R;
use App\Http\Controllers\Admin\Master\Public\Maintenance\MasterAsset;
use App\Http\Controllers\Admin\Dashboard\Public\DashboardChecklist5R;
use App\Http\Controllers\Admin\Report\Public\ReportChecklist5R;
use App\Http\Controllers\DownloadApkController;
use App\Http\Controllers\Admin\Dashboard\Transport\DashboardInLinePlan;
use App\Http\Controllers\Admin\Report\Transport\MonitoringTimeStampProsesDC;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Admin\Dashboard\Outbound\AutoCancelODI;
use App\Http\Controllers\Admin\Report\Inventory\ReportIntercompany;
use App\Http\Controllers\Admin\Report\public\Maintenance\ReportTaskMHE;
use App\Http\Controllers\Admin\Report\Outbound\ReportKontributorLPPB;
use App\Http\Controllers\Admin\Report\Transport\SummaryProgressLC;
use App\Http\Controllers\Admin\Dashboard\Storing\DashboardCaseIDOpenNonLC;
use App\Http\Controllers\Admin\Report\Inbound\Monitoring_receieve_balikan;
use App\Http\Controllers\Admin\Dashboard\Storing\Monitoring_stock_balikan;
use  App\Http\Controllers\Ticket\TicketController;
use  App\Http\Controllers\Ticket\Ticketing;
use App\Http\Controllers\Android\DownloadAPKAndroid;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\Module\PrinterController;
use App\Http\Controllers\Admin\ApplicationRequest\CreateAR;
use App\Http\Controllers\Admin\ApplicationRequest\DashboardAR;
use App\Http\Controllers\Admin\ApplicationRequest\ListAR;
use App\Http\Controllers\Admin\ApplicationRequest\TaskAR;
use App\Http\Controllers\Admin\ApplicationRequest\ReAssignAR;
use App\Http\Controllers\Admin\ApplicationRequest\AssignAR;
use App\Http\Controllers\Admin\Transaction\Public\Maintenance\AssignmentSchedule;
use App\Models\Admin\ApplicationRequest\ModelTaskAR;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MailController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\Admin\Dashboard\Transport\DashboardTransport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Admin\Report\public\ReportAssetDC;
use App\Http\Controllers\Admin\Master\Public\Maintenance\MasterAssetBattery;
use App\Http\Controllers\Admin\Master\Public\Config\MasterActivityMaintenance;
use App\Http\Controllers\Admin\Dashboard\Public\DashboardPerformanceMHE;

use App\Http\Controllers\Admin\Dashboard\Storing\Monitoring_picklist_balikan;
use App\Http\Controllers\Admin\Transaction\Storing\Create_picklist_Balikan;

use App\Http\Controllers\Admin\Report\Transport\ReportDailyController;

use App\Http\Controllers\Admin\Report\Transport\MonitoringPengirimanController;


use App\Http\Controllers\Admin\Report\Transport\OvertimeDriverController;
use App\Http\Controllers\Admin\Report\Transport\DailyReportTransportController;

use App\Http\Controllers\Admin\Report\Transport\TrendDailyReportController;
use App\Http\Middleware\CustomCors;
//use App\Models\Admin;
use App\Http\Middleware\Admin;
use App\Http\Controllers\Admin\Dashboard\Transport\SCMTransportProfileController;
use App\Http\Controllers\Admin\Dashboard\Transport\ScmCrudController;


Route::get('/', function () {
    return view('admin.login');
    //Awalnya view welcome
});

// Route::get('/page-not-found', function () {
//     abort(404);
// });

Route::get('/page-kosong', function () {
    return view('admin.page_not_found');
});



// Route::get('/send-email', [MailController::class, 'sendEmail']);


Route::middleware('admin')->group(function () {
    Route::get('/module/get-printer', [PrinterController::class, 'getPrinter']);
});



//Ini Untuk Admin Controller //
Route::middleware('admin')->group(function () {
    Route::get('/admin/image/{category}/{filename}', function ($category, $filename) {
        $ftpPath = "ftp://anonymous:your_email@example.com@10.1.100.119:2121/Attachment/Checklist5R/{$category}/{$filename}";

        // Get the image content from the FTP server
        $imageContent = file_get_contents($ftpPath);

        // Check if the image was fetched successfully
        if ($imageContent === false) {
            abort(404); // Return a 404 if the image is not found
        }

        // Return the image content with the appropriate headers
        return Response::make($imageContent, 200, [
            'Content-Type' => 'image/jpeg', // Adjust the content type as necessary
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    });
});

Route::middleware('admin')->group(function () {
    Route::get('/admin/image/1/{category}/{filename}/', function ($category, $filename) {
        $ftpPath = "ftp://anonymous:your_email@example.com@10.1.100.119:2121/Attachment/Maintainance/{$category}/{$filename}/";

        // Get the image content from the FTP server
        $imageContent = file_get_contents($ftpPath);

        // Check if the image was fetched successfully
        if ($imageContent === false) {
            abort(404); // Return a 404 if the image is not found
        }

        // Return the image content with the appropriate headers
        return Response::make($imageContent, 200, [
            'Content-Type' => 'image/jpeg', // Adjust the content type as necessary
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    });
});

Route::middleware('admin')->group(function () {
    Route::get('/admin/image/2/{category}/{filename}/', function ($category, $filename) {
        $ftpPath = "ftp://anonymous:your_email@example.com@10.1.100.119:2121/Attachment/SerahTerima/{$category}/{$filename}/";

        // Get the image content from the FTP server
        $imageContent = file_get_contents($ftpPath);

        // Check if the image was fetched successfully
        if ($imageContent === false) {
            abort(404); // Return a 404 if the image is not found
        }

        // Return the image content with the appropriate headers
        return Response::make($imageContent, 200, [
            'Content-Type' => 'image/jpeg', // Adjust the content type as necessary
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    });
});



// Ngambil data dari FTP File
Route::middleware('admin')->group(function () {
    // 1️⃣ Route untuk mencari file berdasarkan AR Number
    Route::get('/admin/find-file/{arnumber}', function ($arnumber) {
        $ftpHost = "10.1.100.119";
        $ftpUser = "anonymous";
        $ftpPass = "your_email@example.com";
        $ftpPort = 2121;
        $ftpFolder = "/Attachment/ApplicationRequest/";

        $connId = ftp_connect($ftpHost, $ftpPort);
        $login = ftp_login($connId, $ftpUser, $ftpPass);
        ftp_pasv($connId, true); // Tambahkan passive mode

        if (!$connId || !$login) {
            return response()->json(["error" => "Gagal koneksi ke FTP"], 500);
        }

        $fileList = ftp_nlist($connId, $ftpFolder);

        if (!$fileList) {
            ftp_close($connId);
            return response()->json([
                "error" => "Gagal mengambil daftar file",
                "debug" => [
                    "ftpFolder" => $ftpFolder
                ]
            ], 500);
        }

        foreach ($fileList as $filePath) {
            $fileName = basename($filePath);
            if (str_starts_with($fileName, $arnumber)) {
                $size = ftp_size($connId, $ftpFolder . $fileName);

                ftp_close($connId);
                return response()->json([
                    "filename" => $fileName,
                    "size" => $size
                ]);
            }
        }

        ftp_close($connId);
        return response()->json(["error" => "File tidak ditemukan"], 404);
    });



    // 2️⃣ Route untuk mendownload file dari FTP
    // Route untuk download file dari FTP
    Route::get('/admin/download/{filename}', function ($filename) {
        $ftpHost = "10.1.100.119";
        $ftpUser = "anonymous";
        $ftpPass = "your_email@example.com";
        $ftpPort = 2121;
        $ftpFolder = "/Attachment/ApplicationRequest/";

        $decodedFilename = urldecode($filename); // Handle spasi dalam nama file
        $ftpPath = "ftp://{$ftpUser}:{$ftpPass}@{$ftpHost}:{$ftpPort}{$ftpFolder}{$decodedFilename}";

        // Ambil isi file dari FTP
        $fileContent = @file_get_contents($ftpPath);

        if ($fileContent === false) {
            return response()->json(["error" => "File tidak ditemukan di FTP"], 404);
        }

        // Tentukan MIME Type berdasarkan ekstensi file
        $mimeTypes = [
            'pdf'  => 'application/pdf',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls'  => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt'  => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'txt'  => 'text/plain',
            'zip'  => 'application/zip',
        ];

        // Ambil ekstensi file
        $extension = pathinfo($decodedFilename, PATHINFO_EXTENSION);
        $mimeType = $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';

        return Response::make($fileContent, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'attachment; filename="' . $decodedFilename . '"',
        ]);
    });
});


//Ini Untuk Dede Download Android
// Route untuk menampilkan halaman download
Route::get('/admin/download-apk', [DownloadApkController::class, 'showDownloadPage'])->name('show.download.page');
// Route khusus untuk menangani proses download file
Route::get('/admin/download-apk/file', [DownloadApkController::class, 'downloadApk'])->name('download.apk');

// Route untuk menampilkan halaman download
Route::get('/admin/download-osodahi', [DownloadApkController::class, 'showDownloadPageOSAHI'])->name('show.download.page.osod.ahi');
// Route khusus untuk menangani proses download file
Route::get('/admin/download-os-od-ahi/file', [DownloadApkController::class, 'downloadOSAHI'])->name('download.OSAHI');

///Ini Untuk Dashboard Profile Dll
Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    Route::post('/admin/profile/image', [AdminController::class, 'AdminProfileImage'])->name('admin.profile.image');
    Route::get('/admin/change/password', [AdminController::class, 'AdminChangePassword'])->name('admin.change.password');
    Route::post('/admin/update/password', [AdminController::class, 'AdminUpdatePassword'])->name('admin.update.password');
    Route::get('/admin/register', [AdminController::class, 'AdminRegister'])->name('admin.register');
    Route::post('/admin/register_submit', [AdminController::class, 'AdminRegisterSubmit'])->name('admin.register_submit');
});

//Ini Untuk Login , Inget ya Route urutannya alamat route, class itu controller nya, setelah class itu nama fungsi yang di class, name itu nama route nya
Route::prefix('admin')->group(function () {
    Route::post('/get_facility', [AdminController::class, 'getFacilityByUserId'])->name('admin.get_facility');
    Route::get('/login', [AdminController::class, 'AdminLogin'])->name('admin.login');
    Route::post('/login_submit', [AdminController::class, 'AdminLoginSubmit'])->name('admin.login_submit');
    Route::get('/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');
    Route::get('/logoutvendor', [VendorController::class, 'VendorLogout'])->name('vendor.logout');
});

//Untuk Checklist5R
Route::middleware('admin')->group(function () {
    Route::controller(MasterChecklist5R::class)->group(function () {
        //Master Jam
        Route::get('/master/public/checklist5r/masterjam5r', 'ViewPageMasterJam5R')->name('admin.master.public.checklist5r.masterjam5r');
        Route::post('/master/public/checklist5r/masterjam5r_submit', 'ViewPageSaveJam5R')->name('admin.master.public.checklist5r.masterjam5r_submit');
        Route::post('/master/public/checklist5r/masterjam5r_update', 'updateShiftPoint')->name('admin.master.public.checklist5r.masterjam5r_update');
        Route::post('/master/public/checklist5r/masterjam5r_delete', 'deleteShiftPoint')->name('admin.master.public.checklist5r.masterjam5r_delete');

        //Master Point
        Route::get('/master/public/checklist5r/masterpoint5r', 'ViewPagePoint5R')->name('admin.master.public.checklist5r.masterpoint5r');
        Route::post('/master/public/checklist5r/masterpoint5r_submit', 'ViewPageSaveJamPoint5R')->name('admin.master.public.checklist5r.masterpoint5r_submit');
        Route::post('/master/public/checklist5r/masterpoint5r_update', 'updateShiftPoint5R')->name('admin.master.public.checklist5r.masterpoint5r_update');
        Route::post('/master/public/checklist5r/masterpoint5r_delete', 'deleteShiftPoint5R')->name('admin.master.public.checklist5r.masterpoint5r_delete');
    });

    Route::controller(DashboardChecklist5R::class)->group(function () {
        //Dashboard
        Route::get('/dashboard/public/checklist5r', 'ViewPageDashboardChecklist5R')->name('admin.dashboard.public.dashboardchecklist5r');
        Route::get('/dashboard/public/checklist5r_area', 'ViewPageArea5R')->name('admin.dashboard.public.dashboardchecklist5r_area');
        Route::post('/dashboard/public/checklist5r_submit', 'ViewPageSummaryDashboardChecklist5R')->name('admin.dashboard.public.dashboardchecklist5r_submit');
        Route::get('/dashboard/public/checklist5r_submit_detail', 'ViewPageDetailDashboardChecklist5R')->name('admin.dashboard.public.dashboardchecklist5r_submit_detail');
    });

    Route::controller(ReportChecklist5R::class)->group(function () {
        //Dashboard
        Route::get('/report/public/checklist5r', 'ViewPageReportChecklist5R')->name('admin.report.public.reportchecklist5r');
        Route::post('/report/public/checklist5r_submit', 'ViewPageSummaryReportChecklist5R')->name('admin.report.public.reportchecklist5r_submit');
        Route::get('/export/checklist5rpdf', 'ViewPageExport5RPDF')->name('admin.export.checklist5rpdf');
        Route::post('/export/checklist5rpdf_submit', 'PrintPageExport5RPDF')->name('admin.export.checklist5rpdf.submit');
    });
});

//Untuk Report Throughput
Route::middleware('admin')->group(function () {
    Route::controller(ReportThroughput::class)->group(function () {
        Route::get('/report/inventory/throughput', 'ViewPageThroughput')->name('admin.report.inventory.throughput');
        Route::post('/report/inventory/throughput', 'ViewSummaryThroughput')->name('admin.report.inventory.summary_throughput');
    });
});


//Untuk Report Demand In Out
Route::middleware('admin')->group(function () {
    Route::controller(ReportDemandInOutBacklog::class)->group(function () {
        Route::get('/report/outbound/demandinoutbacklog', 'ViewPageDemandInOutBacklog')->name('admin.report.outbound.demandinoutbacklog');
        Route::post('/report/outbound/demandinoutbacklog', 'ViewSummaryDemandInOutBacklog')->name('admin.report.outbound.summary_demandinoutbacklog');
    });
});


//Untuk Dashboard Monitoring Barang Rusak
Route::middleware('admin')->group(function () {
    Route::controller(MonitoringBarangRusak::class)->group(function () {
        Route::get('/dashboard/inventory/monitoring_barang_rusak', 'ViewPageMonitoringBarangRusak')->name('admin.dashboard.inventory.monitoringbarangrusak');
        Route::post('/dashboard/inventory/monitoring_barang_rusak', 'ViewPageSummaryBarangRusak')->name('admin.dashboard.inventory.summary_monitoringbarangrusak');
    });
});

Route::middleware('admin')->group(function () {
    Route::controller(Master_damage_from_controller::class)->group(function () {
        Route::get('/master/public/inventory/master_damage_from_lpn', 'ViewPageLPNBarangRusak')->name('admin.master.inventory.master_damage_from_lpn');
        Route::post('/master/public/inventory/master_damage_from_lpn_submit', 'ViewPageSaveLPNBarangRusak')->name('admin.master.inventory.master_damage_from_lpn_submit');
        Route::post('/master/public/inventory/master_damage_from_lpn_update', 'updateLPNBarangRusak')->name('admin.master.inventory.master_damage_from_lpn_update');
        Route::post('/master/public/inventory/master_damage_from_lpn_delete', 'deleteLPNBarangRusak')->name('admin.master.inventory.master_damage_from_lpn_delete');
    });
});

//Route Inline Planning
Route::middleware('admin')->group(function () {
    Route::controller(DashboardInLinePlan::class)->group(function () {
        Route::get('/transport/in-line-plan', 'inlinePlan')->name('transport.inline-plan');
        Route::get('/transport/lc-detail', 'getDetail')->name('lc.get-detail');
    });
});
//Route InLinePlanning

//Untuk Dashboard Auto Cancel ODI
Route::middleware('admin')->group(function () {
    Route::controller(AutoCancelODI::class)->group(function () {
        Route::get('/dashboard/outbound/auto_cancel_odi', 'ViewPageDashboardAutoCancelODI')->name('admin.dashboard.outbound.autocancelodi');
        Route::post('/dashboard/outbound/auto_cancel_odi', 'ViewPageSummaryDashboardAutoCancelODI')->name('admin.dashboard.outbound.summary_autocancelodi');
        Route::post('/dashboard/outbound/auto_cancel_odi_detail', 'ViewPageDetailDashboardAutoCancelODI')->name('admin.dashboard.outbound.detail_autocancelodi');
    });
});
//Untuk Dashboard Auto Cancel ODI

//Untuk Report Intercompany
Route::middleware('admin')->group(function () {
    Route::controller(ReportIntercompany::class)->group(function () {
        Route::get('/report/inventory/reportintercompany', 'ViewPageReportIntercompany')->name('admin.report.inventory.reportintercompany');
        Route::post('/report/inventory/reportintercompany', 'ViewPageSummaryReportIntercompany')->name('admin.report.inventory.summary_reportintercompany');
        Route::post('/report/inventory/reportintercompany_detail', 'ViewPageDetailReportIntercompany')->name('admin.report.inventory.detail_reportintercompany');
        Route::post('/report/inventory/insert_comment', 'InsertCommentReportIntercompany')->name('admin.report.inventory.insert_comment_intercompany');
    });
});
//Untuk Report Intercompany

//Untuk Dashboard Monitoring Case ID
Route::middleware('admin')->group(function () {
    Route::controller(DashboardCaseIDOpenNonLC::class)->group(function () {
        Route::get('/dashboard/storing/dashboardcaseidopennonlc', 'ViewPageDashboardCaseIDNonLC')->name('admin.dashboard.storing.dashboardcaseidopennonlc');
        Route::post('/dashboard/storing/dashboardcaseidopennonlc', 'ViewPageSummaryDashboardCaseIDNonLC')->name('admin.dashboard.storing.summary_dashboardcaseidopennonlc');
        Route::post('/dashboard/storing/dashboardcaseidopennonlc_detail', 'ViewPageDetailDashboardCaseIDNonLC')->name('admin.dashboard.storing.detail_dashboardcaseidopennonlc');
    });
});
//Untuk Dashboard Monitoring Case ID


///Ini Untuk Report Summary LC
Route::middleware('admin')->group(function () {
    Route::controller(SummaryProgressLC::class)->group(function () {
        Route::get('/transport/summary-progress-lc', 'loadPage')->name('transport.summary-progress-lc');
        Route::post('/transport/summary-progress-lc/show', 'showData')->name('transport.summary-progress-lc.show');
    });
});

///Ini Untuk Report Summary LC


///Ini Web Barang Balikan 

//Untuk Dashboard Receive Balikan
Route::middleware('admin')->group(function () {
    Route::controller(Monitoring_receieve_balikan::class)->group(function () {
        Route::get('/Report/Inbound/Monitoring_receieve_balikan', 'ViewPagemonitoringreceievebalikan')->name('admin.report.inbound.monitoringreceievebalikan');
        Route::post('/Report/Inbound/Monitoring_receieve_balikan', 'ViewPageSummaryreceivebalikan')->name('admin.report.inbound.summary_receivebalikan');
    });
});
//Untuk Monitoring stock Balikan
Route::middleware('admin')->group(function () {
    Route::controller(Monitoring_stock_balikan::class)->group(function () {
        Route::get('/dashboard/Storing/Monitoring_stock_balikan', 'ViewPagemonitoringrstockbalikan')->name('admin.dashboard.storing.monitoringstockbalikan');
        Route::post('/dashboard/Storing/Monitoring_stock_balikan', 'ViewPageSummarystockbalikan')->name('admin.dashboard.storing.summary_stockbalikan');
    });
});
//Untuk create picklist Balikan
Route::middleware('admin')->group(function () {
    Route::controller(Create_picklist_Balikan::class)->group(function () {
        Route::get('/transction/Storing/Create_picklist_balikan', 'ViewPagePicklistkbalikan')->name('admin.transaction.storing.picklistbalikan');
        Route::get('/transaction/storing/find_picklist_Balikan', 'ViewPageEditPicklistkbalikan')->name('admin.transaction.storing.findpicklist');
        Route::post('/transction/Storing/Create_picklist_balikan', 'ViewPageSummaryPicklistbalikan')->name('admin.transaction.storing.summary_picklistbalikan');
        Route::post('/transction/Storing/find_picklist_Balikan', 'ViewPageSaveEditPicklistkbalikan')->name('admin.transaction.storing.saveeditpicklist');
        Route::post('/transaction/storing/deletepicklistitem', 'deletePicklistItem')->name('admin.transaction.storing.deletepicklistitem');
    });
});


//Untuk create picklist Balikan
Route::middleware('admin')->group(function () {
    Route::controller(Monitoring_picklist_balikan::class)->group(function () {
        Route::get('/dashboard/Storing/Monitoring_picklist_balikan', 'ViewDataPicklist')->name('admin.dashboard.storing.picklist');
        Route::post('/dashboard/Storing/Monitoring_picklist_balikan', 'ViewSummary')->name('admin.dashboard.storing.picklistsummary');
    });
});

///Ini Web Barang Balikan



//Untuk Report Kontributor LPPB
Route::middleware('admin')->group(function () {
    Route::controller(ReportKontributorLPPB::class)->group(function () {
        Route::get('/report/outbound/reportkontributorlppb', 'ViewPageReportkontributorlppb')->name('admin.report.outbound.reportkontributorlppb');
        Route::post('/report/outbound/reportkontributorlppb', 'ViewPageSummaryReportkontributorlppb')->name('admin.report.outbound.summary_reportkontributorlppb');
        Route::post('/report/outbound/reportkontributorlppb_detail', 'ViewPageDetailReportkontributorlppb')->name('admin.report.outbound.detail_reportkontributorlppb');
    });
});
//Untuk Report Kontributor LPPB


//Ini Untuk Report Task MHE
Route::middleware('admin')->group(function () {
    Route::controller(ReportTaskMHE::class)->group(function () {
        Route::get('/report/public/reporttaskmhe', 'ViewPageReportTaskMHE')->name('admin.report.public.reporttaskmhe');
        Route::post('/report/public/reporttaskmhe', 'ViewPageSummaryReportTaskMHE')->name('admin.report.public.summary_reporttaskmhe');
    });
});
//Ini Untuk Report Task MHE


///Ini Untuk Master Aset
Route::middleware('admin')->group(function () {
    Route::controller(MasterAsset::class)->group(function () {
        //Master Jam
        Route::get('/master/public/maintenance/masteraset', 'ViewPageMasterAsset')->name('admin.master.public.maintenance.masteraset');
        Route::get('/master/public/maintenance/masteraset_data', 'GetData')->name('admin.master.public.maintenance.masteraset_data');
        Route::post('/master/public/maintenance/masteraset_save', 'SaveData')->name('admin.master.public.maintenance.masteraset_save');
        Route::post('/master/public/maintenance/masteraset_delete', 'deleteData')->name('admin.master.public.maintenance.masteraset_delete');
    });
});

///Ini Untuk Master Aset


//Ini Untuk Dashboard Performance MHE
Route::middleware('admin')
    ->prefix('dashboard/public')
    ->name('admin.dashboard.public.')
    ->group(function () {
        Route::controller(DashboardPerformanceMHE::class)->group(function () {
            Route::get('/dashboardperformancemhe', 'ViewPageDashboardPerformanceMHE')->name('dashboardperformancemhe');
            Route::post('/dashboardperformancemhe', 'ViewPageSummaryDashboardPerformanceMHE')->name('summary_dashboardperformancemhe');
            Route::post('/getunittype', 'ViewGetUnitType')->name('get_unit_type_asset');
        });
    });

//Ini Untuk Dashboard Performance MHE


//Untuk Report MonitoringTimeStampProsesDC
Route::middleware('admin')->group(function () {
    Route::controller(MonitoringTimeStampProsesDC::class)->group(function () {
        Route::get('/report/transport/reporttimestampprosesdclc', 'ViewPageReportTimeStampLC')->name('admin.report.transport.reportimestampprosesdclc');
        Route::post('/report/transport/reporttimestampprosesdclc', 'ViewPageSummaryReportTimeStampLC')->name('admin.report.transport.summary_reportimestampprosesdclc');
        Route::post('/report/transport/reporttimestampprosesdclc_detail', 'ViewPageDetailReportTimeStampLC')->name('admin.report.transport.detail_reportimestampprosesdclc');
    });
});
//Untuk Report MonitoringTimeStampProsesDC


//Untuk Report Application Request
Route::middleware('admin')->group(function () {
    Route::controller(CreateAR::class)->group(function () {
        Route::get('/ar/create_ar/view', 'ViewPageCreateAR')->name('admin.ar.create_ar.view');
        Route::get('/ar/create_ar/view2', 'ViewPageCreateARDraft')->name('admin.ar.create_ar.view2');

        Route::post('/ar/upload-attachment', 'uploadAttachment')->name('admin.ar.create_ar.upload.attachment');
        Route::post('/ar/create_ar/submitview', 'ViewPageSubmitCreateAR')->name('admin.ar.create_ar.submitview');
        Route::post('/ar/create_ar/saveview', 'ViewPageSaveCreateAR')->name('admin.ar.create_ar.saveview');
        Route::post('/ar/get-superior', 'getSuperior')->name('admin.ar.getSuperiorAR');
        Route::get('/ar/get-validasi-user', 'getValidasiUser')->name('admin.ar.getValidasiUser');
    });

    Route::controller(DashboardAR::class)->group(function () {
        Route::get('/ar/dashboard/view', 'ViewPageDashboardAR')->name('admin.ar.dashboard.view');
        Route::get('/ar/dashboard/detail1view', 'ViewPageDetail1AR')->name('admin.ar.detail1.view');
    });

    Route::controller(ListAR::class)->group(function () {
        Route::get('/ar/list_ar/view', 'ViewPageListAR')->name('admin.ar.list_ar.view');
        Route::post('/ar/list_ar/submitview', 'ViewPageSubmitListAR')->name('admin.ar.list_ar.submitview');
        Route::post('/ar/list_ar/submitviewdetail', 'ViewPageSubmitListARDetail')->name('admin.ar.list_ar.submitviewdetail');
    });

    Route::controller(TaskAR::class)->group(function () {
        Route::get('/ar/task_ar/view', 'ViewPageTaskAR')->name('admin.ar.task_ar.view');
        Route::post('/ar/task_ar/submitview', 'ViewPageSubmitTaskAR')->name('admin.ar.task_ar.submitview');
        Route::post('/ar/task_ar/submitviewdetail', 'ViewPageSubmitTaskARDetail')->name('admin.ar.task_ar.submitviewdetail');
        Route::post('/ar/task_ar/submitsuperiordansupersuperior', 'ViewPageSubmitSuperiordanSupernya')->name('admin.ar.task_ar.submitsuperiordansupersuperior');
        Route::post('/ar/task_ar/getdeveloper', 'ViewPageSubmitDeveloper')->name('admin.ar.task_ar.developer');
        Route::post('/ar/task_ar/getdeveloper_detail', 'ViewPageSubmitDetailDeveloper')->name('admin.ar.task_ar.developer_detail');
        Route::post('/ar/task_ar/assign_ar', 'ViewPageSubmitAssignAR')->name('admin.ar.task_ar.assign_ar');
    });

    Route::controller(ReAssignAR::class)->group(function () {
        Route::get('/ar/reassign_ar/view', 'ViewPageReAssignAR')->name('admin.ar.reassign_ar.view');
        Route::post('/ar/reassign_ar/submitview', 'ViewPageSubmitReAssignAR')->name('admin.ar.reassign_ar.submitview');
    });

    Route::controller(AssignAR::class)->group(function () {
        Route::get('/ar/assign_ar/view', 'ViewPageAssignAR')->name('admin.ar.assign_ar.view');
        Route::post('/ar/assign_ar/submitview', 'ViewPageSubmitAssignAR')->name('admin.ar.assign_ar.submitview');
    });


    Route::get('/admin/ar/total-ar', function () {
        // Ambil ID user yang sedang login
        $id = Auth::guard('admin')->id();

        if (!$id) {
            return response()->json(['total' => 0]); // Kalau belum login, return 0
        }
        $data = Admin::find($id);
        $UserID = $data->userid;
        $Key1 = '';
        $Key2 = '';
        $Key3 = '';

        // Panggil stored procedure atau query dari model
        $totalAR = ModelTaskAR::getTotalTask($UserID, $Key1, $Key2, $Key3);

        return response()->json(['total' => $totalAR]);
    })->name('admin.ar.total_ar');
});
//Untuk Report Application Request

//Untuk Assignment Schedule
Route::middleware('admin')->group(function () {
    Route::controller(AssignmentSchedule::class)->group(function () {
        Route::get('/maintenance/public/assignmentschedule', 'ViewPageAssignmentSchedule')->name('admin.maintenance.public.assignmentschedule');
        Route::post('/maintenance/public/assignmentschedule', 'ViewPageSubmitAssignmentSchedule')->name('admin.maintenance.public.assignmentschedule_submit');
        Route::post('/maintenance/public/assignmentschedule_insert', 'ViewPageInsertAssignmentSchedule')->name('admin.maintenance.public.assignmentschedule_insert');
    });
});
//Untuk Assignment Schedule

///Ini Untuk Master Aset Battery
Route::middleware('admin')->group(function () {
    Route::controller(MasterAssetBattery::class)->group(function () {
        //Master Jam
        Route::get('/master/public/maintenance/masterasetbattery', 'ViewPageMasterAsset')->name('admin.master.public.maintenance.masterasetbattery');
        Route::get('/master/public/maintenance/masterasetbattery_data', 'GetData')->name('admin.master.public.maintenance.masterasetbattery_data');
        Route::post('/master/public/maintenance/masterasetbattery_save', 'SaveData')->name('admin.master.public.maintenance.masterasetbattery_save');
        Route::post('/master/public/maintenance/masterasetbattery_delete', 'deleteData')->name('admin.master.public.maintenance.masterasetbattery_delete');
    });
});
///Ini Untuk Master Aset Battery

// Route untuk menampilkan halaman Master Activity Maintenance
Route::middleware('admin')->group(function () {
    Route::controller(MasterActivityMaintenance::class)->group(function () {
        Route::get('/master/public/config/masteractivitymaintenance', 'ViewPageMasterActivityMaintenance')->name('admin.master.public.config.masteractivitymaintenance');
        Route::post('/master/public/config/masteractivitymaintenance_save', 'SaveData')->name('admin.master.public.config.masteractivitymaintenance_save');
        Route::post('/master/public/config/masteractivitymaintenance_delete', 'DeleteData')->name('admin.master.public.config.masteractivitymaintenance_delete');
    });
});
// Route untuk menampilkan halaman Master Activity Maintenance

//Ini Untuk Report Asset DC
Route::middleware('admin')
    ->prefix('report/public')
    ->name('admin.report.public.')
    ->group(function () {
        Route::controller(ReportAssetDC::class)->group(function () {
            Route::get('/reportassetdc', 'ViewPageReportAssetDC')->name('reportassetdc');
            Route::post('/reportassetdc', 'ViewPageSummaryReportAssetDC')->name('summary_reportassetdc');
            Route::post('/getunittype', 'ViewGetUnitType')->name('get_unit_type_asset');
        });
    });
//Ini Untuk Report Asset DC

//  Ini untuk Page Vendor ya semuanya disini //
///Ini Untuk Dashboard Profile Dll
Route::middleware('admin')->group(function () {
    Route::get('/vendor/dashboard', [VendorController::class, 'VendorDashboard'])->name('vendor.dashboard');
    Route::get('/vendor/profile', [VendorController::class, 'VendorProfile'])->name('vendor.profile');
    Route::post('/vendor/profile/image', [VendorController::class, 'VendorProfileImage'])->name('vendor.profile.image');
    Route::get('/vendor/change/password', [VendorController::class, 'VendorChangePassword'])->name('vendor.change.password');
    Route::post('/vendor/update/password', [VendorController::class, 'VendorUpdatePassword'])->name('vendor.update.password');
    // Route::get('/vendor/register', [VendorController::class, 'VendorRegister'])->name('vendor.register');
    // Route::post('/vendor/register_submit', [VendorController::class, 'VendorRegisterSubmit'])->name('vendor.register_submit');
});
//  Ini untuk Page Vendor ya semuanya disini //

// Dashboard Transport
Route::middleware('admin')->group(function () {
    Route::controller(DashboardTransport::class)->group(function () {
        Route::get('/transport/DashboardShow',  'dashboardtransport')->name('transport.dashboard');
        Route::post('/transport/DashboardData', 'getDashboardData')->name('transport.dashboard.data');
    });
});
// Dashboard Transport

// Untuk Report Daily Transport & Monitoring Driver
Route::middleware('admin')->group(function () {
    Route::controller(ReportDailyController::class)->group(function () {
        Route::get('/admin/report/transport/daily', 'transport')
            ->name('admin.report.transport.daily');

        Route::get('/admin/report/transport/monitoring-driver', 'monitoring')
            ->name('admin.report.transport.monitoring-driver');
    });
});


Route::middleware(['auth:admin'])->group(function () {
    // Halaman utama lembur driver
    Route::get('/driver/overtime', [AdminController::class, 'DriverOvertime'])
        ->name('driver.overtime');

    // Endpoint AJAX untuk data lembur
    Route::post('/driver/overtime/data', [OvertimeDriverController::class, 'getOvertimeData'])
        ->name('driver.overtime.data');

});


Route::middleware('admin')->group(function () {
    Route::controller(DailyReportTransportController::class)->group(function () {
        Route::get('/admin/transport/DailyReportShow',  'index')->name('transport.dailyreport.show');
        Route::post('/admin/transport/DailyReportData', 'getData')->name('transport.dailyreport.data');
        Route::get('/admin/transport/GetSiteListDaily', 'getSiteListDaily')->name('transport.dailyreport.sitelist');
    });
});


// Route::middleware('admin')->group(function () {

//     // 🔹 1. Halaman utama React Report
//     // Menampilkan view Blade yang berisi React (resources/views/admin/react_page.blade.php)
//     Route::get('/admin/transport/report', function () {
//         return view('admin.react_page');
//     })->name('admin.transport.report');

//     // 🔹 2. API endpoint (dipanggil dari React via axios)
//     Route::controller(TrendDailyReportController::class)->group(function () {

//         // ✅ Ambil daftar site (dropdown)
//         Route::get('/admin/transport/get-site-list', 'getSites')
//             ->name('admin.transport.getSiteList');

//         // ✅ Ambil data laporan (POST karena pakai filter tanggal & site)
//         Route::post('/admin/transport/trend-data', 'getReportData')
//             ->name('admin.transport.getReportData');
//     });

//     // 🔹 (Opsional) Jika kamu pakai React Router di frontend
//     // agar semua sub-path tetap masuk ke Blade react_page.blade.php
//     Route::get('/admin/transport/report/{any?}', function () {
//         return view('admin.react_page');
//     })->where('any', '.*'); // <-- biar React Router handle route-nya
// });
// =======================================================
// 🔥 1. Halaman React (harus login admin dulu)
// =======================================================
Route::middleware('admin')->group(function () {
    Route::get('/admin/transport/report', function () {
        return view('admin.react_page');
    })->name('admin.transport.report');

    // Jika React pakai React Router
    Route::get('/admin/transport/report/{any}', function () {
        return view('admin.react_page');
    })->where('any', '.*');
});

// =======================================================
// 🔥 2. API untuk React (TIDAK boleh memakai middleware admin)
// =======================================================
Route::controller(TrendDailyReportController::class)->group(function () {

    // GET dropdown site list
    Route::get('/admin/transport/get-site-list', 'getSites')
        ->name('admin.transport.getSiteList');

    // POST ambil data report
    Route::post('/admin/transport/trend-data', 'getReportData')
        ->name('admin.transport.getReportData');
});



Route::middleware('admin')->group(function () {

    // 1. React Page (halaman utama SCM Profile)
    Route::get('/admin/transport/scm-profile', function () {
        return view('admin.react_page');
    })->name('transport.scm.profile');

    // 2. API List Facility (JSON)
    Route::get('/transport/scm-profile', 
        [SCMTransportProfileController::class, 'getFacilityList']
    )->name('transport.scm.profile.list');

    // 3. API Pivot Armada (JSON)
    Route::get('/transport/scm-profile/armada',
        [SCMTransportProfileController::class, 'getFacilityArmadaPivot']
    )->name('transport.scm.profile.armada');

     // Halaman React untuk SCM Upload
    Route::get('/admin/transport/scm-uploader', function () {
        return view('admin.react_page');
    })->name('transport.scm.uploader');
});

Route::middleware('admin')->prefix('admin/dashboard/transport')->group(function () {

    Route::get('/scmcrud', function () {
        return view('admin.dashboard.transport.scmcrud');
    })->name('scmcrud.view');

    Route::get('/facility', [ScmCrudController::class, 'index']);
    Route::post('/facility', [ScmCrudController::class, 'store']);
    Route::put('/facility/{facility_ID}', [ScmCrudController::class, 'update']);
});

//PENTING JANGAN DIHAPUS YA ROUTE INI BUAT GET USER INFO
// Add this route for getting user info
Route::post('/get-user-info', [AdminController::class, 'getUserInfo'])->name('admin.get_user_info');



// Route::prefix('ticket')->group(function () {
//     Route::get('/', [TicketController::class, 'TicketLogin'])->name('ticket.login');
//     Route::post('/ticketlogin_submit', [TicketController::class, 'TicketLoginSubmit'])->name('ticket.login_submit');
//     Route::get('/ticket_logout', [TicketController::class, 'TicketLogout'])->name('ticket.logout');
// });

 
///Ini Untuk Dashboard Profile Dll
// Route::middleware('ticket')->group(function () {
//     Route::get('/ticket/dashboard', [TicketController::class, 'TicketDashboard'])->name('ticket.dashboard');
//     Route::get('/ticket/getticket', [Ticketing::class, 'getTicketData'])->name('ticket.ticketdata');
//     Route::get('/ticket/dashboardticket', [Ticketing::class, 'dashboardTicketData'])->name('ticket.ticketdata_dashboard');
//     Route::post('/ticket/dashboardticket', [Ticketing::class, 'dashboardTicketData1'])->name('ticket.ticketdata_dashboard1');
//     Route::post('/ticket/insert', [Ticketing::class, 'insertTicket'])->name('ticket.insert');
//     Route::get('/ticket/get-bus', [Ticketing::class, 'getBusByKeberangkatan'])->name('ticket.getBusByKeberangkatan');
// });




// // Route untuk menampilkan halaman download APK
// Route::get('/admin/downloadapk', [DownloadAPKAndroid::class, 'showDownloadPage'])->name('downloadapk.page');
// // Route untuk mengunduh file APK
// Route::get('/admin/downloadfile', [DownloadAPKAndroid::class, 'downloadFile'])->name('downloadapk.file');

// Route::get('/test-site', function () {
//     try {
//         // Query untuk mengambil data dari tabel m_category menggunakan koneksi sqlsrv51
//         $result = DB::connection('sqlsrv51')->select('SELECT * FROM m_category');

//         // Mengembalikan hasil query dalam format JSON
//         return response()->json($result);
//     } catch (\Exception $e) {
//         // Menangani error dan menampilkan pesan error
//         return response()->json(['error' => $e->getMessage()], 500);
//     }
// });

// Route::get('/test-ftp', function () {
//     // Mendapatkan daftar direktori di root FTP
//     $directories = Storage::disk('ftp')->directories(); // Tidak perlu parameter jika ingin dari root

//     // Menampilkan daftar direktori
//     return response()->json([
//         'success' => true,
//         'directories' => $directories,
//     ]);
// });


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php';

// Manggil MiddleWare
// Ini Pengaturan Untuk Dashboard Adminnya ya, inget route > method > view

//Ini Untuk Admin Controller //

//  Ini untuk Page Vendor ya semuanya disini //
//Ini Untuk Login , Inget ya Route urutannya alamat route, class itu controller nya, setelah class itu nama fungsi yang di class, name itu nama route nya
// Route::prefix('vendor')->group(function () {
//     Route::get('/login', [VendorController::class, 'VendorLogin'])->name('vendor.login');
//     Route::post('/get_facility', [VendorController::class, 'getFacilityByUserId'])->name('vendor.get_facility'); 
//     Route::post('/login_submit', [VendorController::class, 'VendorLoginSubmit'])->name('vendor.login_submit');
//     Route::get('/logout', [VendorController::class, 'VendorLogout'])->name('vendor.logout');
// });