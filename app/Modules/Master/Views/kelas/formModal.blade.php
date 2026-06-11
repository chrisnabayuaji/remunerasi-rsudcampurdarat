<form action="{{ $form_act }}" method="POST" id="form-main">
  @csrf
  <div class="modal-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">ID Kelas <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="kelas_id" value="{{ $main['kelas_id'] ?? '' }}" {{ isset($main) ? 'readonly' : 'required' }}>
      </div>
      <div class="col-md-6">
        <label class="form-label">Nama Kelas <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="kelas_nm" value="{{ $main['kelas_nm'] ?? '' }}" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Singkatan</label>
        <input type="text" class="form-control" name="kelas_singkatan" value="{{ $main['kelas_singkatan'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Kelompok Kelas</label>
        <input type="text" class="form-control" name="kelompokkelas_id" value="{{ $main['kelompokkelas_id'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Kelas BPJS</label>
        <input type="text" class="form-control" name="kelas_bpjs" value="{{ $main['kelas_bpjs'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Kelas Eklaim</label>
        <input type="text" class="form-control" name="kelas_eklaim" value="{{ $main['kelas_eklaim'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Permanen</label>
        <select class="form-select" name="permanen_st">
          <option value="0" {{ ($main['permanen_st'] ?? 0) == 0 ? 'selected' : '' }}>Tidak</option>
          <option value="1" {{ ($main['permanen_st'] ?? 0) == 1 ? 'selected' : '' }}>Ya</option>
        </select>
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
