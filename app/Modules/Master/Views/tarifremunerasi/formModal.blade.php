<form action="{{ $form_act }}" method="POST" id="form-main">
  @csrf
  <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
    <div class="row g-3">
      <!-- General Info -->
      <div class="col-12">
        <h6 class="border-bottom pb-2 text-primary"><i class="fas fa-info-circle me-1"></i> Informasi Umum</h6>
      </div>
      @if(isset($main))
      <div class="col-md-4">
        <label class="form-label">ID Mappings</label>
        <input type="text" class="form-control" name="id" value="{{ $main['id'] ?? '' }}" readonly>
      </div>
      @endif
      <div class="col-md-8">
        <label class="form-label">Tarif <span class="text-danger">*</span></label>
        <select class="form-select" name="tarif_id" required>
          <option value="">-- Pilih Tarif --</option>
          @foreach($tarifs as $t)
            <option value="{{ $t->tarif_id }}" {{ ($main['tarif_id'] ?? '') == $t->tarif_id ? 'selected' : '' }}>
              [{{ $t->tarif_id }}] {{ $t->tarif_nm }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Pelaku Status</label>
        <input type="text" class="form-control" name="pelaku_st" value="{{ $main['pelaku_st'] ?? '' }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Kelompok Kelas ID</label>
        <input type="text" class="form-control" name="kelompokkelas_id" value="{{ $main['kelompokkelas_id'] ?? '' }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Status Aktif</label>
        <select class="form-select" name="active_st">
          <option value="1" {{ ($main['active_st'] ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
          <option value="0" {{ ($main['active_st'] ?? 1) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
        </select>
      </div>

      <!-- Financial Totals -->
      <div class="col-12 mt-4">
        <h6 class="border-bottom pb-2 text-primary"><i class="fas fa-coins me-1"></i> Nilai Jasa & Pendapatan</h6>
      </div>
      <div class="col-md-4">
        <label class="form-label">Total Nilai (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="nilai" value="{{ $main['nilai'] ?? 0 }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Jasa Sarana (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="jasa_sarana" value="{{ $main['jasa_sarana'] ?? 0 }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Jasa Layanan (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="jasa_layanan" value="{{ $main['jasa_layanan'] ?? 0 }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Cost Center (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="cost_center" value="{{ $main['cost_center'] ?? 0 }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Revenue Center (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="revenue_center" value="{{ $main['revenue_center'] ?? 0 }}">
      </div>

      <!-- Management & Office splits -->
      <div class="col-12 mt-4">
        <h6 class="border-bottom pb-2 text-primary"><i class="fas fa-building me-1"></i> Pembagian Manajemen</h6>
      </div>
      <div class="col-md-3">
        <label class="form-label">Direksi (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="direksi" value="{{ $main['direksi'] ?? 0 }}">
      </div>
      <div class="col-md-3">
        <label class="form-label">Direktur (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="direktur" value="{{ $main['direktur'] ?? 0 }}">
      </div>
      <div class="col-md-3">
        <label class="form-label">Kabag / Kasie (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="kabag_kasie" value="{{ $main['kabag_kasie'] ?? 0 }}">
      </div>
      <div class="col-md-3">
        <label class="form-label">Post RM (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="post_rm" value="{{ $main['post_rm'] ?? 0 }}">
      </div>

      <!-- Medical Team splits -->
      <div class="col-12 mt-4">
        <h6 class="border-bottom pb-2 text-primary"><i class="fas fa-user-md me-1"></i> Jasa Pelayanan Medis Utama</h6>
      </div>
      <div class="col-md-6">
        <label class="form-label">Dokter Utama (Porsi Dokter) (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="dokter_utama_dokter" value="{{ $main['dokter_utama_dokter'] ?? 0 }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Dokter Utama (Porsi Perawat) (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="dokter_utama_perawat" value="{{ $main['dokter_utama_perawat'] ?? 0 }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Perawat Utama (Porsi Dokter) (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="perawat_utama_dokter" value="{{ $main['perawat_utama_dokter'] ?? 0 }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Perawat Utama (Porsi Perawat) (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="perawat_utama_perawat" value="{{ $main['perawat_utama_perawat'] ?? 0 }}">
      </div>

      <!-- Anesthesia & Surgery Specific splits -->
      <div class="col-12 mt-4">
        <h6 class="border-bottom pb-2 text-primary"><i class="fas fa-syringe me-1"></i> Tindakan Operasi / Anestesi</h6>
      </div>
      <div class="col-md-4">
        <label class="form-label">Dengan Anestesi: Dokter Op (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="dengan_anestesi_dokter_operator" value="{{ $main['dengan_anestesi_dokter_operator'] ?? 0 }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Dengan Anestesi: Dr Anestesi (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="dengan_anestesi_dokter_anestesi" value="{{ $main['dengan_anestesi_dokter_anestesi'] ?? 0 }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Dengan Anestesi: Perawat OK (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="dengan_anestesi_perawat_ok" value="{{ $main['dengan_anestesi_perawat_ok'] ?? 0 }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Tanpa Anestesi: Dokter Op (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="tanpa_anestesi_dokter_operator" value="{{ $main['tanpa_anestesi_dokter_operator'] ?? 0 }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Tanpa Anestesi: Perawat OK (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="tanpa_anestesi_perawat_ok" value="{{ $main['tanpa_anestesi_perawat_ok'] ?? 0 }}">
      </div>

      <!-- Support splits -->
      <div class="col-12 mt-4">
        <h6 class="border-bottom pb-2 text-primary"><i class="fas fa-ambulance me-1"></i> Penunjang & Lainnya</h6>
      </div>
      <div class="col-md-4">
        <label class="form-label">Supir (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="supir" value="{{ $main['supir'] ?? 0 }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Rekam Medis (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="rekam_medis" value="{{ $main['rekam_medis'] ?? 0 }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">CSSD & Laundry (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="cssd_laundry" value="{{ $main['cssd_laundry'] ?? 0 }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Kantor (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="kantor" value="{{ $main['kantor'] ?? 0 }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Casemix (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="casemix" value="{{ $main['casemix'] ?? 0 }}">
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
    <button type="submit" class="btn btn-primary">Simpan</button>
  </div>
</form>
