<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Report - {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; padding: 20px; background: #fff; color: #111; }
        h2  { text-align: center; font-size: 15pt; margin-bottom: 4px; }
        .sub { text-align: center; font-size: 10pt; color: #555; margin-bottom: 16px; }
        table { border-collapse: collapse; width: 100%; font-size: 9pt; }
        th, td { border: 1px solid #333; padding: 6px 8px; }
        thead th { background: #1a2e5a; color: #fff; text-align: center; font-size: 9pt; }
        tbody tr:nth-child(even) { background: #f5f5f5; }
        td.center { text-align: center; }
        .check { color: #1a7a40; font-weight: bold; }
        .cross { color: #999; }
        .badge-delivered   { color: #1a7a40; font-weight: bold; }
        .badge-waiting-pod { color: #c88000; font-weight: bold; }
        .badge-followup    { color: #1a3c8a; }
        .badge-mariano     { color: #6a1a9a; }
        .badge-customs     { color: #c88000; }
        .badge-other       { color: #555; }
        @media print {
            body { padding: 10px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <h2>Delivery Report — {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</h2>
    <div class="sub">Total: {{ $vessels->count() }} vessels &nbsp;·&nbsp; Delivered: {{ $vessels->where('delivered',true)->count() }} &nbsp;·&nbsp; POD: {{ $vessels->where('pod_status',true)->count() }}</div>

    <table>
        <thead>
            <tr>
                <th style="width:32px">No.</th>
                <th>Vessel</th>
                <th>Driver / Transport</th>
                <th>Address</th>
                <th>Information</th>
                <th style="width:60px">Delivered</th>
                <th style="width:40px">POD</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vessels as $i => $v)
            @php
                $info  = $v->information ?? '';
                $lower = strtolower($info);
                if      ($info === 'Delivered, POD Received')               $cls = 'badge-delivered';
                elseif  ($info === 'Delivered, Waiting for POD')             $cls = 'badge-waiting-pod';
                elseif  ($info === 'Followed up, waiting next information')  $cls = 'badge-followup';
                elseif  ($info === "Waiting POD on custom's mail")           $cls = 'badge-customs';
                else                                                         $cls = 'badge-other';
            @endphp
            <tr>
                <td class="center">{{ $i + 1 }}.</td>
                <td><strong>{{ $v->vessel_name }}</strong></td>
                <td>{{ $v->driver }}</td>
                <td>{{ $v->delivery_address }}</td>
                <td class="{{ $cls }}">{{ $info }}</td>
                <td class="center">{{ $v->delivered    ? '✅' : '–' }}</td>
                <td class="center">{{ $v->pod_status   ? '✅' : '–' }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="7" style="text-align:center;color:#999;font-size:8pt;padding:8px">
                    Generated {{ now()->format('d M Y H:i') }}
                </td>
            </tr>
        </tbody>
    </table>

    <p class="no-print" style="text-align:center;margin-top:16px">
        <a href="javascript:window.close()" style="font-size:10pt;color:#1a2e5a">← Close</a>
    </p>
</body>
</html>
