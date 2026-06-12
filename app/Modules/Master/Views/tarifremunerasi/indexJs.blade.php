<script>
  function renderPctNominal(data, type, row) {
    if (row.tarif_tp === 'G' || !data || parseFloat(data) === 0) return '-';
    var pctStr = data + ' %';
    var base = parseFloat(row.nominal || 0) - parseFloat(row.unit_cost || 0);
    if (base > 0) {
      var nominalShare = (parseFloat(data) / 100) * base;
      return pctStr + '<br><small class="text-muted">' + formatRupiah(nominalShare) + '</small>';
    }
    return pctStr + '<br><small class="text-muted">-</small>';
  }

  function renderJasaLayananSubPct(data, type, row) {
    if (row.tarif_tp === 'G' || !data || parseFloat(data) === 0) return '-';
    var pctStr = data + ' %';
    var base = parseFloat(row.nominal || 0) - parseFloat(row.unit_cost || 0);
    var jasaLayananPct = parseFloat(row.jasa_layanan || 0);
    var jasaLayananNominal = (jasaLayananPct / 100) * base;
    if (jasaLayananNominal > 0) {
      var nominalShare = (parseFloat(data) / 100) * jasaLayananNominal;
      return pctStr + '<br><small class="text-muted">' + formatRupiah(nominalShare) + '</small>';
    }
    return pctStr + '<br><small class="text-muted">-</small>';
  }

  function renderRevenueCenterSubPct(data, type, row) {
    if (row.tarif_tp === 'G' || !data || parseFloat(data) === 0) return '-';
    var pctStr = data + ' %';
    var base = parseFloat(row.nominal || 0) - parseFloat(row.unit_cost || 0);
    var jasaLayananPct = parseFloat(row.jasa_layanan || 0);
    var jasaLayananNominal = (jasaLayananPct / 100) * base;
    var revenueCenterPct = parseFloat(row.revenue_center || 0);
    var revenueCenterNominal = (revenueCenterPct / 100) * jasaLayananNominal;
    if (revenueCenterNominal > 0) {
      var nominalShare = (parseFloat(data) / 100) * revenueCenterNominal;
      return pctStr + '<br><small class="text-muted">' + formatRupiah(nominalShare) + '</small>';
    }
    return pctStr + '<br><small class="text-muted">-</small>';
  }

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
          data: "id",
          className: "align-middle text-center p-2",
          sortable: false,
          render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        },
        /* FITUR AKSI - Uncomment jika diperlukan
        {
          data: "id",
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
              '    <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="fsModalShow(event, {url: \'' + url_edit + '\', title: \'Ubah Data Tarif Remunerasi\'})"><i class="fas fa-edit me-2"></i>Ubah Data</a></li>' +
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
          data: "tarif_nm",
          className: "align-middle text-start p-2",
          render: function(data) {
            return data ? data : '<span class="text-muted">Tidak Ditemukan</span>';
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
          data: "unit_cost",
          className: "align-middle text-end p-2",
          render: function(data) {
            return data && parseFloat(data) > 0 ? formatRupiah(data) : '-';
          }
        },
        {
          data: "nominal",
          className: "align-middle text-end p-2",
          render: function(data, type, row) {
            var diff = parseFloat(row.nominal || 0) - parseFloat(row.unit_cost || 0);
            return diff > 0 ? formatRupiah(diff) : '-';
          }
        },
        {
          data: "jasa_sarana",
          className: "align-middle text-end p-2",
          render: renderPctNominal
        },
        {
          data: "jasa_layanan",
          className: "align-middle text-end p-2",
          render: renderPctNominal
        },
        {
          data: "cost_center",
          className: "align-middle text-end p-2",
          render: renderJasaLayananSubPct
        },
        {
          data: "revenue_center",
          className: "align-middle text-end p-2",
          render: renderJasaLayananSubPct
        },
        {
          data: "direksi",
          className: "align-middle text-end p-2",
          render: renderJasaLayananSubPct
        },
        {
          data: "direktur",
          className: "align-middle text-end p-2",
          render: renderJasaLayananSubPct
        },
        {
          data: "kabag_kasie",
          className: "align-middle text-end p-2",
          render: renderJasaLayananSubPct
        },
        {
          data: "post_rm",
          className: "align-middle text-end p-2",
          render: renderJasaLayananSubPct
        },
        {
          data: "dokter_utama_dokter",
          className: "align-middle text-end p-2",
          render: renderRevenueCenterSubPct
        },
        {
          data: "dokter_utama_perawat",
          className: "align-middle text-end p-2",
          render: renderRevenueCenterSubPct
        },
        {
          data: "perawat_utama_dokter",
          className: "align-middle text-end p-2",
          render: renderRevenueCenterSubPct
        },
        {
          data: "perawat_utama_perawat",
          className: "align-middle text-end p-2",
          render: renderRevenueCenterSubPct
        },
        {
          data: "dengan_anestesi_dokter_operator",
          className: "align-middle text-end p-2",
          render: renderRevenueCenterSubPct
        },
        {
          data: "dengan_anestesi_dokter_anestesi",
          className: "align-middle text-end p-2",
          render: renderRevenueCenterSubPct
        },
        {
          data: "dengan_anestesi_perawat_ok",
          className: "align-middle text-end p-2",
          render: renderRevenueCenterSubPct
        },
        {
          data: "tanpa_anestesi_dokter_operator",
          className: "align-middle text-end p-2",
          render: renderRevenueCenterSubPct
        },
        {
          data: "tanpa_anestesi_perawat_ok",
          className: "align-middle text-end p-2",
          render: renderRevenueCenterSubPct
        },
        {
          data: "supir",
          className: "align-middle text-end p-2",
          render: renderRevenueCenterSubPct
        },
        {
          data: "rekam_medis",
          className: "align-middle text-end p-2",
          render: renderRevenueCenterSubPct
        },
        {
          data: "cssd_laundry",
          className: "align-middle text-end p-2",
          render: renderRevenueCenterSubPct
        },
        {
          data: "kantor",
          className: "align-middle text-end p-2",
          render: renderRevenueCenterSubPct
        },
        {
          data: "casemix",
          className: "align-middle text-end p-2",
          render: renderRevenueCenterSubPct
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
        text: 'Apakah Anda yakin ingin melakukan sinkronisasi data tarif remunerasi dari SIMRS?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Sinkronkan',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            title: 'Sedang Memproses...',
            text: 'Sinkronisasi data tarif remunerasi sedang berjalan, mohon tunggu...',
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
    // Handle Kelompok Tarif Filter Change
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
