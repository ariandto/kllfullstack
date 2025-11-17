<?php

namespace App\Http\Controllers\Admin\Dashboard\Transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Dashboard\Transport\SCMTransportProfile;
use Illuminate\Support\Facades\Validator;

class SCMTransportProfileController extends Controller
{
    /**
     * Halaman utama
     */
    // public function index()
    // {
    //     $facilities = SCMTransportProfile::getFacilityList();
    //     return view('admin.dashboard.transport.scm-profile', compact('facilities'));
    // }

//     public function index()
// {
//     try {
//         $facilities = SCMTransportProfile::getFacilityList();

//         return response()->json([
//             'status'  => true,
//             'message' => 'Success',
//             'facilities' => $facilities
//         ]);

//     } catch (\Exception $e) {
//         return response()->json([
//             'status'  => false,
//             'message' => 'Error: ' . $e->getMessage()
//         ], 500);
//     }
// }
    public function getFacilityList()
{
    try {
        $facilities = SCMTransportProfile::getFacilityList();

        return response()->json([
            'status'     => true,
            'facilities' => $facilities
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status'  => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}


    /**
     * Ambil data Facility dengan Pivot Armada Dinamis
     * Contoh panggilan: /scm-profile/armada?facility=HUB - HUB UTARA
     */
    public function getFacilityArmadaPivot(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'facility' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Parameter facility wajib diisi.',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $facilityName = $request->facility;
            $data = SCMTransportProfile::getFacilityArmadaPivot($facilityName);

            return response()->json([
                'status'  => true,
                'message' => 'Success',
                'data'    => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
