<?php

namespace App\Models\Admin\Report\Public;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModelReportChecklist5R extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv';

    public static function getDataView($facilityID,$Relasi,$Owner, $StartDate, $EndDate)
    {
       
        try {
            set_time_limit(300);
            
           // dd($facilityID,$Relasi,$Owner, $StartDate, $EndDate, $Dept, $Area, $Status);
            // $OwnerString = implode(';', $Owner);
        
            // Menggunakan koneksi yang ditentukan
            $connection = DB::connection('sqlsrv')->getPdo();
            //exec [udsp_Report5R] 'WMWHSE2','WMWHSE2RTL','AHI','2024-10-18','2024-10-18'
            // Menyiapkan query untuk mengeksekusi stored procedure
            $query = "SET NOCOUNT ON; EXEC [udsp_Report5R] ?, ?, ?, ?, ?";
            $stmt = $connection->prepare($query);
            $stmt->execute([
                $facilityID, $Relasi, $Owner, $StartDate, $EndDate
            ]);
        
            // Mengambil hasil dari ketiga tabel
            $results = [];
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel pertama
            $stmt->nextRowset(); // Pindah ke rowset kedua
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel kedua
            $stmt->nextRowset(); // Pindah ke rowset ketiga
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel ketiga
        
            // Mengambil header untuk masing-masing tabel
            $headers1 = array_keys($results[0][0] ?? []); // Header untuk tabel pertama
            $headers2 = array_keys($results[1][0] ?? []); // Header untuk tabel kedua
            $headers3 = array_keys($results[2][0] ?? []); // Header untuk tabel kedua
          
            
           // dd($results[0] ?? [],$results[1] ?? []);

            return [
                'tabel1' => $results[0] ?? [],
                'tabel2' => $results[1] ?? [],
                'tabel3' => $results[2] ?? [], 
                'headers1' => $headers1,
                'headers2' => $headers2,
                'headers3' => $headers3,
            ];
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
        
    }

    public static function getDataViewExportPDF($facilityID,$Relasi,$Owner, $Dept, $StartDate)
    {
       
        try {
            set_time_limit(300);
            
           // dd($facilityID,$Relasi,$Owner, $StartDate, $EndDate, $Dept, $Area, $Status);
            // $OwnerString = implode(';', $Owner);
        
            // Menggunakan koneksi yang ditentukan
            $connection = DB::connection('sqlsrv')->getPdo();
            //exec [udsp_Report5R] 'WMWHSE2','WMWHSE2RTL','AHI','2024-10-18','2024-10-18'
            // Menyiapkan query untuk mengeksekusi stored procedure
            $query = "SET NOCOUNT ON; EXEC [udsp_Export_Report5R] ?, ?, ?, ?, ?"; 
            $stmt = $connection->prepare($query);
            $stmt->execute([
                $facilityID, $Relasi, $Owner, $Dept, $StartDate
            ]);
        
            // Mengambil hasil dari ketiga tabel
            $results = [];
            $results[] = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Tabel pertama 
           
            // Mengambil header untuk masing-masing tabel
            $headers1 = array_keys($results[0][0] ?? []); // Header untuk tabel pertama
       
           // dd($results[0] ?? [],$results[1] ?? []);

            return [
                'tabel1' => $results[0] ?? [], 
                'headers1' => $headers1,
              
            ];
        } catch (\Exception $e) {
            // Tangani exception dengan log atau menampilkan pesan error
            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
        



    }

 // try {
        //     // Ensure there are no unclosed quotes or misplaced characters
        //     $query = "SET NOCOUNT ON; EXEC [udsp_Dashboard5R] ?, ?, ?, ?, ?, ?, ?, ?";
        //     return DB::select($query, [$facilityID,$Relasi,$Owner, $StartDate, $EndDate, $Dept, $Area, $Status]);
        // } catch (\Exception $e) {
          
        //     throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        // }


// EXEC [udsp_Dashboard5R] 'WMWHSE5','WMWHSE5RTL','2024-10-01','2024-10-17','ALL','ALL','ALL'
    // public static function insertDataView($facilityID,$Relasi,$Shift, $Start1, $End1, $Start2, $End2)
    // {
    //     try {
    //         //dd($facilityID,$Relasi,$Shift, $Start1, $End1, $Start2, $End2);
    //         $query = "SET NOCOUNT ON; EXEC [udsp_MasterShiftPoint5R] ?, ?, ?, ?, ?, ?, ?, ?";
    //         return DB::statement($query, ['Insert',$facilityID,$Relasi,$Shift, $Start1, $End1, $Start2, $End2]);
    //     } catch (\Exception $e) {
    //         // Log the error or handle it as needed
    //         throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
    //     }
    // } 

   
}

