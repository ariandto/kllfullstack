<?php

namespace App\Models\Admin\Report\Public;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModelReportAssetDC extends Model
{
    use HasFactory;

    public static function getSummaryData($dcNamesString, $UnitTypeString)
    {
        try {
            set_time_limit(300);
            $connection = DB::connection('sqlsrv')->getPdo();
            $query = 'SET NOCOUNT ON; EXEC [Report_Asset_DC] ?, ?'; // cuma 1 param
            $stmt = $connection->prepare($query);
            $stmt->execute([$dcNamesString, $UnitTypeString]);

            $results = [];
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $stmt->nextRowset();
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $stmt->nextRowset();
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $headers1 = array_keys($results[0][0] ?? []);
            $headers2 = array_keys($results[1][0] ?? []);
            $headers3 = array_keys($results[2][0] ?? []);

            return [
                'tabel1' => $results[0] ?? [],
                'tabel2' => $results[1] ?? [],
                'tabel3' => $results[2] ?? [],
                'headers1' => $headers1,
                'headers2' => $headers2,
                'headers3' => $headers3,
            ];
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

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
            return DB::select($query, ['Get Unit Type Asset','','', $dcNamesString, '']);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure for facilities: ' . $e->getMessage());
        }
    }
}
