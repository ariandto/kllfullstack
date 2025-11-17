<?php

namespace App\Models\Admin\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PrinterConfig extends Model
{
    use HasFactory;

    protected $table = 'TBPrinterConfig';
    // protected $primaryKey = 'id'; // Ganti jika beda
    public $timestamps = false;
}
