<?php

namespace App\Models\Admin\Dashboard\Public;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModelDashboardPerformanceMHE extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv';
    public static function getDCName()
    {
        try {
            $query = 'EXEC [Web_Get_Data] ?,?,?,?,?';
            return DB::select($query, ['Get DC Name Asset', '', '', '', '']);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure for facilities: ' . $e->getMessage());
        }
    }

    public static function getUnitType($dcNamesString)
    {
        try {
            $query = 'EXEC [Web_Get_Data] ?,?,?,?,?';
            return DB::select($query, ['Get Unit Type Asset', '', '', $dcNamesString, '']);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure for facilities: ' . $e->getMessage());
        }
    }

    public static function getSummaryData($dcNamesString, $UnitTypeString)
    {
        try {
            set_time_limit(300);
            $connection = DB::connection('sqlsrv')->getPdo();
            $query = 'SET NOCOUNT ON; EXEC [MonitoringPerformanceTrackingMHE] ?, ?';
            $stmt = $connection->prepare($query);
            $stmt->execute([$dcNamesString, $UnitTypeString]);

            $results = [];

            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel pertama
            $stmt->nextRowset(); // Pindah ke rowset kedua
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel kedua
            $stmt->nextRowset(); // Pindah ke rowset ketiga
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel ketiga
            $stmt->nextRowset(); // Pindah ke rowset keempat
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel keempat

            // Mengambil header untuk masing-masing tabel
            $headers1 = array_keys($results[0][0] ?? []); // Header untuk tabel pertama
            $headers2 = array_keys($results[1][0] ?? []); // Header untuk tabel kedua
            $headers3 = array_keys($results[2][0] ?? []); // Header untuk tabel kedua
            $headers4 = array_keys($results[3][0] ?? []); // Header untuk tabel kedua

            // dd($results[0] ?? [],$results[1] ?? []);

            return [
                'tabel1' => $results[0] ?? [],
                'tabel2' => $results[1] ?? [],
                'tabel3' => $results[2] ?? [],
                'tabel4' => $results[3] ?? [],
                'headers1' => $headers1,
                'headers2' => $headers2,
                'headers3' => $headers3,
                'headers4' => $headers4,
            ];
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
}
