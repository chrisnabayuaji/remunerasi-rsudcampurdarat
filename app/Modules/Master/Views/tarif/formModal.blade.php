<form action="{{ $form_act }}" method="POST" id="form-main">
  @csrf
  <div class="modal-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">ID Tarif <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="tarif_id" value="{{ $main['tarif_id'] ?? '' }}" {{ isset($main) ? 'readonly' : 'required' }}>
      </div>
      <div class="col-md-6">
        <label class="form-label">Nama Tarif <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="tarif_nm" value="{{ $main['tarif_nm'] ?? '' }}" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Kode Tarif</label>
        <input type="text" class="form-control" name="tarif_cd" value="{{ $main['tarif_cd'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Inacbg ID</label>
        <input type="text" class="form-control" name="inacbg_id" value="{{ $main['inacbg_id'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Kelompok Kelas ID</label>
        <input type="text" class="form-control" name="kelompokkelas_id" value="{{ $main['kelompokkelas_id'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Unit Cost (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="unit_cost" value="{{ $main['unit_cost'] ?? 0 }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Nominal Tarif Tertinggi (Rp)</label>
        <input type="number" step="0.01" class="form-control" name="nominal" value="{{ $main['nominal'] ?? 0 }}">
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
