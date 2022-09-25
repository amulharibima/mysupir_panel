<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style> 
        td{ mso-number-format:\@; }
        
        table, th, td {
            border: 1px solid;
        }

        .t1, .t2 { border-collapse: collapse; }
        .t1 > tbody > tr > td { border: 1px solid; }
        .t2 > tbody > tr > td { border: 1px solid; }

        .t3 td { white-space: nowrap; }
    </style>
</head>
<body>
<table class="t2 t3">
    <thead>
    <tr>
        <th><b>No</b></th>
        <th><b>Nama Driver</b></th>
        <th><b>Keterangan</b></th>
        <th><b>Pendapatan Trip</b></th>
        <th><b>Potongan Driver</b></th>
        <th><b>Pendapatan Driver</b></th>
        <th><b>Pencairan Dana</b></th>
        <th><b>Tanggal</b></th>
    </tr>
    </thead>
    <tbody>
    
    @php $total_pendapatan_trip = 0; @endphp
    @php $total_potongan = 0; @endphp
    @php $total_pendapatan = 0; @endphp
    @php $total_pencairan_dana = 0; @endphp

    @foreach($transactions as $k => $t)
    @php
        if($t->driver_id){
            $driver = $t->driver()->first();
            $nama_driver = $driver->name;
            $keterangan = 'Pencairan Dana';
            $pendapatan_trip = '';
            $potongan = '';
            $pendapatan = '';
            $pencairan_dana = $t->nominal;
        }
        else{
            $order = $t->order()->first();
            $driver = $order->driver()->first();

            $nama_driver = $driver->name;
            $keterangan = $order->identifier;
            $pendapatan_trip = $t->total_price;
            $potongan = $t->fee;
            $pendapatan = $pendapatan_trip - $potongan;

            if($pendapatan === 0)
                $pendapatan = '';
            
            $pencairan_dana = '';
        }
        

        setlocale(LC_TIME, 'id_ID');
        Carbon\Carbon::setLocale('id');

        $tanggal = Carbon\Carbon::parse($t->created_at)->formatLocalized('%d %B %Y');

        $total_pendapatan_trip += $pendapatan_trip === '' ? 0 : $pendapatan_trip;
        $total_potongan += $potongan === '' ? 0 : $potongan;
        $total_pendapatan += $pendapatan === '' ? 0 : $pendapatan;
        $total_pencairan_dana += $pencairan_dana === '' ? 0 : $pencairan_dana;

    @endphp
        <tr>
            <td>{{ $k + 1 }}</td>
            <td>{{ $nama_driver }}</td>
            <td>{{ $keterangan }}</td>
            <td data-format="Rp #,##0_-">{{ $pendapatan_trip }}</td>
            <td data-format="Rp #,##0_-">{{ $potongan }}</td>
            <td data-format="Rp #,##0_-">{{ $pendapatan }}</td>
            <td data-format="Rp #,##0_-">{{ $pencairan_dana }}</td>
            <td>{{ $tanggal }}</td>
        </tr>
    @endforeach
        <tr></tr>
        <tr>
            <td>TOTAL</td>
            <td></td>
            <td></td>
            <td data-format="Rp #,##0_-">{{$total_pendapatan_trip}}</td>
            <td data-format="Rp #,##0_-">{{$total_potongan}}</td>
            <td data-format="Rp #,##0_-">{{$total_pendapatan}}</td>
            <td data-format="Rp #,##0_-">{{$total_pencairan_dana}}</td>
        </tr>
    </tbody>
</table>
</body>
</html>