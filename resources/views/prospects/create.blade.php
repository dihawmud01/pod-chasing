@extends('layouts.app')

@section('title', 'Add Prospect')

@section('content')
<div class="toolbar">
    <h2>
        <i class="fas fa-plus-circle" style="color:var(--teal)"></i> Add Prospect
        <small>New shipment planning entry</small>
    </h2>
    <a href="{{ route('prospects.index', ['date' => $prospectDate]) }}" class="btn btn-outline">
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

        <form method="POST" action="{{ route('prospects.store') }}">
            @csrf

            {{-- Prospect Date --}}
            <div class="form-group">
                <label>
                    Prospect Date <span style="color:var(--red)">*</span>
                    <small style="color:var(--text-dim);font-weight:400;margin-left:.4rem;">— change to reschedule to another date</small>
                </label>
                <input type="date" name="prospect_date" class="form-control"
                       value="{{ old('prospect_date', $prospectDate) }}" required style="max-width:220px;">
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
                               {{ old('section', 'nl_be') === $key ? 'checked' : '' }}
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
                           value="{{ old('vessel_name') }}" required placeholder="e.g. MV HORIZON ARCTIC">
                </div>
                <div class="form-group">
                    <label>Port</label>
                    <input type="text" name="port" class="form-control"
                           value="{{ old('port') }}" placeholder="e.g. Singapore">
                </div>
            </div>

            {{-- ETA / ETB / ETD with time ── --}}
            <div class="form-row" style="grid-template-columns:1fr 1fr 1fr;">
                <div class="form-group">
                    <label>ETA <small style="color:var(--text-dim);font-size:.72rem;">(date &amp; time)</small></label>
                    <input type="datetime-local" name="eta" class="form-control"
                           value="{{ old('eta') }}">
                </div>
                <div class="form-group">
                    <label>ETB <small style="color:var(--text-dim);font-size:.72rem;">(date &amp; time)</small></label>
                    <input type="datetime-local" name="etb" class="form-control"
                           value="{{ old('etb') }}">
                </div>
                <div class="form-group">
                    <label>ETD <small style="color:var(--text-dim);font-size:.72rem;">(date &amp; time)</small></label>
                    <input type="datetime-local" name="etd" class="form-control"
                           value="{{ old('etd') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Destination Country</label>
                    <input type="text" name="destination_country" class="form-control"
                           value="{{ old('destination_country') }}" placeholder="e.g. Indonesia">
                </div>
                <div class="form-group">
                    <label>Transport Company</label>
                    <input type="text" name="forwarder" class="form-control"
                           value="{{ old('forwarder') }}" placeholder="Forwarder name">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Delivery Date</label>
                    <input type="date" name="delivery_date" class="form-control"
                           value="{{ old('delivery_date') }}">
                    <small style="color:var(--text-dim);font-size:.74rem;margin-top:.25rem;display:block;">
                        Required to enable the "Create Delivery" button.
                    </small>
                </div>
                <div class="form-group">
                    <label>Status <span style="color:var(--red)">*</span></label>
                    <select name="status" id="status-select" class="form-control"
                            onchange="toggleCustomsNote(this.value)">
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ old('status', 'planning') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Customs Note — visible only when status = customs ── --}}
            <div class="form-group" id="customs-note-group"
                 style="{{ old('status') === 'customs' ? '' : 'display:none;' }}">
                <label>
                    <i class="fas fa-clipboard-list" style="color:#f5a842;"></i>
                    Customs Detail
                    <small style="color:var(--text-dim);font-weight:400;margin-left:.3rem;">— describe the customs issue</small>
                </label>
                <input type="text" name="customs_note" class="form-control"
                       value="{{ old('customs_note') }}"
                       placeholder="e.g. Missing B/L, Hold by Bea Cukai, Tariff dispute...">
            </div>

            <div class="form-group">
                <label>Notes</label>
                <textarea name="notes" class="form-control" rows="5"
                          placeholder="Operational notes...">{{ old('notes') }}</textarea>
            </div>

            <div class="modal-footer">
                <a href="{{ route('prospects.index', ['date' => $prospectDate]) }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-teal">
                    <i class="fas fa-save"></i> Save Prospect
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleCustomsNote(status) {
    const group = document.getElementById('customs-note-group');
    group.style.display = status === 'customs' ? '' : 'none';
}
// Run on load
toggleCustomsNote(document.getElementById('status-select').value);
</script>
@endsection
