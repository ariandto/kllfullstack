<?php

namespace App\Http\Controllers\Admin\Dashboard\Transport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Dashboard\Transport\CrudScmModel;
use Illuminate\Support\Facades\Validator;

class ScmCrudController extends Controller
{

    /**
     * GET Facility (by ID or list)
     */
    public function index(Request $request)
    {
        try {
            $params = [
                'facility_ID' => $request->facility_ID,
                'Name'        => $request->Name,
            ];

            $result = CrudScmModel::execSP('GET', $params);

            return response()->json([
                'success' => true,
                'data'    => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    /**
     * INSERT Facility
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Name' => 'required|string|max:200',
            'Is_Loading_Dock' => 'required|boolean',
            'Opening_Date' => 'nullable|date',
            'Area_Staging_P' => 'nullable|integer',
            'Area_Staging_L' => 'nullable|integer',
            'Area_Staging_T' => 'nullable|integer',
            'Area_Loading_P' => 'nullable|integer',
            'Area_Loading_L' => 'nullable|integer',
            'Alamat' => 'nullable|string|max:500',
            'Demand_DO' => 'nullable|integer',
            'Capacity_DO' => 'nullable|integer',
            'Capacity_CBM' => 'nullable|integer',
            'NIK_Leader' => 'nullable|string|max:50',
            'Telp' => 'nullable|string|max:50',
            'Background_Image' => 'nullable|string',
            'Staff_Up' => 'nullable|integer',
            'Driver' => 'nullable|integer',
            'Asst_Driver' => 'nullable|integer',
            'WHM' => 'nullable|integer',
            'Security' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $result = CrudScmModel::execSP('INSERT', $request->all());

            return response()->json([
                'success' => true,
                'message' => 'Facility created successfully',
                'new_facility_id' => $result[0]->Newfacility_ID ?? null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ], 500);
        }
    }



    /**
     * UPDATE Facility
     */
    public function update(Request $request, $facility_ID)
    {
        $data = $request->all();
        $data['facility_ID'] = $facility_ID;

        $validator = Validator::make($data, [
            'Name' => 'required|string|max:200',
            'Is_Loading_Dock' => 'required|boolean',
            'Opening_Date' => 'nullable|date',
            'Area_Staging_P' => 'nullable|integer',
            'Area_Staging_L' => 'nullable|integer',
            'Area_Staging_T' => 'nullable|integer',
            'Area_Loading_P' => 'nullable|integer',
            'Area_Loading_L' => 'nullable|integer',
            'Alamat' => 'nullable|string|max:500',
            'Demand_DO' => 'nullable|integer',
            'Capacity_DO' => 'nullable|integer',
            'Capacity_CBM' => 'nullable|integer',
            'NIK_Leader' => 'nullable|string|max:50',
            'Telp' => 'nullable|string|max:50',
            'Background_Image' => 'nullable|string',
            'Staff_Up' => 'nullable|integer',
            'Driver' => 'nullable|integer',
            'Asst_Driver' => 'nullable|integer',
            'WHM' => 'nullable|integer',
            'Security' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            CrudScmModel::execSP('UPDATE', $data);

            return response()->json([
                'success' => true,
                'message' => 'Facility updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
