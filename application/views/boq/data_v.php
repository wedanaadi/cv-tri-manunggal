<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Bill Of Quantity (BOQ)</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-header">
              <a href="<?= base_url() ?>Boq_c/addBoq" class="btn btn-icon icon-left btn-primary note-btn" id="pegawai_t">
                <i class="fas fa-plus"></i>
                Tambah
              </a>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped nowrap" id="boq_tabel" style="width:100%">
                  <thead>
                    <tr>
                      <th style="display: none;">Id Proyek</th>
                      <th>Nama Konsumen/PPK</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Modal -->
  <div class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" id="modal">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="pegawai_f" class="needs-validation" novalidate="" autocomplete="off">
          <div class="modal-body">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('_partials/footer'); ?>

<script>
  $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
    return {
      "iStart": oSettings._iDisplayStart,
      "iEnd": oSettings.fnDisplayEnd(),
      "iLength": oSettings._iDisplayLength,
      "iTotal": oSettings.fnRecordsTotal(),
      "iFilteredTotal": oSettings.fnRecordsDisplay(),
      "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
      "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
    };
  };

  var tabel = $("#boq_tabel").DataTable({
    initComplete: function() {
      var api = this.api();
      $('#mytable_filter input').off('.DT').on('input.DT', function() {
        api.search(this.value).draw();
      });
    },
    oLanguage: {
      sProcessing: "loading..."
    },
    processing: false,
    scrollX: true,
    serverSide: true,
    responsive: true,
    ajax: {
      "url": "<?php echo base_url() . 'Boq_c/ignited_boq' ?>",
      "type": "POST"
    },
    columns: [{
        "data": "nama_kegiatan"
      },
      {
        "data": "nama_konsumen"
      },
    ],
    order: [
      [1, 'asc']
    ],
    rowCallback: function(row, data, iDisplayIndex) {
      var info = this.fnPagingInfo();
      var page = info.iPage;
      var length = info.iLength;
      $('td:eq(0)', row).css('display', 'none');
      $.ajax({
        method: "GET",
        dataType: "JSON",
        data: {
          konsumen: data.nama_satker
        },
        url: "<?= base_url() ?>Boq_c/getProyekKonsumen",
        success: function(respon) {
          $(row).addClass('shown');
          rowx = tabel.row(row);
          var pgno = 1;
          var tbody = '';

          let tabeldetil = '<table id="data-' + data.nama_kegiatan + '" class="table table-bordered" style="width:100%">' +
            '<thead>' +
            '<tr>' +
            '<th style="width: 5%" class="text-center">NO</th>' +
            '<th class="text-center">Kegiatan</th>' +
            '<th class="text-center">Pekerjaan</th>' +
            '<th class="text-center">Lokasi</th>' +
            '<th class="text-center">Tahun Anggaran</th>' +
            '<th class="text-center">Action</th>' +
            '<th class="text-center" style="display:none">id</th>' +
            '</tr>' +
            '</thead><tbody>';
          $.each(respon, function(index, value) {
            tbody += '<tr>' +
              '<td class="details-control"></td>' +
              '<td style="vertical-align: middle;">' + value.nama_kegiatan + '</td>' +
              '<td style="vertical-align: middle;">' + value.nama_proyek_pekerjaan + '</td>' +
              '<td style="vertical-align: middle;">' + value.lokasi + '</td>' +
              '<td style="vertical-align: middle; text-align:center;">' + value.tahun_anggaran + '</td>' +
              '<td style="vertical-align: middle;"><a href="<?= base_url() ?>Boq_c/rekap/' + value.id_proyek + '" target="_blank" class="btn btn-primary"><i class="fas fa-print"></i> Rekaptulasi</a></td>' +
              '<td style="display:none">' + value.id_proyek + '</td>' +
              '</tr>';
          });
          let tfoot = '</tbody></table>';
          rowx.child(tabeldetil + tbody + tfoot).show();
          const dtname = "#data-" + data.nama_kegiatan;
          var tabelproyek = $(dtname).DataTable({
            searching: false,
            paging: false,
            info: false
          });

          $(dtname + ' tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = tabelproyek.row(tr);
            if (row.child.isShown()) {
              // This row is already open - close it
              row.child.hide();
              tr.removeClass('shown');
            } else {
              format(row.child, row.data()[6]); // create new if not exist
              tr.addClass('shown');
            }
          });
        }
      });

    }
  });

  function format(d, idpro) {
    $.ajax({
      method: "GET",
      dataType: "JSON",
      data: {
        idproyek: idpro
      },
      url: "<?= base_url() ?>Boq_c/getDetailJP",
      success: function(respon) {
        console.log(respon);
        var pgno = 1;
        var tbody = '';

        let tabeldetil = '<table id="det" class="table table-bordered" border="1">' +
          '<thead>' +
          '<tr>' +
          '<th style="width: 5%" class="text-center">NO</th>' +
          '<th class="text-center">Jenis Proyek</th>' +
          '<th style="width: 250px" class="text-center">Aksi</th>' +
          '</tr>' +
          '</thead><tbody>';
        $.each(respon, function(index, value) {
          var button = '<button class="btn btn-icon icon-left btn-success" id-pk="' + value.id_boq + '" id="boq_detail">' +
            '<i class="fas fa-eye"></i>' +
            'Detail' +
            '</button> ' +
            '<a href="<?= base_url() ?>Boq_c/cetak/' + value.id_boq + '" target="_blank" class="btn btn-icon icon-left btn-primary" id-pk="$1" id="k_u">' +
            '<i class="fas fa-print"></i>' +
            'Cetak' +
            '</a> ' +
            '<a href="<?= base_url() ?>Boq_c/ubahBoq/' + value.id_boq + '" class="btn btn-icon icon-left btn-warning" id-pk="$1" id="k_u">' +
            '<i class="fas fa-edit"></i>' +
            'Ubah' +
            '</a> ' +
            '<button class="btn btn-icon icon-left btn-danger" id-pk="' + value.id_boq + '" id="boq_d">' +
            '<i class="fas fa-trash"></i>' +
            'Hapus' +
            '</button>';

          tbody += "<tr><td>" + pgno + "</td><td>" + value.nama_jenis_proyek + "</td><td class='text-center'>" + button + "</td></tr>";
          pgno++;
        });
        let tfoot = '</tbody></table>';
        d($(tabeldetil + tbody + tfoot)).show();
        // rowx.child(tabeldetil + tbody + tfoot).show();
      }
    });
  }

  $(document).on('click', '#boq_d', function() {
    console.log('delete');
    isSave = 0;
    const id = $(this).attr('id-pk');
    Swal.fire({
      title: 'Hapus data ?',
      text: "Setelah dihapus, Anda tidak akan dapat memulihkan data ini!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Hapus',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "POST",
          url: "<?= base_url() ?>Boq_c/hapus/" + id,
          success: function(respon) {
            tabel.ajax.reload();
            notifsukses('Data BOQ', 'dihapus');
          }
        });
      } else {
        notifgagal('Data BOQ', 'dihapus');
      }
    })
  });

  $(document).on('click', '#boq_detail', function() {
    const id = $(this).attr('id-pk');
    $.ajax({
      type: "GET",
      dataType: "JSON",
      url: "<?= base_url(); ?>Boq_c/form/" + id,
      success: function(respon) {
        $(".modal-body").html(respon.view);
        $('.modal-title').text('Detail Bill Of Quantity (BOQ)');
        $('#modal').modal('show').scrollTop(0);;
      }
    });
  });
</script>