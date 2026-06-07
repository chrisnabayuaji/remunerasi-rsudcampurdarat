<main id="main">
  <div class="page-content">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <div>
        <h4 class="page-title mb-0">{{ $menu_title }}</h4>
        <div class="page-sub">{{ $parent_title }}</div>
      </div>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-12 fade-up delay-2">
        <div class="card">
          <div class="card-header">
            <h6 class="card-title"><i class="fas fa-edit text-primary me-2"></i> Form {{ $menu_title }}</h6>
          </div>
          <div class="card-body">
            <form id="form" class="form" action="{{ $form_act }}" method="POST">
              @csrf
              
              <div class="row mb-3">
                <label for="aplikasi_merk" class="col-md-2 col-sm-4 col-form-label">Merk Aplikasi</label>
                <div class="col-md-5 col-sm-8">
                  <input type="text" class="form-control" id="aplikasi_merk" name="aplikasi_merk" value="{{ $main['aplikasi_merk'] ?? '' }}" placeholder="Contoh: Akuntify">
                </div>
              </div>

              <div class="row mb-3">
                <label for="aplikasi_nm" class="col-md-2 col-sm-4 col-form-label required">Nama Aplikasi</label>
                <div class="col-md-6 col-sm-8">
                  <input type="text" class="form-control" id="aplikasi_nm" name="aplikasi_nm" value="{{ $main['aplikasi_nm'] ?? '' }}" required placeholder="Contoh: Sistem Informasi Akuntansi">
                </div>
              </div>

              <div class="row mb-3">
                <label for="aplikasi_versi" class="col-md-2 col-sm-4 col-form-label required">Versi Aplikasi</label>
                <div class="col-md-3 col-sm-4">
                  <input type="text" class="form-control" id="aplikasi_versi" name="aplikasi_versi" value="{{ $main['aplikasi_versi'] ?? '' }}" required placeholder="Contoh: 1.0.0">
                </div>
              </div>

              <div class="row mb-3">
                <label for="fasyankes_nm" class="col-md-2 col-sm-4 col-form-label required">Nama Fasyankes / RS</label>
                <div class="col-md-6 col-sm-8">
                  <input type="text" class="form-control" id="fasyankes_nm" name="fasyankes_nm" value="{{ $main['fasyankes_nm'] ?? '' }}" required placeholder="Contoh: RSUD Campurdarat dr. Karneni">
                </div>
              </div>

              <div class="row mb-3">
                <label for="fasyankes_cd" class="col-md-2 col-sm-4 col-form-label">Kode Fasyankes / RS</label>
                <div class="col-md-4 col-sm-6">
                  <input type="text" class="form-control" id="fasyankes_cd" name="fasyankes_cd" value="{{ $main['fasyankes_cd'] ?? '' }}" placeholder="Contoh: 3504012">
                </div>
              </div>

              <div class="row mb-3">
                <label for="alamat_lengkap" class="col-md-2 col-sm-4 col-form-label">Alamat Lengkap</label>
                <div class="col-md-6 col-sm-8">
                  <textarea class="form-control" id="alamat_lengkap" name="alamat_lengkap" rows="3" placeholder="Alamat lengkap rumah sakit">{{ $main['alamat_lengkap'] ?? '' }}</textarea>
                </div>
              </div>

              <div class="row mb-3">
                <label for="direktur_nm" class="col-md-2 col-sm-4 col-form-label">Direktur / Kepala RS</label>
                <div class="col-md-5 col-sm-8">
                  <input type="text" class="form-control" id="direktur_nm" name="direktur_nm" value="{{ $main['direktur_nm'] ?? '' }}" placeholder="Contoh: dr. Karneni">
                </div>
              </div>

              <div class="row mb-3">
                <label for="telp_no" class="col-md-2 col-sm-4 col-form-label">No. Telepon / WA</label>
                <div class="col-md-4 col-sm-6">
                  <input type="text" class="form-control" id="telp_no" name="telp_no" value="{{ $main['telp_no'] ?? '' }}" placeholder="Contoh: 0355-567890">
                </div>
              </div>

              <div class="row mb-3">
                <label for="email" class="col-md-2 col-sm-4 col-form-label">Email</label>
                <div class="col-md-5 col-sm-8">
                  <input type="email" class="form-control" id="email" name="email" value="{{ $main['email'] ?? '' }}" placeholder="Contoh: info@rsudcampurdarat.com">
                </div>
              </div>

              <div class="row mb-3">
                <label for="website" class="col-md-2 col-sm-4 col-form-label">Website</label>
                <div class="col-md-5 col-sm-8">
                  <input type="text" class="form-control" id="website" name="website" value="{{ $main['website'] ?? '' }}" placeholder="Contoh: rsudcampurdarat.tulungagung.go.id">
                </div>
              </div>

              <div class="row mb-3">
                <label for="npwp_no" class="col-md-2 col-sm-4 col-form-label">No. NPWP</label>
                <div class="col-md-4 col-sm-6">
                  <input type="text" class="form-control" id="npwp_no" name="npwp_no" value="{{ $main['npwp_no'] ?? '' }}" placeholder="Contoh: 01.234.567.8-901.000">
                </div>
              </div>

              <div class="row mb-3">
                <label for="izin_operasional_no" class="col-md-2 col-sm-4 col-form-label">No. Izin Operasional</label>
                <div class="col-md-5 col-sm-8">
                  <input type="text" class="form-control" id="izin_operasional_no" name="izin_operasional_no" value="{{ $main['izin_operasional_no'] ?? '' }}" placeholder="Contoh: 440/123/OPERASIONAL/2025">
                </div>
              </div>

              <div class="row mb-3">
                <label for="mulai_data_tgl" class="col-md-2 col-sm-4 col-form-label">Tgl. Mulai Data</label>
                <div class="col-md-2 col-sm-6">
                  <input type="date" class="form-control" id="mulai_data_tgl" name="mulai_data_tgl" value="{{ $main['mulai_data_tgl'] ?? '' }}">
                </div>
              </div>

              <div class="row border-top pt-2 mt-4">
                <div class="col-md-8 offset-md-2 col-sm-8 offset-sm-4">
                  <button type="submit" class="btn btn-primary btn-submit px-4" onclick="fsSave(event)">
                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div><!-- /page-content -->
</main>
