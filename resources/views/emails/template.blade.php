<!DOCTYPE html>
<html>

<head>
    <title>{{ $details['title'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .email-container {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            max-width: 600px;
            margin: auto;
            background-color: #f9f9f9;
        }

        h2 {
            color: #333;
        }

        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
            text-align: left;
        }

        .footer p {
            margin: 5px 0;
        }

        ul {
            list-style-type: none;
            padding-left: 0;
        }

        li {
            margin-bottom: 15px;
            /* Jarak antar item lebih lebar */
        }

        strong {
            color: #333;
        }

        p {
            color: #333;
            line-height: 1.5;
        }

        /* Menambahkan Flexbox untuk mengatur jarak label dan nilai */
        .details-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .details-row .label {
            font-weight: bold;
            min-width: 150px;
            /* Lebar label tetap */
            margin-right: 10px;
            /* Jarak antara label dan nilai */
        }

        .details-row .value {
            flex: 1;
            /* Nilai akan mengisi sisa ruang */
        }
    </style>
</head>

<body>

    <div class="email-container">
        <h2>{{ $details['title'] }}</h2>

        <p>Kepada Yth,</p>
        <p>Dengan hormat,</p>

        <p>Kami ingin menginformasikan bahwa telah ada AR baru yang masuk untuk
            <strong>{{ $details['project_name'] }}</strong> dengan detail sebagai berikut:
        </p>

        <div class="details">
            <div class="details-row">
                <div class="label"><strong>No AR </strong></div>
                <div class="value">{{ $details['no_ar'] }}</div>
            </div>
            <div class="details-row">
                <div class="label"><strong>Nama Proyek </strong></div>
                <div class="value">{{ $details['project_name'] }}</div>
            </div>
            <div class="details-row">
                <div class="label"><strong>New Project Owner </strong></div>
                <div class="value">{{ $details['new_project_owner'] }}</div>
            </div>
            <div class="details-row">
                <div class="label"><strong>Parent Project ID </strong></div>
                <div class="value">{{ $details['parent_project_id'] }}</div>
            </div>
            <div class="details-row">
                <div class="label"><strong>Original Project Owner </strong></div>
                <div class="value">{{ $details['original_project_owner'] }}</div>
            </div>
            <div class="details-row">
                <div class="label"><strong>Email Karyawan </strong></div>
                <div class="value">{{ $details['email_karyawan'] }}</div>
            </div>
            <div class="details-row">
                <div class="label"><strong>Keterangan </strong></div>
                <div class="value">{{ $details['keterangan'] }}</div>
            </div>
        </div>

        <p>Mohon untuk melakukan tindak lanjut sesuai dengan prosedur yang berlaku.</p>
        <p>Apabila ada pertanyaan atau klarifikasi lebih lanjut, jangan ragu untuk menghubungi kami.</p>

        <p>Terima kasih atas perhatian dan kerjasamanya.</p>

        <div class="footer">
            <p>Hormat kami,</p>
            <p><strong>Logistic Innovation Executive</strong></p>
        </div>
    </div>

</body>

</html>
