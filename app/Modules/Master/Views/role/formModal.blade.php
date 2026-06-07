<form id="form" class="form" action="{{ $form_act }}" method="POST" enctype="multipart/form-data">
  @csrf

  <div class="row mb-2">
    <label for="role_nm" class="col-sm-4 col-form-label required">Role Name</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="role_nm" name="role_nm" value="{{ $main['role_nm'] ?? '' }}" required>
    </div>
  </div>

  <div class="row mb-2">
    <label for="deskripsi" class="col-sm-4 col-form-label">Description</label>
    <div class="col-sm-8">
      <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3">{{ $main['deskripsi'] ?? '' }}</textarea>
    </div>
  </div>

  <div class="row mb-2">
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
