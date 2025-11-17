<?php

namespace App\Models\Admin\Report\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModelReportIntercompany extends Model
{
    use HasFactory;

    public static function getSummaryData($facilityID, $Relasi,  $StartDate, $EndDate, $Key1, $Key2)
    {
        try {
            //dd($facilityID, $Relasi,  $Owner, $StartDate, $Key1, $Key2);
            set_time_limit(300);
            //dd("EXEC Report_Auto_Cancel_ODI 'Summary', '$facilityID', '$Relasi', '$Owner', '$StartDate', '$Key1', '$Key2'");

            // Menggunakan koneksi yang ditentukan
            $connection = DB::connection('sqlsrv')->getPdo();
            // Menyiapkan query untuk mengeksekusi stored procedure
            $query = "SET NOCOUNT ON; EXEC [Report_Intercompany_Transaksi] ?,?,?,?,?,?,?";
            $stmt = $connection->prepare($query);
            $stmt->execute([
                'Summary',
                $facilityID,
                $Relasi,
                $StartDate,
                $EndDate,
                $Key1,
                $Key2
            ]);
            $results = [];
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel pertama
            $stmt->nextRowset(); // Pindah ke rowset kedua
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel kedua
            $headers1 = array_keys($results[0][0] ?? []); // Header untuk tabel pertama 
            $headers2 = array_keys($results[1][0] ?? []); // Header untuk tabel pertama 
            return [
                'tabel1' => $results[0] ?? [],
                'tabel2' => $results[1] ?? [],
                'headers1' => $headers1,
                'headers2' => $headers2,
            ];
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getDetailData($facilityID, $Relasi,  $StartDate, $EndDate, $Key1, $Key2)
    {
        try {
            set_time_limit(300);

            // Menggunakan koneksi yang ditentukan
            $connection = DB::connection('sqlsrv')->getPdo();
            // Menyiapkan query untuk mengeksekusi stored procedure
            $query = "SET NOCOUNT ON; EXEC [Report_Intercompany_Transaksi] ?,?,?,?,?,?,?";
            $stmt = $connection->prepare($query);
            $stmt->execute([
                'Detail',
                $facilityID,
                $Relasi,
                $StartDate,
                $EndDate,
                $Key1,
                $Key2
            ]);

            $results = [];
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel pertama
            // $stmt->nextRowset(); // Pindah ke rowset kedua
            // $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel kedua
            // $stmt->nextRowset(); // Pindah ke rowset ketiga
            // $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel ketiga

            $headers3 = array_keys($results[0][0] ?? []); // Header untuk tabel pertama
            // $headers2 = array_keys($results[1][0] ?? []); // Header untuk tabel kedua
            // $headers3 = array_keys($results[2][0] ?? []); // Header untuk tabel kedua


            return [
                'tabel3' => $results[0] ?? [],
                // 'tabel2' => $results[1] ?? [],
                // 'tabel3' => $results[2] ?? [],
                'headers3' => $headers3,
                // 'headers2' => $headers2,
                // 'headers3' => $headers3,
            ];
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function insertDataComments($facilityID, $Relasi, $Tanggal, $Comment, $Addwho)
    {
        try {
            //dd($$facilityID,$Relasi,$Owner,$Dept,$Shift, $Start1, $End1, $Start2, $End2);
            $query = "SET NOCOUNT ON; EXEC [Insert_Notes_Intercompany] ?, ?, ? , ?, ?";
            return DB::statement($query, [$facilityID, $Relasi, $Tanggal, $Comment, $Addwho]);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
}


     // dd([
            //     'tabel1' => $results[0] ?? [],
            //     'tabel2' => $results[1] ?? [],
            //     'headers1' => $headers1,
            //     'headers2' => $headers2,
            // ]);