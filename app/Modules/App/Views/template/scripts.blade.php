<!-- ═══════════════════════════════════════════
  SCRIPTS
════════════════════════════════════════════ -->
<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<!-- Bootstrap 5.3 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<!-- Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.12.2/sweetalert2.all.min.js"></script>
<!-- jQuery Validation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/2.1.3/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap5.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- FS Scripts -->
<script src="{{ asset('storage/dist/js/fs.lib.js') }}"></script>
<script src="{{ asset('storage/dist/js/fs.js') }}"></script>
<script>
  var _base_url = "{{ url('/') }}";
  var _token = "{{ csrf_token() }}";
</script>
<script>
  $.fn.dataTable.ext.errMode = 'throw';
  
  // ── Toastr config
  toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: 'toast-top-right',
    timeOut: 3500,
    extendedTimeOut: 1000,
    showEasing: 'swing',
    hideEasing: 'linear',
    showMethod: 'fadeIn',
    hideMethod: 'fadeOut'
  };

  var paginate = {
    first: '<i class="fa-solid fa-angles-left"></i>',
    previous: '<i class="fa-solid fa-angle-left"></i>',
    next: '<i class="fa-solid fa-angle-right"></i>',
    last: '<i class="fa-solid fa-angles-right"></i>'
  };

  // ── Bootstrap Tooltips & Popovers
  $(function() {
    $('[data-bs-toggle="tooltip"]').each(function() {
      new bootstrap.Tooltip(this);
    });
    $('[data-bs-toggle="popover"]').each(function() {
      new bootstrap.Popover(this);
    });

    // ── Select2 Initialization
    $('.select2-customer').select2({
      theme: 'bootstrap-5',
      placeholder: 'Cari Pelanggan...',
      width: '100%'
    });

    // Re-validate on Select2 change
    $('.select2-customer').on('change', function() {
      $(this).valid();
    });

    // ════════════════════════════════════════════════
    //  Toastr
    // ════════════════════════════════════════════════

    // Toastr Configuration
    toastr.options = {
      "closeButton": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "timeOut": "4000",
    };

    // Flash Message Check
    const flashError = "{{ session('flash_error') }}";
    const flashSuccess = "{{ session('flash_success') }}";

    if (flashError) {
      toastr.error(flashError, "Kesalahan!");
    }

    if (flashSuccess) {
      toastr.success(flashSuccess, "Berhasil!");
    }
  });

  // ── Sidebar toggle
  function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (window.innerWidth < 992) {
      sidebar.classList.toggle('open');
      overlay.classList.toggle('show');
    } else {
      document.body.classList.toggle('sidebar-collapsed');
    }
  }

  // ── Set active page
  function setPage(name) {
    document.getElementById('pageTitle').textContent = name;

    // Manage active classes
    document.querySelectorAll('.nav-link-l1, .nav-link-l2, .nav-link-l3').forEach(el => el.classList.remove('active'));
    if (window.event && window.event.currentTarget) {
      window.event.currentTarget.classList.add('active');
    }

    // Auto-close sidebar on mobile
    if (window.innerWidth < 992 && document.getElementById('sidebar').classList.contains('open')) {
      toggleSidebar();
    }
  }

  // ── Confirm logout (SweetAlert)
  function confirmLogout() {
    Swal.fire({
      title: 'Keluar dari aplikasi ini?',
      text: 'Pastikan semua data sudah tersimpan sebelum keluar.',
      icon: 'question',
      iconColor: '#E65100',
      showCancelButton: true,
      confirmButtonColor: '#E65100',
      cancelButtonColor: '#90A4AE',
      confirmButtonText: '<i class="fa-solid fa-right-from-bracket me-1"></i> Ya, Keluar',
      cancelButtonText: 'Batal',
      borderRadius: '16px'
    }).then(result => {
      if (result.isConfirmed) {
        window.location.href = "{{ url('/app/auth/logout_action') }}";
      }
    });
  }

  // ── Auto-logout Idle Detection (Multi-tab support via LocalStorage)
  (function() {
    const IDLE_TIMEOUT = 15 * 60 * 1000; // 15 Menit (dalam milidetik)
    const CHECK_INTERVAL = 5000; // Cek setiap 5 detik
    const STORAGE_KEY = 'rs_remun_last_active';
    let lastUpdateTime = 0;

    // Reset status aktif
    function resetActiveTime() {
      const now = Date.now();
      // Throttle penulisan ke localStorage maksimal 1 detik sekali untuk performa
      if (now - lastUpdateTime > 1000) {
        localStorage.setItem(STORAGE_KEY, now);
        lastUpdateTime = now;
      }
    }

    // Set waktu awal aktif saat halaman dimuat
    localStorage.setItem(STORAGE_KEY, Date.now());

    // Daftarkan event listener untuk mendeteksi aktivitas pengguna
    const events = ['mousemove', 'mousedown', 'keypress', 'scroll', 'touchstart', 'click'];
    events.forEach(event => {
      document.addEventListener(event, resetActiveTime, { passive: true });
    });

    // Cek berkala apakah pengguna menganggur (idle)
    setInterval(function() {
      const lastActive = parseInt(localStorage.getItem(STORAGE_KEY) || Date.now(), 10);
      const elapsed = Date.now() - lastActive;

      if (elapsed >= IDLE_TIMEOUT) {
        // Hapus waktu aktif agar tidak loop redirect di tab lain
        localStorage.removeItem(STORAGE_KEY);
        
        // Redirect ke logout
        window.location.href = "{{ url('/app/auth/logout_action') }}";
      }
    }, CHECK_INTERVAL);
  })();
</script>