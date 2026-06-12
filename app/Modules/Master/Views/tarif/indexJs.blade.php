<script>
  $(document).ready(function() {
    var table = $('#datatable-main').DataTable({
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
        paginate: paginate
      },
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{ url($nav_url . '/ajax_datatables') }}?n={{ $nav_id }}",
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}",
        }
      },
      columns: [{
          data: "tarif_id",
          className: "align-middle text-center p-2",
          sortable: false,
          render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        },
        /* FITUR AKSI - Uncomment jika diperlukan
        {
          data: "tarif_id",
          className: "align-middle text-start p-2",
          sortable: false,
          render: function(data, type, row, meta) {
            var url_edit = '{{ $nav_url }}/form_modal/' + data + '?n={{ $nav_id }}';
            var url_delete = '{{ $nav_url }}/delete/' + data + '/' + _token + '?n={{ $nav_id }}';

            var html = '<div class="btn-group">' +
              '  <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="viewport">' +
              '    Aksi' +
              '  </button>' +
              '  <ul class="dropdown-menu dropdown-sm shadow-lg border-0">' +
              '    <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="fsModalShow(event, {url: \'' + url_edit + '\', title: \'Ubah Data Tarif\'})"><i class="fas fa-edit me-2"></i>Ubah Data</a></li>' +
              '    <li><a class="dropdown-item py-2 text-danger" href="javascript:void(0)" onclick="fsDeleteConfirm(event, {url: \'' + url_delete + '\'})"><i class="fas fa-trash-alt me-2"></i>Hapus Data</a></li>' +
              '  </ul>' +
              '</div>';

            return html;
          }
        },
        */
        {
          data: "tarif_id",
          className: "align-middle text-start p-2"
        },
        {
          data: "tarif_cd",
          className: "align-middle text-start p-2"
        },
        {
          data: "tarif_nm",
          className: "align-middle text-start p-2"
        },
        /* inacbg_id and kelompokkelas_id columns commented out as requested
        {
          data: "inacbg_id",
          className: "align-middle text-start p-2"
        },
        {
          data: "kelompokkelas_id",
          className: "align-middle text-start p-2"
        },
        */
        {
          data: "unit_cost",
          className: "align-middle text-end p-2",
          render: function(data) {
            return data && parseFloat(data) > 0 ? formatRupiah(data) : '-';
          }
        },
        {
          data: "nominal",
          className: "align-middle text-end p-2",
          render: function(data) {
            return data && parseFloat(data) > 0 ? formatRupiah(data) : '-';
          }
        },
        {
          data: "active_st",
          className: "align-middle text-center p-2",
          sortable: false,
          render: function(data, type, row, meta) {
            var result = "";
            if (data == 1) {
              result = '<i class="fas fa-check-circle text-success"></i>';
            } else {
              result = '<i class="fas fa-times-circle text-danger"></i>';
            }
            return result;
          }
        }
      ],
    });

    // Tombol Sinkronisasi
    $('#btnSync').click(function() {
      Swal.fire({
        title: 'Verifikasi Password',
        text: 'Masukkan password login Anda untuk melakukan sinkronisasi data tarif dari SIMRS:',
        input: 'password',
        inputPlaceholder: 'Password login Anda...',
        inputAttributes: {
          autocapitalize: 'off',
          autocorrect: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Verifikasi & Sinkronkan',
        cancelButtonText: 'Batal',
        inputValidator: (value) => {
          if (!value) {
            return 'Password wajib diisi!'
          }
        }
      }).then((result) => {
        if (result.isConfirmed) {
          var password = result.value;

          Swal.fire({
            title: 'Sedang Memproses...',
            text: 'Sinkronisasi data tarif sedang berjalan, mohon tunggu...',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });

          $.ajax({
            url: '{{ url($nav_url . "/sync") }}?n={{ $nav_id }}',
            type: 'POST',
            data: {
              _token: '{{ csrf_token() }}',
              password: password
            },
            success: function(response) {
              Swal.close();
              if (response.success) {
                Swal.fire('Berhasil', response.message, 'success');
                table.ajax.reload();
                setTimeout(function() {
                  location.reload();
                }, 1500);
              } else {
                Swal.fire('Error', response.message, 'error');
              }
            },
            error: function(xhr) {
              Swal.close();
              var message = 'Gagal melakukan sinkronisasi';
              if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
              }
              Swal.fire('Error', message, 'error');
            }
          });
        }
      });
    });

    // Handle Tipe Tarif Filter Change
    $('#filter_tarif_tp').change(function() {
      var val = $(this).val();
      $.ajax({
        url: "{{ url($nav_url . '/set_filter') }}?n={{ $nav_id }}",
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}",
          tarif_tp: val
        },
        success: function(response) {
          if (response.success) {
            table.ajax.reload();
          }
        }
      });
    });

    // Handle Reset Filter Click
    $('#btnResetFilter').click(function() {
      $('#filter_tarif_tp').val('').trigger('change');
    });
  });
</script>
