<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Prospects Export — {{ now()->format('d M Y') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Arial, sans-serif;
            font-size: 9px;
            color: #1a2540;
            background: #fff;
        }

        /* ── HEADER ── */
        .pdf-header {
            background: linear-gradient(135deg, #0a1e42 0%, #0d2a5c 100%);
            color: #fff;
            padding: 14px 20px;
            margin-bottom: 14px;
            border-radius: 6px;
        }
        .pdf-header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .pdf-title {
            font-size: 18px;
            font-weight: bold;
            color: #00c9b1;
            letter-spacing: 0.5px;
        }
        .pdf-subtitle {
            font-size: 10px;
            color: #8fb8e8;
            margin-top: 2px;
        }
        .pdf-meta {
            text-align: right;
            font-size: 8px;
            color: #a0c0e0;
            line-height: 1.6;
        }

        /* ── FILTER BADGE ── */
        .filter-info {
            display: inline-block;
            background: rgba(0,201,177,.15);
            border: 1px solid rgba(0,201,177,.4);
            color: #00c9b1;
            border-radius: 12px;
            padding: 3px 10px;
            font-size: 8px;
            font-weight: bold;
            margin-top: 6px;
        }

        /* ── STATS ROW ── */
        .stats-row {
            display: table;
            width: 100%;
            margin-bottom: 12px;
            border-spacing: 6px 0;
        }
        .stat-box {
            display: table-cell;
            background: #f4f7fc;
            border: 1px solid #dce6f5;
            border-radius: 5px;
            padding: 7px 10px;
            text-align: center;
            width: 20%;
        }
        .stat-num {
            font-size: 16px;
            font-weight: bold;
            color: #0a1e42;
            line-height: 1;
        }
        .stat-lbl {
            font-size: 7.5px;
            color: #5a7090;
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .stat-box.planning  .stat-num { color: #4a7de0; }
        .stat-box.confirmed .stat-num { color: #27ae60; }
        .stat-box.delayed   .stat-num { color: #e0a020; }
        .stat-box.cancelled .stat-num { color: #e05252; }
        .stat-box.completed .stat-num { color: #00a896; }

        /* ── TABLE ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }
        thead tr {
            background: #0d2a5c;
            color: #fff;
        }
        thead th {
            padding: 7px 6px;
            text-align: left;
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }
        tbody tr:nth-child(even) { background: #f4f7fc; }
        tbody tr:nth-child(odd)  { background: #ffffff; }
        tbody td {
            padding: 5px 6px;
            font-size: 8.5px;
            border-bottom: 1px solid #e8eef8;
            vertical-align: top;
        }
        td.vessel { font-weight: bold; font-size: 9px; color: #0a1e42; }

        /* Status badges */
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 10px;
            font-size: 7.5px;
            font-weight: bold;
        }
        .badge-planning  { background: #dce8ff; color: #2a5fc0; }
        .badge-confirmed { background: #d4f4e4; color: #1a7a45; }
        .badge-delayed   { background: #fff0cc; color: #a06800; }
        .badge-cancelled { background: #fde0e0; color: #b53030; }
        .badge-completed { background: #ccf5ef; color: #007a6a; }

        /* Notes */
        .notes-cell {
            color: #5a7090;
            font-style: italic;
            font-size: 8px;
            max-width: 140px;
        }
        td.delivery-date {
            font-weight: bold;
            color: #c07000;
        }

        /* ── FOOTER ── */
        .pdf-footer {
            margin-top: 14px;
            padding-top: 8px;
            border-top: 1px solid #dce6f5;
            display: flex;
            justify-content: space-between;
            font-size: 7.5px;
            color: #8a9ec0;
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="pdf-header">
        <div class="pdf-header-top">
            <div>
                <div class="pdf-title">🔭 Prospects Report</div>
                <div class="pdf-subtitle">POD Chasing — Logistics Division | Shipment Planning</div>
                @if($filterLabel !== 'All')
                    <div class="filter-info">Filter: {{ $filterLabel }}</div>
                @else
                    <div class="filter-info">Showing: All Statuses</div>
                @endif
            </div>
            <div class="pdf-meta">
                <div style="font-size:11px;font-weight:bold;color:#fff;">{{ $prospects->count() }} Records</div>
                <div>Generated: {{ now()->format('d M Y, H:i') }}</div>
                <div>POD Chasing System</div>
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
        <div class="stat-box planning">
            <div class="stat-num">{{ $byStatus->get('planning', collect())->count() }}</div>
            <div class="stat-lbl">Planning</div>
        </div>
        <div class="stat-box confirmed">
            <div class="stat-num">{{ $byStatus->get('confirmed', collect())->count() }}</div>
            <div class="stat-lbl">Confirmed</div>
        </div>
        <div class="stat-box delayed">
            <div class="stat-num">{{ $byStatus->get('delayed', collect())->count() }}</div>
            <div class="stat-lbl">Delayed</div>
        </div>
        <div class="stat-box completed">
            <div class="stat-num">{{ $byStatus->get('completed', collect())->count() + $byStatus->get('cancelled', collect())->count() }}</div>
            <div class="stat-lbl">Completed / Cancelled</div>
        </div>
    </div>

    {{-- TABLE --}}
    <table>
        <thead>
            <tr>
                <th style="width:22px">#</th>
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
                <td style="color:#8a9ec0;text-align:center;">{{ $i + 1 }}</td>
                <td class="vessel">{{ $p->vessel_name }}</td>
                <td>{{ $p->port ?? '—' }}</td>
                <td>{{ $p->eta ? $p->eta->format('d M Y') : '—' }}</td>
                <td>{{ $p->etb ? $p->etb->format('d M Y') : '—' }}</td>
                <td>{{ $p->etd ? $p->etd->format('d M Y') : '—' }}</td>
                <td>{{ $p->destination_country ?? '—' }}</td>
                <td>{{ $p->forwarder ?? '—' }}</td>
                <td>
                    <span class="badge badge-{{ $p->status }}">{{ $p->statusLabel() }}</span>
                </td>
                <td class="{{ $p->delivery_date ? 'delivery-date' : '' }}">
                    {{ $p->delivery_date ? $p->delivery_date->format('d M Y') : '—' }}
                </td>
                <td class="notes-cell">{{ $p->notes ? \Illuminate\Support\Str::limit($p->notes, 80) : '—' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="11" style="text-align:center;padding:20px;color:#8a9ec0;font-style:italic;">
                    No prospects found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- FOOTER --}}
    <div class="pdf-footer">
        <span>POD Chasing — Logistics Division Internal Tool</span>
        <span>Generated on {{ now()->format('d F Y \a\t H:i:s') }}</span>
    </div>

</body>
</html>
