<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModelGetTicketLogin extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv';
    public static function getRole($UserID, $Rolee)
    {
        try {
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [Get_E_Ticket] ?, ?,? ,?";
            return DB::select($query, ['Get Role', $UserID, $Rolee, '']);
        } catch (\Exception $e) {

            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getTicket($UserID, $Rolee)
    {
        try {
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [Get_E_Ticket] ?, ?,? ,?";
            return DB::select($query, ['Get Ticket', $UserID, $Rolee, '']);
        } catch (\Exception $e) {

            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getDashboard($UserID, $Rolee)
    {
        try {
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [Get_E_Ticket] ?, ?,? ,?";
            return DB::select($query, ['Dashboard', $UserID, $Rolee, '']);
        } catch (\Exception $e) {

            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getDashboard1($Keberangkatan, $Bus, $Status)
    {
        try {
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [Get_E_Ticket] ?, ?,? ,?";
            return DB::select($query, ['Dashboard1', $Keberangkatan, $Bus, $Status]);
        } catch (\Exception $e) {

            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getKeberangkatan()
    {
        try {
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [Get_E_Ticket] ?, ?,? ,?";
            return DB::select($query, ['GetKeberangkatan', '', '', '']);
        } catch (\Exception $e) {

            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getBus($Keberangkatan)
    {
        try {
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [Get_E_Ticket] ?, ?,? ,?";
            return DB::select($query, ['GetBus', $Keberangkatan, '', '']);
        } catch (\Exception $e) {

            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }

    public static function getStatusTicket($Rolee)
    {
        try {
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [Get_E_Ticket] ?, ?,? ,?";
            return DB::select($query, ['Chek Status', $Rolee, '', '']);
        } catch (\Exception $e) {

            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
    public static function postTicket($UserID, $Rolee)
    {
        try {
            // Ensure there are no unclosed quotes or misplaced characters
            $query = "SET NOCOUNT ON; EXEC [Get_E_Ticket] ?, ?,? ,?";
            return DB::statement($query, ['Scan', $UserID, $Rolee, '']);
        } catch (\Exception $e) {

            throw new \Exception('Error executing stored procedure: ' . $e->getMessage());
        }
    }
}
