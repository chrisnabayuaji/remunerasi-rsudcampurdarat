<script>
  $(document).ready(function() {
    var table = $('#datatable-main').DataTable({
      language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json', paginate: paginate },
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{ url($nav_url . '/ajax_datatables') }}?n={{ $nav_id }}",
        type: "POST",
        data: { _token: "{{ csrf_token() }}" }
      },
      columns: [
      {
        data: "agama_id",
        className: "align-middle text-center p-2",
        sortable: false,
        render: function(data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        }
      },
      {
        data: "agama_id",
        className: "align-middle text-start p-2"
      },
      {
        data: "agama_nm",
        className: "align-middle text-start p-2"
      },
      {
        data: "active_st",
        className: "align-middle text-center p-2",
        sortable: false,
        render: function(data, type, row, meta) {
          return data == 1 ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>';
        }
      }
    ]
    });

    $('#btnSync').click(function() {
      Swal.fire({
        title: 'Sinkronisasi Data',
        text: 'Apakah Anda yakin ingin melakukan sinkronisasi?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Sinkronkan',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({ title: 'Sedang Memproses...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
          $.ajax({
            url: '{{ url($nav_url . "/sync") }}?n={{ $nav_id }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
              Swal.close();
              if (response.success) {
                Swal.fire('Berhasil', response.message, 'success');
                table.ajax.reload();
                setTimeout(function() { location.reload(); }, 1500);
              } else {
                Swal.fire('Error', response.message, 'error');
              }
            },
            error: function(xhr) {
              Swal.close();
              Swal.fire('Error', 'Gagal melakukan sinkronisasi', 'error');
            }
          });
        }
      });
    });
  });
</script>