<?php

namespace App\Models\Admin\Dashboard\Storing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ModelMonitoringPicklistBalikan extends Model
{
    public static function getpicklistData($type, $sku, $whseid, $relasi, $loc, $storerkey, $lpn, $param1, $param2, $param3)
    {
        try {
            set_time_limit(300);

            $connection = DB::connection('sqlsrv')->getPdo();
            $query = "SET NOCOUNT ON; EXEC [GETDATA_STOCK_BALIKAN] ?,?,?,?,?,?,?,?,?,?";
            $stmt = $connection->prepare($query);
            \Log::info("Executing Stored Procedure: EXEC GETDATA_STOCK_BALIKAN ?,?,?,?,?,?,?,?,?,?", [
                $type, $sku, $whseid, $relasi, $loc, $storerkey, $lpn, $param1, $param2, $param3
            ]);
            $stmt->execute([
                $type,
                $sku,
                $whseid,
                $relasi,
                $loc,
                $storerkey,
                $lpn,
                $param1,
                $param2,
                $param3
            ]);
            
            $results = [];
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Ambil hasil query
            //dd( $results);
            $headers1 = array_keys($results[0][0] ?? []);

            return [
                'tabel1' => $results[0] ?? [],
                'headers1' => $headers1,
            ];

        } Catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }

    }
}