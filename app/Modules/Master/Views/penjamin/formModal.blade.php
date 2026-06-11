<form action="{{ $form_act }}" method="POST" id="form-main">
  @csrf
  <div class="modal-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">ID Penjamin <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="penjamin_id" value="{{ $main['penjamin_id'] ?? '' }}" {{ isset($main) ? 'readonly' : 'required' }}>
      </div>
      <div class="col-md-6">
        <label class="form-label">Nama Penjamin <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="penjamin_nm" value="{{ $main['penjamin_nm'] ?? '' }}" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">BPJS</label>
        <select class="form-select" name="bpjs_st">
          <option value="1" {{ ($main['bpjs_st'] ?? 1) == 1 ? 'selected' : '' }}>Ya</option>
          <option value="0" {{ ($main['bpjs_st'] ?? 1) == 0 ? 'selected' : '' }}>Tidak</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Permanen</label>
        <select class="form-select" name="permanent_st">
          <option value="1" {{ ($main['permanent_st'] ?? 1) == 1 ? 'selected' : '' }}>Ya</option>
          <option value="0" {{ ($main['permanent_st'] ?? 1) == 0 ? 'selected' : '' }}>Tidak</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Urutan</label>
        <input type="number" class="form-control" name="urut_no" value="{{ $main['urut_no'] ?? 99 }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Margin Farmasi (%)</label>
        <input type="number" step="0.01" class="form-control" name="margin_farmasi" value="{{ $main['margin_farmasi'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">RIS ID</label>
        <input type="text" class="form-control" name="ris_id" value="{{ $main['ris_id'] ?? '' }}">
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
