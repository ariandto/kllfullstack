<?php

namespace App\Http\Controllers\Admin\Dashboard\Transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Dashboard\Transport\SCMAssetArmada;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SCMAssetArmadaController extends Controller
{

    /**
     * Get list of Zones (HUB, NDC, RDC, SDC, etc)
     */
    public function getZoneList()
    {
        try {

            Log::info("API /zones HIT");

            $zoneList = SCMAssetArmada::getZoneList();

            Log::info("API /zones SUCCESS", [
                'count' => count($zoneList)
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Success',
                'zone'    => $zoneList
            ]);

        } catch (\Exception $e) {

            Log::error("API /zones FAILED", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Error fetching zone list: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Get Armada Pivot based on Zone
     */
    public function getArmadaPivotByZone(Request $request)
    {
        Log::info("API /pivot HIT", [
            'payload' => $request->all(),
            'ip'      => $request->ip(),
            'method'  => $request->method(),
            'url'     => $request->fullUrl()
        ]);

        /* ---------------- VALIDATION ---------------- */
        $validator = Validator::make($request->all(), [
            'zone' => 'required'
        ]);

        if ($validator->fails()) {

            Log::warning("API /pivot VALIDATION FAIL", [
                'errors' => $validator->errors()->toArray()
            ]);

            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        /* ---------------- PROCESS ---------------- */
        try {

            $zone = $request->query('zone'); 

            Log::info("API /pivot CALLING MODEL", ['zone' => $zone]);

            $data = SCMAssetArmada::getArmadaPivotByZone($zone);

            Log::info("API /pivot SUCCESS", [
                'row_count' => count($data['react']),
                'total_armada' => array_sum(array_column($data['react'], 'total_armada'))
            ]);

            return response()->json([
                'status'   => true,
                'message'  => 'Success',
                'raw'      => $data['raw'],
                'react'    => $data['react'],
                'summary'  => [
                    'total_facility' => count($data['react']),
                    'total_armada'   => array_sum(array_column($data['react'], 'total_armada')),
                ]
            ]);

        } catch (\Exception $e) {

            Log::error("API /pivot FAILED", [
                'zone'  => $request->zone,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status'  => false,
                'message' => "Error fetching pivot data: " . $e->getMessage()
            ], 500);
        }
    }
}
