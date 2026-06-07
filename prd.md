# PRODUCT REQUIREMENT DOCUMENT (PRD) & STANDARDIZASI PENGEMBANGAN
## Proyek: Sistem Informasi Remunerasi Jasa Medis - RSUD Campurdarat dr. Karneni

> [!IMPORTANT]
> Dokumen ini adalah acuan utama bagi pengembang (AI & Human) untuk mempertahankan kualitas kode, arsitektur, gaya desain, dan konsistensi struktur data di seluruh sistem.

---

## 1. Standarisasi Kolom Tabel Database

Setiap tabel baru atau modifikasi kolom pada database PostgreSQL wajib mengikuti standar penamaan berikut:

### 1.1 Penamaan Kunci & Kolom Dasar
* **Primary Key (Kunci Utama)**: Format nama kolom kunci utama adalah `[nama_tabel_tunggal]_id` (contoh: `user_id`, `role_id`, `nav_id`, `identitas_id`).
* **Sufiks Nama (`_nm`)**: Digunakan untuk kolom nama teks (contoh: `user_nm`, `full_nm`, `role_nm`, `nav_nm`, `fasyankes_nm`, `direktur_nm`).
* **Sufiks Status (`_st`)**: Digunakan untuk nilai boolean/status bilangan bulat kecil (contoh: `active_st`, `deleted_st`, `utama_st`, `module_st`).
* **Sufiks Kode (`_cd`)**: Digunakan untuk kode identitas unik (contoh: `fasyankes_cd`).
* **Sufiks Nomor (`_no`)**: Digunakan untuk string berisi nomor (contoh: `npwp_no`, `telp_no`, `izin_operasional_no`).
* **Sufiks Tanggal (`_tgl`)**: Digunakan untuk kolom bertipe date (contoh: `mulai_data_tgl`).

### 1.2 Kolom Audit Wajib
Setiap tabel data transaksi atau master wajib memiliki kolom audit di bawah ini untuk pencatatan riwayat perubahan.

> [!WARNING]
> Seluruh kolom audit wajib diletakkan di **paling awal (urutan teratas)** pada struktur kolom tabel (berada sebelum primary key dan kolom fungsional lainnya).

| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `created_at` | `timestamp` | Waktu data dibuat |
| `created_by` | `varchar` | Nama user/sistem pembuat |
| `updated_at` | `timestamp` (nullable) | Waktu pembaruan terakhir |
| `updated_by` | `varchar` (nullable) | Nama user pembaru terakhir |
| `deleted_at` | `timestamp` (nullable) | Waktu penghapusan logis |
| `deleted_by` | `varchar` (nullable) | Nama user penghapus |
| `deleted_st` | `smallint` (default `0`) | Penanda status hapus (`1` = dihapus, `0` = aktif) |
| `active_st`  | `smallint` (default `1`) | Penanda status aktif (`1` = aktif, `0` = non-aktif) |

---

## 2. Standarisasi Pembuatan Fitur (Modul)

Aplikasi dibangun menggunakan struktur modular. Pengembang harus mengikuti alur dan penempatan file sebagai berikut:

### 2.1 Struktur Folder Modul
Semua modul diletakkan di bawah direktori `app/Modules/` dengan struktur internal:
```text
app/Modules/
└── [NamaModul]/
    ├── Controllers/
    │   └── [NamaController].php
    ├── Models/
    │   └── [NamaModel].php
    └── Views/
        └── [fitur_name]/
            ├── index.blade.php
            ├── indexJs.blade.php
            └── formModal.blade.php
```

### 2.2 Penanganan Rute Otomatis
Rute didaftarkan secara otomatis melalui scanner di `routes/web.php`. Pengembang cukup membuat Controller baru dengan Namespace `App\Modules\[NamaModul]\Controllers` dan mewarisi `BaseController`.
* Rute dasar: `/nama-modul/nama-controller/nama-method`
* Rute default index: `/nama-modul/nama-controller`

### 2.3 Standar AJAX & Modal Rendering
Aplikasi ini menggunakan pola AJAX Modal dinamis untuk meminimalkan perpindahan halaman.
* **Controller Rendering**: Gunakan `$this->renderView(...)` di `BaseController`. Method ini akan mendeteksi query parameter `_ajax_st`. Jika bernilai `true`, controller hanya akan me-render file Blade parsial modal tersebut; jika `false`, controller akan membungkus view dalam layout dashboard utama `app::template.index`.
* **Pemicuan Modal di View**: Pemicuan modal menggunakan fungsi javascript `fsModalShow` dengan argumen URL target:
  ```html
  onclick="fsModalShow(event, {url: '{{ $nav_url }}/form_modal?n={{ $nav_id }}', title: 'Tambah Data'})"
  ```
* **Submit Form Modal**: Gunakan tombol submit dengan pemicu `onclick="fsSave(event)"` untuk memanfaatkan validasi input otomatis.

