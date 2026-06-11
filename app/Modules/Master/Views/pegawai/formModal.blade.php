<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="formModalLabel">
          <i class="fa-solid fa-user"></i> {{ isset($main) ? 'Edit' : 'Tambah' }} Data Pegawai
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ $form_act }}" method="POST" id="formPegawai">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="pegawai_id">ID Pegawai <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="pegawai_id" name="pegawai_id" 
                  value="{{ $main['pegawai_id'] ?? '' }}" 
                  {{ isset($main) ? 'readonly' : 'required' }}>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="nip">NIP</label>
                <input type="text" class="form-control" id="nip" name="nip" 
                  value="{{ $main['nip'] ?? '' }}">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="pegawai_nm">Nama Pegawai <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="pegawai_nm" name="pegawai_nm" 
                  value="{{ $main['pegawai_nm'] ?? '' }}" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                  value="{{ $main['nama_lengkap'] ?? '' }}">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="gelar_depan">Gelar Depan</label>
                <input type="text" class="form-control" id="gelar_depan" name="gelar_depan" 
                  value="{{ $main['gelar_depan'] ?? '' }}">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="gelar_belakang">Gelar Belakang</label>
                <input type="text" class="form-control" id="gelar_belakang" name="gelar_belakang" 
                  value="{{ $main['gelar_belakang'] ?? '' }}">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="nik">NIK</label>
                <input type="text" class="form-control" id="nik" name="nik" 
                  value="{{ $main['nik'] ?? '' }}" maxlength="16">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="lahir_tmp">Tempat Lahir</label>
                <input type="text" class="form-control" id="lahir_tmp" name="lahir_tmp" 
                  value="{{ $main['lahir_tmp'] ?? '' }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="lahir_tgl">Tanggal Lahir</label>
                <input type="date" class="form-control" id="lahir_tgl" name="lahir_tgl" 
                  value="{{ $main['lahir_tgl'] ?? '' }}">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="sex_id">Jenis Kelamin</label>
                <select class="form-control" id="sex_id" name="sex_id">
                  <option value="">-- Pilih --</option>
                  <option value="L" {{ ($main['sex_id'] ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                  <option value="P" {{ ($main['sex_id'] ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="agama_id">Agama</label>
                <input type="text" class="form-control" id="agama_id" name="agama_id" 
                  value="{{ $main['agama_id'] ?? '' }}">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="pernikahan_id">Status Pernikahan</label>
                <input type="text" class="form-control" id="pernikahan_id" name="pernikahan_id" 
                  value="{{ $main['pernikahan_id'] ?? '' }}">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="jabatan_id">Jabatan</label>
                <input type="text" class="form-control" id="jabatan_id" name="jabatan_id" 
                  value="{{ $main['jabatan_id'] ?? '' }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="unit_id">Unit Kerja</label>
                <input type="text" class="form-control" id="unit_id" name="unit_id" 
                  value="{{ $main['unit_id'] ?? '' }}">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="hp_no">No. HP</label>
                <input type="text" class="form-control" id="hp_no" name="hp_no" 
                  value="{{ $main['hp_no'] ?? '' }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" 
                  value="{{ $main['email'] ?? '' }}">
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="alamat_lengkap">Alamat</label>
            <textarea class="form-control" id="alamat_lengkap" name="alamat_lengkap" rows="2">{{ $main['alamat_lengkap'] ?? '' }}</textarea>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="dokter_st">Dokter</label>
                <select class="form-control" id="dokter_st" name="dokter_st">
                  <option value="0" {{ ($main['dokter_st'] ?? 0) == 0 ? 'selected' : '' }}>Tidak</option>
                  <option value="1" {{ ($main['dokter_st'] ?? 0) == 1 ? 'selected' : '' }}>Ya</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="dpjp_st">DPJP</label>
                <select class="form-control" id="dpjp_st" name="dpjp_st">
                  <option value="0" {{ ($main['dpjp_st'] ?? 0) == 0 ? 'selected' : '' }}>Tidak</option>
                  <option value="1" {{ ($main['dpjp_st'] ?? 0) == 1 ? 'selected' : '' }}>Ya</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="active_st">Status Aktif</label>
                <select class="form-control" id="active_st" name="active_st">
                  <option value="1" {{ ($main['active_st'] ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
                  <option value="0" {{ ($main['active_st'] ?? 1) == 0 ? 'selected' : '' }}>Nonaktif</option>
                </select>
              </div>
            </div>
          </div>

          <hr>
          <h6>Informasi Profesi</h6>
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="profesi_id">Profesi</label>
                <input type="text" class="form-control" id="profesi_id" name="profesi_id" 
                  value="{{ $main['profesi_id'] ?? '' }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="subprofesi_id">Sub Profesi</label>
                <input type="text" class="form-control" id="subprofesi_id" name="subprofesi_id" 
                  value="{{ $main['subprofesi_id'] ?? '' }}">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="str_no">No. STR</label>
                <input type="text" class="form-control" id="str_no" name="str_no" 
                  value="{{ $main['str_no'] ?? '' }}">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="str_tgl">Tanggal STR</label>
                <input type="date" class="form-control" id="str_tgl" name="str_tgl" 
                  value="{{ $main['str_tgl'] ?? '' }}">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="str_exp">Expired STR</label>
                <input type="date" class="form-control" id="str_exp" name="str_exp" 
                  value="{{ $main['str_exp'] ?? '' }}">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="sip_no">No. SIP</label>
                <input type="text" class="form-control" id="sip_no" name="sip_no" 
                  value="{{ $main['sip_no'] ?? '' }}">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="sip_tgl">Tanggal SIP</label>
                <input type="date" class="form-control" id="sip_tgl" name="sip_tgl" 
                  value="{{ $main['sip_tgl'] ?? '' }}">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="sip_exp">Expired SIP</label>
                <input type="date" class="form-control" id="sip_exp" name="sip_exp" 
                  value="{{ $main['sip_exp'] ?? '' }}">
              </div>
            </div>
          </div>

          <hr>
          <h6>Informasi Kepegawaian</h6>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="statuspegawai_id">Status Pegawai</label>
                <input type="text" class="form-control" id="statuspegawai_id" name="statuspegawai_id" 
                  value="{{ $main['statuspegawai_id'] ?? '' }}">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="jenispegawai_id">Jenis Pegawai</label>
                <input type="text" class="form-control" id="jenispegawai_id" name="jenispegawai_id" 
                  value="{{ $main['jenispegawai_id'] ?? '' }}">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="pangkat_id">Pangkat</label>
                <input type="text" class="form-control" id="pangkat_id" name="pangkat_id" 
                  value="{{ $main['pangkat_id'] ?? '' }}">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="tmt_cpns">TMT CPNS</label>
                <input type="date" class="form-control" id="tmt_cpns" name="tmt_cpns" 
                  value="{{ $main['tmt_cpns'] ?? '' }}">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="tmt_pns">TMT PNS</label>
                <input type="date" class="form-control" id="tmt_pns" name="tmt_pns" 
                  value="{{ $main['tmt_pns'] ?? '' }}">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="tmt_pegawai">TMT Pegawai</label>
                <input type="date" class="form-control" id="tmt_pegawai" name="tmt_pegawai" 
                  value="{{ $main['tmt_pegawai'] ?? '' }}">
              </div>
            </div>
          </div>

          <hr>
          <h6>Informasi Bank</h6>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="bank_id">Bank</label>
                <input type="text" class="form-control" id="bank_id" name="bank_id" 
                  value="{{ $main['bank_id'] ?? '' }}">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="rekening_no">No. Rekening</label>
                <input type="text" class="form-control" id="rekening_no" name="rekening_no" 
                  value="{{ $main['rekening_no'] ?? '' }}">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="npwp">NPWP</label>
                <input type="text" class="form-control" id="npwp" name="npwp" 
                  value="{{ $main['npwp'] ?? '' }}">
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fa-solid fa-times"></i> Batal
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-save"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
