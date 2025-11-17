<?php

namespace App\Models\Admin\Report\Public\Maintenance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModelReportTaskMHE extends Model
{
    use HasFactory;

    public static function getSummaryData($facilityID, $Relasi,  $StartDate, $EndDate)
    {
        try {
            //dd($facilityID, $Relasi,  $Owner, $StartDate, $Key1, $Key2);
            set_time_limit(300);
            //dd("EXEC Report_Auto_Cancel_ODI 'Summary', '$facilityID', '$Relasi', '$Owner', '$StartDate', '$Key1', '$Key2'");

            // Menggunakan koneksi yang ditentukan
            $connection = DB::connection('sqlsrv')->getPdo();
            // Menyiapkan query untuk mengeksekusi stored procedure
            //EXEC [Report_Task_MHE] 'WMWHSE5','WMWHSE5RTL','2025-02-01','2025-02-28'
            $query = "SET NOCOUNT ON; EXEC [Report_Task_MHE] ?,?,?,?";
            $stmt = $connection->prepare($query);
            $stmt->execute([
                $facilityID,
                $Relasi,
                $StartDate,
                $EndDate
            ]);
            $results = [];
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel pertama
            $stmt->nextRowset(); // Pindah ke rowset kedua
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel kedua
            $stmt->nextRowset(); // Pindah ke rowset kedua
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel kedua
            $headers1 = array_keys($results[0][0] ?? []); // Header untuk tabel pertama 
            $headers2 = array_keys($results[1][0] ?? []); // Header untuk tabel pertama 
            $headers3 = array_keys($results[2][0] ?? []); // Header untuk tabel pertama 
            return [
                'tabel1' => $results[0] ?? [],
                'tabel2' => $results[1] ?? [],
                'tabel3' => $results[2] ?? [],
                'headers1' => $headers1,
                'headers2' => $headers2,
                'headers3' => $headers3,
            ];
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
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