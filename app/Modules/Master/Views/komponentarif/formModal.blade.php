<form action="{{ $form_act }}" method="POST" id="form-main">
  @csrf
  <div class="modal-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">ID Komponen <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="komponentarif_id" value="{{ $main['komponentarif_id'] ?? '' }}" {{ isset($main) ? 'readonly' : 'required' }}>
      </div>
      <div class="col-md-6">
        <label class="form-label">Nama Komponen <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="komponentarif_nm" value="{{ $main['komponentarif_nm'] ?? '' }}" required>
      </div>
      <div class="col-md-12">
        <label class="form-label">Parent Komponen (Struktur Berjenjang)</label>
        <select class="form-select" name="komponentarif_parent">
          <option value="">-- Tanpa Parent (Tingkat Utama) --</option>
          @foreach($parent_list as $p)
            <option value="{{ $p->komponentarif_id }}" {{ ($main['komponentarif_parent'] ?? '') == $p->komponentarif_id ? 'selected' : '' }}>
              [{{ $p->komponentarif_id }}] {{ $p->komponentarif_nm }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">No Urut</label>
        <input type="number" class="form-control" name="urut_no" value="{{ $main['urut_no'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Grup Komponen ID</label>
        <input type="text" class="form-control" name="grupkomponentarif_id" value="{{ $main['grupkomponentarif_id'] ?? '' }}">
      </div>
      <div class="col-md-12">
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
