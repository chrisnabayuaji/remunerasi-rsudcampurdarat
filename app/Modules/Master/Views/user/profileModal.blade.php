<form id="profileForm" class="form" action="{{ $form_act }}" method="POST" autocomplete="off">
  @csrf

  <div class="row mb-3">
    <label for="full_nm" class="col-sm-4 col-form-label required">Nama Lengkap</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="full_nm" name="full_nm" value="{{ $main['full_nm'] ?? '' }}" required>
    </div>
  </div>

  <div class="row mb-3">
    <label for="user_nm" class="col-sm-4 col-form-label required">Username Login</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="user_nm" name="user_nm" value="{{ $main['user_nm'] ?? '' }}" required autocomplete="off">
      <small class="text-muted">Digunakan untuk login. Tidak boleh mengandung spasi.</small>
    </div>
  </div>

  <div class="row mb-3">
    <label for="password" class="col-sm-4 col-form-label">Password Baru</label>
    <div class="col-sm-8">
      <input type="password" class="form-control" id="password" name="password" autocomplete="new-password">
      <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
    </div>
  </div>

  <div class="row mb-3">
    <label for="password_confirmation" class="col-sm-4 col-form-label">Konfirmasi Password</label>
    <div class="col-sm-8">
      <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
    </div>
  </div>

  <div class="row border-top pt-3 mt-4">
    <div class="col-sm-8 offset-sm-4 d-flex align-items-center gap-2">
      <button type="submit" class="btn btn-primary btn-submit px-4" onclick="fsSave(event)">
        <i class="fas fa-save me-2"></i>Simpan Perubahan
      </button>
      <button type="button" class="btn btn-outline-secondary btn-cancel px-4" onclick="fsModalHide(event, 0)">
        <i class="fas fa-times me-2"></i>Batal
      </button>
    </div>
  </div>
</form>

<script>
  $(document).ready(function() {
    $('#profileForm').on('submit', function(e) {
      var pass = $('#password').val();
      var conf = $('#password_confirmation').val();

      if (pass !== '' && pass !== conf) {
        e.preventDefault();
        alert('Konfirmasi password baru tidak cocok!');
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
