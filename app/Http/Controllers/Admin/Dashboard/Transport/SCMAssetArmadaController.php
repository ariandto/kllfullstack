<?php

namespace App\Http\Controllers\Admin\Dashboard\Transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Dashboard\Transport\SCMAssetArmada;
use Illuminate\Support\Facades\Validator;

class SCMAssetArmadaController extends Controller
{

    /**
     * Get list of facilities
     */
    public function getFacilityList()
    {
        try {
            // BUG FIX: harusnya SCMAssetArmada, bukan SCMTransportProfile
            $facilities = SCMAssetArmada::getFacilityList();

            return response()->json([
                'status'     => true,
                'message'    => 'Success',
                'facilities' => $facilities ?? []
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Error fetching facility list: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Get pivot result of Facility Armada (from SP sp_GetFacilityArmadaPivotv2)
     */
    public function getFacilityArmadaPivot(Request $request)
    {
        // ---------------------------
        // 1. VALIDASI
        // ---------------------------
        $validator = Validator::make($request->all(), [
            'facility' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        try {

            $facilityName = $request->facility;

            // Ambil data dari Model
            $pivotData = SCMAssetArmada::getFacilityArmadaPivot($facilityName);

            return response()->json([
                'status'  => true,
                'message' => 'Success',
                'pivot'   => $pivotData['pivot'] ?? []
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Error fetching pivot data: ' . $e->getMessage()
            ], 500);
        }
    }

}
