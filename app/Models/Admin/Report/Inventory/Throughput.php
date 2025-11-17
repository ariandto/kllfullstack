<?php

namespace App\Models\Admin\Report\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Throughput extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv';
    public static function getSummaryData($facilityID, $Relasi, $StartDate, $EndDate, $Owner, $ViewBy)
    {
        // dd($Owner);
        try {
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [sp_occupancyDC_V3] ?, ?, ?, ?, ?, ?, ?, ?, ?";
            return DB::select($query, ['Summary',$facilityID,$StartDate,$EndDate,'',$Owner,'',$Relasi,$ViewBy]);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
}
