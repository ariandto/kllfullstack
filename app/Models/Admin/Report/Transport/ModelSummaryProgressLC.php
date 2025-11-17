<?php
namespace App\Models\Admin\Report\Transport;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ModelSummaryProgressLC extends Model
{
    protected $connection = 'sqlsrv';

    public static function getFacilities($facility)    
    {  
        try {
            $query = "EXEC [udsp_Get_Data] ?,?,?,?,?";
            return DB::select($query, ['Get Owner InlinePlan', '', '', $facility, '']);
        } catch (\Exception $e) {
            throw new \Exception('Error executing stored procedure for facilities: ' . $e->getMessage());
        }
    }

    public static function getSummaryProgressLC($viewType, $startDate, $endDate, $relasi, $owners, $name)
    {
        try {
            //dd($viewType, $startDate, $endDate, $relasi, $owners, $name);
            $query = "SET NOCOUNT ON; EXEC [dbo].[Monitoring_Progress_LC_summary] ?, ?, ?, ?, ?, ?, ?";
            $ownersString = implode(';', array_map('strtoupper', $owners)); 
            return DB::select($query, ['PLANDELIVERY', $viewType,  $startDate, $endDate, $relasi, $ownersString, $name]);
            
        } catch (\Exception $e) {        
            throw new \Exception('Error executing stored procedure for Summary Progress LC: ' . $e->getMessage());
        }
    }


}