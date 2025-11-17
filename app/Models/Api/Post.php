<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'image',
        'title',
        'content',
    ];

    /**
     * image
     *
     * @return Attribute
     */
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($image) => url('/storage/posts/' . $image),
        );
    }

    //Public
    public static function getOwner($param1, $param2, $param3)
    {
        try {
            // Gunakan connection dari properti model
            $query = "SET NOCOUNT ON; EXEC [SPGetOwner] ?, ?, ?";
            // Gunakan DB::connection untuk menggunakan koneksi yang ditentukan di model
            return DB::connection((new static)->connection)->select($query, [
                $param1,
                $param2,
                $param3
            ]);
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
    //Public

    ///Ini Checklist5R
    ///Semua tarikan data itu disini di model ya, untuk exec query nya
    public static function getFacility($param1, $param2, $param3)
    {

        try {
            // Gunakan connection dari properti model
            $query = "SET NOCOUNT ON; EXEC [udsp_Get_Data] ?, ?, ?, ?";
            // Gunakan DB::connection untuk menggunakan koneksi yang ditentukan di model
            return DB::connection((new static)->connection)->select($query, [
                'Get Data Relasi',
                $param1,
                $param2,
                $param3
            ]);
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getValidasiUser($param1, $param2, $param3)
    {

        try {
            // Gunakan connection dari properti model
            $query = "SET NOCOUNT ON; EXEC [Mobile_Get_Login] ?, ?, ?, ?";
            // Gunakan DB::connection untuk menggunakan koneksi yang ditentukan di model
            return DB::connection((new static)->connection)->select($query, [
                'Get Validasi User',
                $param1,
                $param2,
                $param3
            ]);
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }


    public static function getVersiAndroid($param1, $param2, $param3)
    {

        try {
            // Gunakan connection dari properti model
            $query = "SET NOCOUNT ON; EXEC [Mobile_Get_Data] ?, ?, ?, ?";
            // Gunakan DB::connection untuk menggunakan koneksi yang ditentukan di model
            return DB::connection((new static)->connection)->select($query, [
                'Get Validasi Versi',
                $param1,
                $param2,
                $param3
            ]);
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }


    public static function getOwnerAndroid($param1, $param2, $param3, $param4, $param5)
    {

        try {
            // Gunakan connection dari properti model
            $query = "SET NOCOUNT ON; EXEC [udsp_Checklist5R] ?, ?, ?, ?, ? , ?";
            // Gunakan DB::connection untuk menggunakan koneksi yang ditentukan di model
            return DB::connection((new static)->connection)->select($query, [
                'Get Owner',
                $param1,
                $param2,
                $param3,
                $param4,
                $param5
            ]);
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getDeptAndroid($param1, $param2, $param3, $param4, $param5)
    {

        try {
            // Gunakan connection dari properti model
            $query = "SET NOCOUNT ON; EXEC [udsp_Checklist5R] ?, ?, ?, ?, ? , ?";
            // Gunakan DB::connection untuk menggunakan koneksi yang ditentukan di model
            return DB::connection((new static)->connection)->select($query, [
                'Get Dept',
                $param1,
                $param2,
                $param3,
                $param4,
                $param5
            ]);
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getMasterShiftAndroid($param1, $param2, $param3)
    {

        try {
            // Gunakan connection dari properti model
            $query = "SET NOCOUNT ON; EXEC [Mobile_Get_Data] ?, ?, ?, ?";
            // Gunakan DB::connection untuk menggunakan koneksi yang ditentukan di model
            return DB::connection((new static)->connection)->select($query, [
                'Cek Master Shift',
                $param1,
                $param2,
                $param3
            ]);
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getAreaAndroid($param1, $param2, $param3, $param4, $param5)
    {
        try {
            $connection = DB::connection('sqlsrv')->getPdo();

            $query = "SET NOCOUNT ON; EXEC [udsp_Checklist5R] ?, ?, ?, ?, ? , ?";
            $stmt = $connection->prepare($query);
            $stmt->execute(['Get Area', $param1, $param2, $param3, $param4, $param5]);

            $results = [];
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel pertama
            $stmt->nextRowset();
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel kedua

            // Konversi data Pict menjadi base64
            foreach ($results as &$table) {
                foreach ($table as &$row) {
                    if (isset($row['Pict'])) {
                        $row['Pict'] = base64_encode($row['Pict']);
                    }
                }
            }

            // Mengembalikan data dalam format JSON
            return response()->json([
                'success' => true,
                'message' => 'List Data Retrieved from SP',
                'data' => [
                    'table1' => $results[0] ?? [],
                    'table2' => $results[1] ?? [],
                ]
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getTransaksiAndroid($param1, $param2, $param3, $param4, $param5)
    {
        try {
            $connection = DB::connection('sqlsrv')->getPdo();

            $query = "SET NOCOUNT ON; EXEC [udsp_Checklist5R] ?, ?, ?, ?, ? , ?";
            $stmt = $connection->prepare($query);
            $stmt->execute(['Get Transaksi', $param1, $param2, $param3, $param4, $param5]);

            $results = [];
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel pertama
            $stmt->nextRowset();
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel kedua 

            // Mengembalikan data dalam format JSON
            return response()->json([
                'success' => true,
                'message' => 'List Data Retrieved from SP',
                'data' => [
                    'table1' => $results[0] ?? [],
                    'table2' => $results[1] ?? [],
                ]
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getFTPAndroid($param1, $param2, $param3)
    {

        try {
            // Gunakan connection dari properti model
            $query = "SET NOCOUNT ON; EXEC [Mobile_Get_Data] ?, ?, ?, ?";
            // Gunakan DB::connection untuk menggunakan koneksi yang ditentukan di model
            return DB::connection((new static)->connection)->select($query, [
                'Get FTP',
                $param1,
                $param2,
                $param3
            ]);
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function insertChecklist($params)
    {
        try {
            $query = "
                        EXEC [Insert_Checklist5R_V2] 
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, 
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, 
                        ?, ?, ?, ?
                    ";
            return DB::connection((new static)->connection)->statement($query, $params);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
    public static function getReportChecklist5R($param1, $param2, $param3, $param4, $param5)
    {
        try {
            $connection = DB::connection('sqlsrv')->getPdo();

            $query = "SET NOCOUNT ON; EXEC [udsp_Report5R] ?, ?, ?, ? , ?";
            $stmt = $connection->prepare($query);
            $stmt->execute([$param1, $param2, $param3, $param4, $param5]);

            $results = [];
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel pertama
            $stmt->nextRowset();
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel kedua 
            $stmt->nextRowset();
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel ketiga

            // Mengembalikan data dalam format JSON
            return response()->json([
                'success' => true,
                'message' => 'List Data Retrieved from SP',
                'data' => [
                    'table1' => $results[0] ?? [],
                    'table2' => $results[1] ?? [],
                    'table3' => $results[2] ?? [],
                ]
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }


    ///Ini Checklist5R


    ///Ini CICO ASET
    public static function PostLogPage($param1, $param2, $param3, $param4)
    {
        try {
            // Eksekusi stored procedure
            $query = "SET NOCOUNT ON; EXEC [InsertLogPage] ?, ?, ?, ?, ?";
            return DB::statement($query, ['Insert', $param1, $param2, $param3, $param4]);
        } catch (\Exception $e) {
            // Log kesalahan jika ada
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function PostInsertAssetMobile($param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8)
    {
        try {
            // Eksekusi stored procedure
            $query = "SET NOCOUNT ON; EXEC [Mobile_Log_Asset] ?, ?, ?, ?, ?, ?, ?, ?, ?";
            return DB::statement($query, ['Insert', $param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8]);
        } catch (\Exception $e) {
            // Log kesalahan jika ada
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getAndroidAsetDetail($param1, $param2, $param3, $param4, $param5)
    {
        try {

            $connection = DB::connection('sqlsrv')->getPdo();

            $query = "SET NOCOUNT ON; EXEC [Mobile_Asset_Dashboard_Detail] ?, ?, ?, ?, ?";
            $stmt = $connection->prepare($query);
            $stmt->execute([$param1, $param2, $param3, $param4, $param5]);

            $results = [];
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel pertama 

            // Konversi data Pict menjadi base64
            foreach ($results as &$table) {
                foreach ($table as &$row) {
                    if (isset($row['Pict'])) {
                        $row['Pict'] = base64_encode($row['Pict']);
                    }
                    if (isset($row['EmpPhoto'])) {
                        $row['EmpPhoto'] = base64_encode($row['EmpPhoto']);
                    }
                }
            }

            // Mengembalikan data dalam format JSON
            return response()->json([
                'success' => true,
                'message' => 'List Data Retrieved from SP',
                'data' => [
                    'table1' => $results[0] ?? [],
                ]
            ]);
        } catch (\Exception $e) {

            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getChecklistCico($param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8)
    {

        try {
            // Gunakan connection dari properti model
            $query = "SET NOCOUNT ON; EXEC [Mobile_Log_Asset] ?, ?, ?, ?, ?, ?, ?, ?, ?";
            // Gunakan DB::connection untuk menggunakan koneksi yang ditentukan di model
            return DB::connection((new static)->connection)->select($query, [
                'Get Checklist',
                $param1,
                $param2,
                $param3,
                $param4,
                $param5,
                $param6,
                $param7,
                $param8
            ]);
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getLogUserCico($param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8)
    {

        try {
            // Gunakan connection dari properti model
            $query = "SET NOCOUNT ON; EXEC [Mobile_Log_Asset] ?, ?, ?, ?, ?, ?, ?, ?, ?";
            // Gunakan DB::connection untuk menggunakan koneksi yang ditentukan di model
            return DB::connection((new static)->connection)->select($query, [
                'Get User Name',
                $param1,
                $param2,
                $param3,
                $param4,
                $param5,
                $param6,
                $param7,
                $param8
            ]);
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getUnitTypeCico($param1, $param2, $param3)
    {

        try {
            // Gunakan connection dari properti model
            $query = "SET NOCOUNT ON; EXEC [Mobile_Get_Data] ?, ?, ?, ?";
            // Gunakan DB::connection untuk menggunakan koneksi yang ditentukan di model
            return DB::connection((new static)->connection)->select($query, [
                'Get Unit Type Asset',
                $param1,
                $param2,
                $param3
            ]);
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }


    public static function getDashboardCico($param1, $param2, $param3)
    {

        try {

            $connection = DB::connection('sqlsrv')->getPdo();

            $query = "SET NOCOUNT ON; EXEC [Mobile_Asset_Dashboard] ?, ?, ?";
            $stmt = $connection->prepare($query);
            $stmt->execute([$param1, $param2, $param3]);

            $results = [];
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel pertama 

            // Konversi data Pict menjadi base64
            foreach ($results as &$table) {
                foreach ($table as &$row) {
                    if (isset($row['Picture'])) {
                        $row['Picture'] = base64_encode($row['Picture']);
                    }
                }
            }

            // Mengembalikan data dalam format JSON
            return response()->json([
                'success' => true,
                'message' => 'List Data Retrieved from SP',
                'data' => [
                    'table1' => $results[0] ?? [],
                ]
            ]);
        } catch (\Exception $e) {

            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getDetDashboardCico($param1, $param2, $param3, $param4)
    {

        try {

            $connection = DB::connection('sqlsrv')->getPdo();

            $query = "SET NOCOUNT ON; EXEC [Mobile_Asset_Dashboard_Detail] ?, ?, ?, ?";
            $stmt = $connection->prepare($query);
            $stmt->execute([$param1, $param2, $param3, $param4]);

            $results = [];
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel pertama 

            // Konversi data Pict menjadi base64
            foreach ($results as &$table) {
                foreach ($table as &$row) {
                    if (isset($row['Picture'])) {
                        $row['Picture'] = base64_encode($row['Picture']);
                    }
                }
            }

            // Mengembalikan data dalam format JSON
            return response()->json([
                'success' => true,
                'message' => 'List Data Retrieved from SP',
                'data' => [
                    'table1' => $results[0] ?? [],
                ]
            ]);
        } catch (\Exception $e) {

            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
    ///Ini CICO ASET

    ///Ini Untuk Empty Loc
    public static function getReasonEmptyLoc($param1, $param2, $param3)
    {

        try {
            // Gunakan connection dari properti model
            $query = "SET NOCOUNT ON; EXEC [Mobile_Get_Data] ?, ?, ?, ?";
            // Gunakan DB::connection untuk menggunakan koneksi yang ditentukan di model
            return DB::connection((new static)->connection)->select($query, [
                'Get Reason Empty Loc',
                $param1,
                $param2,
                $param3
            ]);
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getValidasiReasonEmptyLoc($param1, $param2, $param3)
    {

        try {
            // Gunakan connection dari properti model
            $query = "SET NOCOUNT ON; EXEC [CC_Reason_UnCycleCount_Validation] ?, ?, ?";
            // Gunakan DB::connection untuk menggunakan koneksi yang ditentukan di model
            return DB::connection((new static)->connection)->select($query, [
                $param1,
                $param2,
                $param3
            ]);
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function PostInsertReasonEmptyLoc($param1, $param2, $param3, $param4, $param5)
    {
        try {
            // Eksekusi stored procedure
            $query = "SET NOCOUNT ON; EXEC [CC_Reason_UnCycleCount_Mobile] ?, ?, ?, ?, ?";
            return DB::statement($query, [$param1, $param2, $param3, $param4, $param5]);
        } catch (\Exception $e) {
            // Log kesalahan jika ada
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    ///Ini Untuk Empty Loc

    /// Ini Untuk Barang Balikan Store
    public static function getDescrBalikanStore($param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8, $param9, $param10, $param11, $param12)
    {

        try {
            // Gunakan connection dari properti model
            $query = "SET NOCOUNT ON; EXEC [INBOUND_BARANG_BALIKAN_FBI] ?, ?, ?, ?,?, ?, ?, ?,?, ?, ?, ?,?";
            // Gunakan DB::connection untuk menggunakan koneksi yang ditentukan di model
            return DB::connection((new static)->connection)->select($query, [
                'Get Data 1',
                $param1,
                $param2,
                $param3,
                $param4,
                $param5,
                $param6,
                $param7,
                $param8,
                $param9,
                $param10,
                $param11,
                $param12
            ]);
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getStoreBalikanStore($param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8, $param9, $param10, $param11, $param12)
    {

        try {
            // Gunakan connection dari properti model
            $query = "SET NOCOUNT ON; EXEC [INBOUND_BARANG_BALIKAN_FBI] ?, ?, ?, ?,?, ?, ?, ?,?, ?, ?, ?,?";
            // Gunakan DB::connection untuk menggunakan koneksi yang ditentukan di model
            return DB::connection((new static)->connection)->select($query, [
                'Get Data 2',
                $param1,
                $param2,
                $param3,
                $param4,
                $param5,
                $param6,
                $param7,
                $param8,
                $param9,
                $param10,
                $param11,
                $param12
            ]);
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function insertReceiveBarangBalikanStore($params)
    {
        try {
            $query = "
                        EXEC [INSERT_RECEIEVE_BALIKAN] 
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                        ?
                    ";
            return DB::connection((new static)->connection)->statement($query, $params);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }


    public static function postInsertMoveBalikanStore($param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8, $param9, $param10, $param11, $param12, $param13, $param14)
    {
        try {
            // Eksekusi stored procedure
            $query = "SET NOCOUNT ON; EXEC [MOVE_LOKASI_Balikan] ?, ?, ?, ?, ?,?, ?, ?, ?, ?,?, ?, ?, ?";
            return DB::statement($query, [$param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8, $param9, $param10, $param11, $param12, $param13, $param14]);
        } catch (\Exception $e) {
            // Log kesalahan jika ada
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getMoveBalikanStore($param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8)
    {

        try {
            // Gunakan connection dari properti model
            $query = "SET NOCOUNT ON; EXEC [GETDATA_MOVE_BALIKAN] ?, ?, ?, ?,?, ?, ?, ?";
            // Gunakan DB::connection untuk menggunakan koneksi yang ditentukan di model
            return DB::connection((new static)->connection)->select($query, [
                $param1,
                $param2,
                $param3,
                $param4,
                $param5,
                $param6,
                $param7,
                $param8,

            ]);
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    /// Ini Untuk Barang Balikan Store

























    /// Ini Untuk Projek MHE
    public static function getDataTaskMHE($param1, $param2, $param3, $param4, $param5, $param6)
    {
        //exec [Task_MHE] 'Summary Task','WMWHSE5','WMWHSE5RTL','2025-02-01','2025-02-20','' 
        try {
            $connection = DB::connection('sqlsrv')->getPdo();

            $query = "SET NOCOUNT ON; EXEC [Task_MHE] ?, ?, ?, ? , ?, ?";
            $stmt = $connection->prepare($query);
            $stmt->execute([$param1, $param2, $param3, $param4, $param5, $param6]);

            if ($param1 === 'Summary Task') {
                $results = [];
                $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel pertama

                return response()->json([
                    'success' => true,
                    'message' => 'List Data Retrieved from SP',
                    'data' => [
                        'table1' => $results[0] ?? [],
                    ]
                ]);
            } else if ($param1 === 'Today Task') {
                $results = [];
                $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel pertama

                $stmt->nextRowset(); // Pindah ke tabel kedua
                $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Ambil tabel kedua

                // Konversi data Pict menjadi base64
                foreach ($results as &$table) {
                    foreach ($table as &$row) {
                        if (isset($row['Picture'])) {
                            $row['Picture'] = base64_encode($row['Picture']);
                        }
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'List Data Retrieved from SP',
                    'data' => [
                        'table1' => $results[0] ?? [],
                        'table2' => $results[1] ?? [],
                    ]
                ]);
            }
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function updateDailyAssignmentSchedule($params)
    {
        try {
            $query = "
                        EXEC [Update_Daily_Assignment_Schedule_MHE] 
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                    ";
            return DB::connection((new static)->connection)->statement($query, $params);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function InsertFormHelpdeskMHE($params)
    {
        try {
            $query = "
                        EXEC [InsertMHEHelpdesk] 
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                    ";
            return DB::connection((new static)->connection)->statement($query, $params);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function InsertAssignmentScheduleMobile($params)
    {
        try {
            $query = "
                        EXEC [Insert_Daily_Assignment_Schedule_MHE_Mobile] 
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                    ";
            return DB::connection((new static)->connection)->statement($query, $params);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function InsertService($params)
    {
        try {
            $query = "
                        EXEC [SPInsertServiceMHE] 
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                    ";
            return DB::connection((new static)->connection)->statement($query, $params);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function InsertMHEEmp($params)
    {
        try {
            $query = "
                        EXEC [InsertMHEEmp] 
                        ?, ?, ?, ?, ?, ?
                    ";
            return DB::connection((new static)->connection)->statement($query, $params);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
    /// Ini Untuk Projek MHE


    /// Ini general untuk get data mobile
    public static function getDataMobile($param1, $param2, $param3, $param4, $param5)
    {
        try {
            $connection = DB::connection('sqlsrv')->getPdo();

            $query = "SET NOCOUNT ON; EXEC [Mobile_Get_Data] ?, ?, ?, ?, ?";
            $stmt = $connection->prepare($query);
            $stmt->execute([$param1, $param2, $param3, $param4, $param5]);

            $results = [];
            $tableIndex = 1;

            do {
                $tableData = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                if (!empty($tableData)) {
                    $results["table{$tableIndex}"] = $tableData;
                }
                $tableIndex++;
            } while ($stmt->nextRowset());

            // Optional: konversi field Picture ke base64 jika ada
            foreach ($results as &$table) {
                foreach ($table as &$row) {
                    if (isset($row['Picture'])) {
                        $row['Picture'] = base64_encode($row['Picture']);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'List Data Retrieved from SP',
                'data' => (object) $results // supaya data tetap {} meski kosong
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getDataMobileArray($param1, $param2, $param3, $param4, $param5)
    {
        try {
            // Gunakan connection dari properti model
            $query = "SET NOCOUNT ON; EXEC [Mobile_Get_Data] ?, ?, ?, ?, ?";
            // Gunakan DB::connection untuk menggunakan koneksi yang ditentukan di model
            return DB::connection((new static)->connection)->select($query, [
                $param1,
                $param2,
                $param3,
                $param4,
                $param5
            ]);
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
    /// Ini general untuk get data mobile

    /// ini untuk serah terima dokumen
    public static function InsertSerahTerima($params)
    {
        try {
            $query = "
                        EXEC [Serah_terima_Doc_v1] 
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                    ";
            return DB::connection((new static)->connection)->statement($query, $params);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
    /// ini untuk serah terima dokumen

    // ini untuk update master user
    public static function UpdatePassLogin($params)
    {
        try {
            $query = "
                            exec Mobile_Get_Login 
                            ?, ?, ?, ?
                        ";
            return DB::connection((new static)->connection)->statement($query, $params);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
    // ini untuk update master user

    /// Ini untuk Repelenish
    public static function getDataReplensih($param1, $param2, $param3, $param4, $param5, $param6, $param7)
    {
        try {
            // Gunakan connection dari properti model
            $connection = DB::connection('sqlsrv')->getPdo();

            $query = 'SET NOCOUNT ON; EXEC [udsp_Monitoring_Replenish_Mobile] ?, ?, ?, ?, ?, ?, ?';
            // Gunakan DB::connection untuk menggunakan koneksi yang ditentukan di model
            $stmt = $connection->prepare($query);
            $stmt->execute([$param1, $param2, $param3, $param4, $param5, $param6, $param7]);
            $results = [];
            $tableIndex = 1;

            do {
                $tableData = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                if (!empty($tableData)) {
                    $results["table{$tableIndex}"] = $tableData;
                }
                $tableIndex++;
            } while ($stmt->nextRowset());

            // Optional: konversi field Picture ke base64 jika ada
            foreach ($results as &$table) {
                foreach ($table as &$row) {
                    if (isset($row['Picture'])) {
                        $row['Picture'] = base64_encode($row['Picture']);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'List Data Retrieved from SP',
                'data' => (object) $results, // supaya data tetap {} meski kosong
            ]);
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
    /// Ini untuk Repelenish

    //INI TAMBAHAN BELUM DI SYNC
    public static function InsertRatingHelpdeskMHE($params)
    {
        try {
            $query = "
                        EXEC [InsertMHEHelpdeskRating] 
                        ?, ?, ?, ?, ?, ?
                    ";
            return DB::connection((new static)->connection)->statement($query, $params);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getDataAttachment5R($param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8)
    {
        try {
            $connection = DB::connection('sqlsrv')->getPdo();

            $query = 'SET NOCOUNT ON; EXEC [Get_Attachment_5R] ?, ?, ?, ?, ?, ?, ?, ?';
            $stmt = $connection->prepare($query);
            $stmt->execute([$param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8]);

            $results = [];
            $tableIndex = 1;

            do {
                $tableData = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                if (!empty($tableData)) {
                    $results["table{$tableIndex}"] = $tableData;
                }
                $tableIndex++;
            } while ($stmt->nextRowset());

            // Optional: konversi field Picture ke base64 jika ada
            foreach ($results as &$table) {
                foreach ($table as &$row) {
                    if (isset($row['Picture'])) {
                        $row['Picture'] = base64_encode($row['Picture']);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'List Data Retrieved from SP',
                'data' => (object) $results, // supaya data tetap {} meski kosong
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
    //INI TAMBAHAN BELUM DI SYNC


}
