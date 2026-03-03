@extends('layouts.app')

@section('title', 'Delivery Report')

@section('styles')
<style>
/* Status select */
.status-select {
    width: 100%;
    background: var(--navy-row);
    border: 1px solid var(--border);
    border-radius: 8px;
    color: var(--text);
    font-family: inherit;
    font-size: .88rem;
    padding: .55rem .9rem;
    outline: none;
    transition: border .2s;
    cursor: pointer;
    appearance: auto;
}
.status-select:focus { border-color: var(--teal); box-shadow: 0 0 0 3px rgba(0,201,177,.15); }
.status-select option { background: #0f2040; color: var(--text); }
.other-info-input { margin-top: .5rem; }
</style>
@endsection

@section('content')

@php
    $totalDelivered = $vessels->where('delivered', true)->count();
    $totalPod       = $vessels->where('pod_status', true)->count();
    $totalPending   = $vessels->where('delivered', false)->count();
    $dateFormatted  = \Carbon\Carbon::parse($date)->format('d F Y');

    $statusTemplates = \App\Models\Vessel::$statusTemplates;
@endphp

{{-- ── TOOLBAR ── --}}
<div class="toolbar">
    <div>
        <h2>
            📦 Delivery Report
            <small>{{ $dateFormatted }} &nbsp;·&nbsp; {{ $vessels->count() }} vessels</small>
        </h2>
    </div>
    <form method="GET" action="{{ route('vessels.index') }}" style="display:flex;gap:.6rem;align-items:center;flex-wrap:wrap;">
        <input type="date" name="date" class="input-date" value="{{ $date }}" onchange="this.form.submit()">
        <button class="btn btn-outline" type="submit"><i class="fas fa-filter"></i> Filter</button>
    </form>
    <a href="{{ route('vessels.print', ['date' => $date]) }}" target="_blank" class="btn btn-outline">
        <i class="fas fa-print"></i> Print
    </a>
    <button class="btn btn-teal" onclick="openModal()">
        <i class="fas fa-plus"></i> Add Vessel
    </button>
</div>

{{-- ── STATS ── --}}
<div class="stats-row">
    <div class="stat-card">
        <div class="num">{{ $vessels->count() }}</div>
        <div class="lbl">Total Vessels</div>
    </div>
    <div class="stat-card stat-delivered">
        <div class="num">{{ $totalDelivered }}</div>
        <div class="lbl">Delivered</div>
    </div>
    <div class="stat-card stat-pod">
        <div class="num">{{ $totalPod }}</div>
        <div class="lbl">POD Received</div>
    </div>
    <div class="stat-card stat-pending">
        <div class="num">{{ $totalPending }}</div>
        <div class="lbl">Pending</div>
    </div>
    <div class="stat-card">
        <div class="num" style="color:#d88cf5">{{ $vessels->where('delivered', true)->where('pod_status', false)->count() }}</div>
        <div class="lbl">Waiting POD</div>
    </div>
</div>

{{-- ── TABLE ── --}}
<div class="table-wrap">
    <div class="table-scroll">
        @if($vessels->isEmpty())
            <div class="no-data">
                <i class="fas fa-ship"></i>
                No vessels found for this date.
            </div>
        @else
        <table id="vesselTable">
            <thead>
                <tr>
                    <th style="width:40px">No</th>
                    <th>Vessel</th>
                    <th>Transport / Driver</th>
                    <th>Address</th>
                    <th>Information / Status</th>
                    <th style="width:70px;text-align:center">Delivered</th>
                    <th style="width:60px;text-align:center">POD</th>
                    <th style="width:90px;text-align:center">POD File</th>
                    <th style="width:130px;text-align:center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vessels as $i => $v)
                <tr id="row-{{ $v->id }}" data-id="{{ $v->id }}">
                    <td style="color:var(--text-dim);font-size:.8rem;font-weight:600">{{ $i + 1 }}.</td>

                    {{-- VESSEL NAME --}}
                    <td>
                        <div class="vessel-name" id="view-vessel-{{ $v->id }}">{{ $v->vessel_name }}</div>
                        <input class="inline-input hidden" id="edit-vessel-{{ $v->id }}" value="{{ $v->vessel_name }}" placeholder="Vessel name">
                    </td>

                    {{-- DRIVER --}}
                    <td>
                        <div class="driver-name" id="view-driver-{{ $v->id }}">{{ $v->driver ?? '—' }}</div>
                        <input class="inline-input hidden" id="edit-driver-{{ $v->id }}" value="{{ $v->driver }}" placeholder="Transport company">
                    </td>

                    {{-- ADDRESS --}}
                    <td>
                        <div class="address-name" id="view-address-{{ $v->id }}">{{ $v->delivery_address ?? '—' }}</div>
                        <input class="inline-input hidden" id="edit-address-{{ $v->id }}" value="{{ $v->delivery_address }}" placeholder="Port / address">
                    </td>

                    {{-- INFORMATION --}}
                    <td>
                        <div id="view-info-{{ $v->id }}">
                            @php
                                $info  = $v->information ?? '';
                                $lower = strtolower($info);
                                if ($info === 'Delivered, POD Received') {
                                    $badgeClass = 'badge-delivered';
                                } elseif ($info === 'Delivered, Waiting for POD') {
                                    $badgeClass = 'badge-waiting-pod';
                                } elseif ($info === 'Followed up, waiting next information') {
                                    $badgeClass = 'badge-followup';
                                } elseif ($info === "Waiting POD on custom's mail") {
                                    $badgeClass = 'badge-customs';
                                } elseif ($info) {
                                    $badgeClass = 'badge-other';
                                }
                            @endphp
                            @if($info)
                                <span class="badge {{ $badgeClass }}">{{ $info }}</span>
                            @else
                                <span style="color:var(--text-dim)">—</span>
                            @endif
                        </div>
                        {{-- Inline edit: keep as text input for quick edits --}}
                        <input class="inline-input hidden" id="edit-info-{{ $v->id }}" value="{{ $v->information }}" placeholder="Status...">
                    </td>

                    {{-- DELIVERED --}}
                    <td style="text-align:center">
                        <div class="toggle-wrap">
                            <label class="toggle-switch" title="Toggle Delivered">
                                <input type="checkbox" class="quick-toggle" data-id="{{ $v->id }}" data-field="delivered" {{ $v->delivered ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </td>

                    {{-- POD STATUS --}}
                    <td style="text-align:center">
                        <div class="toggle-wrap">
                            <label class="toggle-switch" title="Toggle POD Received">
                                <input type="checkbox" class="quick-toggle" data-id="{{ $v->id }}" data-field="pod_status" {{ $v->pod_status ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </td>

                    {{-- POD FILE --}}
                    <td style="text-align:center">
                        @if($v->pod_file)
                            <a href="{{ asset('storage/pod_files/' . $v->pod_file) }}" target="_blank" class="pod-link" title="View POD">
                                <i class="fas fa-file-pdf"></i> POD
                            </a>
                        @else
                            <form action="{{ route('vessels.pod', $v->id) }}" method="POST" enctype="multipart/form-data" class="upload-form" id="upload-{{ $v->id }}">
                                @csrf
                                <label class="file-label" for="pod-file-{{ $v->id }}" title="Upload POD">
                                    <i class="fas fa-upload"></i>
                                </label>
                                <input type="file" id="pod-file-{{ $v->id }}" name="pod" accept=".pdf,.jpg,.jpeg,.png" onchange="submitUpload({{ $v->id }})">
                            </form>
                        @endif
                    </td>

                    {{-- ACTIONS --}}
                    <td>
                        <div class="edit-actions" id="view-actions-{{ $v->id }}">
                            <button class="btn btn-sm btn-outline btn-icon" title="Quick Edit" onclick="startEdit({{ $v->id }})">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button class="btn btn-sm btn-gold btn-icon" title="Full Edit" onclick="openEditModal({{ $v->id }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('vessels.destroy', $v->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this vessel?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger btn-icon" type="submit" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                        <div class="edit-actions hidden" id="edit-actions-{{ $v->id }}">
                            <button class="btn btn-sm btn-teal" onclick="saveEdit({{ $v->id }})"><i class="fas fa-check"></i> Save</button>
                            <button class="btn btn-sm btn-outline" onclick="cancelEdit({{ $v->id }})"><i class="fas fa-times"></i></button>
                        </div>
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="9" style="padding:.5rem;background:transparent;border:none"></td>
                </tr>
            </tbody>
        </table>
        @endif
    </div>
</div>

{{-- ── STATUS PICKER PARTIAL (reused in Add & Edit modals) ── --}}
{{-- Add Modal --}}
<div class="modal-overlay" id="addModal">
    <div class="modal">
        <h3><i class="fas fa-plus-circle"></i> Add New Vessel</h3>
        <form action="{{ route('vessels.store') }}" method="POST">
            @csrf
            <input type="hidden" name="report_date" value="{{ $date }}">
            <div class="form-row">
                <div class="form-group">
                    <label>Vessel Name *</label>
                    <input type="text" name="vessel_name" class="form-control" required placeholder="e.g. HORIZON ARCTIC">
                </div>
                <div class="form-group">
                    <label>Delivery Address</label>
                    <input type="text" name="delivery_address" class="form-control" placeholder="e.g. ROTTERDAM">
                </div>
            </div>
            <div class="form-group">
                <label>Transport / Driver</label>
                <input type="text" name="driver" class="form-control"
                    list="driversList"
                    placeholder="Select or type transport company..."
                    autocomplete="off">
            </div>

            {{-- STATUS DROPDOWN --}}
            <div class="form-group">
                <label>Delivery Status</label>
                <select name="information_type" class="status-select" id="addStatusSelect" onchange="handleStatusChange('add')">
                    <option value="">— Select status —</option>
                    @foreach($statusTemplates as $s)
                        <option value="{{ $s }}">{{ $s }}</option>
                    @endforeach
                    <option value="__other__">✏️ Other (custom)...</option>
                </select>
                <input type="text" name="information_custom" id="addStatusCustom"
                    class="form-control other-info-input hidden"
                    placeholder="Describe the status...">
            </div>

            <div class="form-checks">
                <label class="check-label"><input type="checkbox" name="delivered"> Delivered</label>
                <label class="check-label"><input type="checkbox" name="pod_status"> POD Received</label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-teal"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal-overlay" id="editModal">
    <div class="modal">
        <h3><i class="fas fa-edit"></i> Edit Vessel</h3>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
            <input type="hidden" id="editReportDate" name="report_date" value="{{ $date }}">
            <div class="form-row">
                <div class="form-group">
                    <label>Vessel Name *</label>
                    <input type="text" id="editVesselName" name="vessel_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Delivery Address</label>
                    <input type="text" id="editAddress" name="delivery_address" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label>Transport / Driver</label>
                <input type="text" id="editDriver" name="driver" class="form-control"
                    list="driversList"
                    placeholder="Select or type transport company..."
                    autocomplete="off">
            </div>

            {{-- STATUS DROPDOWN --}}
            <div class="form-group">
                <label>Delivery Status</label>
                <select name="information_type" class="status-select" id="editStatusSelect" onchange="handleStatusChange('edit')">
                    <option value="">— Select status —</option>
                    @foreach($statusTemplates as $s)
                        <option value="{{ $s }}">{{ $s }}</option>
                    @endforeach
                    <option value="__other__">✏️ Other (custom)...</option>
                </select>
                <input type="text" name="information_custom" id="editStatusCustom"
                    class="form-control other-info-input hidden"
                    placeholder="Describe the status...">
            </div>

            <div class="form-checks">
                <label class="check-label"><input type="checkbox" id="editDelivered" name="delivered"> Delivered</label>
                <label class="check-label"><input type="checkbox" id="editPod" name="pod_status"> POD Received</label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> Update</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
const CSRF      = document.querySelector('meta[name="csrf-token"]').content;
const PRESETS   = @json(\App\Models\Vessel::$statusTemplates);
const VESSELS   = @json($vessels->keyBy('id'));

// ── MODAL OPEN/CLOSE ──
function openModal() {
    document.getElementById('addStatusSelect').value = '';
    document.getElementById('addStatusCustom').classList.add('hidden');
    document.getElementById('addModal').classList.add('open');
}
function closeModal()     { document.getElementById('addModal').classList.remove('open'); }
function closeEditModal() { document.getElementById('editModal').classList.remove('open'); }

['addModal','editModal'].forEach(id => {
    document.getElementById(id).addEventListener('click', e => {
        if (e.target.id === id) document.getElementById(id).classList.remove('open');
    });
});

// ── STATUS DROPDOWN HANDLER ──
function handleStatusChange(prefix) {
    const sel    = document.getElementById(prefix + 'StatusSelect');
    const custom = document.getElementById(prefix + 'StatusCustom');
    if (sel.value === '__other__') {
        custom.classList.remove('hidden');
        custom.focus();
    } else {
        custom.classList.add('hidden');
        custom.value = '';
    }
}

// ── OPEN FULL EDIT MODAL ──
function openEditModal(id) {
    const v = VESSELS[id];
    if (!v) return;
    document.getElementById('editForm').action  = `/vessels/${id}`;
    document.getElementById('editVesselName').value  = v.vessel_name || '';
    document.getElementById('editAddress').value     = v.delivery_address || '';
    document.getElementById('editDriver').value      = v.driver || '';
    document.getElementById('editDelivered').checked = !!v.delivered;
    document.getElementById('editPod').checked       = !!v.pod_status;

    // Set status dropdown
    const sel    = document.getElementById('editStatusSelect');
    const custom = document.getElementById('editStatusCustom');
    const info   = v.information || '';

    if (PRESETS.includes(info)) {
        sel.value = info;
        custom.classList.add('hidden');
        custom.value = '';
    } else if (info) {
        sel.value    = '__other__';
        custom.value = info;
        custom.classList.remove('hidden');
    } else {
        sel.value = '';
        custom.classList.add('hidden');
        custom.value = '';
    }

    document.getElementById('editModal').classList.add('open');
}

// ── QUICK TOGGLE ──
document.querySelectorAll('.quick-toggle').forEach(checkbox => {
    checkbox.addEventListener('change', function () {
        const id = this.dataset.id, field = this.dataset.field, val = this.checked;
        fetch(`/vessels/${id}/quick`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ [field]: val })
        })
        .then(r => r.json())
        .then(data => { if (!data.success) { this.checked = !val; alert('Failed to update.'); } })
        .catch(() => { this.checked = !val; alert('Network error.'); });
    });
});

// ── INLINE QUICK EDIT ──
function startEdit(id) {
    ['vessel','driver','address','info'].forEach(f => {
        document.getElementById(`view-${f}-${id}`).classList.add('hidden');
        document.getElementById(`edit-${f}-${id}`).classList.remove('hidden');
    });
    document.getElementById(`view-actions-${id}`).classList.add('hidden');
    document.getElementById(`edit-actions-${id}`).classList.remove('hidden');
    document.getElementById(`row-${id}`).classList.add('editing');
    document.getElementById(`edit-vessel-${id}`).focus();
}

function cancelEdit(id) {
    ['vessel','driver','address','info'].forEach(f => {
        document.getElementById(`view-${f}-${id}`).classList.remove('hidden');
        document.getElementById(`edit-${f}-${id}`).classList.add('hidden');
    });
    document.getElementById(`view-actions-${id}`).classList.remove('hidden');
    document.getElementById(`edit-actions-${id}`).classList.add('hidden');
    document.getElementById(`row-${id}`).classList.remove('editing');
}

function saveEdit(id) {
    const vessel_name      = document.getElementById(`edit-vessel-${id}`).value.trim();
    const driver           = document.getElementById(`edit-driver-${id}`).value.trim();
    const delivery_address = document.getElementById(`edit-address-${id}`).value.trim();
    const information      = document.getElementById(`edit-info-${id}`).value.trim();
    if (!vessel_name) { alert('Vessel name is required.'); return; }

    fetch(`/vessels/${id}/quick`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify({ vessel_name, driver, delivery_address, information })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`view-vessel-${id}`).textContent  = vessel_name;
            document.getElementById(`view-driver-${id}`).textContent  = driver || '—';
            document.getElementById(`view-address-${id}`).textContent = delivery_address || '—';
            const infoEl = document.getElementById(`view-info-${id}`);
            infoEl.innerHTML = information
                ? `<span class="badge ${getBadgeClass(information)}">${information}</span>`
                : '<span style="color:var(--text-dim)">—</span>';
            cancelEdit(id);
        } else { alert('Failed to save.'); }
    })
    .catch(() => alert('Network error.'));
}

function getBadgeClass(info) {
    if (info === 'Delivered, POD Received')                return 'badge-delivered';
    if (info === 'Delivered, Waiting for POD')             return 'badge-waiting-pod';
    if (info === 'Followed up, waiting next information')  return 'badge-followup';
    if (info === "Waiting POD on custom's mail")           return 'badge-customs';
    return 'badge-other';
}

function submitUpload(id) {
    const file = document.getElementById(`pod-file-${id}`).files[0];
    if (file) document.getElementById(`upload-${id}`).submit();
}
</script>

{{-- ── TRANSPORT COMPANY DATALIST (shared by Add & Edit modals) ── --}}
<datalist id="driversList">
    @foreach($drivers as $d)
        <option value="{{ $d }}">
    @endforeach
</datalist>

@endsection
