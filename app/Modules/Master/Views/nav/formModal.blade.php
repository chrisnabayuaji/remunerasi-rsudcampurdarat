<form id="form" class="form" action="{{ $form_act }}" method="POST" enctype="multipart/form-data">
  @csrf
  <div class="row mb-2">
    <label for="module_id" class="col-sm-4 col-form-label">Module Group</label>
    <div class="col-sm-8">
      <select class="form-select fs-chose" name="module_id" id="module_id">
        <option value="">-- Pilih Modul -- </option>
        @foreach ($all_module as $r)
        <option value="{{ $r['nav_id'] }}" @selected(($main['module_id'] ?? '' )==$r['nav_id'])>
          {{ $r['nav_id'] }} - {{ $r['nav_nm'] }}
        </option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="row mb-2">
    <label for="parent_id" class="col-sm-4 col-form-label">Parent Menu</label>
    <div class="col-sm-8">
      <select class="form-select fs-chose" name="parent_id" id="parent_id">
        <option value="">-- Pilih Parent -- </option>
        @foreach ($all_data as $r)
        <option value="{{ $r['nav_id'] }}" @selected(($main['parent_id'] ?? '' )==$r['nav_id'])>
          {{ $r['nav_id'] }} - {{ $r['nav_nm'] }}
        </option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="row mb-2">
    <label for="nav_id" class="col-sm-4 col-form-label required">Nav ID (Unique)</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="nav_id" name="nav_id" value="{{ $main['nav_id'] ?? '' }}">
    </div>
  </div>

  <div class="row mb-2">
    <label for="nav_nm" class="col-sm-4 col-form-label required">Menu Name</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="nav_nm" name="nav_nm" value="{{ $main['nav_nm'] ?? '' }}" required>
    </div>
  </div>

  <div class="row mb-2">
    <label for="nav_url" class="col-sm-4 col-form-label required">URL Path</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="nav_url" name="nav_url" value="{{ $main['nav_url'] ?? '' }}" required>
    </div>
  </div>

  <div class="row mb-2">
    <label for="nav_icon" class="col-sm-4 col-form-label required">Ikon</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="nav_icon" name="nav_icon" value="{{ $main['nav_icon'] ?? '' }}" required placeholder="fas fa-link">
    </div>
  </div>

  <div class="row mb-2">
    <label class="col-sm-4 col-form-label">Module Section?</label>
    <div class="col-sm-8 d-flex align-items-center">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="module_st" id="module_st_1" value="1" @checked(($main['module_st'] ?? 0)==1)>
        <label class="form-check-label" for="module_st_1">Ya</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="module_st" id="module_st_0" value="0" @checked(($main['module_st'] ?? 0)==0)>
        <label class="form-check-label" for="module_st_0">Tidak</label>
      </div>
    </div>
  </div>

  <div class="row mb-4">
    <label class="col-sm-4 col-form-label">Is Active?</label>
    <div class="col-sm-8 d-flex align-items-center">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="active_st" id="active_st_1" value="1" @checked(($main['active_st'] ?? 1)==1)>
        <label class="form-check-label" for="active_st_1">Ya</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="active_st" id="active_st_0" value="0" @checked(($main['active_st'] ?? 1)==0)>
        <label class="form-check-label" for="active_st_0">Tidak</label>
      </div>
    </div>
  </div>

  <div class="row border-top pt-3">
    <div class="col-sm-8 offset-md-4 d-flex align-items-center gap-2">
      <button type="submit" class="btn btn-primary btn-submit px-4" onclick="fsSave(event)">
        <i class="fas fa-save me-2"></i>Simpan
      </button>
      <button type="button" class="btn btn-outline-secondary btn-cancel px-4" onclick="fsModalHide(event, 0)">
        <i class="fas fa-times me-2"></i>Batal
      </button>
    </div>
  </div>
</form>