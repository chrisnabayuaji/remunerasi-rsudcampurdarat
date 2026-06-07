/**
 * Start Modal
 */

function fsLoadingShow(message) {
    $("#fs-modal-loading").modal("show");
    $("#fs-modal-loading-message").html("Loading...");
    if (message != null) {
        $("#fs-modal-loading-message").html(message);
    }
}

function fsModalShow(e, arg, idx = 0) {
    // Reset
    $("#fs-modal-" + idx + "-title").html("");
    $("#fs-modal-" + idx + "-body").html(
        '<i class="fas fa-sync fa-spin me-2"></i> Memuat...',
    );

    // Title
    let title = e.target.innerText;
    if (typeof arg.title !== "undefined") {
        title = arg.title;
    }
    $("#fs-modal-" + idx + "-title").html(title);

    // Size
    $("#fs-modal-" + idx + "-dialog")
        .removeClass("modal-xs")
        .removeClass("modal-sm")
        .removeClass("modal-md")
        .removeClass("modal-lg")
        .removeClass("modal-xl");
    if (typeof arg.size !== "undefined") {
        $("#fs-modal-" + idx + "-dialog").addClass(arg.size);
    } else {
        $("#fs-modal-" + idx + "-dialog").addClass("modal-md");
    }

    // Call Ajax
    $.ajax({
        url: arg.url,
        type: "GET",
        data: {
            _ajax_st: true,
            _token: _token,
        },
        success: function (res) {
            if (res.indexOf('id="loginForm"') !== -1) {
                window.location.reload();
                return;
            }
            jQuery.loadScript = function (url, callback) {
                jQuery.ajax({
                    url: _base_url + "/storage/dist/js/fs.lib.js",
                    dataType: "script",
                    success: callback,
                    async: false,
                });
            };
            $.loadScript(_base_url + "/storage/dist/js/fs.lib.js", function () {
                $("#fs-modal-" + idx + "-body").html(res);
            });
        },
        error: function (xhr) {
            if (xhr.status === 401 || xhr.status === 302) {
                window.location.reload();
            }
        }
    });

    $("#fs-modal-" + idx).modal("show");
}

function fsModalHide(e, idx = 0) {
    $("#fs-modal-" + idx).modal("hide");
}

/**
 * End Modal
 */

/**
 * Start Toast
 */
// Toastr Global Configuration
if (typeof toastr !== 'undefined') {
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "4000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
}

function fsToast(type, message) {
    if (type == "success") {
        toastr.success(message, "Berhasil!");
    } else if (type == "error") {
        toastr.error(message, "Kesalahan!");
    } else if (type == "warning") {
        toastr.warning(message, "Perhatian!");
    } else {
        toastr.info(message, "Informasi");
    }
}
/**
 * End Toast
 */

/**
 * Start Form
 */
function fsSave(e, params = {}) {
    let formId = e.target?.form?.id;

    $("#" + formId).validate({
        rules: {},
        messages: {},
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            if (element.closest(".input-group").length) {
                element.closest(".input-group").append(error);
            } else if (element.hasClass("select2-hidden-accessible")) {
                error.insertAfter(element.next(".select2-container"));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
        submitHandler: function (form) {
            $(".btn-submit").html(
                '<i class="fas fa-spinner fa-spin me-2"></i>Proses...',
            );
            $(".btn-submit").attr("disabled", "disabled");
            $(".btn-cancel").attr("disabled", "disabled");
            form.submit();
        },
    });
}
/**
 * End Form
 */

/**
 * Start Delete
 */
function fsDeleteConfirm(event, arg = {}) {
    const queryString = window.location.search;

    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Aksi ini tidak bisa dikembalikan. Data ini mungkin terhubung dengan data lain.",
        icon: "warning",
        customClass: {
            title: "swal-title-sm",
            popup: "swal-popup-sm",
            htmlContianer: "swal-text-sm",
        },
        width: "480px",
        showCancelButton: true,
        cancelButtonColor: "#858F9B",
        cancelButtonText: "Batal",
        confirmButtonColor: "#dc3444",
        confirmButtonText: "Hapus",
    }).then((result) => {
        if (result.isConfirmed) {
            fsLoadingShow("Proses menghapus data...");
            setTimeout(() => {
                window.location = arg.url;
            }, 1000);
        }
    });
}

function fsSyncConfirm(event, arg = {}) {
    Swal.fire({
        title: "Sinkronisasi Data?",
        text: "Apakah Anda yakin ingin melakukan sinkronisasi data dengan server?",
        icon: "question",
        customClass: {
            title: "swal-title-sm",
            popup: "swal-popup-sm",
            htmlContianer: "swal-text-sm",
        },
        width: "320px",
        showCancelButton: true,
        cancelButtonColor: "#858F9B",
        cancelButtonText: "Batal",
        confirmButtonColor: "#28a745",
        confirmButtonText: "Ya, Sync!",
    }).then((result) => {
        if (result.isConfirmed) {
            fsLoadingShow("Proses sinkronisasi data...");
            setTimeout(() => {
                window.location = arg.url;
            }, 1000);
        }
    });
}
/**
 * End Delete
 */

/**
 * Formating
 */
function formatRupiah(angka) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
    }).format(angka);
}

// DataTables Global Configuration
if (typeof $.fn.dataTable !== 'undefined') {
    $.extend(true, $.fn.dataTable.defaults, {
        language: {
            paginate: {
                previous: '<',
                next: '>'
            }
        }
    });
}
