<?php

namespace App\Models\Admin\Dashboard\Transport;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SCMAssetArmada extends Model
{
    protected $connection = 'sqlsrv';

    /**
     * Get list of available zones
     */
    public static function getZoneList()
    {
        try {
            $result = DB::connection('sqlsrv')->select(
                "SELECT DISTINCT zone FROM MASTER_FACILITY_V1 WHERE zone IS NOT NULL AND LTRIM(RTRIM(zone)) <> ''"
            );

            return array_map(fn($r) => (array)$r, $result);

        } catch (\Exception $e) {
            Log::error("SCMAssetArmada::getZoneList ERROR", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw new \Exception('Error executing query Get SCM Zone: ' . $e->getMessage());
        }
    }


    /**
     * Get Armada Pivot based on Zone (NDC, RDC, or multi-zone "NDC,RDC")
     */
    public static function getArmadaPivotByZone($zone)
    {
        if (empty($zone)) {
            throw new \InvalidArgumentException("Zone is required.");
        }

        // If array → "NDC,RDC"
        if (is_array($zone)) {
            $zone = implode(',', $zone);
        }

        try {
            Log::info("SCMAssetArmada::getArmadaPivotByZone START", [
                'zone_received' => $zone
            ]);

            //--------------------------------------------------
            // 🔥 SP EXECUTION — PARAMETER WAJIB pakai @name!!!
            //--------------------------------------------------
            $rows = DB::connection('sqlsrv')->select(
                "EXEC dbo.sp_GetFacilityArmadaPivotv2 @name = :name",
                ['name' => $zone]
            );

            // Convert to array assoc
            $rows = array_map(fn($r) => (array)$r, $rows);

            Log::info("SCMAssetArmada::SP_Result_Count", [
                'zone'  => $zone,
                'count' => count($rows)
            ]);

            return [
                'raw'   => $rows,
                'react' => self::formatPivotForReact($rows)
            ];

        } catch (\Exception $e) {

            Log::error("SCMAssetArmada::getArmadaPivotByZone ERROR", [
                'zone'  => $zone,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw new \Exception("Error executing SP GetFacilityArmadaPivotv2: " . $e->getMessage());
        }
    }


    /**
     * Format Pivot result for React frontend (dynamic columns)
     */
    private static function formatPivotForReact($rows)
    {
        $output = [];

        foreach ($rows as $r) {

            $item = [
                'zone'         => $r['Zone'] ?? null,
                'facility'     => $r['Facility_Name'] ?? null,
                'source'       => $r['Source_DB'] ?? null,
                'armada'       => [],
                'total_armada' => 0,
            ];

            foreach ($r as $key => $value) {

                // Skip non-armada fields
                if (in_array($key, ['Zone', 'Facility_Name', 'Source_DB'])) {
                    continue;
                }

                // Convert null → 0
                $value = (int) ($value ?? 0);

                $item['armada'][$key] = $value;
                $item['total_armada'] += $value;
            }

            $output[] = $item;
        }

        return $output;
    }
}
