<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Progress Proyek</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
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
    serverSide: true,
    responsive: true,
    ajax: {
      "url": "<?php echo base_url() . 'ProgressProyek_c/ignited_progressadmin' ?>",
      "type": "POST"
    },
    columns: [{
        "data": "proyek_id"
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
          konsumen: data.proyek_id
        },
        url: "<?= base_url() ?>ProgressProyek_c/getDetailJP",
        success: function(respon) {
          $(row).addClass('shown');
          rowx = tabel.row(row);
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
          if (respon.length > 0) {
            $.each(respon, function(index, value) {
              var button = '<a href="<?= base_url() ?>ProgressProyek_c/detailadmin/' + value.id_progress + '" class="btn btn-icon icon-left btn-success" id-pk="' + value.id_progress + '" id="boq_detail">' +
                '<i class="fas fa-eye"></i>' +
                'Detail' +
                '</a>';

              tbody += "<tr><td>" + pgno + "</td><td>" + value.nama_jenis_proyek + "</td><td class='text-center'>" + button + "</td></tr>";
              pgno++;
            });
          } else {
            tbody += "<tr><td colspan='3'>Tidak Ada Data</td></tr>";
          }
          let tfoot = '</tbody></table>';
          rowx.child(tabeldetil + tbody + tfoot).show();
        }
      });

    }
  });

  $(document).on('click', '#boq_d', function() {
    console.log('delete');
    isSave = 0;
    const id = $(this).attr('id-pk');
    swal({
        title: 'Hapus data ?',
        text: 'Setelah dihapus, Anda tidak akan dapat memulihkan data ini!',
        icon: 'warning',
        buttons: ["Batal", "Hapus"],
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            type: "POST",
            url: "<?= base_url() ?>Boq_c/hapus/" + id,
            success: function(respon) {
              tabel.ajax.reload();
              notifsukses('Data Kegiatan', 'dihapus');
            }
          });
        } else {
          notifgagal('Data Kegiatan');
        }
      });
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