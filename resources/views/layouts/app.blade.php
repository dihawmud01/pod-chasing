<!DOCTYPE html>
<html lang="en" id="htmlRoot">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'POD Chasing') — Vessel Delivery Tracker</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --navy:      #0a1628;
            --navy-mid:  #0f2040;
            --navy-card: #142240;
            --navy-row:  #19294f;
            --teal:      #00c9b1;
            --teal-dim:  #009e8b;
            --gold:      #f0b429;
            --gold-dim:  #c88e00;
            --red:       #e05252;
            --green:     #27ae60;
            --text:      #d4e0f5;
            --text-dim:  #8fafd6;
            --border:    rgba(0,201,177,.18);
            --shadow:    0 8px 32px rgba(0,0,0,.45);
        }

        /* ── LIGHT THEME ── */
        [data-theme="light"] {
            --navy:      #f0f4fb;
            --navy-mid:  #e2eaf6;
            --navy-card: #ffffff;
            --navy-row:  #f7f9fd;
            --teal:      #007d6e;
            --teal-dim:  #005f54;
            --gold:      #b87a00;
            --gold-dim:  #8a5c00;
            --red:       #c0392b;
            --green:     #1e8449;
            --text:      #1a2a44;
            --text-dim:  #4a6080;
            --border:    rgba(0,125,110,.18);
            --shadow:    0 8px 32px rgba(0,0,0,.12);
        }
        [data-theme="light"] body { background: var(--navy); }
        [data-theme="light"] .topbar {
            background: linear-gradient(135deg, #dde9f8 0%, #e8f0fc 60%, #f0f4fb 100%);
            box-shadow: 0 2px 12px rgba(0,0,0,.08);
        }
        [data-theme="light"] .topbar-brand { color: #1a2a44; }
        [data-theme="light"] .table-wrap { background: #fff; }
        [data-theme="light"] table thead th { background: #e8f0fc; color: #2d4a70; }
        [data-theme="light"] table tbody tr:hover { background: rgba(0,125,110,.04); }
        [data-theme="light"] .filter-bar select { background: #f0f4fb; color: #1a2a44; }
        [data-theme="light"] .notif-dropdown { background: #fff; }
        [data-theme="light"] .notif-btn { color: #4a6080; }
        [data-theme="light"] .notif-btn:hover { background: rgba(0,125,110,.1); }

        /* ── THEME TOGGLE BUTTON ── */
        .theme-btn {
            background: none; border: 1px solid var(--border); cursor: pointer;
            color: var(--text-dim); font-size: .9rem;
            padding: .28rem .55rem; border-radius: 8px;
            transition: all .18s; line-height: 1;
        }
        .theme-btn:hover { color: var(--teal); border-color: var(--teal); background: rgba(0,201,177,.08); }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--navy);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── SIDEBAR / HEADER ── */
        .topbar {
            background: linear-gradient(135deg, #0a2050 0%, #0d1e3d 60%, #061126 100%);
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 24px rgba(0,0,0,.5);
        }
        .topbar-brand {
            display: flex;
            align-items: center;
            gap: .7rem;
            font-size: 1.3rem;
            font-weight: 800;
            letter-spacing: -.5px;
            color: #fff;
        }
        .topbar-brand .icon {
            background: linear-gradient(135deg, var(--teal), #0099cc);
            width: 38px; height: 38px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            box-shadow: 0 0 18px rgba(0,201,177,.4);
        }
        .topbar-brand span { color: var(--teal); }
        .topbar-right { display: flex; align-items: center; gap: 1.25rem; }

        /* ── BELL NOTIFICATION ── */
        .notif-wrap { position: relative; }
        .notif-btn {
            background: none; border: none; cursor: pointer;
            color: var(--text-dim); font-size: 1.15rem;
            padding: .3rem .45rem;
            border-radius: 8px; transition: all .18s;
            position: relative; line-height: 1;
        }
        .notif-btn:hover { color: var(--teal); background: rgba(0,201,177,.1); }
        .notif-badge {
            position: absolute; top: -3px; right: -3px;
            background: #e05252; color: #fff;
            font-size: .6rem; font-weight: 700;
            min-width: 16px; height: 16px;
            border-radius: 10px; padding: 0 4px;
            display: flex; align-items: center; justify-content: center;
            pointer-events: none;
        }
        .notif-dropdown {
            display: none;
            position: absolute; top: calc(100% + 10px); right: 0;
            width: 340px;
            background: var(--navy-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 16px 48px rgba(0,0,0,.45);
            z-index: 9999;
            overflow: hidden;
        }
        .notif-dropdown.open { display: block; }
        .notif-header {
            padding: .85rem 1.1rem .65rem;
            border-bottom: 1px solid var(--border);
            font-size: .78rem; font-weight: 700;
            color: var(--text); display: flex; align-items: center;
            justify-content: space-between;
        }
        .notif-header span { color: var(--text-dim); font-weight: 400; }
        .notif-list { max-height: 380px; overflow-y: auto; }
        .notif-item {
            display: flex; align-items: flex-start; gap: .75rem;
            padding: .75rem 1.1rem;
            border-bottom: 1px solid rgba(255,255,255,.04);
            text-decoration: none;
            transition: background .15s;
        }
        .notif-item:hover { background: rgba(255,255,255,.04); }
        .notif-item:last-child { border-bottom: none; }
        .notif-icon {
            width: 30px; height: 30px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: .8rem; flex-shrink: 0; margin-top: 1px;
        }
        .notif-icon.danger  { background: rgba(224,82,82,.18);  color: #f07070; }
        .notif-icon.warning { background: rgba(240,180,41,.15); color: #f5c842; }
        .notif-text { flex: 1; min-width: 0; }
        .notif-title { font-size: .76rem; font-weight: 700; color: var(--text); margin-bottom: 2px; }
        .notif-title.danger  { color: #f07070; }
        .notif-title.warning { color: #f5c842; }
        .notif-msg { font-size: .72rem; color: var(--text-dim); line-height: 1.35;
                     white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .notif-empty {
            padding: 2rem 1rem; text-align: center;
            font-size: .8rem; color: var(--text-dim);
        }
        .notif-empty i { font-size: 1.6rem; display: block; margin-bottom: .5rem; color: var(--teal); opacity:.5; }
        .notif-footer {
            padding: .6rem 1.1rem;
            border-top: 1px solid var(--border);
            text-align: center;
        }
        .notif-footer a { font-size: .74rem; color: var(--teal); text-decoration: none; }
        .notif-footer a:hover { text-decoration: underline; }

        /* ── NAV MENU ── */
        .topbar-nav { display: flex; align-items: center; gap: .25rem; }
        .nav-link {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .42rem 1rem;
            border-radius: 8px;
            font-size: .83rem;
            font-weight: 600;
            color: var(--text-dim);
            text-decoration: none;
            transition: all .18s;
            white-space: nowrap;
            border: 1px solid transparent;
        }
        .nav-link:hover {
            color: var(--teal);
            background: rgba(0,201,177,.08);
            border-color: rgba(0,201,177,.2);
        }
        .nav-link.active {
            color: var(--teal);
            background: rgba(0,201,177,.12);
            border-color: rgba(0,201,177,.3);
        }
        .nav-divider { width: 1px; height: 20px; background: var(--border); margin: 0 .25rem; }

        .clock {
            font-size: .85rem;
            color: var(--text-dim);
            background: rgba(255,255,255,.05);
            padding: .35rem .85rem;
            border-radius: 20px;
            border: 1px solid var(--border);
            white-space: nowrap;
        }

        /* ── MAIN CONTENT ── */
        .main { padding: 2rem; max-width: 1600px; margin: 0 auto; }

        /* ── ALERTS ── */
        .alert {
            padding: .85rem 1.2rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: .9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: .6rem;
        }
        .alert-success { background: rgba(39,174,96,.18); border: 1px solid rgba(39,174,96,.4); color: #5dde8a; }
        .alert-error   { background: rgba(224,82,82,.18);  border: 1px solid rgba(224,82,82,.4);  color: #f07070; }

        /* ── FILTERS ROW ── */
        .toolbar {
            background: var(--navy-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.2rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow);
        }
        .toolbar h2 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
            flex: 1;
            min-width: 200px;
        }
        .toolbar h2 small { color: var(--teal); font-weight: 600; font-size: .85em; display: block; margin-top: 2px; }

        .input-date {
            background: var(--navy-row);
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 8px;
            padding: .5rem .9rem;
            font-family: inherit;
            font-size: .9rem;
            outline: none;
            transition: border .2s;
        }
        .input-date:focus { border-color: var(--teal); }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .52rem 1.1rem;
            border-radius: 8px;
            font-family: inherit;
            font-size: .85rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all .18s;
            text-decoration: none;
            white-space: nowrap;
        }
        .btn-teal    { background: var(--teal);  color: #0a1628; }
        .btn-teal:hover { background: #00dfc6; transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,201,177,.35); }
        .btn-gold    { background: var(--gold);  color: #0a1628; }
        .btn-gold:hover { background: #ffc740; transform: translateY(-1px); box-shadow: 0 4px 14px rgba(240,180,41,.35); }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text); }
        .btn-outline:hover { border-color: var(--teal); color: var(--teal); background: rgba(0,201,177,.07); }
        .btn-danger  { background: rgba(224,82,82,.15); border: 1px solid rgba(224,82,82,.3); color: var(--red); }
        .btn-danger:hover { background: rgba(224,82,82,.28); }
        .btn-sm { padding: .3rem .7rem; font-size: .78rem; border-radius: 6px; }
        .btn-icon { padding: .3rem .55rem; }

        /* ── TABLE ── */
        .table-wrap {
            background: var(--navy-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }
        table { width: 100%; border-collapse: collapse; font-size: .85rem; }
        thead tr { background: linear-gradient(135deg, #0d2550 0%, #0a1e42 100%); }
        thead th {
            padding: .85rem .9rem;
            text-align: left;
            color: var(--teal);
            font-weight: 700;
            font-size: .78rem;
            letter-spacing: .06em;
            text-transform: uppercase;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        tbody tr {
            border-bottom: 1px solid rgba(255,255,255,.04);
            transition: background .15s;
        }
        tbody tr:hover   { background: rgba(0,201,177,.04); }
        tbody tr.editing { background: rgba(0,201,177,.08); border-left: 3px solid var(--teal); }
        tbody td {
            padding: .7rem .9rem;
            vertical-align: middle;
            color: var(--text);
        }
        td .vessel-name  { font-weight: 700; font-size: .9rem; color: #fff; }
        td .driver-name  { color: var(--text-dim); font-size: .82rem; }
        td .address-name { color: var(--text-dim); font-size: .82rem; }

        /* ── BADGE ── */
        .badge {
            display: inline-block;
            padding: .22rem .65rem;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .04em;
            white-space: nowrap;
        }
        .badge-delivered   { background: rgba(39,174,96,.2);  color: #4cd98a; border: 1px solid rgba(39,174,96,.35); }
        .badge-waiting-pod { background: rgba(240,180,41,.2); color: #f5c842; border: 1px solid rgba(240,180,41,.35); }
        .badge-followup    { background: rgba(99,130,200,.2); color: #8aaff5; border: 1px solid rgba(99,130,200,.3); }
        .badge-mariano     { background: rgba(180,90,220,.2); color: #d88cf5; border: 1px solid rgba(180,90,220,.3); }
        .badge-customs     { background: rgba(240,180,41,.2); color: #f5c842; border: 1px solid rgba(240,180,41,.35); }
        .badge-other       { background: rgba(150,170,200,.15); color: #9ab0d0; border: 1px solid rgba(150,170,200,.25); }

        /* ── TOGGLE SWITCH ── */
        .toggle-wrap { display: flex; align-items: center; justify-content: center; }
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 22px;
            cursor: pointer;
        }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .toggle-slider {
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,.1);
            border-radius: 22px;
            transition: .2s;
            border: 1px solid rgba(255,255,255,.15);
        }
        .toggle-slider::before {
            content: '';
            position: absolute;
            height: 16px; width: 16px;
            left: 2px; bottom: 2px;
            background: #fff;
            border-radius: 50%;
            transition: .2s;
        }
        input:checked + .toggle-slider { background: var(--teal); border-color: var(--teal); }
        input:checked + .toggle-slider::before { transform: translateX(18px); }

        /* ── INLINE EDIT ROW ── */
        .inline-input {
            background: var(--navy);
            border: 1px solid var(--teal);
            border-radius: 6px;
            color: #fff;
            font-family: inherit;
            font-size: .82rem;
            padding: .3rem .55rem;
            width: 100%;
            min-width: 120px;
            outline: none;
        }
        .inline-input:focus { box-shadow: 0 0 0 2px rgba(0,201,177,.25); }
        .edit-actions { display: flex; gap: .3rem; align-items: center; flex-wrap: wrap; }
        .hidden { display: none; }

        /* ── POD file icon ── */
        .pod-link { color: var(--gold); text-decoration: none; font-size: .85rem; }
        .pod-link:hover { color: #ffd966; }

        /* ── STATS ROW ── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .stat-card {
            background: var(--navy-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1rem 1.2rem;
            text-align: center;
        }
        .stat-card .num  { font-size: 2rem; font-weight: 800; color: #fff; line-height: 1; }
        .stat-card .lbl  { font-size: .72rem; color: var(--text-dim); margin-top: .3rem; text-transform: uppercase; letter-spacing: .06em; }
        .stat-delivered .num { color: var(--teal); }
        .stat-pod .num       { color: var(--gold); }
        .stat-pending .num   { color: #7fa5e0; }

        /* ── MODAL ── */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(5,12,30,.75);
            z-index: 500;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none;
            transition: opacity .2s;
        }
        .modal-overlay.open { opacity: 1; pointer-events: all; }
        .modal {
            background: var(--navy-card);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 2rem;
            width: 100%;
            max-width: 560px;
            box-shadow: 0 24px 80px rgba(0,0,0,.6);
            transform: translateY(20px);
            transition: transform .2s;
        }
        .modal-overlay.open .modal { transform: translateY(0); }
        .modal h3 { font-size: 1.1rem; font-weight: 700; color: #fff; margin-bottom: 1.5rem; display: flex; align-items: center; gap: .5rem; }
        .modal h3 i { color: var(--teal); }

        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-size: .78rem; font-weight: 600; color: var(--text-dim); margin-bottom: .35rem; text-transform: uppercase; letter-spacing: .05em; }
        .form-control {
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
        }
        .form-control:focus { border-color: var(--teal); box-shadow: 0 0 0 3px rgba(0,201,177,.15); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-checks { display: flex; gap: 1.5rem; flex-wrap: wrap; margin-top: .5rem; }
        .check-label { display: flex; align-items: center; gap: .4rem; font-size: .82rem; cursor: pointer; }
        .check-label input[type=checkbox] {
            accent-color: var(--teal);
            width: 15px; height: 15px;
            cursor: pointer;
        }
        .modal-footer { display: flex; gap: .7rem; justify-content: flex-end; margin-top: 1.5rem; padding-top: 1.2rem; border-top: 1px solid var(--border); }

        /* ── UPLOAD FORM ── */
        .upload-form { display: flex; align-items: center; gap: .4rem; }
        input[type=file] { display: none; }
        .file-label {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .28rem .6rem;
            background: rgba(240,180,41,.1);
            border: 1px solid rgba(240,180,41,.3);
            border-radius: 6px;
            color: var(--gold);
            font-size: .75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all .15s;
        }
        .file-label:hover { background: rgba(240,180,41,.2); }
        .upload-form .btn { padding: .28rem .55rem; font-size: .75rem; }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--navy); }
        ::-webkit-scrollbar-thumb { background: #1e3a6e; border-radius: 3px; }

        /* Responsive table */
        @media (max-width: 900px) {
            .main { padding: 1rem; }
            .form-row { grid-template-columns: 1fr; }
            .stats-row { grid-template-columns: repeat(2, 1fr); }
        }
        .table-scroll { overflow-x: auto; }
        .no-data { text-align: center; padding: 3rem; color: var(--text-dim); }
        .no-data i { font-size: 2.5rem; display: block; margin-bottom: 1rem; opacity: .4; }
    </style>
    @yield('styles')
</head>
<body>
    <header class="topbar">
        <div class="topbar-brand">
            <div class="icon"><i class="fas fa-ship"></i></div>
            <div>POD <span>Chasing</span></div>
        </div>

        <nav class="topbar-nav">
            <a href="{{ route('prospects.index') }}"
               class="nav-link {{ request()->routeIs('prospects.*') ? 'active' : '' }}">
                <i class="fas fa-binoculars"></i> Prospects
            </a>
            <div class="nav-divider"></div>
            <a href="{{ route('vessels.index') }}"
               class="nav-link {{ request()->routeIs('vessels.index') ? 'active' : '' }}">
                <i class="fas fa-truck"></i> Delivery Monitoring
            </a>
            <!-- <div class="nav-divider"></div>
            <a href="{{ route('vessels.index') }}"
               class="nav-link {{ false ? 'active' : '' }}">
                <i class="fas fa-file-signature"></i> POD Chasing
            </a> -->
        </nav>

        <div class="topbar-right">
            {{-- Theme Toggle --}}
            <button class="theme-btn" id="themeBtn" onclick="toggleTheme()" title="Toggle Theme">
                <i class="fas fa-moon" id="themeIcon"></i>
            </button>

            {{-- Bell Notification --}}
            <div class="notif-wrap" id="notifWrap">
                <button class="notif-btn" id="notifBtn"
                        onclick="toggleNotifDropdown(event)"
                        title="Notifications" aria-label="Notifications">
                    <i class="fas fa-bell"></i>
                    <span class="notif-badge" id="notifBadge" style="display:none;"></span>
                </button>

                <div class="notif-dropdown" id="notifDropdown">
                    <div class="notif-header">
                        <div><i class="fas fa-bell" style="color:var(--teal);margin-right:.4rem;"></i> Notifications</div>
                        <span id="notifCount">Loading…</span>
                    </div>
                    <div class="notif-list" id="notifList">
                        <div class="notif-empty"><i class="fas fa-spinner fa-spin"></i> Loading…</div>
                    </div>
                    <div class="notif-footer">
                        <a href="{{ route('prospects.index') }}">View all Prospects →</a>
                    </div>
                </div>
            </div>

            <div class="clock" id="clock"><i class="far fa-clock"></i> --:--:--</div>
        </div>
    </header>

    <main class="main">
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error"><i class="fas fa-exclamation-triangle"></i> {{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    {{-- Prevent theme flash on load --}}
    <script>
        (function() {
            const t = localStorage.getItem('pod-theme');
            if (t === 'light') document.getElementById('htmlRoot').setAttribute('data-theme', 'light');
        })();
    </script>

    <script>
        // ── Theme Toggle ──
        function toggleTheme() {
            const html = document.getElementById('htmlRoot');
            const isLight = html.getAttribute('data-theme') === 'light';
            html.setAttribute('data-theme', isLight ? '' : 'light');
            localStorage.setItem('pod-theme', isLight ? 'dark' : 'light');
            document.getElementById('themeIcon').className = isLight ? 'fas fa-moon' : 'fas fa-sun';
        }

        // Set correct icon on load
        (function() {
            const t = localStorage.getItem('pod-theme');
            if (t === 'light') document.getElementById('themeIcon').className = 'fas fa-sun';
        })();

        // ── Clock ──
        function updateClock() {
            const now = new Date();
            document.getElementById('clock').innerHTML =
                '<i class="far fa-clock"></i> ' +
                now.toLocaleDateString('en-GB', {day:'2-digit', month:'short', year:'numeric'}) +
                ' &nbsp;' + now.toLocaleTimeString('en-GB');
        }
        updateClock();
        setInterval(updateClock, 1000);

        // ── Notification Bell ──
        let notifLoaded = false;

        function toggleNotifDropdown(e) {
            e.stopPropagation();
            const dd = document.getElementById('notifDropdown');
            dd.classList.toggle('open');
            if (dd.classList.contains('open') && !notifLoaded) {
                loadNotifications();
            }
        }

        function loadNotifications() {
            fetch('/notifications')
                .then(r => r.json())
                .then(data => {
                    notifLoaded = true;
                    const list  = document.getElementById('notifList');
                    const badge = document.getElementById('notifBadge');
                    const count = document.getElementById('notifCount');

                    count.textContent = data.total + ' alert' + (data.total !== 1 ? 's' : '');

                    if (data.total > 0) {
                        badge.textContent = data.total > 9 ? '9+' : data.total;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }

                    if (data.items.length === 0) {
                        list.innerHTML = '<div class="notif-empty"><i class="fas fa-check-circle"></i>All clear! No issues found.</div>';
                        return;
                    }

                    list.innerHTML = data.items.map(item => `
                        <div class="notif-item-wrap" style="position:relative; display:flex; align-items:center;">
                            <a href="${item.url}" class="notif-item" style="flex:1; padding-right:2.5rem;" onclick="dismissAndGo(event, ${item.id}, '${item.url}')">
                                <div class="notif-icon ${item.type}">
                                    <i class="fas fa-${item.icon}"></i>
                                </div>
                                <div class="notif-text">
                                    <div class="notif-title ${item.type}">${item.title}</div>
                                    <div class="notif-msg" title="${item.message}">${item.message}</div>
                                </div>
                            </a>
                            <button onclick="dismissOnly(event, ${item.id}, this)" title="Mark as Read" style="position:absolute; right:.75rem; background:none; border:none; color:var(--text-dim); cursor:pointer; font-size:.85rem; padding:.25rem;">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    `).join('');
                })
                .catch(() => {
                    document.getElementById('notifList').innerHTML =
                        '<div class="notif-empty">Failed to load notifications.</div>';
                });
        }

        // Fetch count badge on page load
        fetch('/notifications')
            .then(r => r.json())
            .then(data => {
                if (data.total > 0) {
                    const badge = document.getElementById('notifBadge');
                    badge.textContent = data.total > 9 ? '9+' : data.total;
                    badge.style.display = 'flex';
                }
            });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const wrap = document.getElementById('notifWrap');
            if (wrap && !wrap.contains(e.target)) {
                document.getElementById('notifDropdown').classList.remove('open');
            }
        });

        // ── Dismiss Alerts Logic ──
        function dismissAndGo(event, id, url) {
            event.preventDefault();
            fetch('/prospects/' + id + '/dismiss-alert', {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            }).finally(() => {
                window.location.href = url;
            });
        }

        function dismissOnly(event, id, btn) {
            event.stopPropagation();
            event.preventDefault();
            
            const wrap = btn.closest('.notif-item-wrap');
            if (wrap) wrap.style.opacity = '0.5';

            fetch('/prospects/' + id + '/dismiss-alert', {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            }).then(r => r.json()).then(res => {
                if (res.success) {
                    if (wrap) {
                        wrap.style.height = wrap.offsetHeight + 'px';
                        setTimeout(() => { wrap.style.display = 'none'; }, 200);
                    }
                    
                    // Decrement badge locally
                    const badge = document.getElementById('notifBadge');
                    const count = document.getElementById('notifCount');
                    let current = parseInt(badge.textContent) || 0;
                    if (current > 0) {
                        current--;
                        badge.textContent = current > 9 ? '9+' : current;
                        count.textContent = current + ' alert' + (current !== 1 ? 's' : '');
                        if (current === 0) {
                            badge.style.display = 'none';
                            document.getElementById('notifList').innerHTML = '<div class="notif-empty"><i class="fas fa-check-circle"></i>All clear! No issues found.</div>';
                        }
                    }
                }
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
