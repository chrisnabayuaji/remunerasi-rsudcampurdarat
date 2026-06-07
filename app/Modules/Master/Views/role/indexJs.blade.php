<script>
  $(document).ready(function() {
    $('#datatable-main').DataTable({
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
        paginate: paginate
      },
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{ url('master/role/ajax_datatables') }}?n={{ $nav_id }}",
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}",
        }
      },
      columns: [{
          data: "role_id",
          className: "align-middle text-center p-2",
          sortable: false,
          render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        },
        {
          data: "role_id",
          className: "align-middle text-start p-2",
          sortable: false,
          render: function(data, type, row, meta) {
            var url_edit = '{{ $nav_url }}/form_modal/' + data + '?n={{ $nav_id }}';
            var url_permission = '{{ $nav_url }}/permission_modal/' + data + '?n={{ $nav_id }}';
            var url_delete = '{{ $nav_url }}/delete/' + data + '/' + _token + '?n={{ $nav_id }}';

            var html = '<div class="btn-group">' +
              '  <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="viewport">' +
              '    Aksi' +
              '  </button>' +
              '  <ul class="dropdown-menu dropdown-sm shadow-lg border-0">' +
              '    <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="fsModalShow(event, {url: \'' + url_edit + '\', title: \'Ubah Data Peran\'})"><i class="fas fa-edit me-2"></i>Ubah Data</a></li>' +
              '    <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="fsModalShow(event, {url: \'' + url_permission + '\', title: \'Hak Akses Menu\', size: \'modal-lg\'})"><i class="fas fa-key text-amber-600 me-2"></i>Hak Akses</a></li>' +
              '    <li><a class="dropdown-item py-2 text-danger" href="javascript:void(0)" onclick="fsDeleteConfirm(event, {url: \'' + url_delete + '\'})"><i class="fas fa-trash-alt me-2"></i>Hapus Data</a></li>' +
              '  </ul>' +
              '</div>';

            return html;
          }
        },
        {
          data: "role_nm",
          className: "align-middle text-start p-2"
        },
        {
          data: "deskripsi",
          className: "align-middle text-start p-2"
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
  });
</script>