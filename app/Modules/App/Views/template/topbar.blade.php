<!-- ═══════════════════════════════════════════
  TOPBAR
════════════════════════════════════════════ -->
<header id="topbar">
  <button class="btn-toggle" onclick="toggleSidebar()"><i class="fa-solid fa-bars"></i></button>
  <div class="topbar-breadcrumb">
    @if (!empty($parent_title) && $parent_title !== $module_title)
      <small>{{ $module_title ?? 'AccuCount' }} / {{ $parent_title }} /</small>
    @else
      <small>{{ $module_title ?? 'AccuCount' }} /</small>
    @endif
    <span class="page-title" id="pageTitle">{{ $menu_title ?? 'Dashboard' }}</span>
  </div>
  <div class="topbar-search">
    <i class="fa-solid fa-magnifying-glass search-icon"></i>
    <input type="text" placeholder="Cari transaksi, barang…" id="globalSearch" />
  </div>
  <div class="topbar-actions">
    <div class="btn-icon" title="Notifikasi" onclick="toastr.info('Anda memiliki 3 notifikasi baru.')">
      <i class="fa-solid fa-bell"></i>
      <span class="badge-dot"></span>
    </div>
    <div class="btn-icon" title="Pesan">
      <i class="fa-solid fa-envelope"></i>
    </div>
    <div class="btn-icon" title="Kalender">
      <i class="fa-solid fa-calendar-days"></i>
    </div>
    <div class="user-chip dropdown" data-bs-toggle="dropdown">
      <div class="avatar">
        {{ strtoupper(substr(session('nama') ?? session('username') ?? 'U', 0, 2)) }}
      </div>
      <div>
        <div class="user-name">{{ session('nama') ?? session('username') ?? 'User' }}</div>
        <div class="user-role">{{ session('role_nm') ?? 'Administrator' }}</div>
      </div>
      <i class="fa-solid fa-chevron-down ms-1" style="font-size:9px; color:var(--muted)"></i>
    </div>
    <ul class="dropdown-menu dropdown-menu-end shadow" style="font-size:13px; border-color:var(--border); border-radius:12px;">
      <li><a class="dropdown-item" href="#"><i class="fa-solid fa-user me-2 text-primary"></i> Profil Saya</a></li>
      <li><a class="dropdown-item" href="#"><i class="fa-solid fa-building me-2 text-primary"></i> Profil Perusahaan</a></li>
      <li><a class="dropdown-item" href="#"><i class="fa-solid fa-gear me-2 text-primary"></i> Pengaturan</a></li>
      <li>
        <hr class="dropdown-divider" />
      </li>
      <li><a class="dropdown-item text-danger" href="#" onclick="confirmLogout()"><i class="fa-solid fa-right-from-bracket me-2"></i> Keluar</a></li>
    </ul>
  </div>
</header>