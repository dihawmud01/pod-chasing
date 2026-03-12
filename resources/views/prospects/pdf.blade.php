<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Prospects Report — {{ now()->format('d M Y') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9px;
            color: #333;
            background: #fff;
            padding: 10px;
        }

        /* ── HEADER ── */
        .pdf-header {
            margin-bottom: 20px;
            border-bottom: 2px solid #222;
            padding-bottom: 15px;
        }
        .pdf-header-top {
            display: table;
            width: 100%;
        }
        .header-left, .header-right {
            display: table-cell;
            vertical-align: top;
        }
        .header-right { text-align: right; }
        .pdf-title {
            font-size: 20px;
            font-weight: bold;
            color: #111;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .pdf-subtitle {
            font-size: 10px;
            color: #555;
            margin-top: 4px;
        }
        .pdf-meta {
            font-size: 9px;
            color: #666;
            line-height: 1.6;
        }

        /* ── FILTER STRING ── */
        .filter-info {
            margin-top: 10px;
            font-size: 10px;
            font-weight: 600;
            color: #444;
            background: #f9f9f9;
            padding: 6px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: inline-block;
        }

        /* ── STATS ROW ── */
        .stats-row {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-spacing: 10px 0;
            margin-left: -10px;
        }
        .stat-box {
            display: table-cell;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            width: 20%;
        }
        .stat-num {
            font-size: 16px;
            font-weight: bold;
            color: #111;
        }
        .stat-lbl {
            font-size: 8px;
            color: #777;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* ── TABLE ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        thead tr {
            border-bottom: 1px solid #333;
        }
        thead th {
            padding: 8px 6px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            color: #222;
        }
        tbody tr {
            border-bottom: 1px solid #eee;
        }
        tbody tr:nth-child(even) { background: #fafafa; }
        tbody td {
            padding: 7px 6px;
            font-size: 9px;
            vertical-align: top;
            color: #444;
        }
        td.vessel { font-weight: bold; color: #111; }

        /* Statuses (Minimalist) */
        .status-label {
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }
        .status-planning          { color: #5a7b9c; }
        .status-arranged          { color: #2e7d32; }
        .status-confirmed         { color: #2e7d32; } /* mapped from old status */
        .status-waiting_customers { color: #6a1b9a; }
        .status-customs           { color: #e65100; }
        .status-delayed           { color: #f57f17; }
        .status-cancelled         { color: #c62828; text-decoration: line-through; }
        .status-completed         { color: #00695c; }

        .notes-cell {
            color: #666;
            font-style: italic;
            font-size: 8.5px;
            max-width: 160px;
        }
        td.delivery-date { font-weight: bold; color: #111; }

        /* ── FOOTER ── */
        .pdf-footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            display: table;
            width: 100%;
            font-size: 8px;
            color: #888;
        }
        .footer-left { display: table-cell; text-align: left; }
        .footer-right { display: table-cell; text-align: right; }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="pdf-header">
        <div class="pdf-header-top">
            <div class="header-left">
                <div class="pdf-title">Prospects Report</div>
                <div class="pdf-subtitle">POD Chasing System — Logistics Division</div>
                <div class="filter-info">
                    {{ $filterLabel ?: 'Showing All Records' }}
                </div>
            </div>
            <div class="header-right">
                <div class="pdf-meta">
                    <div><strong>{{ $prospects->count() }}</strong> Total Records</div>
                    <div>Generated: {{ now()->format('d M Y, H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- STATS --}}
    @php
        $byStatus = $prospects->groupBy('status');
    @endphp
    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-num">{{ $prospects->count() }}</div>
            <div class="stat-lbl">Total Prospects</div>
        </div>
        <div class="stat-box">
            <div class="stat-num">{{ $byStatus->get('planning', collect())->count() }}</div>
            <div class="stat-lbl">Planning</div>
        </div>
        <div class="stat-box">
            <!-- Includes Arranged as well since Confirmed was renamed -->
            <div class="stat-num">{{ $byStatus->get('arranged', collect())->count() + $byStatus->get('confirmed', collect())->count() }}</div>
            <div class="stat-lbl">Arranged</div>
        </div>
        <div class="stat-box">
            <div class="stat-num">{{ $byStatus->get('delayed', collect())->count() }}</div>
            <div class="stat-lbl">Delayed</div>
        </div>
        <div class="stat-box">
            <div class="stat-num">{{ $byStatus->get('completed', collect())->count() + $byStatus->get('cancelled', collect())->count() }}</div>
            <div class="stat-lbl">Resolved (Done/Cancel)</div>
        </div>
    </div>

    {{-- TABLE --}}
    <table>
        <thead>
            <tr>
                <th style="width:20px;text-align:center;">#</th>
                <th>Vessel</th>
                <th>Port</th>
                <th>ETA</th>
                <th>ETB</th>
                <th>ETD</th>
                <th>Destination</th>
                <th>Forwarder</th>
                <th>Status</th>
                <th>Delivery Date</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @forelse($prospects as $i => $p)
            <tr>
                <td style="color:#aba;text-align:center;">{{ $i + 1 }}</td>
                <td class="vessel">{{ $p->vessel_name }}</td>
                <td>{{ $p->port ?? '—' }}</td>
                <td>{{ $p->eta ? $p->eta->format('d M y') : '—' }}</td>
                <td>{{ $p->etb ? $p->etb->format('d M y') : '—' }}</td>
                <td>{{ $p->etd ? $p->etd->format('d M y') : '—' }}</td>
                <td>{{ $p->destination_country ?? '—' }}</td>
                <td>{{ $p->forwarder ?? '—' }}</td>
                <td>
                    <span class="status-label status-{{ $p->status }}">{{ $p->statusLabel() }}</span>
                </td>
                <td class="{{ $p->delivery_date ? 'delivery-date' : '' }}">
                    @if($p->delivery_date)
                        {{ $p->delivery_date->format('d M y') }}
                        @if($p->delivery_date->format('H:i') !== '00:00')
                            <br><span style="font-size:7.5px;color:#888;font-weight:normal;">{{ $p->delivery_date->format('H:i') }}</span>
                        @endif
                    @else
                        —
                    @endif
                </td>
                <td class="notes-cell">{{ $p->notes ? \Illuminate\Support\Str::limit($p->notes, 80) : '—' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="11" style="text-align:center;padding:30px;color:#999;font-style:italic;">
                    No records found for the selected criteria.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- FOOTER --}}
    <div class="pdf-footer">
        <div class="footer-left">POD Chasing System</div>
        <div class="footer-right">Printed on {{ now()->format('d M Y \a\t H:i') }}</div>
    </div>

</body>
</html>
