<form action="{{ $form_act }}" method="POST" id="form-main">
  @csrf
<div class="modal-body">
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">ID <span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="agama_id" value="{{ $main['agama_id'] ?? '' }}" {{ isset($main) ? 'readonly' : 'required' }}>
    </div>
    <div class="col-md-6">
      <label class="form-label">Nama <span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="agama_nm" value="{{ $main['agama_nm'] ?? '' }}" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Status Aktif</label>
      <select class="form-select" name="active_st">
        <option value="1" {{ (\$main['active_st'] ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
        <option value="0" {{ (\$main['active_st'] ?? 1) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
      </select>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
  <button type="submit" class="btn btn-primary">Simpan</button>
</div>
</form>