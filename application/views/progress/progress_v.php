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
      <div class="card">
        <div class="card-body">
          <div class="form-group row" style="margin-bottom:0px">
            <div class="col-xs-12 col-md-12 col-lg-12 col-form-label">
              <h5><?= $data->nama_proyek_pekerjaan ?></h5>
            </div>
          </div>
          <div class="form-group row" style="margin-bottom:0px">
            <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Nama Konsumen/PPK</label>
            <div class="col-xs-12 col-md-10 col-lg-10 col-form-label">
              <?= $data->nama_konsumen ?>
            </div>
          </div>
          <div class="form-group row" style="margin-bottom:0px">
            <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">No SPMK</label>
            <div class="col-xs-12 col-md-10 col-lg-10 col-form-label">
              <?= $data->nospmk ?>
            </div>
          </div>
          <div class="form-group row" style="margin-bottom:0px">
            <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">No SPK</label>
            <div class="col-xs-12 col-md-10 col-lg-10 col-form-label">
              <?= $data->no_surat_kontrak ?>
            </div>
          </div>
          <div class="form-group row" style="margin-bottom:0px">
            <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Tanggal Mulai</label>
            <div class="col-xs-12 col-md-10 col-lg-10 col-form-label">
              <?= $data->tanggal_mulai ?>
            </div>
          </div>
          <div class="form-group row" style="margin-bottom:0px">
            <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Tanggal Selesai</label>
            <div class="col-xs-12 col-md-10 col-lg-10 col-form-label">
              <?= $data->tanggal_selesai ?>
            </div>
          </div>
          <div class="form-group row" style="margin-bottom:0px">
            <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Volume Target</label>
            <div class="col-xs-12 col-md-10 col-lg-10 col-form-label">
              <?= $data->volume ?> Unit
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col text-center">
              <h4 style="background-color: #D7D7D7; color: #2D2D2D; padding: 10px; font-weight: 900;"><?= $jenisproyek->nama_jenis_proyek ?> <?= $jenisproyekstatus !== 0 ? '<button type="button" class="btn btn-primary btn-icon icon-left"> Proyek Selesai <span class="badge badge-transparent"><i class="far fa-check-circle fa-10x"></i></span></button>' : '' ?></h4>
            </div>
          </div>
          <?php if ($jenisproyekstatus === 0) : ?>
            <a href="<?= base_url('ProgressProyek_c/formAdd/' . $data->id_proyek . '/' . $jenisproyek->id_jenis_proyek) ?>" class="btn btn-icon icon-left btn-primary note-btn" id="pegawai_t">
              <i class="fas fa-plus"></i>
              Tambah
            </a>
          <?php endif; ?>
          <hr>
          <div class="table-responsive">
            <table class="table table-striped nowrap" id="progress_tabel" style="width:100%">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Tanggal</th>
                  <th>Keterangan</th>
                  <th>Persentase</th>
                  <th>Status</th>
                  <th>Validasi Keterangan</th>
                  <th>Gambar</th>
                  <th>Aksi</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="col text-right">
              <a href="javascript:void(0);" onclick="goBack()" type="button" id="cancel" class="btn btn-icon icon-left btn-light">
                <i class="fas fa-arrow-left"></i>
                Kembali
              </a> &nbsp;
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <div class="modal fade" data-backdrop="static" role="dialog" id="modal">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('_partials/footer'); ?>
<script>
  var idproyek = JSON.parse('<?= $idproyek ?>');
  var idjenis = JSON.parse('<?= $idjenis ?>');
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

  var tabel = $("#progress_tabel").DataTable({
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
    scrollX: true,
    scrollY: false,
    fixedHeader: true,
    crollCollapse: true,
    fixedColumns: {
      leftColumns: 0,
      rightColumns: 1
    },
    ajax: {
      "url": "<?php echo base_url() . 'ProgressProyek_c/ignited_progress' ?>",
      "type": "POST",
      data: {
        proyek: idproyek,
        jenis: idjenis
      }
    },
    columns: [{
        "data": null,
        width: "10px",
        orderable: false,
        searchable: false
      },
      {
        "data": "tanggal",
        render: function(v) {
          var tgl = v.split(' ')[0].split('-');
          return tgl[2] + '-' + tgl[1] + '-' + tgl[0];
        }
      },
      {
        "data": "ket"
      },
      {
        "data": "persentases"
      },
      {
        "data": "validasi",
        render: function(v) {
          var k = '';
          if (v === '0') {
            k = 'Menunggu Validasi';
          } else if (v === '1') {
            k = 'Diterima';
          } else {
            k = 'Ditolak'
          }
          return k;
        }
      },
      {
        "data": "validasi_ket"
      },
      {
        "data": "gambar",
        orderable: false,
        searchable: false,
        class: 'text-center'
      },
      {
        "data": "view",
        orderable: false,
        searchable: false,
        class: 'text-center'
      }
    ],
    order: [
      [1, 'asc']
    ],
    rowCallback: function(row, data, iDisplayIndex) {
      var info = this.fnPagingInfo();
      var page = info.iPage;
      var length = info.iLength;
      $('td:eq(0)', row).html(iDisplayIndex + 1 + info.iStart);
      if (data.validasi === '1') {
        $('.tomboledit', row).css('display', 'none');
        $('td:eq(7)', row).text('Tidak Bisa Diubah');
      }
    }
  });

  $(document).on('click', '.fotodetil', function(e) {
    e.preventDefault();
    var pro = $(this).attr('id-pro');
    $.ajax({
      method: "GET",
      dataType: "JSON",
      data: {
        idprogress: pro,
      },
      url: "<?= base_url() ?>ProgressProyek_c/getFoto",
      success: function(respon) {
        console.log(respon);
        $('.modal-body').html("");
        if (respon.status === 'kosong') {
          $('.modal-body').html("<p>Tidak Ada Gambar</p>");
        } else {
          $('.modal-body').html(respon.data);
        }
        $('.modal-title').text('Photo');
        $('#modal').modal('show');
      }
    });
    return false;
  });
</script>