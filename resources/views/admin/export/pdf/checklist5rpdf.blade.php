<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Checklist 5R</title>

    <style>
        /* Atur lebar kolom */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        table th:nth-child(1),
        table td:nth-child(1),
        /* Dept */
        table th:nth-child(2),
        table td:nth-child(2),
        /* Area */
        table th:nth-child(3),
        table td:nth-child(3),
        /* Attribute */
        table th:nth-child(5),
        table td:nth-child(5),
        /* Point */
        table th:nth-child(6),
        table td:nth-child(6)

        /* Checker */
            {
            width: 10%;
            /* Kolom lebih kecil */
        }

        table th:nth-child(4),
        table td:nth-child(4) {
            /* Kondisi */
            width: 40%;
            /* Kolom Kondisi lebih lebar */
        }

        /* Table styles */
        table img {
            max-width: 100px;
            max-height: auto;
        }

        /* Judul di tengah */
        h1 {
            text-align: center;
        }

        .info-row {
            display: flex;
            align-items: center;
            /* Menjaga agar semua elemen sejajar vertikal */
        }

        .info-label {
            margin-right: 5px;
            /* Menambahkan sedikit jarak antara label dan titik dua */
        }

        .info-colon {
            margin-right: 5px;
            /* Menambahkan sedikit jarak antara titik dua dan nilai */
        }

        .info-value {
            margin-left: 10px;
            /* Menambahkan jarak lebih agar nilai lebih terpisah dari titik dua */
            font-weight: bold;
            /* Opsional, jika ingin nilai tampil lebih tebal */
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="mb-4 text-center">Checklist 5R</h1>
        <h1 class="mb-4 text-center"></h1>


        <div class="info-label">Tanggal &nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;
            @if (!empty($data1) && isset($data1[0]['AddDate']))
                {{ \Carbon\Carbon::parse($data1[0]['AddDate'])->format('Y-m-d') }}
            @else
                Tanggal tidak tersedia
            @endif
        </div>



        <div class="info-label">Owner&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; : &nbsp;&nbsp;

            @if (!empty($data1) && isset($data1[0]['Owner']))
                {{ $data1[0]['Owner'] }}
            @else
                Owner tidak tersedia
            @endif
        </div>





        <!-- Table Display -->
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>Dept</th>
                    <th>Area</th>
                    <th>Attribute</th>
                    <th>Kondisi</th>
                    <th>Point</th>
                    {{-- <th>Final Point</th> --}}
                    {{-- <th>Shift</th>
                    <th>Check Point</th> --}}
                    <th>Checker</th>
                    <th>Foto 1</th>
                    <th>Foto 2</th>
                    <th>Foto 3</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $lastDept = null;
                    $lastArea = null;
                    $deptRowspan = 1;
                    $areaRowspan = 1;
                @endphp

                @foreach ($data1 as $index => $row)
                    @if (is_array($row))
                        @php

                            // Handle Dept rowspan
                            if ($lastDept !== $row['Dept']) {
                                if ($lastDept !== null) {
                                    $deptRowspan--; // Decrease rowspan for the previous row if it has the same Dept value
                                }
                                $deptRowspan = 1; // Reset Dept rowspan counter when the Dept value changes
                            } else {
                                $deptRowspan++; // Increment Dept rowspan counter if the Dept value is the same
                            }

                            // Handle Area rowspan
                            if ($lastArea !== $row['Area']) {
                                if ($lastArea !== null) {
                                    $areaRowspan--; // Decrease rowspan for the previous row if it has the same Area value
                                }
                                $areaRowspan = 1; // Reset Area rowspan counter when the Area value changes
                            } else {
                                $areaRowspan++; // Increment Area rowspan counter if the Area value is the same
                            }
                        @endphp

                        <tr>
                            <!-- Merge Dept -->
                            @if ($lastDept !== $row['Dept'])
                                <td rowspan="{{ $deptRowspan }}">{{ $row['Dept'] }}</td>
                            @else
                                <td></td> <!-- Empty cell if Dept value is the same as last -->
                            @endif

                            <!-- Merge Area cell if the Area value is different from last one -->
                            @if ($lastArea !== $row['Area'])
                                <td rowspan="{{ $areaRowspan }}">{{ $row['Area'] }}</td>
                            @else
                                <td></td> <!-- Empty cell if Area value is the same as last -->
                            @endif

                            <!-- Other Columns -->
                            <td>{{ $row['Attribute'] ?? 'No Attribute' }}</td>
                            <td>{{ $row['Kondisi'] ?? 'No Kondisi' }}</td>
                            <td style="text-align: right;">{{ $row['Point'] ?? 'No Point' }}</td>
                            {{-- <td>{{ $row['FinalPoint'] ?? 'No Final Point' }}</td> --}}
                            {{-- <td>{{ $row['Shift'] ?? 'No Shift' }}</td>
                        <td>{{ $row['CheckPoint'] ?? 'No CheckPoint' }}</td> --}}
                            <td>{{ $row['Checker'] ?? 'No Checker' }}</td>

                            <!-- Images -->
                            <td>
                                @if ($row['Doc1'] !== 'No Image')
                                    <img src="{{ $row['Doc1'] }}" alt="Doc1"
                                        style="max-width: 150px; max-height: 150px;">
                                @else
                                    <span>No Image</span>
                                @endif
                            </td>
                            <td>
                                @if ($row['Doc2'] !== 'No Image')
                                    <img src="{{ $row['Doc2'] }}" alt="Doc2"
                                        style="max-width: 150px; max-height: 150px;">
                                @else
                                    <span>No Image</span>
                                @endif
                            </td>
                            <td>
                                @if ($row['Doc3'] !== 'No Image')
                                    <img src="{{ $row['Doc3'] }}" alt="Doc3"
                                        style="max-width: 150px; max-height: 150px;">
                                @else
                                    <span>No Image</span>
                                @endif
                            </td>
                        </tr>
                        </tr>
                        @php
                            $lastDept = $row['Dept'];
                            $lastArea = $row['Area'];
                        @endphp
                    @else
                        <!-- Tindakan jika $row bukan array -->
                        <tr>
                            <td colspan="12">data1 tidak valid</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>


</body>

</html>
