<?php

namespace App\Models\Admin\Dashboard\Storing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModelDashboardCaseIDNonLC extends Model
{
    use HasFactory;

    public static function getSummaryData($facilityID, $Relasi, $Owner, $TypeOrder, $StartDate, $EndDate, $Key1, $Key2)
    {
        try {
            //dd($facilityID, $Relasi, $Owner, $TypeOrder, $StartDate, $EndDate, $Key1, $Key2);
            //dd($facilityID, $Relasi,  $Owner, $StartDate, $Key1, $Key2);
            set_time_limit(300);
            //dd("EXEC Report_Auto_Cancel_ODI 'Summary', '$facilityID', '$Relasi', '$Owner', '$StartDate', '$Key1', '$Key2'");

            // Menggunakan koneksi yang ditentukan
            $connection = DB::connection('sqlsrv')->getPdo();
            // Menyiapkan query untuk mengeksekusi stored procedure
            $query = "SET NOCOUNT ON; EXEC [Dashboard_CaseID_Open_NonLC] ?,?,?,?,?,?,?,?,?";
            $stmt = $connection->prepare($query);
            $stmt->execute([
                'Summary',
                $facilityID,
                $Relasi,
                $Owner,
                $TypeOrder,
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

    public static function getDetailData($facilityID, $Relasi, $Owner, $TypeOrder, $StartDate, $EndDate, $Key1, $Key2)
    {
        try {
            set_time_limit(300);
            //dd($facilityID, $Relasi, $Owner, $TypeOrder, $StartDate, $EndDate, $Key1, $Key2);
            // Menggunakan koneksi yang ditentukan
            $connection = DB::connection('sqlsrv')->getPdo();
            // Menyiapkan query untuk mengeksekusi stored procedure
            $query = "SET NOCOUNT ON; EXEC [Dashboard_CaseID_Open_NonLC] ?,?,?,?,?,?,?,?,?";
            $stmt = $connection->prepare($query);
            $stmt->execute([
                'Detail',
                $facilityID,
                $Relasi,
                $Owner,
                $TypeOrder,
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
}
