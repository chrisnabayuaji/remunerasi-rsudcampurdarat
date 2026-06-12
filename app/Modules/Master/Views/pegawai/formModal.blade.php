<form action="{{ $form_act }}" method="POST" id="form-main">
  @csrf
  <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
    <div class="row g-3">
      <!-- Personal Info Section -->
      <div class="col-12">
        <h6 class="border-bottom pb-2 text-primary"><i class="fas fa-user me-1"></i> Data Pribadi</h6>
      </div>
      <div class="col-md-6">
        <label class="form-label">ID Pegawai <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="pegawai_id" value="{{ $main['pegawai_id'] ?? '' }}" {{ isset($main) ? 'readonly' : 'required' }}>
      </div>
      <div class="col-md-6">
        <label class="form-label">NIP</label>
        <input type="text" class="form-control" name="nip" value="{{ $main['nip'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Nama Pegawai <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="pegawai_nm" value="{{ $main['pegawai_nm'] ?? '' }}" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" class="form-control" name="nama_lengkap" value="{{ $main['nama_lengkap'] ?? '' }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Gelar Depan</label>
        <input type="text" class="form-control" name="gelar_depan" value="{{ $main['gelar_depan'] ?? '' }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Gelar Belakang</label>
        <input type="text" class="form-control" name="gelar_belakang" value="{{ $main['gelar_belakang'] ?? '' }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">NIK</label>
        <input type="text" class="form-control" name="nik" value="{{ $main['nik'] ?? '' }}" maxlength="16">
      </div>
      <div class="col-md-6">
        <label class="form-label">Tempat Lahir</label>
        <input type="text" class="form-control" name="lahir_tmp" value="{{ $main['lahir_tmp'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Tanggal Lahir</label>
        <input type="date" class="form-control" name="lahir_tgl" value="{{ $main['lahir_tgl'] ?? '' }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Jenis Kelamin</label>
        <select class="form-select" name="sex_id">
          <option value="">-- Pilih --</option>
          <option value="L" {{ ($main['sex_id'] ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
          <option value="P" {{ ($main['sex_id'] ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Agama</label>
        <select class="form-select" name="agama_id">
          <option value="">-- Pilih --</option>
          @foreach($agama_list as $ag)
            <option value="{{ $ag->agama_id }}" {{ ($main['agama_id'] ?? '') == $ag->agama_id ? 'selected' : '' }}>
              {{ $ag->agama_nm }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Status Pernikahan</label>
        <select class="form-select" name="pernikahan_id">
          <option value="">-- Pilih --</option>
          @foreach($pernikahan_list as $pn)
            <option value="{{ $pn->pernikahan_id }}" {{ ($main['pernikahan_id'] ?? '') == $pn->pernikahan_id ? 'selected' : '' }}>
              {{ $pn->pernikahan_nm }}
            </option>
          @endforeach
        </select>
      </div>

      <!-- Professional Placement Section -->
      <div class="col-12 mt-4">
        <h6 class="border-bottom pb-2 text-primary"><i class="fas fa-briefcase me-1"></i> Penempatan & Jabatan</h6>
      </div>
      <div class="col-md-6">
        <label class="form-label">Jabatan Utama</label>
        <select class="form-select" name="jabatan_id">
          <option value="">-- Pilih --</option>
          @foreach($jabatan_list as $jb)
            <option value="{{ $jb->jabatan_id }}" {{ ($main['jabatan_id'] ?? '') == $jb->jabatan_id ? 'selected' : '' }}>
              {{ $jb->jabatan_nm }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Unit / Lokasi Kerja</label>
        <select class="form-select" name="lokasi_id">
          <option value="">-- Pilih --</option>
          @foreach($lokasi_list as $lk)
            <option value="{{ $lk->lokasi_id }}" {{ ($main['lokasi_id'] ?? '') == $lk->lokasi_id ? 'selected' : '' }}>
              {{ $lk->lokasi_nm }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">No. HP</label>
        <input type="text" class="form-control" name="hp_no" value="{{ $main['hp_no'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">NPWP</label>
        <input type="text" class="form-control" name="npwp" value="{{ $main['npwp'] ?? '' }}">
      </div>
      <div class="col-12">
        <label class="form-label">Alamat Lengkap</label>
        <textarea class="form-control" name="alamat_lengkap" rows="2">{{ $main['alamat_lengkap'] ?? '' }}</textarea>
      </div>

      <!-- Settings & Flags -->
      <div class="col-md-4">
        <label class="form-label">Apakah Dokter?</label>
        <select class="form-select" name="dokter_st">
          <option value="0" {{ ($main['dokter_st'] ?? 0) == 0 ? 'selected' : '' }}>Tidak</option>
          <option value="1" {{ ($main['dokter_st'] ?? 0) == 1 ? 'selected' : '' }}>Ya</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Apakah DPJP?</label>
        <select class="form-select" name="dpjp_st">
          <option value="0" {{ ($main['dpjp_st'] ?? 0) == 0 ? 'selected' : '' }}>Tidak</option>
          <option value="1" {{ ($main['dpjp_st'] ?? 0) == 1 ? 'selected' : '' }}>Ya</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Status Aktif</label>
        <select class="form-select" name="active_st">
          <option value="1" {{ ($main['active_st'] ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
          <option value="0" {{ ($main['active_st'] ?? 1) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
        </select>
      </div>

      <!-- Professional Credentials -->
      <div class="col-12 mt-4">
        <h6 class="border-bottom pb-2 text-primary"><i class="fas fa-id-card me-1"></i> STR & SIP (Kredensial Medis)</h6>
      </div>
      <div class="col-md-6">
        <label class="form-label">No. STR</label>
        <input type="text" class="form-control" name="str_no" value="{{ $main['str_no'] ?? '' }}">
      </div>
      <div class="col-md-3">
        <label class="form-label">Tanggal STR</label>
        <input type="date" class="form-control" name="str_tgl" value="{{ $main['str_tgl'] ?? '' }}">
      </div>
      <div class="col-md-3">
        <label class="form-label">STR Berlaku Hingga</label>
        <input type="date" class="form-control" name="str_exp" value="{{ $main['str_exp'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">No. SIP</label>
        <input type="text" class="form-control" name="sip_no" value="{{ $main['sip_no'] ?? '' }}">
      </div>
      <div class="col-md-3">
        <label class="form-label">Tanggal SIP</label>
        <input type="date" class="form-control" name="sip_tgl" value="{{ $main['sip_tgl'] ?? '' }}">
      </div>
      <div class="col-md-3">
        <label class="form-label">SIP Berlaku Hingga</label>
        <input type="date" class="form-control" name="sip_exp" value="{{ $main['sip_exp'] ?? '' }}">
      </div>

      <!-- Employment Details Section -->
      <div class="col-12 mt-4">
        <h6 class="border-bottom pb-2 text-primary"><i class="fas fa-file-contract me-1"></i> Kepegawaian & Finansial</h6>
      </div>
      <div class="col-md-4">
        <label class="form-label">Status Hubungan Kerja</label>
        <select class="form-select" name="statuspegawai_id">
          <option value="">-- Pilih --</option>
          @foreach($statuspegawai_list as $sp)
            <option value="{{ $sp->statuspegawai_id }}" {{ ($main['statuspegawai_id'] ?? '') == $sp->statuspegawai_id ? 'selected' : '' }}>
              {{ $sp->statuspegawai_nm }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">TMT PNS</label>
        <input type="date" class="form-control" name="tmt_pns" value="{{ $main['tmt_pns'] ?? '' }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">TMT Pegawai</label>
        <input type="date" class="form-control" name="tmt_pegawai" value="{{ $main['tmt_pegawai'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">No. Rekening Bank</label>
        <input type="text" class="form-control" name="rekening_no" value="{{ $main['rekening_no'] ?? '' }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Nama Bank</label>
        <input type="text" class="form-control" name="bank_id" value="{{ $main['bank_id'] ?? '' }}">
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
    <button type="submit" class="btn btn-primary">Simpan</button>
  </div>
</form>
