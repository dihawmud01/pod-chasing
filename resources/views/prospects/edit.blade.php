@extends('layouts.app')

@section('title', 'Edit Prospect')

@section('content')
<div class="toolbar">
    <h2>
        <i class="fas fa-edit" style="color:var(--teal)"></i> Edit Prospect
        <small>{{ $prospect->vessel_name }}</small>
    </h2>
    <a href="{{ route('prospects.index', ['date' => $prospect->prospect_date->toDateString()]) }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div style="max-width:820px;margin:0 auto;">
    <div class="table-wrap" style="padding:2rem;border-radius:16px;">
        @if($errors->any())
            <div class="alert alert-error" style="margin-bottom:1.5rem;">
                <i class="fas fa-exclamation-triangle"></i>
                <div>@foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach</div>
            </div>
        @endif

        <form method="POST" action="{{ route('prospects.update', $prospect) }}">
            @csrf @method('PUT')

            {{-- Prospect Date --}}
            <div class="form-group">
                <label>
                    Prospect Date <span style="color:var(--red)">*</span>
                    <small style="color:var(--text-dim);font-weight:400;margin-left:.4rem;">— change to reschedule to another date</small>
                </label>
                <input type="date" name="prospect_date" class="form-control"
                       value="{{ old('prospect_date', $prospect->prospect_date->format('Y-m-d')) }}"
                       required style="max-width:220px;">
            </div>

            {{-- Section (NL-BE / EU+GB) --}}
            <div class="form-group">
                <label>Section <span style="color:var(--red)">*</span></label>
                <div style="display:flex;gap:.75rem;margin-top:.35rem;">
                    @foreach($sections as $key => $label)
                    <label style="display:flex;align-items:center;gap:.4rem;cursor:pointer;
                                  padding:.5rem 1.1rem;border-radius:10px;user-select:none;
                                  border:1px solid {{ $key === 'nl_be' ? 'rgba(99,130,200,.3)' : 'rgba(39,174,96,.3)' }};
                                  background:{{ $key === 'nl_be' ? 'rgba(99,130,200,.08)' : 'rgba(39,174,96,.08)' }};">
                        <input type="radio" name="section" value="{{ $key }}"
                               {{ old('section', $prospect->section) === $key ? 'checked' : '' }}
                               style="accent-color:{{ $key === 'nl_be' ? '#8aaff5' : '#4cd98a' }}">
                        <span style="font-weight:700;font-size:.88rem;
                                     color:{{ $key === 'nl_be' ? '#8aaff5' : '#4cd98a' }}">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Vessel Name <span style="color:var(--red)">*</span></label>
                    <input type="text" name="vessel_name" class="form-control"
                           value="{{ old('vessel_name', $prospect->vessel_name) }}" required>
                </div>
                <div class="form-group">
                    <label>Port</label>
                    <input type="text" name="port" class="form-control"
                           value="{{ old('port', $prospect->port) }}" placeholder="e.g. Singapore">
                </div>
            </div>

            {{-- ETA / ETB / ETD with time ── --}}
            <div class="form-row" style="grid-template-columns:1fr 1fr 1fr;">
                <div class="form-group">
                    <label>ETA <small style="color:var(--text-dim);font-size:.72rem;">(date &amp; time)</small></label>
                    <input type="datetime-local" name="eta" class="form-control"
                           value="{{ old('eta', $prospect->eta?->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="form-group">
                    <label>ETB <small style="color:var(--text-dim);font-size:.72rem;">(date &amp; time)</small></label>
                    <input type="datetime-local" name="etb" class="form-control"
                           value="{{ old('etb', $prospect->etb?->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="form-group">
                    <label>ETD <small style="color:var(--text-dim);font-size:.72rem;">(date &amp; time)</small></label>
                    <input type="datetime-local" name="etd" class="form-control"
                           value="{{ old('etd', $prospect->etd?->format('Y-m-d\TH:i')) }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Destination Country</label>
                    <input type="text" name="destination_country" class="form-control"
                           value="{{ old('destination_country', $prospect->destination_country) }}">
                </div>
                <div class="form-group">
                    <label>Transport Company</label>
                    <input type="text" name="forwarder" class="form-control"
                           value="{{ old('forwarder', $prospect->forwarder) }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Delivery Date</label>
                    <input type="date" name="delivery_date" class="form-control"
                           value="{{ old('delivery_date', $prospect->delivery_date?->format('Y-m-d')) }}">
                    <small style="color:var(--text-dim);font-size:.74rem;margin-top:.25rem;display:block;">
                        Required to enable the "Create Delivery" button.
                    </small>
                </div>
                <div class="form-group">
                    <label>Status <span style="color:var(--red)">*</span></label>
                    <select name="status" id="status-select" class="form-control"
                            onchange="toggleCustomsNote(this.value)">
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('status', $prospect->status) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Customs Note ── --}}
            <div class="form-group" id="customs-note-group"
                 style="{{ old('status', $prospect->status) === 'customs' ? '' : 'display:none;' }}">
                <label>
                    <i class="fas fa-clipboard-list" style="color:#f5a842;"></i>
                    Customs Detail
                    <small style="color:var(--text-dim);font-weight:400;margin-left:.3rem;">— describe the customs issue</small>
                </label>
                <input type="text" name="customs_note" class="form-control"
                       value="{{ old('customs_note', $prospect->customs_note) }}"
                       placeholder="e.g. Missing B/L, Hold by Bea Cukai, Tariff dispute...">
            </div>

            <div class="form-group">
                <label>Notes</label>
                <textarea name="notes" class="form-control" rows="7"
                          placeholder="Operational notes...">{{ old('notes', $prospect->notes) }}</textarea>
            </div>

            <div class="modal-footer">
                <a href="{{ route('prospects.index', ['date' => $prospect->prospect_date->toDateString()]) }}"
                   class="btn btn-outline">Cancel</a>

                @if($prospect->delivery_date && $prospect->status !== 'cancelled' && $prospect->status !== 'completed')
                <div style="margin-right:auto;">
                    <form method="POST" action="{{ route('prospects.createDelivery', $prospect) }}"
                          onsubmit="return confirm('Create delivery from this prospect?')">
                        @csrf
                        <button type="submit" class="btn btn-gold">
                            <i class="fas fa-truck"></i> Create Delivery
                        </button>
                    </form>
                </div>
                @endif

                <button type="submit" class="btn btn-teal">
                    <i class="fas fa-save"></i> Update Prospect
                </button>
            </div>
        </form>
    </div>

    <div style="margin-top:1rem;background:var(--navy-card);border:1px solid var(--border);border-radius:12px;padding:1rem 1.25rem;">
        <div style="font-size:.75rem;color:var(--text-dim);display:flex;gap:2rem;flex-wrap:wrap;">
            <span><i class="fas fa-calendar-day"></i> Prospect Date: {{ $prospect->prospect_date->format('d M Y') }}</span>
            <span><i class="fas fa-calendar-plus"></i> Created: {{ $prospect->created_at->format('d M Y H:i') }}</span>
            <span><i class="fas fa-calendar-edit"></i> Updated: {{ $prospect->updated_at->format('d M Y H:i') }}</span>
            <span><i class="fas fa-hashtag"></i> ID: {{ $prospect->id }}</span>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleCustomsNote(status) {
    const group = document.getElementById('customs-note-group');
    group.style.display = status === 'customs' ? '' : 'none';
}
toggleCustomsNote(document.getElementById('status-select').value);
</script>
@endsection
