<?php

namespace App\Models\Admin\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PostUserView extends Model
{
        use HasFactory;
        // Tentukan koneksi database yang akan digunakan oleh model ini
        

        protected $connection = 'sqlsrv';
        // Data Ini Dari Controller
        public static function PostUserViewPage($facilityID, $pagename,$pagenamedetail,$userid,$RELASI,$ipaddress) 

        {
            try { 
                // Ensure there are no unclosed quotes or misplaced characters
                $query = "SET NOCOUNT ON; EXEC [sp_LogUsingFormUser] ?, ?, ?, ?, ? ,? ,? ,?";
                return DB::statement($query, [$facilityID,  $pagename, 'SCM Website', $pagenamedetail, $userid ,'Insert',$RELASI,$ipaddress]);
            } catch (\Exception $e) {
                // Log the error or handle it as needed
                throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
            }
        }

    
}
