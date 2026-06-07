<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- ═══════════════════════════════════════════
     SIDEBAR
════════════════════════════════════════════ -->
<nav id="sidebar">
  <!-- Brand -->
  <div class="sidebar-brand">
    <div class="logo-wrap">
      <img src="{{ asset('favicon.png') }}" alt="Logo" style="width: 24px; height: 24px; object-fit: contain;">
    </div>
    <div class="brand-text">
      <span class="brand-name">{{ $identitas['aplikasi_merk'] }}</span><br>
      <span class="brand-sub">{{ $identitas['aplikasi_singkatan_nm'] }}</span>
    </div>
  </div>

  <!-- Dynamic Menu -->
  <ul class="list-unstyled mb-0 mt-4">
    @forelse ($nav_list ?? [] as $l1)
      @php
        $l1_id   = 'nav-' . md5($l1['nav_id']);
        $l1_url  = !empty($l1['nav_url']) ? url($l1['nav_url'] . '?n=' . md5($l1['nav_id'])) : '#';
        $hasL1Child = !empty($l1['child']);

        $l1Active = ($nav_id ?? '') === md5($l1['nav_id']);

        if (!$l1Active && $hasL1Child) {
          foreach ($l1['child'] as $l2) {
            if (($nav_id ?? '') === md5($l2['nav_id'])) { $l1Active = true; break; }
            if (!empty($l2['child'])) {
              foreach ($l2['child'] as $l3) {
                if (($nav_id ?? '') === md5($l3['nav_id'])) { $l1Active = true; break 2; }
              }
            }
          }
        }

        $showSection = !empty($l1['module_st']) && $l1['module_st'] == 1;
      @endphp

      {{-- Section Divider --}}
      @if ($showSection)
        <div class="sidebar-section">{{ $l1['nav_nm'] }}</div>
      @elseif ($hasL1Child)
        {{-- Level 1 with children → collapsible button --}}
        <li>
          <button class="nav-link-l1 {{ $l1Active ? 'active' : '' }}"
                  data-bs-toggle="collapse"
                  data-bs-target="#{{ $l1_id }}"
                  aria-expanded="{{ $l1Active ? 'true' : 'false' }}">
            <span class="nav-icon">
              @if (!empty($l1['nav_icon']))
                <i class="{{ $l1['nav_icon'] }}"></i>
              @else
                <i class="fa-solid fa-circle-dot"></i>
              @endif
            </span>
            {{ $l1['nav_nm'] }}
            <i class="fa-solid fa-chevron-right nav-arrow ms-auto"></i>
          </button>

          <ul class="collapse nav-sub {{ $l1Active ? 'show' : '' }}" id="{{ $l1_id }}">
            @foreach ($l1['child'] as $l2)
              @php
                $l2_id  = 'nav-' . md5($l2['nav_id']);
                $l2_url = !empty($l2['nav_url']) ? url($l2['nav_url'] . '?n=' . md5($l2['nav_id'])) : '#';
                $hasL2Child = !empty($l2['child']);

                $l2Active = ($nav_id ?? '') === md5($l2['nav_id']);
                if (!$l2Active && $hasL2Child) {
                  foreach ($l2['child'] as $l3) {
                    if (($nav_id ?? '') === md5($l3['nav_id'])) { $l2Active = true; break; }
                  }
                }
              @endphp

              @if ($hasL2Child)
                {{-- Level 2 with children → collapsible --}}
                <li>
                  <button class="nav-link-l2 {{ $l2Active ? 'active' : '' }}"
                          data-bs-toggle="collapse"
                          data-bs-target="#{{ $l2_id }}"
                          aria-expanded="{{ $l2Active ? 'true' : 'false' }}">
                    @if (!empty($l2['nav_icon']))
                      <i class="{{ $l2['nav_icon'] }}" style="width:16px"></i>
                    @else
                      <i class="fa-solid fa-minus" style="width:16px"></i>
                    @endif
                    {{ $l2['nav_nm'] }}
                    <i class="fa-solid fa-chevron-right nav-arrow"></i>
                  </button>

                  <ul class="collapse nav-sub2 {{ $l2Active ? 'show' : '' }}" id="{{ $l2_id }}">
                    @foreach ($l2['child'] as $l3)
                      @php
                        $l3_url    = !empty($l3['nav_url']) ? url($l3['nav_url'] . '?n=' . md5($l3['nav_id'])) : '#';
                        $l3Active  = ($nav_id ?? '') === md5($l3['nav_id']);
                      @endphp
                      <li>
                        <a href="{{ $l3_url }}"
                           class="nav-link-l3 {{ $l3Active ? 'active' : '' }}">
                          {{ $l3['nav_nm'] }}
                        </a>
                      </li>
                    @endforeach
                  </ul>
                </li>
              @else
                {{-- Level 2 tanpa children → link biasa --}}
                <li>
                  <a href="{{ $l2_url }}"
                     class="nav-link-l2 {{ $l2Active ? 'active' : '' }}">
                    @if (!empty($l2['nav_icon']))
                      <i class="{{ $l2['nav_icon'] }}" style="width:16px"></i>
                    @else
                      <i class="fa-solid fa-minus" style="width:16px"></i>
                    @endif
                    {{ $l2['nav_nm'] }}
                  </a>
                </li>
              @endif
            @endforeach
          </ul>
        </li>
      @else
        {{-- Level 1 tanpa children → link biasa --}}
        <li>
          <a href="{{ $l1_url }}"
             class="nav-link-l1 {{ $l1Active ? 'active' : '' }}">
            <span class="nav-icon">
              @if (!empty($l1['nav_icon']))
                <i class="{{ $l1['nav_icon'] }}"></i>
              @else
                <i class="fa-solid fa-circle-dot"></i>
              @endif
            </span>
            {{ $l1['nav_nm'] }}
          </a>
        </li>
      @endif
    @empty
      <li>
        <span class="nav-link-l1 text-muted" style="cursor:default">
          <span class="nav-icon"><i class="fa-solid fa-triangle-exclamation"></i></span>
          Tidak ada menu
        </span>
      </li>
    @endforelse
  </ul>

  <!-- Footer -->
  <div class="mt-auto p-3" style="font-size:11px; color:var(--sidebar-muted); border-top:1px solid rgba(255,255,255,.07); margin-top: 20px;">
    <div style="font-weight:600; color:rgba(255,255,255,.5);">{{ $identitas['aplikasi_merk'] }} v{{ $identitas['aplikasi_versi'] }}</div>
    <div>© {{ date('Y') }} {{ $identitas['perusahaan_nm'] }}</div>
  </div>
</nav>