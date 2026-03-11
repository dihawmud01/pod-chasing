@extends('layouts.app')

@section('title', 'Prospects')

@section('styles')
<style>
    .badge-planning          { background: rgba(99,130,200,.2);  color: #8aaff5; border: 1px solid rgba(99,130,200,.3); }
    .badge-confirmed         { background: rgba(39,174,96,.2);   color: #4cd98a; border: 1px solid rgba(39,174,96,.35); }
    .badge-waiting_customers { background: rgba(180,100,240,.2); color: #d08cf5; border: 1px solid rgba(180,100,240,.3); }
    .badge-customs           { background: rgba(240,150,40,.2);  color: #f5a842; border: 1px solid rgba(240,150,40,.35); }
    .badge-delayed           { background: rgba(240,180,41,.2);  color: #f5c842; border: 1px solid rgba(240,180,41,.35); }
    .badge-cancelled         { background: rgba(224,82,82,.2);   color: #f07070; border: 1px solid rgba(224,82,82,.3); }
    .badge-completed         { background: rgba(0,201,177,.15);  color: #00c9b1; border: 1px solid rgba(0,201,177,.3); }

    .notes-preview {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: var(--text-dim);
        font-size: .8rem;
        font-style: italic;
    }
    .filter-bar { display: flex; align-items: center; gap: .75rem; flex-wrap: wrap; }
    .filter-bar select {
        background: var(--navy-row);
        border: 1px solid var(--border);
        color: var(--text);
        border-radius: 8px;
        padding: .45rem .9rem;
        font-family: inherit;
        font-size: .85rem;
        cursor: pointer;
        outline: none;
    }
    .filter-bar select:focus { border-color: var(--teal); }
    .btn-purple { background: rgba(167,139,250,.15); border: 1px solid rgba(167,139,250,.35); color: #c4b5fd; }
    .btn-purple:hover { background: rgba(167,139,250,.28); transform: translateY(-1px); }
</style>
@endsection

@section('content')

@php $dateFormatted = \Carbon\Carbon::parse($date)->format('d F Y'); @endphp

<div class="toolbar">
    <h2>
        <i class="fas fa-binoculars" style="color:var(--teal)"></i> Prospects
        <small>{{ $dateFormatted }} &nbsp;·&nbsp; {{ $prospects->count() }} records</small>
    </h2>

    <div class="filter-bar">
        <form method="GET" action="{{ route('prospects.index') }}"
              style="display:flex;gap:.6rem;align-items:center;flex-wrap:wrap;" id="filterForm">

            {{-- Date picker — filters by prospect_date --}}
            <input type="date" name="date" class="input-date"
                   value="{{ $date }}"
                   onchange="document.getElementById('filterForm').submit()">

            {{-- Status filter --}}
            <select name="status" onchange="document.getElementById('filterForm').submit()">
                <option value="">All Statuses</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </form>

        {{-- Export PDF (preserves active date + status filters) --}}
        <a href="{{ route('prospects.exportPdf', array_filter(['date' => $date, 'status' => request('status')])) }}"
           class="btn btn-purple" target="_blank">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>

        <a href="{{ route('prospects.create', ['date' => $date]) }}" class="btn btn-teal">
            <i class="fas fa-plus"></i> Add Prospect
        </a>
    </div>
</div>

<div class="table-wrap">
    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Vessel</th>
                    <th>Port</th>
                    <th>ETA</th>
                    <th>ETB</th>
                    <th>ETD</th>
                    <th>Destination</th>
                    <th>Transport Company</th>
                    <th>Status</th>
                    <th>Delivery Date</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prospects as $i => $prospect)
                <tr>
                    <td style="color:var(--text-dim);font-size:.78rem;">{{ $i + 1 }}</td>
                    <td><div class="vessel-name">{{ $prospect->vessel_name }}</div></td>
                    <td>{{ $prospect->port ?? '—' }}</td>
                    <td>{{ $prospect->eta ? $prospect->eta->format('d M Y') : '—' }}</td>
                    <td>{{ $prospect->etb ? $prospect->etb->format('d M Y') : '—' }}</td>
                    <td>{{ $prospect->etd ? $prospect->etd->format('d M Y') : '—' }}</td>
                    <td>{{ $prospect->destination_country ?? '—' }}</td>
                    <td>{{ $prospect->forwarder ?? '—' }}</td>
                    <td>
                        <span class="badge badge-{{ $prospect->status }}">
                            {{ $prospect->statusLabel() }}
                        </span>
                    </td>
                    <td>
                        @if($prospect->delivery_date)
                            <span style="color:var(--gold);font-weight:600;font-size:.85rem;">
                                <i class="fas fa-calendar-check" style="font-size:.75rem;"></i>
                                {{ $prospect->delivery_date->format('d M Y') }}
                            </span>
                        @else
                            <span style="color:var(--text-dim);font-size:.8rem;">Not set</span>
                        @endif
                    </td>
                    <td>
                        <div class="notes-preview" title="{{ $prospect->notes }}">
                            {{ $prospect->notes ?: '—' }}
                        </div>
                    </td>
                    <td>
                        <div style="display:flex;gap:.35rem;flex-wrap:wrap;align-items:center;">
                            <a href="{{ route('prospects.edit', $prospect) }}"
                               class="btn btn-outline btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            @if($prospect->delivery_date && $prospect->status !== 'cancelled' && $prospect->status !== 'completed')
                            <form method="POST" action="{{ route('prospects.createDelivery', $prospect) }}"
                                  onsubmit="return confirm('Create delivery from {{ addslashes($prospect->vessel_name) }}?')">
                                @csrf
                                <button type="submit" class="btn btn-gold btn-sm">
                                    <i class="fas fa-truck"></i> Create Delivery
                                </button>
                            </form>
                            @elseif(!$prospect->delivery_date)
                                <span style="font-size:.72rem;color:var(--text-dim);font-style:italic;">
                                    Set delivery date first
                                </span>
                            @endif

                            <form method="POST" action="{{ route('prospects.destroy', $prospect) }}"
                                  onsubmit="return confirm('Delete this prospect?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="12">
                        <div class="no-data">
                            <i class="fas fa-binoculars"></i>
                            No prospects found for {{ $dateFormatted }}.
                            <div style="margin-top:.75rem;">
                                <a href="{{ route('prospects.create', ['date' => $date]) }}" class="btn btn-teal">
                                    <i class="fas fa-plus"></i> Add Prospect for this Date
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
