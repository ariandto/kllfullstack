<?php
namespace App\Models\Admin\Report\Transport;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ModelMonitoringPengiriman extends Model
{
    protected $connection = 'sqlsrv';

    /**
     * Ambil data monitoring pengiriman dari SP spMonitoringPengiriman
     *
     * @param string|null $startDate  Format 'YYYY-MM-DD'
     * @param string|null $endDate    Format 'YYYY-MM-DD'
     * @param string|null $site
     * @param string|null $owner
     * @return array
     * @throws \Exception
     */
    public static function getMonitoringPengiriman($startDate = null, $endDate = null, $site = null, $owner = null)
    {
        try {
            // koneksi PDO langsung biar bisa pakai multiple param dengan prepare
            $connection = DB::connection('sqlsrv')->getPdo();

            $query = "SET NOCOUNT ON; 
                      EXEC spMonitoringPengiriman 
                          @startdate = ?, 
                          @enddate   = ?, 
                          @site      = ?, 
                          @owner     = ?";

            $stmt = $connection->prepare($query);
            
            // Bind parameters dengan handle NULL values
            $stmt->bindValue(1, $startDate, $startDate ? \PDO::PARAM_STR : \PDO::PARAM_NULL);
            $stmt->bindValue(2, $endDate, $endDate ? \PDO::PARAM_STR : \PDO::PARAM_NULL);
            $stmt->bindValue(3, $site, $site ? \PDO::PARAM_STR : \PDO::PARAM_NULL);
            $stmt->bindValue(4, $owner, $owner ? \PDO::PARAM_STR : \PDO::PARAM_NULL);
            
            $stmt->execute();

            // SP ini hanya 1 resultset → cukup fetchAll
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        } catch (\Exception $e) {
            // logging error untuk debugging
            Log::error("Error executing spMonitoringPengiriman", [
                'startDate' => $startDate,
                'endDate'   => $endDate,
                'site'      => $site,
                'owner'     => $owner,
                'error'     => $e->getMessage()
            ]);

            throw new \Exception("Error fetching monitoring pengiriman: " . $e->getMessage());
        }
    }

    /**
     * Alternatif menggunakan DB::select untuk yang lebih sederhana
     *
     * @param string|null $startDate
     * @param string|null $endDate
     * @param string|null $site
     * @param string|null $owner
     * @return array
     */
    public static function getMonitoringPengirimanAlternative($startDate = null, $endDate = null, $site = null, $owner = null)
    {
        try {
            return DB::connection('sqlsrv')->select(
                "EXEC spMonitoringPengiriman 
                 @startdate = :startdate, 
                 @enddate   = :enddate, 
                 @site      = :site, 
                 @owner     = :owner",
                [
                    'startdate' => $startDate,
                    'enddate'   => $endDate,
                    'site'      => $site,
                    'owner'     => $owner
                ]
            );

        } catch (\Exception $e) {
            Log::error("Error executing spMonitoringPengiriman (alternative)", [
                'startDate' => $startDate,
                'endDate'   => $endDate,
                'site'      => $site,
                'owner'     => $owner,
                'error'     => $e->getMessage()
            ]);

            throw new \Exception("Error fetching monitoring pengiriman: " . $e->getMessage());
        }
    }
}