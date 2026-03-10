@extends('layouts.app')

@section('title', 'Add Prospect')

@section('content')
<div class="toolbar">
    <h2>
        <i class="fas fa-plus-circle" style="color:var(--teal)"></i> Add Prospect
        <small>New shipment planning entry</small>
    </h2>
    <a href="{{ route('prospects.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div style="max-width:800px;margin:0 auto;">
    <div class="table-wrap" style="padding:2rem;border-radius:16px;">
        @if($errors->any())
            <div class="alert alert-error" style="margin-bottom:1.5rem;">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    @foreach($errors->all() as $err)
                        <div>{{ $err }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('prospects.store') }}">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label>Vessel Name <span style="color:var(--red)">*</span></label>
                    <input type="text" name="vessel_name" class="form-control"
                           value="{{ old('vessel_name') }}" required placeholder="e.g. MV HORIZON ARCTIC">
                </div>
                <div class="form-group">
                    <label>Port</label>
                    <input type="text" name="port" class="form-control"
                           value="{{ old('port') }}" placeholder="e.g. Singapore, Rotterdam">
                </div>
            </div>

            <div class="form-row" style="grid-template-columns:1fr 1fr 1fr;">
                <div class="form-group">
                    <label>ETA</label>
                    <input type="date" name="eta" class="form-control" value="{{ old('eta') }}">
                </div>
                <div class="form-group">
                    <label>ETB</label>
                    <input type="date" name="etb" class="form-control" value="{{ old('etb') }}">
                </div>
                <div class="form-group">
                    <label>ETD</label>
                    <input type="date" name="etd" class="form-control" value="{{ old('etd') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Destination Country</label>
                    <input type="text" name="destination_country" class="form-control"
                           value="{{ old('destination_country') }}" placeholder="e.g. Indonesia, Malaysia">
                </div>
                <div class="form-group">
                    <label>Transport Company</label>
                    <input type="text" name="forwarder" class="form-control"
                           value="{{ old('forwarder') }}" placeholder="Forwarder company name">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Delivery Date</label>
                    <input type="date" name="delivery_date" class="form-control"
                           value="{{ old('delivery_date') }}">
                    <small style="color:var(--text-dim);font-size:.75rem;margin-top:.3rem;display:block;">
                        <i class="fas fa-info-circle"></i>
                        Fill this when delivery date is confirmed. Required to enable the "Create Delivery" button.
                    </small>
                </div>
                <div class="form-group">
                    <label>Status <span style="color:var(--red)">*</span></label>
                    <select name="status" class="form-control">
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ old('status', 'planning') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Notes</label>
                <textarea name="notes" class="form-control" rows="6"
                          placeholder="Operational notes...&#10;e.g.&#10;- Vessel delayed 2 days&#10;- Vessel departed Singapore&#10;- Waiting agent confirmation&#10;- Follow up forwarder">{{ old('notes') }}</textarea>
                <small style="color:var(--text-dim);font-size:.75rem;margin-top:.3rem;display:block;">
                    <i class="fas fa-sticky-note"></i>
                    Use this field for operational notes: vessel delays, departures, agent updates, follow-ups, etc.
                </small>
            </div>

            <div class="modal-footer">
                <a href="{{ route('prospects.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-teal">
                    <i class="fas fa-save"></i> Save Prospect
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
