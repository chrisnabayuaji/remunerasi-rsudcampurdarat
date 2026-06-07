<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>{{ $menu_title }} — {{ $identitas['aplikasi_nm'] }}</title>
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />

<!-- Bootstrap 5.3 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet" />
<!-- Font Awesome 6 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
<!-- Toastr -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
<!-- DataTables BS5 -->
<link href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<!-- AutoNumeric -->
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.8.1/dist/autoNumeric.min.js"></script>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet" />

<style>
  :root {
    --primary: #2196F3;
    --primary-dark: #1565C0;
    --primary-light: #BBDEFB;
    --primary-xlight: #E3F2FD;
    --accent: #00BCD4;
    --accent2: #4CAF50;
    --warn: #FF9800;
    --danger: #F44336;
    --sidebar-bg: #0D47A1;
    --sidebar-mid: #1565C0;
    --sidebar-hover: #1976D2;
    --sidebar-active: #2196F3;
    --sidebar-text: rgba(255, 255, 255, .82);
    --sidebar-muted: rgba(255, 255, 255, .45);
    --sidebar-width: 270px;
    --topbar-h: 64px;
    --card-radius: 14px;
    --font: 'Plus Jakarta Sans', sans-serif;
    --mono: 'DM Mono', monospace;
    --bg: #EFF6FF;
    --surface: #ffffff;
    --text: #1a2744;
    --muted: #64748B;
    --border: #c9d1db;
    --input-border: #94A3B8;
  }

  *,
  *::before,
  *::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
  }

  body {
    font-family: var(--font);
    background: var(--bg);
    color: var(--text);
    overflow-x: hidden;
    font-size: 14px;
  }

  /* ───────── SIDEBAR ───────── */
  #sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: var(--sidebar-width);
    height: 100vh;
    background: linear-gradient(175deg, var(--sidebar-bg) 0%, #0a3880 100%);
    display: flex;
    flex-direction: column;
    z-index: 1050;
    transition: transform .3s cubic-bezier(.4, 0, .2, 1);
    overflow-y: auto;
    overflow-x: hidden;
  }

  #sidebar::-webkit-scrollbar {
    width: 4px;
  }

  #sidebar::-webkit-scrollbar-track {
    background: transparent;
  }

  #sidebar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, .2);
    border-radius: 4px;
  }

  .sidebar-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0 22px;
    height: var(--topbar-h);
    border-bottom: 1px solid rgba(255, 255, 255, .08);
    flex-shrink: 0;
  }

  .sidebar-brand .logo-wrap {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, #42A5F5, #00BCD4);
    display: grid;
    place-items: center;
    font-size: 18px;
    color: #fff;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0, 188, 212, .4);
  }

  .sidebar-brand .brand-text {
    line-height: 1.15;
  }

  .sidebar-brand .brand-name {
    font-size: 15px;
    font-weight: 800;
    color: #fff;
    letter-spacing: .2px;
  }

  .sidebar-brand .brand-sub {
    font-size: 10px;
    color: var(--sidebar-muted);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  .sidebar-section {
    font-size: 9.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.4px;
    color: var(--sidebar-muted);
    padding: 18px 22px 6px;
    user-select: none;
  }

  /* NAV ITEMS — level 1 */
  .nav-link-l1 {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 22px;
    color: var(--sidebar-text);
    font-size: 13.5px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    text-decoration: none;
    border-left: 3px solid transparent;
    transition: background .15s, color .15s, border-color .15s;
    border-radius: 0 8px 8px 0;
    margin-right: 8px;
    position: relative;
  }

  .nav-link-l1:hover {
    background: rgba(255, 255, 255, .07);
    color: #fff;
  }

  .nav-link-l1.active {
    background: rgba(33, 150, 243, .28);
    color: #fff;
    border-left-color: #42A5F5;
    font-weight: 600;
  }

  .nav-link-l1 .nav-icon {
    width: 22px;
    font-size: 14px;
    text-align: center;
    flex-shrink: 0;
  }

  .nav-link-l1 .nav-badge {
    margin-left: auto;
    background: var(--warn);
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    padding: 1px 6px;
    border-radius: 20px;
  }

  .nav-link-l1 .nav-arrow {
    margin-left: auto;
    font-size: 10px;
    transition: transform .25s;
  }

  .nav-link-l1[aria-expanded="true"] .nav-arrow {
    transform: rotate(90deg);
  }

  /* level 2 */
  .nav-sub {
    list-style: none;
    padding: 0;
    background: rgba(0, 0, 0, .12);
    border-left: none;
  }

  .nav-link-l2 {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 7px 22px 7px 46px;
    color: var(--sidebar-text);
    font-size: 12.8px;
    font-weight: 400;
    cursor: pointer;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    text-decoration: none;
    border-left: 3px solid transparent;
    transition: background .15s, color .15s;
  }

  .nav-link-l2:hover {
    background: rgba(255, 255, 255, .06);
    color: #fff;
  }

  .nav-link-l2.active {
    color: #90CAF9;
    border-left-color: #90CAF9;
    font-weight: 600;
  }

  .nav-link-l2 .nav-arrow {
    margin-left: auto;
    font-size: 10px;
    transition: transform .25s;
  }

  .nav-link-l2[aria-expanded="true"] .nav-arrow {
    transform: rotate(90deg);
  }

  /* level 3 */
  .nav-sub2 {
    list-style: none;
    padding: 0;
    background: rgba(0, 0, 0, .08);
  }

  .nav-link-l3 {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 22px 6px 68px;
    color: var(--sidebar-muted);
    font-size: 12px;
    font-weight: 400;
    cursor: pointer;
    text-decoration: none;
    transition: color .15s;
  }

  .nav-link-l3:hover {
    color: #90CAF9;
  }

  .nav-link-l3.active {
    color: #90CAF9;
    font-weight: 600;
  }

  .nav-link-l3::before {
    content: '';
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: currentColor;
    flex-shrink: 0;
  }

  /* ───────── TOPBAR ───────── */
  #topbar {
    position: fixed;
    top: 0;
    left: var(--sidebar-width);
    right: 0;
    height: var(--topbar-h);
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    padding: 0 24px;
    gap: 16px;
    z-index: 1040;
    box-shadow: 0 2px 12px rgba(33, 150, 243, .06);
    transition: left .3s cubic-bezier(.4, 0, .2, 1);
  }

  #topbar .btn-toggle {
    background: none;
    border: none;
    font-size: 20px;
    color: var(--primary);
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 8px;
  }

  #topbar .btn-toggle:hover {
    background: var(--primary-xlight);
  }

  .topbar-breadcrumb {
    font-size: 13px;
    color: var(--muted);
  }

  .topbar-breadcrumb .page-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--text);
    display: block;
  }

  .topbar-search {
    margin-left: auto;
    position: relative;
  }

  .topbar-search input {
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: 7px 16px 7px 38px;
    font-size: 13px;
    font-family: var(--font);
    background: var(--bg);
    color: var(--text);
    width: 220px;
    transition: border-color .2s, width .3s;
  }

  .topbar-search input:focus {
    outline: none;
    border-color: var(--primary);
    width: 280px;
    background: #fff;
  }

  .topbar-search .search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--muted);
    font-size: 13px;
  }

  .topbar-actions {
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .topbar-actions .btn-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: grid;
    place-items: center;
    background: var(--bg);
    border: 1.5px solid var(--border);
    color: var(--text);
    font-size: 15px;
    cursor: pointer;
    position: relative;
    transition: background .15s;
  }

  .topbar-actions .btn-icon:hover {
    background: var(--primary-xlight);
    border-color: var(--primary-light);
  }

  .topbar-actions .badge-dot {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--danger);
    border: 2px solid var(--surface);
  }

  .page-title {
    color: var(--text);
    font-size: 24px;
    font-weight: 700;
    margin: 0;
  }

  .page-sub {
    color: var(--muted);
    font-size: 13px;
    font-weight: 500;
    margin: 0;
  }

  .user-chip {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 4px 8px 4px 4px;
    border-radius: 12px;
    border: 1.5px solid var(--border);
    background: var(--bg);
    cursor: pointer;
    transition: background .15s;
  }

  .user-chip:hover {
    background: var(--primary-xlight);
  }

  .user-chip .avatar {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    display: grid;
    place-items: center;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
  }

  .user-chip .user-name {
    font-size: 12.5px;
    font-weight: 600;
  }

  .user-chip .user-role {
    font-size: 10.5px;
    color: var(--muted);
    line-height: 1;
  }

  /* ───────── MAIN ───────── */
  #main {
    margin-left: var(--sidebar-width);
    padding-top: var(--topbar-h);
    min-height: 100vh;
    transition: margin-left .3s cubic-bezier(.4, 0, .2, 1);
  }

  .page-content {
    padding: 20px 28px 48px;
  }

  /* ───────── CARDS ───────── */
  .card {
    border: 1.5px solid var(--border);
    border-radius: var(--card-radius);
    background: var(--surface);
    box-shadow: 0 2px 12px rgba(33, 150, 243, .05);
  }

  .card-header {
    background: transparent;
    border-bottom: 1.5px solid var(--border);
    padding: 14px 14px;
  }

  .card-title {
    font-size: 14px;
    font-weight: 700;
    margin: 0;
  }

  /* ───────── STAT CARDS ───────── */
  .stat-card {
    border-radius: var(--card-radius);
    padding: 20px 22px;
    position: relative;
    overflow: hidden;
    border: none;
  }

  .stat-card::after {
    content: '';
    position: absolute;
    top: -20px;
    right: -20px;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(255, 255, 255, .12);
  }

  .stat-card .stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: rgba(255, 255, 255, .2);
    display: grid;
    place-items: center;
    font-size: 18px;
    color: #fff;
    margin-bottom: 14px;
  }

  .stat-card .stat-val {
    font-size: 26px;
    font-weight: 800;
    color: #fff;
    font-family: var(--mono);
    line-height: 1;
  }

  .stat-card .stat-label {
    font-size: 12px;
    color: rgba(255, 255, 255, .75);
    margin-top: 4px;
    font-weight: 500;
  }

  .stat-card .stat-change {
    font-size: 11.5px;
    font-weight: 600;
    margin-top: 10px;
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .stat-card .stat-change.up {
    color: #A5D6A7;
  }

  .stat-card .stat-change.down {
    color: #FFAB91;
  }

  .bg-grad-blue {
    background: linear-gradient(135deg, #1E88E5, #1565C0);
    box-shadow: 0 8px 24px rgba(30, 136, 229, .35);
  }

  .bg-grad-cyan {
    background: linear-gradient(135deg, #00ACC1, #006064);
    box-shadow: 0 8px 24px rgba(0, 172, 193, .35);
  }

  .bg-grad-green {
    background: linear-gradient(135deg, #43A047, #1B5E20);
    box-shadow: 0 8px 24px rgba(67, 160, 71, .35);
  }

  .bg-grad-orange {
    background: linear-gradient(135deg, #FB8C00, #E65100);
    box-shadow: 0 8px 24px rgba(251, 140, 0, .35);
  }

  .bg-grad-purple {
    background: linear-gradient(135deg, #7E57C2, #4527A0);
    box-shadow: 0 8px 24px rgba(126, 87, 194, .35);
  }

  .bg-grad-red {
    background: linear-gradient(135deg, #E53935, #B71C1C);
    box-shadow: 0 8px 24px rgba(229, 57, 53, .35);
  }

  /* ───────── MINI CHARTS (SVG sparklines placeholder) ───────── */
  .sparkline {
    width: 100%;
    height: 48px;
  }

  /* ───────── TABLE ───────── */
  .table-responsive {
    overflow: visible !important;
  }

  .table {
    font-size: 13px;
  }

  .table thead th,
  table.dataTable thead th,
  table.dataTable thead td {
    background: var(--primary-xlight) !important;
    background-color: var(--primary-xlight) !important;
    color: var(--primary-dark) !important;
    font-weight: 700;
    font-size: 11.5px;
    text-transform: uppercase;
    letter-spacing: .5px;
    border-color: var(--border) !important;
  }

  .table tbody td {
    vertical-align: middle;
    border-color: var(--border);
  }

  .table-hover tbody tr:hover {
    background: var(--primary-xlight);
  }

  /* ───────── BADGES / STATUS ───────── */
  .status-badge {
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
  }

  .status-paid {
    background: #E8F5E9;
    color: #2E7D32;
  }

  .status-pending {
    background: #FFF8E1;
    color: #F57F17;
  }

  .status-overdue {
    background: #FFEBEE;
    color: #C62828;
  }

  .status-draft {
    background: #E3F2FD;
    color: #1565C0;
  }

  .status-in {
    background: #E0F7FA;
    color: #006064;
  }

  .status-out {
    background: #FFF3E0;
    color: #E65100;
  }

  .status-low {
    background: #FFEBEE;
    color: #C62828;
  }

  /* ───────── PROGRESS ───────── */
  .progress {
    height: 7px;
    border-radius: 10px;
    background: var(--primary-xlight);
  }

  .progress-bar {
    border-radius: 10px;
  }

  /* ───────── TIMELINE ───────── */
  .timeline {
    list-style: none;
    padding: 0;
    position: relative;
  }

  .timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--border);
  }

  .timeline li {
    padding: 0 0 20px 44px;
    position: relative;
  }

  .timeline li:last-child {
    padding-bottom: 0;
  }

  .timeline-dot {
    position: absolute;
    left: 7px;
    top: 2px;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    border: 2px solid #fff;
    display: grid;
    place-items: center;
    font-size: 8px;
    color: #fff;
  }

  .timeline-time {
    font-size: 11px;
    color: var(--muted);
  }

  .timeline-text {
    font-size: 13px;
    margin-top: 2px;
  }

  /* ───────── ACCORDION ───────── */
  .accordion-item {
    border: 1.5px solid var(--border);
    border-radius: 12px !important;
    overflow: hidden;
    margin-bottom: 12px;
    background: #fff;
  }

  .accordion-button {
    border-radius: 12px !important;
    font-weight: 600;
    font-size: 14px;
    color: var(--text);
    padding: 12px 16px;
  }

  .accordion-button:not(.collapsed) {
    background: var(--primary-xlight);
    color: var(--primary-dark);
    box-shadow: none;
    border-bottom: 1.5px solid var(--border);
    border-radius: 12px 12px 0 0 !important;
  }

  .accordion-button:focus {
    box-shadow: none;
    border-color: var(--border);
  }

  .accordion-body {
    padding: 16px;
    font-size: 13.5px;
    color: var(--muted);
    line-height: 1.6;
  }

  /* ───────── ALERTS ───────── */
  .alert {
    border-radius: 10px;
    font-size: 13px;
    border: none;
  }

  /* ───────── QUICK FORMS ───────── */
  .form-control:not(textarea),
  .form-select {
    border: 1.5px solid var(--input-border);
    border-radius: 9px;
    font-size: 14px;
    font-family: var(--font);
    background: #ffffff;
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.02);
    height: 36px !important;
    padding: 4px 12px !important;
  }

  textarea.form-control {
    border: 1.5px solid var(--input-border);
    border-radius: 9px;
    font-size: 14px;
    font-family: var(--font);
    background: #ffffff;
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.02);
    height: auto !important;
    padding: 8px 12px !important;
  }

  .form-control:focus,
  .form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(33, 150, 243, .15);
    background: #fff;
  }

  .form-label,
  .col-form-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text);
  }

  .invalid-feedback {
    font-size: 13px;
    font-weight: 600;
    margin-top: 4px;
  }

  .input-group-text {
    background: var(--primary-xlight);
    border: 1.5px solid var(--border);
    border-radius: 9px 0 0 9px;
    font-size: 14px;
    color: var(--primary-dark);
    height: 36px !important;
    padding: 4px 12px !important;
  }

  .required::after {
  content: " *";
  color: #ef4444;
  font-weight: 700;
  margin-left: 3px;
}

  /* ───────── CHECKBOX & RADIO ───────── */
  .form-check-input {
    border: 1.5px solid var(--input-border);
    cursor: pointer;
  }

  .form-check-input:checked {
    background-color: var(--primary);
    border-color: var(--primary);
    box-shadow: 0 2px 4px rgba(33, 150, 243, .2);
  }

  .form-check-input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(33, 150, 243, .15);
  }

  .form-check-label {
    font-size: 13.5px;
    color: var(--text);
    cursor: pointer;
    user-select: none;
    padding-top: 2px;
  }

  /* ───────── BUTTONS ───────── */
  .btn {
    font-size: 14px;
    border-radius: 8px;
    font-weight: 600;
    transition: all .2s;
    height: 36px !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 16px !important;
    line-height: normal !important;
  }

  .btn-sm {
    font-size: 12px;
    padding: 0 10px !important;
    border-radius: 6px;
    height: 30px !important;
  }

  .btn-xs {
    font-size: 12px;
    padding: 0 10px !important;
    border-radius: 6px;
    height: 26px !important;
  }

  .btn-primary {
    /* background: var(--primary); */
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-color: var(--primary);
    color: #fff;
  }

  .btn-outline-primary {
    border-color: var(--primary);
    color: var(--primary);
  }

  .btn-success {
    background: var(--accent2);
    border-color: var(--accent2);
    color: #fff;
  }

  /* ───────── DROPDOWN ───────── */
  .dropdown-menu {
    border-radius: 8px;
    padding: 4px;
    font-size: 12px;
    border: 1px solid var(--border);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
  }

  .dropdown-item {
    border-radius: 6px;
    padding: 4px 10px;
    min-height: 30px;
    display: flex;
    align-items: center;
    transition: background .15s;
  }

  .dropdown-item:hover,
  .dropdown-item:focus {
    background-color: var(--primary-xlight);
    color: var(--primary-dark);
  }

  /* ───────── DONUT CHART MOCK ───────── */
  .donut-wrap {
    position: relative;
    width: 140px;
    height: 140px;
    margin: 0 auto;
  }

  .donut-wrap svg {
    transform: rotate(-90deg);
  }

  .donut-center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
  }

  .donut-center .val {
    font-size: 22px;
    font-weight: 800;
    color: var(--text);
    font-family: var(--mono);
  }

  .donut-center .lbl {
    font-size: 10px;
    color: var(--muted);
  }

  /* ───────── RESPONSIVE ───────── */
  @media (max-width: 991px) {
    #sidebar {
      transform: translateX(-100%);
    }

    #sidebar.open {
      transform: translateX(0);
    }

    #main {
      margin-left: 0;
    }

    #topbar {
      left: 0;
    }

    .sidebar-overlay.show {
      display: block;
    }
  }

  /* Desktop Collapsed */
  @media (min-width: 992px) {
    body.sidebar-collapsed #sidebar {
      transform: translateX(-100%);
    }

    body.sidebar-collapsed #main {
      margin-left: 0;
    }

    body.sidebar-collapsed #topbar {
      left: 0;
    }
  }

  .sidebar-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, .4);
    z-index: 1049;
  }

  /* ───────── SCROLL ANIM ───────── */
  .fade-up {
    opacity: 0;
    transform: translateY(14px);
    animation: fadeUp .4s forwards;
  }

  @keyframes fadeUp {
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .delay-1 {
    animation-delay: .05s;
  }

  .delay-2 {
    animation-delay: .10s;
  }

  .delay-3 {
    animation-delay: .15s;
  }

  .delay-4 {
    animation-delay: .20s;
  }

  .delay-5 {
    animation-delay: .25s;
  }

  .delay-6 {
    animation-delay: .30s;
  }

  .delay-7 {
    animation-delay: .35s;
  }

  .delay-8 {
    animation-delay: .40s;
  }

  /* ───────── MISC ───────── */
  .section-divider {
    border: none;
    border-top: 1.5px dashed var(--border);
    margin: 24px 0;
  }

  .tab-content {
    padding-top: 16px;
  }

  .nav-tabs .nav-link {
    font-size: 13px;
    color: var(--muted);
  }

  .nav-tabs .nav-link.active {
    color: var(--primary);
    font-weight: 700;
    border-color: var(--border) var(--border) #fff;
  }

  .list-group-item {
    border-color: var(--border);
    font-size: 13px;
  }

  .list-group-item-action:hover {
    background: var(--primary-xlight);
  }

  /* ───────── DATATABLES CUSTOM (v2) ───────── */
  .dt-container .dt-info {
    font-size: 14px;
    color: var(--muted);
    padding-top: 15px !important;
  }

  .dt-container .dt-paging {
    padding-top: 12px !important;
  }

  .dt-container .pagination {
    margin-bottom: 0;
    gap: 3px;
  }

  .dt-container .page-link {
    height: 30px !important;
    min-width: 30px;
    padding: 0 10px !important;

    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;

    font-size: 12px !important;
    font-weight: 600;
    line-height: 1 !important;

    border-radius: 6px !important;
    border: 1.5px solid var(--input-border);

    background: #fff;
    color: var(--muted);

    transition: all .18s ease;
  }

  .dt-container .page-item.active .page-link {
    background: var(--primary);
    border-color: var(--primary);
    color: #fff;
    box-shadow: 0 2px 6px rgba(33, 150, 243, .22);
  }

  .dt-container .page-link:hover {
    background: var(--primary-xlight);
    border-color: var(--primary-light);
    color: var(--primary-dark);
  }

  .dt-container .dt-length {
    font-size: 14px;
    color: var(--muted);
    margin-bottom: 12px;
  }

  .dt-container .dt-length select {
    padding: 4px 30px 4px 10px;
    font-size: 14px;
    border-radius: 8px;
    border: 1.5px solid var(--border);
  }

  .dt-container .dt-search {
    font-size: 14px;
    color: var(--muted);
    margin-bottom: 12px;
  }

  .dt-container .dt-search input {
    padding: 6px 12px;
    font-size: 14px;
    border-radius: 8px;
    border: 1.5px solid var(--border);
    background: var(--bg);
    transition: all .2s;
  }

  .dt-container .dt-search input:focus {
    outline: none;
    border-color: var(--primary);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(33, 150, 243, .1);
  }

  table.dataTable thead th {
    border-bottom: 1.5px solid var(--border) !important;
  }

  table.dataTable.no-footer {
    border-bottom: none !important;
  }

  #datatable-main_wrapper>.row>* {
    padding: 0;
  }

  /* ───────── MODALS ───────── */
  .modal-content {
    border-radius: 16px;
    border: 1.5px solid var(--border);
  }

  .modal-header {
    background: var(--primary-xlight);
    border-bottom: 1.5px solid var(--border);
    border-radius: 14px 14px 0 0;
    padding: 12px 14px;
  }

  .modal-title {
    font-size: 14px;
    font-weight: 700;
    margin: 0;
  }

  /* ───────── SELECT2 CUSTOM ───────── */
  .select2-container--bootstrap-5 .select2-selection {
    border: 1.5px solid var(--input-border) !important;
    border-radius: 9px !important;
    height: 36px !important;
    font-size: 14px !important;
    font-family: var(--font) !important;
    background-color: #fff !important;
    display: flex !important;
    align-items: center !important;
  }

  .select2-container--bootstrap-5.select2-container--focus .select2-selection {
    border-color: var(--primary) !important;
    box-shadow: 0 0 0 4px rgba(33, 150, 243, .15) !important;
  }

  .select2-container--bootstrap-5 .select2-dropdown {
    border-color: var(--border) !important;
    border-radius: 10px !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, .08) !important;
  }

  .select2-results__option {
    font-size: 14px !important;
    padding: 8px 12px !important;
  }

  .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    line-height: 33px !important;
    color: var(--text) !important;
  }

  .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
    height: 33px !important;
  }
</style>