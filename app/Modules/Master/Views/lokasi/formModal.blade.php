<form action="{{ $form_act }}" method="POST" id="form-main">
  @csrf
  <div class="modal-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">ID Lokasi <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="lokasi_id" value="{{ $main['lokasi_id'] ?? '' }}" {{ isset($main) ? 'readonly' : 'required' }}>
      </div>
      <div class="col-md-6">
        <label class="form-label">Nama Lokasi</label>
        <input type="text" class="form-control" name="lokasi_nm" value="{{ $main['lokasi_nm'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Tipe Lokasi</label>
        <input type="text" class="form-control" name="lokasi_tp" value="{{ $main['lokasi_tp'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Lokasi Parent</label>
        <input type="text" class="form-control" name="lokasi_parent" value="{{ $main['lokasi_parent'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">BPJS Nama</label>
        <input type="text" class="form-control" name="bpjs_nm" value="{{ $main['bpjs_nm'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">BPJS Code</label>
        <input type="text" class="form-control" name="bpjs_cd" value="{{ $main['bpjs_cd'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">BPJS Sub Code</label>
        <input type="text" class="form-control" name="bpjs_sub_cd" value="{{ $main['bpjs_sub_cd'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Antrian Code</label>
        <input type="text" class="form-control" name="antrian_cd" value="{{ $main['antrian_cd'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Jenis Lokasi ID</label>
        <input type="text" class="form-control" name="jenislokasi_id" value="{{ $main['jenislokasi_id'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Jenis Registrasi ID</label>
        <input type="text" class="form-control" name="jenisregistrasi_id" value="{{ $main['jenisregistrasi_id'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Kelas Default ID</label>
        <input type="text" class="form-control" name="kelasdefault_id" value="{{ $main['kelasdefault_id'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Shift ID</label>
        <input type="text" class="form-control" name="shift_id" value="{{ $main['shift_id'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">IHS ID</label>
        <input type="text" class="form-control" name="ihs_id" value="{{ $main['ihs_id'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Jenis Pelayanan SIRS ID</label>
        <input type="text" class="form-control" name="jenispelayanansirs_id" value="{{ $main['jenispelayanansirs_id'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Lokasi Loket ID</label>
        <input type="text" class="form-control" name="lokasiloket_id" value="{{ $main['lokasiloket_id'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Lokasi Depo ID</label>
        <input type="text" class="form-control" name="lokasidepo_id" value="{{ $main['lokasidepo_id'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Lokasi Kasir ID</label>
        <input type="text" class="form-control" name="lokasikasir_id" value="{{ $main['lokasikasir_id'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Lokasi Apotek ID</label>
        <input type="text" class="form-control" name="lokasiapotek_id" value="{{ $main['lokasiapotek_id'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Memiliki Bed</label>
        <select class="form-select" name="memilikibed_st">
          <option value="0" {{ ($main['memilikibed_st'] ?? 0) == 0 ? 'selected' : '' }}>Tidak</option>
          <option value="1" {{ ($main['memilikibed_st'] ?? 0) == 1 ? 'selected' : '' }}>Ya</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Lokasi Depo</label>
        <select class="form-select" name="lokasidepo_st">
          <option value="0" {{ ($main['lokasidepo_st'] ?? 0) == 0 ? 'selected' : '' }}>Tidak</option>
          <option value="1" {{ ($main['lokasidepo_st'] ?? 0) == 1 ? 'selected' : '' }}>Ya</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Lokasi Apotek</label>
        <select class="form-select" name="lokasiapotek_st">
          <option value="0" {{ ($main['lokasiapotek_st'] ?? 0) == 0 ? 'selected' : '' }}>Tidak</option>
          <option value="1" {{ ($main['lokasiapotek_st'] ?? 0) == 1 ? 'selected' : '' }}>Ya</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Lokasi Kasir</label>
        <select class="form-select" name="lokasikasir_st">
          <option value="0" {{ ($main['lokasikasir_st'] ?? 0) == 0 ? 'selected' : '' }}>Tidak</option>
          <option value="1" {{ ($main['lokasikasir_st'] ?? 0) == 1 ? 'selected' : '' }}>Ya</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Registrasi Online</label>
        <select class="form-select" name="registrasionline_st">
          <option value="0" {{ ($main['registrasionline_st'] ?? 0) == 0 ? 'selected' : '' }}>Tidak</option>
          <option value="1" {{ ($main['registrasionline_st'] ?? 0) == 1 ? 'selected' : '' }}>Ya</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Monitoring</label>
        <select class="form-select" name="monitoring_st">
          <option value="0" {{ ($main['monitoring_st'] ?? 0) == 0 ? 'selected' : '' }}>Tidak</option>
          <option value="1" {{ ($main['monitoring_st'] ?? 0) == 1 ? 'selected' : '' }}>Ya</option>
        </select>
      </div>
      <div class="col-md-12">
        <label class="form-label">Lokasi Map</label>
        <textarea class="form-control" name="lokasi_map" rows="2">{{ $main['lokasi_map'] ?? '' }}</textarea>
      </div>
      <div class="col-md-12">
        <label class="form-label">Lokasi Sub Map</label>
        <textarea class="form-control" name="lokasi_submap" rows="2">{{ $main['lokasi_submap'] ?? '' }}</textarea>
      </div>
      <div class="col-md-6">
        <label class="form-label">Status Aktif</label>
        <select class="form-select" name="active_st">
          <option value="1" {{ ($main['active_st'] ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
          <option value="0" {{ ($main['active_st'] ?? 1) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
        </select>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
    <button type="submit" class="btn btn-primary">Simpan</button>
  </div>
</form>
