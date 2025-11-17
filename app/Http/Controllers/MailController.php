<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;

class MailController extends Controller
{
    public function sendEmail()
    {
        // Mengatur detail email, ini bisa diambil dari database atau request
        $details = [
            'title' => 'Information New Application Request - ' . 'No AR Example', // Subject
            'no_ar' => '12345',
            'nama_perusahaan' => 'HCI JABABEKA',
            'nik' => '12345678',
            'nama_karyawan' => 'Rudi Gunawan',
            'email_karyawan' => 'rudi.gunawan@kawanlamacorp.com',
            'keterangan' => 'Deskripsi keterangan terkait AR'
        ];

        // Mengirim email
        Mail::to('rudi.gunawan@kawanlamacorp.com')->send(new SendEmail($details));

        return "Email berhasil dikirim!";
    }
}
