<?php

namespace App\Models\Admin\Report\Outbound;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Support\Facades\DB;

class DemandInOutBacklog extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv';
    public static function getSummaryData($facilityID,  $StartDate, $EndDate, $Owner,$TypeOrder,  $ViewType, $ViewBy, $Relasi)
    {
        try {            
            // Menampilkan isi variabel dan menghentikan eksekusi
            //dd($facilityID, $StartDate, $EndDate, $Owner, $TypeOrder, $ViewType, $ViewBy, $Relasi);
    
            $query = "SET NOCOUNT ON; EXEC [sp_OrderInVsOut] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
            return DB::select($query, ['Summary',$facilityID,$StartDate,$EndDate,'1',$Owner,'',$TypeOrder,$ViewType, $ViewBy,$Relasi]);  
        } catch (\Exception $e) { 
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
}
