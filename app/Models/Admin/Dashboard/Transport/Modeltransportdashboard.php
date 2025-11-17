<?php
namespace App\Models\Admin\Dashboard\Transport;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Modeltransportdashboard extends Model
{     protected $connection = 'sqlsrv';

    public static function getFacilities()    
    {  
        try {
            $query = "exec udsp_Get_Data ?,?,?";
            return DB::select($query, ['Get Owner Dashboard Web Trans', '', '']);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure for facilities: ' . $e->getMessage());
        }
    }

    public static function getDashboardData($startDate, $site , $RELASI )
    {
        try {
            set_time_limit(300);
            $connection = DB::connection('sqlsrv')->getPdo();
    
            // Eksekusi stored procedure
            $query = "SET NOCOUNT ON; EXEC [Dashboard Transport Web] ?, ?, ?, ?";
            $stmt = $connection->prepare($query);
            $stmt->execute([$startDate,'', $RELASI, $site]);
    
            // Ambil hasil pertama (Data Armada & Trip)
            $dataArmada = $stmt->fetchAll(\PDO::FETCH_ASSOC);          
            $stmt->nextRowset();
            $dataHitMiss = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->nextRowset();
            $dataChart1 = $stmt->fetchAll(\PDO::FETCH_ASSOC);         
            $stmt->nextRowset();
            $dataChart2 = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->nextRowset();
            $dataChart3 = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->nextRowset();
            $datatripext = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->nextRowset();
            $top10area = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->nextRowset();
            $dataujp = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->nextRowset();
            $datapending = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
            return [
                'armada' => $dataArmada ?? [],
                'hitmiss' => $dataHitMiss ?? [],
                'chart1' => $dataChart1 ?? [],
                'chart2' => $dataChart2 ?? [],
                'chart3' => $dataChart3 ?? [],
                'tripext' => $datatripext ?? [],
                'top10' => $top10area ?? [],
                'ujp' => $dataujp ?? [],
                'pending' => $datapending ?? []

            ];
        } catch (\Exception $e) {
            throw new \Exception('Error fetching dashboard data: ' . $e->getMessage());
        }
    }


}