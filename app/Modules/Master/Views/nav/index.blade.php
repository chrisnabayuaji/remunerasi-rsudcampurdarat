<main id="main">
  <div class="page-content">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <div>
        <h4 class="page-title mb-0">{{ $menu_title }}</h4>
        <div class="page-sub">{{ $parent_title }}</div>
      </div>
      <button type="button" class="btn btn-primary rounded-3 px-4 shadow-sm" onclick="fsModalShow(event, {url: '{{ $nav_url }}/form_modal?n={{ $nav_id }}', title: 'Tambah Peran Baru'})">
        <i class="fas fa-plus me-2"></i>Tambah Data
      </button>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-12 fade-up delay-2">
        <div class="card">
          <div class="card-header">
            <h6 class="card-title"><i class="fas fa-list text-primary me-2"></i> List {{ $menu_title }}</h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="datatable-main" class="table table-striped table-hover align-middle w-100" style="font-size:13px">
                <thead>
                  <tr>
                    <th class="text-center" width="70">No</th>
                    <th class="text-start" width="80">Aksi</th>
                    <th class="text-start" width="100">ID</th>
                    <th class="text-start" width="100">Parent</th>
                    <th class="text-start" width="100">Module ID</th>
                    <th class="text-start">Nama Menu</th>
                    <th class="text-start">URL Path</th>
                    <th class="text-start">Icon Class</th>
                    <th class="text-center" width="80">Module?</th>
                    <th class="text-center" width="80">Aktif?</th>
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