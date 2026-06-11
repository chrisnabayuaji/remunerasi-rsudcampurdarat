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
        <button type="button" class="btn btn-primary rounded-3 px-4 shadow-sm" onclick="fsModalShow(event, {url: '{{ $nav_url }}/form_modal?n={{ $nav_id }}', title: 'Tambah Kelas Baru'})">
          <i class="fas fa-plus me-2"></i>Tambah Data
        </button>
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
                    <th class="text-start" width="80">Aksi</th>
                    <th class="text-start">ID Kelas</th>
                    <th class="text-start">Nama Kelas</th>
                    <th class="text-start">Singkatan</th>
                    <th class="text-start">Kelas BPJS</th>
                    <th class="text-start">Kelas Eklaim</th>
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
