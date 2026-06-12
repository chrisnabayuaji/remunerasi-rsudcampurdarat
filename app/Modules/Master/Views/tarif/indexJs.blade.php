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
        {
          data: "inacbg_id",
          className: "align-middle text-start p-2"
        },
        {
          data: "kelompokkelas_id",
          className: "align-middle text-start p-2"
        },
        {
          data: "unit_cost",
          className: "align-middle text-end p-2",
          render: function(data) {
            return data ? formatRupiah(data) : 'Rp 0';
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
        title: 'Sinkronisasi Data',
        text: 'Apakah Anda yakin ingin melakukan sinkronisasi data tarif dari SIMRS?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Sinkronkan',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
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
              _token: '{{ csrf_token() }}'
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
  });
</script>
