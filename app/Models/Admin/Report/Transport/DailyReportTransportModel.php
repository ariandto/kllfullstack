<?php

namespace App\Models\Admin\Report\Transport;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DailyReportTransportModel extends Model
{
    public $timestamps = false;

    /**
     * 🔹 Ambil data utama (Summary)
     * SP: EXEC Daily_Report_Transport @Mode, @StartDate, @EndDate, @Key1, @FacilityID
     * Backend: cukup pakai 1 tanggal → StartDate = EndDate = $Tanggal
     */
    public static function getReport($Tanggal, $Key1, $FacilityID)
    {
        try {
            $StartDate = $Tanggal;
            $EndDate   = $Tanggal;

            $cacheKey = "daily_report_{$Tanggal}_{$Key1}_{$FacilityID}";

            return Cache::remember($cacheKey, 60, function () use ($StartDate, $EndDate, $Key1, $FacilityID) {

                $pdo = DB::connection('sqlsrv')->getPdo();

                // 5 parameter sesuai SP: Mode, StartDate, EndDate, Key1, FacilityID
                $stmt = $pdo->prepare("
                    SET NOCOUNT ON;
                    EXEC [dbo].[Daily_Report_Transport] ?, ?, ?, ?, ?
                ");
                $stmt->execute([
                    '',         // Mode summary
                    $StartDate,
                    $EndDate,
                    $Key1,
                    $FacilityID,
                ]);

                $results = [];
                $index   = 0;

                // 🔒 Proteksi IMSSP: cek columnCount dulu
                do {
                    if ($stmt->columnCount() > 0) {
                        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                        if (!empty($data)) {
                            $results[$index] = $data;
                            $index++;
                        }
                    }
                } while ($stmt->nextRowset());

                return [
                    'prodCustomer'        => $results[0] ?? [],
                    'prodStore'           => $results[1] ?? [],
                    'deliveryCustomer'    => $results[2] ?? [],
                    'deliveryStore'       => $results[3] ?? [],
                    'monitoringExternal'  => $results[4] ?? [],
                    'monitoringInternal'  => $results[5] ?? [],
                    'slaCustomer'         => $results[6] ?? [],
                    'prodArmadaCust'      => $results[7] ?? [],
                    'prodArmadaStore'     => $results[8] ?? [],
                ];
            });

        } catch (\Exception $e) {
            \Log::error('Error getReport: ' . $e->getMessage());
            throw new \Exception('Gagal memuat laporan transportasi: ' . $e->getMessage());
        }
    }

    /**
     * 🔹 Ambil data detail (breakdown)
     * Tetap SP yang sama, Mode='Detail'
     * Backend: 1 tanggal → StartDate = EndDate = $Tanggal
     */
    public static function getDetail($Tanggal, $Key1, $FacilityID)
    {
        try {
            $StartDate = $Tanggal;
            $EndDate   = $Tanggal;

            $pdo = DB::connection('sqlsrv')->getPdo();

            $stmt = $pdo->prepare("
                SET NOCOUNT ON;
                EXEC [dbo].[Daily_Report_Transport] ?, ?, ?, ?, ?
            ");
            $stmt->execute([
                'Detail',
                $StartDate,
                $EndDate,
                $Key1,
                $FacilityID,
            ]);

            $results = [];

            do {
                if ($stmt->columnCount() > 0) {
                    $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                }
            } while ($stmt->nextRowset());

            return $results;

        } catch (\Exception $e) {
            \Log::error('Error getDetail: ' . $e->getMessage());
            throw new \Exception('Gagal memuat detail laporan transportasi: ' . $e->getMessage());
        }
    }

    /**
     * 🔹 Ambil data pemakaian armada
     */
    public static function getArmadaUtilization($site, $date)
    {
        try {
            $pdo = DB::connection('sqlsrv')->getPdo();

            $spName = null;
            if (str_contains(strtoupper($site), 'AHI')) {
                $spName = 'sp_GetArmadaUtilization_AHI';
            } elseif (str_contains(strtoupper($site), 'INFORMA JABABEKA')) {
                $spName = 'sp_GetArmadaUtilization_HCI';
            } elseif (str_contains(strtoupper($site), 'INFORMA CIKUPA')) {
                $spName = 'sp_GetArmadaUtilization_CIKUPA';
            }

            if (!$spName) {
                return [];
            }

            $stmt = $pdo->prepare("SET NOCOUNT ON; EXEC dbo.$spName @Tanggal = ?");
            $stmt->execute([$date]);

            $results = [];
            do {
                if ($stmt->columnCount() > 0) {
                    $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                }
            } while ($stmt->nextRowset());

            // Ambil index ke-1 (seperti VB)
            return $results[1] ?? [];

        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure ' . $spName . ': ' . $e->getMessage());
        }
    }

    /**
     * 🔹 Ambil data pemakaian driver (tetap dengan facilityMap)
     */
    public static function getDriverUtilization($site, $date)
    {
        try {
            $pdo = DB::connection('sqlsrv')->getPdo();

            $facilityMap = [
                'DC AHI JABABEKA'     => 'AHI JABABEKA',
                'DC INFORMA CIKUPA'   => 'HCI CIKUPA',
                'DC INFORMA JABABEKA' => 'HCI JABABEKA',
                'DC INFORMA MAKASSAR' => 'HCI MAKASSAR',
                'DC MEDAN TAMORA'     => 'HCI MEDAN TAMORA',
                'DC SIDOARJO'         => 'DC SIDOARJO',
            ];

            $facility = $facilityMap[$site] ?? '';
            $startDate = $date;
            $endDate   = $date;

            $stmt = $pdo->prepare("
                SET NOCOUNT ON;
                EXEC [dbo].[Daily_Report_Monitoring_Driver] ?, ?, ?, ?
            ");
            $stmt->execute([$startDate, $endDate, $site, $facility]);

            $results = [];
            $index   = 0;

            do {
                if ($stmt->columnCount() > 0) {
                    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    if (!empty($data)) {
                        $results[$index] = $data;
                        $index++;
                    }
                }
            } while ($stmt->nextRowset());

            // Gunakan hasil ke-3 (index ke-2)
            return $results[2] ?? [];

        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure Daily_Report_Monitoring_Driver: ' . $e->getMessage());
        }
    }

    /**
     * 🔹 Ambil daftar site (udsp_Get_Data)
     */
    public static function getSiteList()
    {
        try {
            $sites = DB::select("
                EXEC [udsp_Get_Data] 'Get Zone DC 2','DC INFORMA JABABEKA','WMWHSE4RTL','NDC','ALL'
            ");

            return collect($sites)
                ->map(fn($row) => (object) ['Facility' => $row->NAME ?? 'Unknown'])
                ->unique('Facility')
                ->values()
                ->toArray();

        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure udsp_Get_Data: ' . $e->getMessage());
        }
    }
}
