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
        <button type="button" class="btn btn-primary rounded-3 px-4 shadow-sm" onclick="fsModalShow(event, {url: '{{ $nav_url }}/form_modal?n={{ $nav_id }}', title: 'Tambah Tarif Baru'})">
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
          <div class="col-md-6 col-lg-4">
            <label class="form-label mb-1" style="font-weight: 600; font-size: 13px;"><i class="fas fa-filter text-primary me-1"></i> Kelompok Tarif</label>
            <div class="d-flex gap-2 align-items-center">
              <div class="flex-grow-1">
                <select class="form-select form-select-sm fs-chose" id="filter_tarif_tp">
                  <option value="" {{ session('tarif_parent_filter') === '' || session('tarif_parent_filter') === null ? 'selected' : '' }}>Semua Kelompok Tarif</option>
                  @foreach($list_paket as $paket)
                  <option value="{{ $paket->tarif_id }}" {{ session('tarif_parent_filter') === $paket->tarif_id ? 'selected' : '' }}>
                    {{ $paket->tarif_nm }} ({{ $paket->tarif_id }})
                  </option>
                  @endforeach
                </select>
              </div>
              <button type="button" class="btn btn-outline-secondary px-3" id="btnResetFilter" title="Reset Pencarian" style="height: 38px;">
                <i class="fas fa-undo me-1"></i>Reset
              </button>
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
            <div class="table-responsive">
              <table id="datatable-main" class="table table-hover table-striped align-middle w-100" style="font-size:13px">
                <thead>
                  <tr>
                    <th class="text-center" width="70">No</th>
                    {{-- FITUR AKSI - Uncomment jika diperlukan
                    <th class="text-start" width="80">Aksi</th>
                    --}}
                    <th class="text-start">ID Tarif</th>
                    <th class="text-start">Kode Tarif</th>
                    <th class="text-start">Nama Tarif</th>
                    {{-- Hidden columns as requested
                    <th class="text-start">Inacbg ID</th>
                    <th class="text-start">Kelompok Kelas ID</th>
                    --}}
                    <th class="text-start">Unit Cost</th>
                    <th class="text-start">Nominal (SIMRS)</th>
                    <th class="text-center" width="80">Aktif</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div><!-- /page-content -->
</main>

@include($template.'indexJs')