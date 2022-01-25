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
              <?= $data->volume ?> Unit <span class="text-danger">*dari <?= $data->countvolume ?> Jenis Proyek</span>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col text-center">
              <h4 style="background-color: #D7D7D7; color: #2D2D2D; padding: 10px; font-weight: 900;">PROGRESS PROYEK</h4>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-striped nowrap" id="jn_tabel" style="width:100%">
              <thead>
                <tr class="text-center">
                  <th>
                    #
                  </th>
                  <th>Jenis Proyek</th>
                  <th>Durasi</th>
                  <th>Progress</th>
                  <th>Vol Target</th>
                  <th>Status</th>
                  <th>BOQ</th>
                  <th>Realisasi</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($array as $i => $a) : ?>
                  <tr>
                    <td><?= $i + 1; ?></td>
                    <td><?= $a->nama_jenis_proyek ?></td>
                    <td><?= $a->durasi ?></td>
                    <td>
                      <div class="progress mb-3">
                        <div class="progress-bar bg-success" style="color: #2D2D2D; width:<?= $a->progress ?>" role="progressbar" data-width="<?= $a->progress . '%' ?>" aria-valuenow="<?= $a->progress ?>" aria-valuemin="0" aria-valuemax="100"><?= $a->progress ?>%</div>
                      </div>
                    </td>
                    <td><?= $a->vol ?></td>
                    <td>
                      <?= $a->status === 1 ? '<span class="badge badge-success">Proyek Selesai</span>' : '<span class="badge badge-info">Proyek Berjalan</span>' ?>
                      <?= $a->validasi === 1 ? '<i class="far fa-check-circle fa-lg"></i>' : '' ?>
                    </td>
                    <td><?= number_format(bulatkan($a->boq), 0, ',', '.') ?></td>
                    <td><?= number_format($a->realisasi, 0, ',', '.') ?></td>
                    <td>
                      <a href="<?= base_url('ProgressProyek_c/progresslist/' . $a->id_proyek . '/' . $a->jenis_proyek) ?>" class="btn btn-icon icon-left btn-info" id-pk="$1" id="k_u">
                        <i class="fas fa-sync"></i>
                        Detail
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="col text-right">
              <a href="<?= base_url('ProgressProyek_c/index') ?>" type="button" id="cancel" class="btn btn-icon icon-left btn-light">
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
  $(document).on('click', '.fotodetil', function(e) {
    e.preventDefault();
    var pro = $(this).attr('id-pro');
    var jn = $(this).attr('id-jn');
    $.ajax({
      method: "GET",
      dataType: "JSON",
      data: {
        idproyek: pro,
        idjenis: jn
      },
      url: "<?= base_url() ?>ProgressProyek_c/getFoto",
      success: function(respon) {
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

  var tabel = $("#jn_tabel").DataTable({
    searching: false,
    paging: false,
    info: false,
    fixedColumns: {
      leftColumns: 0,
      rightColumns: 1
    },
    // order: [
    //   [1, 'asc']
    // ]
  });
</script>