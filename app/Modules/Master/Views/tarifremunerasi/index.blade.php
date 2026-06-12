<style>
  .dt-layout-table {
    overflow-x: auto !important;
    position: relative;
  }

  #datatable-main {
    border-collapse: separate;
    border-spacing: 0;
    width: max-content !important;
    min-width: 100%;
  }

  #datatable-main th:nth-child(1),
  #datatable-main td:nth-child(1) {
    position: sticky;
    left: 0;
    background-color: #fff !important;
    z-index: 5;
    box-shadow: inset -1px 0 0 #dee2e6;
  }

  #datatable-main th:nth-child(2),
  #datatable-main td:nth-child(2) {
    position: sticky;
    left: 70px;
    background-color: #fff !important;
    z-index: 5;
    box-shadow: inset -1px 0 0 #dee2e6;
  }

  #datatable-main th:nth-child(3),
  #datatable-main td:nth-child(3) {
    position: sticky;
    left: 190px;
    background-color: #fff !important;
    z-index: 5;
    box-shadow: inset -2px 0 0 #adb5bd; /* Stronger border at the freeze boundary */
  }

  /* Make sure header stays on top */
  #datatable-main th:nth-child(1),
  #datatable-main th:nth-child(2),
  #datatable-main th:nth-child(3) {
    z-index: 6;
    background-color: #f8f9fa !important;
  }

  /* Ensure hover effect stays consistent */
  #datatable-main tr:hover td:nth-child(1),
  #datatable-main tr:hover td:nth-child(2),
  #datatable-main tr:hover td:nth-child(3) {
    background-color: #eceff1 !important;
  }
</style>

<main id="main">
  <div class="page-content">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <div>
        <h4 class="page-title mb-0">{{ $menu_title }}</h4>
        <div class="page-sub">{{ $parent_title }}</div>
      </div>
      <div class="d-flex gap-2">
        <button type="button" class="btn btn-info rounded-3 px-4 shadow-sm" id="btnSync" title="Sinkronisasi Data dari SIMRS">
          <i class="fas fa-sync me-2"></i>Sinkronisasi
        </button>
        {{-- FITUR TAMBAH DATA - Uncomment jika diperlukan
        <button type="button" class="btn btn-primary rounded-3 px-4 shadow-sm" onclick="fsModalShow(event, {url: '{{ $nav_url }}/form_modal?n={{ $nav_id }}', title: 'Tambah Tarif Remunerasi Baru'})">
          <i class="fas fa-plus me-2"></i>Tambah Data
        </button>
        --}}
      </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="alert alert-info fade show" role="alert" id="syncInfo">
      <i class="fas fa-info-circle me-2"></i>
      <strong>Informasi Sinkronisasi:</strong>
      @if($last_sync)
        Terakhir sinkronisasi: {{ \Carbon\Carbon::parse($last_sync->synced_at)->format('d M Y H:i:s') }}
        ({{ $last_sync->records_synced }} data)
      @else
        Belum pernah dilakukan sinkronisasi
      @endif
    </div>

    <div class="row g-3 mb-3">
      <div class="card shadow-sm border-0">
        <div class="card-body p-3">
          <div class="row g-3">
            <div class="col-md-6 col-lg-4">
              <label class="form-label mb-1" style="font-weight: 600; font-size: 13px;"><i class="fas fa-filter text-primary me-1"></i> Kelompok Tarif</label>
              <div class="d-flex gap-2 align-items-center">
                <div class="flex-grow-1">
                  <select class="form-select form-select-sm fs-chose" id="filter_tarif_tp">
                    <option value="" {{ ($nav_sess['tarif_tp'] ?? '') === '' ? 'selected' : '' }}>Semua Kelompok Tarif</option>
                    @foreach($list_paket as $paket)
                    <option value="{{ $paket->tarif_id }}" {{ ($nav_sess['tarif_tp'] ?? '') === $paket->tarif_id ? 'selected' : '' }}>
                      {{ $paket->tarif_nm }} ({{ $paket->tarif_id }})
                    </option>
                    @endforeach
                  </select>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary px-3" id="btnResetFilter" title="Reset Pencarian" style="height: 38px;">
                  <i class="fas fa-undo me-1"></i>Reset
                </button>
              </div>
            </div>

            <div class="col-md-6 col-lg-4">
              <label class="form-label mb-1" style="font-weight: 600; font-size: 13px;"><i class="fas fa-search text-primary me-1"></i> Cari Secara Manual</label>
              <div class="d-flex gap-2 align-items-center">
                <input type="text" class="form-control form-control-sm" id="search_keyword" value="{{ $nav_sess['search'] ?? '' }}" placeholder="Cari Kode / Nama / ID..." style="height: 38px;">
                <button type="button" class="btn btn-sm btn-primary px-3" id="btnSearchSubmit" title="Cari Data" style="height: 38px;">
                  <i class="fas fa-search me-1"></i>Cari
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-12 fade-up delay-2">
        <div class="card">
          <div class="card-header">
            <h6 class="card-title"><i class="fas fa-list text-primary me-2"></i> List {{ $menu_title }}</h6>
          </div>
          <div class="card-body">
            <table id="datatable-main" class="table table-hover table-striped align-middle w-100" style="font-size:12px; white-space: nowrap;">
              <thead>
                <tr>
                  <th class="text-center" width="70">No</th>
                  {{-- FITUR AKSI - Uncomment jika diperlukan
                  <th class="text-start" width="80">Aksi</th>
                  --}}
                  <th class="text-start" width="120">ID Tarif</th>
                  <th class="text-start" width="300">Nama Tarif</th>
                  <th class="text-start">Nominal (SIMRS)</th>
                  <th class="text-start">Unit Cost</th>
                  <th class="text-start">Tarif - Unit Cost</th>
                  <th class="text-start">Jasa Sarana</th>
                  <th class="text-start">Jasa Layanan</th>
                  <th class="text-start">Cost Center</th>
                  <th class="text-start">Revenue Center</th>
                  <th class="text-start">Direksi</th>
                  <th class="text-start">Direktur</th>
                  <th class="text-start">Kabag/Kasie</th>
                  <th class="text-start">Post RM</th>
                  <th class="text-start">Dr Utama (Dr)</th>
                  <th class="text-start">Dr Utama (Prw)</th>
                  <th class="text-start">Prw Utama (Dr)</th>
                  <th class="text-start">Prw Utama (Prw)</th>
                  <th class="text-start">Dg Anest: Dr Op</th>
                  <th class="text-start">Dg Anest: Dr An</th>
                  <th class="text-start">Dg Anest: Prw</th>
                  <th class="text-start">Tpa Anest: Dr Op</th>
                  <th class="text-start">Tpa Anest: Prw</th>
                  <th class="text-start">Supir</th>
                  <th class="text-start">Rekam Medis</th>
                  <th class="text-start">CSSD Laundry</th>
                  <th class="text-start">Kantor</th>
                  <th class="text-start">Casemix</th>
                  <th class="text-center" width="80">Aktif</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div><!-- /page-content -->
</main>

@include($template.'indexJs')
