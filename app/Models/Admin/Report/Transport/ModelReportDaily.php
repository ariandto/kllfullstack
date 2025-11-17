<?php 

namespace App\Models\Admin\Report\Transport;

use Illuminate\Support\Facades\DB;

class ModelReportDaily
{
    /**
     * Ambil data Daily_Report_Transport
     *
     * @param string $state
     * @param string $dateStart
     * @param string $dateEnd
     * @param string $whseid
     * @param string $dcname
     * @return array
     */
    public static function getTransportReport($state, $dateStart, $dateEnd, $whseid, $dcname)
{
    try {
        // Coba ambil data
        return DB::select("EXEC dbo.Daily_Report_Transport ?, ?, ?, ?, ?", [
            $state, $dateStart, $dateEnd, $whseid, $dcname
        ]);
    } catch (\Illuminate\Database\QueryException $e) {
        // Kalau SP tidak mengembalikan field, ambil pakai statement
        DB::statement("EXEC dbo.Daily_Report_Transport ?, ?, ?, ?, ?", [
            $state, $dateStart, $dateEnd, $whseid, $dcname
        ]);
        // Return array kosong supaya view tidak crash
        return [];
    }
}


    /**
     * Ambil data Daily_Report_Monitoring_Driver
     *
     * @param string $dateStart
     * @param string $dateEnd
     * @param string $site
     * @param string|null $facility
     * @return array
     */
  public static function getMonitoringDriver($dateStart, $dateEnd, $site, $siteName = null)
{
    $siteName = $siteName ?? '';
    $pdo = DB::connection()->getPdo();

    $stmt = $pdo->prepare("EXEC dbo.Daily_Report_Monitoring_Driver ?, ?, ?, ?");
    $stmt->execute([$dateStart, $dateEnd, $site, $siteName]);

    $result = [];

    // Loop sampai menemukan result set dengan kolom
    do {
        try {
            $data = $stmt->fetchAll(\PDO::FETCH_OBJ);
            if (!empty($data)) {
                $result = $data;
                break; // stop loop jika ada data
            }
        } catch (\PDOException $e) {
            // Jika result set kosong / no fields, skip
        }
    } while ($stmt->nextRowset());

    return $result;
}


}
