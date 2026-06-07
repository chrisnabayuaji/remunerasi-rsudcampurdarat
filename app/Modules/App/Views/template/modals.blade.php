<!-- Bootstrap Modal Demo -->
<?php for ($i = 0; $i < 4; $i++): ?>
  <!-- Modal -->
  <div class="modal fade" id="fs-modal-<?= $i ?>" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="fs-modal-<?= $i ?>-title" aria-hidden="true">
    <div id="fs-modal-<?= $i ?>-dialog" class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header d-flex align-items-center">
          <h5 class="modal-title mb-0" id="fs-modal-<?= $i ?>-title">
            Loading title...
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="fs-modal-<?= $i ?>-body">
          Loading body...
        </div>
      </div>
    </div>
  </div>
<?php endfor; ?>

<!-- Modal Loading -->
<div class="modal fade" id="fs-modal-loading" tabindex="-1" role="dialog" aria-labelledby="fs-modal-loading-title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div id="fs-modal-loading-body" class="modal-body text-center">
        <i class="fas fa-2x fa-spinner fa-spin"></i>
        <br>
        <div class="mt-2" id="fs-modal-loading-message"></div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalTambahBarang" tabindex="-1" aria-modal="true" role="dialog">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa-solid fa-box-open text-primary me-2"></i>Tambah Barang Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <form id="formBarang" novalidate="">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Kode SKU</label>
              <input type="text" class="form-control" id="skuCode" placeholder="SKU-XXXXX" required="">
            </div>
            <div class="col-md-6">
              <label class="form-label">Nama Barang</label>
              <input type="text" class="form-control" id="itemName" placeholder="Nama produk" required="">
            </div>
            <div class="col-md-4">
              <label class="form-label">Kategori</label>
              <select class="form-select" required="">
                <option value="">Pilih kategori</option>
                <option>Elektronik</option>
                <option>ATK</option>
                <option>Furnitur</option>
                <option>Lainnya</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Satuan</label>
              <select class="form-select">
                <option>Pcs</option><option>Rim</option><option>Box</option><option>Kg</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Gudang</label>
              <select class="form-select">
                <option>Gudang Utama</option><option>Gudang B</option><option>Gudang C</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Harga Beli</label>
              <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" class="form-control" placeholder="0" style="border-radius:0 9px 9px 0">
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label">Harga Jual</label>
              <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" class="form-control" placeholder="0" style="border-radius:0 9px 9px 0">
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label">Stok Minimum</label>
              <input type="number" class="form-control" placeholder="10">
            </div>
            <div class="col-12">
              <label class="form-label">Deskripsi</label>
              <textarea class="form-control" rows="2" placeholder="Keterangan tambahan..."></textarea>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer" style="border-top:1.5px solid var(--border)">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" onclick="saveBarang()"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan Barang</button>
      </div>
    </div>
  </div>
</div>