@extends('layouts.app')

@section('title', 'Prospects')

@section('styles')
<style>
    /* ── Status badges ── */
    .badge-planning          { background: rgba(99,130,200,.2);  color: #8aaff5; border: 1px solid rgba(99,130,200,.3); }
    .badge-confirmed         { background: rgba(39,174,96,.2);   color: #4cd98a; border: 1px solid rgba(39,174,96,.35); }
    .badge-waiting_customers { background: rgba(180,100,240,.2); color: #d08cf5; border: 1px solid rgba(180,100,240,.3); }
    .badge-customs           { background: rgba(240,150,40,.2);  color: #f5a842; border: 1px solid rgba(240,150,40,.35); }
    .badge-delayed           { background: rgba(240,180,41,.2);  color: #f5c842; border: 1px solid rgba(240,180,41,.35); }
    .badge-cancelled         { background: rgba(224,82,82,.2);   color: #f07070; border: 1px solid rgba(224,82,82,.3); }
    .badge-completed         { background: rgba(0,201,177,.15);  color: #00c9b1; border: 1px solid rgba(0,201,177,.3); }

    /* ── Inline status select ── */
    .status-select {
        border: none; outline: none; cursor: pointer;
        border-radius: 20px; padding: 3px 8px 3px 10px;
        font-size: .78rem; font-weight: 600; font-family: inherit;
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23aaa'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 8px center;
        padding-right: 24px;
        transition: opacity .15s;
    }
    .status-select:hover { opacity: .85; }
    .status-select.saving { opacity: .5; pointer-events: none; }
    .status-select.badge-planning          { background-color: rgba(99,130,200,.2);  color: #8aaff5; }
    .status-select.badge-confirmed         { background-color: rgba(39,174,96,.2);   color: #4cd98a; }
    .status-select.badge-waiting_customers { background-color: rgba(180,100,240,.2); color: #d08cf5; }
    .status-select.badge-customs           { background-color: rgba(240,150,40,.2);  color: #f5a842; }
    .status-select.badge-delayed           { background-color: rgba(240,180,41,.2);  color: #f5c842; }
    .status-select.badge-cancelled         { background-color: rgba(224,82,82,.2);   color: #f07070; }
    .status-select.badge-completed         { background-color: rgba(0,201,177,.15);  color: #00c9b1; }

    /* ── Alert panel ── */
    .alert-panel { margin-bottom: 1.25rem; display: flex; flex-direction: column; gap: .6rem; }
    .alert-item {
        display: flex; align-items: flex-start; gap: .75rem;
        padding: .7rem 1rem; border-radius: 10px; font-size: .82rem; line-height: 1.4;
    }
    .alert-item i { margin-top: .1rem; font-size: 1rem; flex-shrink: 0; }
    .alert-danger  { background: rgba(224,82,82,.12);  border: 1px solid rgba(224,82,82,.3);  color: #f07070; }
    .alert-warning { background: rgba(240,180,41,.1);  border: 1px solid rgba(240,180,41,.3); color: #f5c842; }
    .alert-item a  { color: inherit; text-decoration: underline; }

    /* ── Filter bar ── */
    .filter-bar { display: flex; align-items: center; gap: .75rem; flex-wrap: wrap; }
    .filter-bar select {
        background: var(--navy-row); border: 1px solid var(--border); color: var(--text);
        border-radius: 8px; padding: .45rem .9rem; font-family: inherit;
        font-size: .85rem; cursor: pointer; outline: none;
    }
    .filter-bar select:focus { border-color: var(--teal); }
    .btn-purple { background: rgba(167,139,250,.15); border: 1px solid rgba(167,139,250,.35); color: #c4b5fd; }
    .btn-purple:hover { background: rgba(167,139,250,.28); transform: translateY(-1px); }

    .notes-preview {
        max-width: 180px; white-space: nowrap; overflow: hidden;
        text-overflow: ellipsis; color: var(--text-dim); font-size: .8rem; font-style: italic;
    }
    .datetime-val { display: flex; flex-direction: column; gap: 1px; }
    .datetime-val .dv-date { font-size: .82rem; }
    .datetime-val .dv-time { font-size: .72rem; color: var(--text-dim); font-family: monospace; }

    /* Customs note inline */
    .customs-inline-wrap { margin-top: 4px; }
    .customs-input {
        background: rgba(240,150,40,.1); border: 1px solid rgba(240,150,40,.3);
        color: #f5a842; border-radius: 8px; padding: 3px 8px; font-size: .75rem;
        font-family: inherit; outline: none; width: 130px;
    }
    .customs-input:focus { border-color: #f5a842; }
</style>
@endsection

@section('content')
@php
    $dateFormatted = \Carbon\Carbon::parse($date)->format('d F Y');
    $csrfToken = csrf_token();
@endphp

{{-- ── ALERT PANEL ── --}}
@if($overdueProspects->isNotEmpty() || $confirmedNoDates->isNotEmpty())
<div class="alert-panel">

    @foreach($overdueProspects as $op)
    <div class="alert-item alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <div>
            <strong>Delivery Overdue</strong> —
            <a href="{{ route('prospects.edit', $op) }}">{{ $op->vessel_name }}</a>
            &nbsp;<span class="badge badge-{{ $op->status }}">{{ $op->statusLabel() }}</span>
            &nbsp;· Delivery date was
            <strong>{{ $op->delivery_date->format('d M Y') }}</strong>
            ({{ $op->delivery_date->diffForHumans() }})
        </div>
    </div>
    @endforeach

    @foreach($confirmedNoDates as $cp)
    <div class="alert-item alert-warning">
        <i class="fas fa-calendar-times"></i>
        <div>
            <strong>Confirmed — No Delivery Date</strong> —
            <a href="{{ route('prospects.edit', $cp) }}">{{ $cp->vessel_name }}</a>
            &nbsp;· Prospect date: {{ $cp->prospect_date->format('d M Y') }}
        </div>
    </div>
    @endforeach

</div>
@endif

{{-- ── TOOLBAR ── --}}
<div class="toolbar">
    <h2>
        <i class="fas fa-binoculars" style="color:var(--teal)"></i> Prospects
        <small>{{ $dateFormatted }} &nbsp;·&nbsp; {{ $prospects->count() }} records</small>
    </h2>
    <div class="filter-bar">
        <form method="GET" action="{{ route('prospects.index') }}"
              style="display:flex;gap:.6rem;align-items:center;flex-wrap:wrap;" id="filterForm">
            <input type="date" name="date" class="input-date"
                   value="{{ $date }}"
                   onchange="document.getElementById('filterForm').submit()">
            <select name="status" onchange="document.getElementById('filterForm').submit()">
                <option value="">All Statuses</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('prospects.exportPdf', array_filter(['date' => $date, 'status' => request('status')])) }}"
           class="btn btn-purple" target="_blank">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
        <a href="{{ route('prospects.create', ['date' => $date]) }}" class="btn btn-teal">
            <i class="fas fa-plus"></i> Add Prospect
        </a>
    </div>
</div>

{{-- ── TABLE ── --}}
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
                    <th>Transport Co.</th>
                    <th style="min-width:170px;">Status</th>
                    <th>Delivery Date</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prospects as $i => $prospect)
                <tr id="row-{{ $prospect->id }}" class="{{ $prospect->isDeliveryOverdue() ? 'row-warning' : '' }}">
                    <td style="color:var(--text-dim);font-size:.78rem;">{{ $i + 1 }}</td>
                    <td><div class="vessel-name">{{ $prospect->vessel_name }}</div></td>
                    <td>{{ $prospect->port ?? '—' }}</td>

                    {{-- ETA with optional time --}}
                    <td>
                        @if($prospect->eta)
                            <div class="datetime-val">
                                <span class="dv-date">{{ $prospect->eta->format('d M Y') }}</span>
                                @if($prospect->eta->format('H:i') !== '00:00')
                                    <span class="dv-time">{{ $prospect->eta->format('H:i') }}</span>
                                @endif
                            </div>
                        @else —@endif
                    </td>
                    <td>
                        @if($prospect->etb)
                            <div class="datetime-val">
                                <span class="dv-date">{{ $prospect->etb->format('d M Y') }}</span>
                                @if($prospect->etb->format('H:i') !== '00:00')
                                    <span class="dv-time">{{ $prospect->etb->format('H:i') }}</span>
                                @endif
                            </div>
                        @else —@endif
                    </td>
                    <td>
                        @if($prospect->etd)
                            <div class="datetime-val">
                                <span class="dv-date">{{ $prospect->etd->format('d M Y') }}</span>
                                @if($prospect->etd->format('H:i') !== '00:00')
                                    <span class="dv-time">{{ $prospect->etd->format('H:i') }}</span>
                                @endif
                            </div>
                        @else —@endif
                    </td>

                    <td>{{ $prospect->destination_country ?? '—' }}</td>
                    <td>{{ $prospect->forwarder ?? '—' }}</td>

                    {{-- Inline status select ── --}}
                    <td>
                        <select class="status-select badge-{{ $prospect->status }}"
                                data-id="{{ $prospect->id }}"
                                data-status="{{ $prospect->status }}"
                                onchange="quickStatus(this)">
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ $prospect->status === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        {{-- Customs note inline edit ── --}}
                        <div class="customs-inline-wrap" id="customs-wrap-{{ $prospect->id }}"
                             style="{{ $prospect->status === 'customs' ? '' : 'display:none;' }}">
                            <input type="text" class="customs-input"
                                   id="customs-note-{{ $prospect->id }}"
                                   value="{{ $prospect->customs_note }}"
                                   placeholder="Customs detail..."
                                   onblur="saveCustomsNote({{ $prospect->id }}, this.value)"
                                   onkeydown="if(event.key==='Enter') this.blur()">
                        </div>
                    </td>

                    <td>
                        @if($prospect->delivery_date)
                            <span style="color:{{ $prospect->isDeliveryOverdue() ? 'var(--red)' : 'var(--gold)' }};font-weight:600;font-size:.85rem;">
                                <i class="fas fa-{{ $prospect->isDeliveryOverdue() ? 'exclamation-triangle' : 'calendar-check' }}" style="font-size:.75rem;"></i>
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
                                    <i class="fas fa-truck"></i> Deliver
                                </button>
                            </form>
                            @endif

                            <form method="POST" action="{{ route('prospects.destroy', $prospect) }}"
                                  onsubmit="return confirm('Delete this prospect?')">
                                @csrf @method('DELETE')
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

@section('scripts')
<script>
const CSRF = '{{ $csrfToken }}';

function quickStatus(el) {
    const id     = el.dataset.id;
    const status = el.value;
    const prev   = el.dataset.status;

    // Show/hide customs note field
    const wrap = document.getElementById('customs-wrap-' + id);
    if (wrap) wrap.style.display = status === 'customs' ? '' : 'none';

    el.classList.add('saving');

    fetch(`/prospects/${id}/quick-status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
        },
        body: JSON.stringify({ status }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Re-colour badge
            ['planning','confirmed','waiting_customers','customs','delayed','cancelled','completed']
                .forEach(s => el.classList.remove('badge-' + s));
            el.classList.add('badge-' + status);
            el.dataset.status = status;

            // Refresh delivery date cell colour if needed
        } else {
            el.value = prev; // rollback
        }
    })
    .catch(() => { el.value = prev; })
    .finally(() => el.classList.remove('saving'));
}

function saveCustomsNote(id, note) {
    fetch(`/prospects/${id}/quick-status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
        },
        body: JSON.stringify({ status: 'customs', customs_note: note }),
    });
}
</script>
@endsection
