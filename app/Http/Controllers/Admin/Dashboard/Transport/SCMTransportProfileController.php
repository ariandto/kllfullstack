<?php

namespace App\Http\Controllers\Admin\Dashboard\Transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Dashboard\Transport\SCMTransportProfile;
use Illuminate\Support\Facades\Validator;

class SCMTransportProfileController extends Controller
{

    /**
     * Get list of facilities
     */
    public function getFacilityList()
    {
        try {
            $facilities = SCMTransportProfile::getFacilityList();

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
     * Dynamic Pivot Armada — kolom bergantung pada SP SQL Server
     */
  public function getFacilityArmadaPivot(Request $request)
{
    // Validasi request
    $validator = Validator::make($request->all(), [
        'facility' => 'required|string|max:100',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status'  => false,
            'message' => 'Validasi gagal.',
            'errors'  => $validator->errors()
        ], 422);
    }

    try {
        $facilityName = $request->facility;

        // Ambil 3 resultset dari model
        $result = SCMTransportProfile::getFacilityArmadaPivot($facilityName);

        $detail = $result['facility_detail'] ?? [];
        $pivot  = $result['armada_pivot'] ?? [];
        $jalur  = $result['jalur_index'] ?? [];

        // Jika pivot kosong, tetap kirim detail & jalur
        if (empty($pivot)) {
            return response()->json([
                'status'          => true,
                'message'         => 'Data pivot tidak ditemukan.',
                'facility_detail' => $detail,
                'jalur'           => $jalur,
                'data'            => [],
                'columns'         => []
            ]);
        }

        return response()->json([
            'status'          => true,
            'message'         => 'Success',
            'facility_detail' => $detail,                // result set pertama
            'data'            => $pivot,                 // result set kedua (pivot)
            'columns'         => array_keys($pivot[0]),  // dynamic columns
            'jalur'           => $jalur                  // result set ketiga
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status'  => false,
            'message' => 'Error fetching pivot armada: ' . $e->getMessage()
        ], 500);
    }
}

}


