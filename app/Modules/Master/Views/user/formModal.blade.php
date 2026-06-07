<form id="form" class="form" action="{{ $form_act }}" method="POST" enctype="multipart/form-data">
  @csrf

  <div class="row mb-2">
    <label for="full_nm" class="col-sm-4 col-form-label required">Nama Lengkap</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="full_nm" name="full_nm" value="{{ $main['full_nm'] ?? '' }}" required>
    </div>
  </div>

  <div class="row mb-2">
    <label for="user_nm" class="col-sm-4 col-form-label required">Username Login</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="user_nm" name="user_nm" value="{{ $main['user_nm'] ?? '' }}" required autocomplete="off">
      <small class="text-muted">Digunakan untuk login. Tidak boleh mengandung spasi.</small>
    </div>
  </div>

  <div class="row mb-2">
    <label for="password" class="col-sm-4 col-form-label {{ isset($main['user_id']) ? '' : 'required' }}">Password</label>
    <div class="col-sm-8">
      <input type="password" class="form-control" id="password" name="password" {{ isset($main['user_id']) ? '' : 'required' }} autocomplete="new-password">
      @if(isset($main['user_id']))
        <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
      @endif
    </div>
  </div>

  <div class="row mb-2">
    <label for="password_confirmation" class="col-sm-4 col-form-label {{ isset($main['user_id']) ? '' : 'required' }}">Konfirmasi Password</label>
    <div class="col-sm-8">
      <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" {{ isset($main['user_id']) ? '' : 'required' }} autocomplete="new-password">
    </div>
  </div>

  <div class="row mb-2">
    <label for="role_id" class="col-sm-4 col-form-label required">Role</label>
    <div class="col-sm-8">
      <select class="form-select" id="role_id" name="role_id" required>
        <option value="">-- Pilih Role --</option>
        @foreach($all_role as $role)
          <option value="{{ $role['role_id'] }}" {{ ($main['role_id'] ?? '') == $role['role_id'] ? 'selected' : '' }}>{{ $role['role_nm'] }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="row mb-4">
    <label class="col-sm-4 col-form-label">Aktif?</label>
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

<script>
  $(document).ready(function() {
    $('#form').on('submit', function(e) {
      var pass = $('#password').val();
      var conf = $('#password_confirmation').val();

      if (pass !== '' && pass !== conf) {
        e.preventDefault();
        alert('Konfirmasi password tidak cocok!');
        return false;
      }

      // Validasi username tidak boleh ada spasi
      var username = $('#user_nm').val();
      if (/\s/.test(username)) {
        e.preventDefault();
        alert('Username tidak boleh mengandung spasi!');
        return false;
      }
    });
  });
</script>