---

## 3. Standarisasi Desain & Style

Tampilan dashboard harus premium, seragam, responsif, dan konsisten menggunakan aset serta skema warna yang ditentukan:

### 3.1 Skema Warna Orange/Amber Premium
Desain UI didominasi warna orange/amber dari halaman login untuk memberikan kesan estetis yang profesional:
* **Primary / Orange Utama**: `--primary: #E65100` (Orange pekat untuk tombol utama, sorotan, link aktif).
* **Primary Dark**: `--primary-dark: #BF360C` (Merah bata/orange gelap untuk hover state).
* **Primary Light**: `--primary-light: #FFCCBC` (Orange muda untuk border/soft highlight).
* **Primary Extra Light**: `--primary-xlight: #FFF3E0` (Krem orange sangat muda untuk background baris tabel aktif / hover menu).
* **Sidebar Background**: Menggunakan gradasi biru navy tua (`#0D47A1` ke `#0a3880`) untuk memberikan kontras hierarki visual yang tinggi terhadap konten utama.

### 3.2 Tipografi & Ikon
* **Font Utama**: Menggunakan `'Plus Jakarta Sans', sans-serif` dari Google Fonts untuk seluruh teks UI.
* **Font Monospace**: Menggunakan `'DM Mono', monospace` khusus untuk menampilkan angka keuangan/remunerasi agar sejajar dan mudah dibaca.
* **Ikon**: Menggunakan pustaka FontAwesome 6 (contoh: `<i class="fa-solid fa-user-doctor"></i>`).

### 3.3 Aset UI Pendukung
* **SweetAlert2**: Untuk dialog peringatan (seperti konfirmasi hapus `fsDeleteConfirm` atau konfirmasi keluar) wajib menggunakan tombol konfirmasi dengan warna tema `#E65100`.
* **Toastr**: Digunakan untuk notifikasi flash cepat (sukses/gagal) yang dipicu dari session Laravel.
* **Animasi Masuk**: Gunakan class CSS `.fade-up` dipadukan dengan `.delay-1` sampai `.delay-8` pada card/elemen utama halaman untuk memberikan transisi halus saat halaman selesai dimuat.

---

## 4. Larangan & Ketentuan Teknis Khusus

> [!WARNING]
> Pelanggaran terhadap poin-poin di bawah ini dapat mengganggu integrasi database dan kebersihan repository proyek.

* **DILARANG menggunakan Laravel Migrations & Seeders**:
  Struktur database PostgreSQL dikelola langsung secara manual pada server database lokal/staging. Penambahan rute navigasi aplikasi harus di-insert langsung ke tabel `app_nav` dan `app_role_nav` melalui script PHP SQL mentah atau query langsung.
* **Penempatan File Sementara di `tmp/`**:
  Semua file draf, berkas uji coba, backup data sementara, dan script pengujian sekali pakai **wajib** diletakkan di dalam folder `tmp/` di root workspace atau direktori scratch `.gemini/antigravity-ide/brain/.../scratch/`. Jangan menulis berkas sampah di luar folder tersebut.

---

## 5. Dokumentasi API Utilitas & Helper Sistem

### 5.1 FsHelper.php (Global PHP Helpers)
* **`fsPost($key = null)`**: Mengambil data input POST dengan otomatis membuang token CSRF (`_token`) dan flag AJAX (`_ajax_st`).
* **`fsGet($key)`**: Mengambil query string dari URL.
* **`fsResponse($code, $url, $data)`**: Menghasilkan respon JSON terstandardisasi (`'11'` = Berhasil Dibuat, `'12'` = Berhasil Diubah).
* **`fsUploadFile($folder, $field, $config)`**: Mengunggah berkas secara aman ke direktori `public/upload/` dengan validasi ekstensi, ukuran file maks, dan enkripsi nama file otomatis (UUID).
* **`fsEncrypt($value)` / `fsDecrypt($value)`**: Enkripsi/dekripsi string sensitif menggunakan AES-256-CBC berbasis kunci proyek.

### 5.2 fs.js & fs.lib.js (Frontend Javascript)
* **`fsModalShow(event, config, index)`**: Memuat modul/view form modal secara dinamis lewat AJAX.
* **`fsModalHide(event, index)`**: Menutup modal aktif.
* **`fsSave(event)`**: Menjalankan validasi input form menggunakan plugin jQuery Validation, menampilkan spinner loading pada tombol, menonaktifkan tombol submit selama proses, lalu melakukan form submission.
* **`fsDeleteConfirm(event, {url: '...'})`**: Menampilkan konfirmasi pop-up SweetAlert2 bertema orange sebelum menghapus data.
* **`formatRupiah(angka)`**: Mengubah angka numerik ke format IDR Rupiah (contoh: `Rp 150.000,00`).
