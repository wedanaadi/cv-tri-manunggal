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
      <form id="update_f" autocomplete="off" class="needs-validation" novalidate="">
        <div class="card">
          <div class="card-header">
            <div class="card-title">
              <h4><?= $data->nama_proyek_pekerjaan ?></h4>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table">
                <tr>
                  <td>Jenis Proyek</td>
                  <td>:</td>
                  <td><?= $data->nama_jenis_proyek ?></td>
                  <td>&nbsp;</td>
                  <td>Lokasi Pekerjaan</td>
                  <td>
                    <input type="text" name="lokasi" class="form-control" required="">
                    <input type="hidden" name="proyekid" class="form-control" value="<?= $data->id_proyek ?>">
                    <input type="hidden" name="jpid" class="form-control" value="<?= $data->id_jenis_proyek ?>">
                    <input type="hidden" name="aksi" class="form-control">
                    <div class="invalid-feedback">
                      Lokasi Pekerjaan ?
                    </div>
                    <div class="valid-feedback">
                      Terisi!
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Tanggal Mulai</td>
                  <td>:</td>
                  <td><?= $data->tanggal_mulai ?></td>
                  <td>&nbsp;</td>
                  <td>Status</td>
                  <td>
                    <select name="status" class="form-control">
                      <option value="0" <?= $data->status === '0' ? 'selected' : '' ?>>Proyek Berjalan</option>
                      <option value="1" <?= $data->status === '1' ? 'selected' : '' ?>>Proyek Selesai</option>
                    </select>
                    <div class="invalid-feedback">
                      Nama Konsumen/PPK ?
                    </div>
                    <div class="valid-feedback">
                      Terisi!
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Tanggal Penyelesaian</td>
                  <td>:</td>
                  <td><?= $data->tanggal_selesai ?></td>
                  <td>&nbsp;</td>
                  <td rowspan="2">Keterangan</td>
                  <td rowspan="2">
                    <textarea name="ket" cols="30" rows="10" class="form-control" required></textarea>
                    <div class="invalid-feedback">
                      Keterangan ?
                    </div>
                    <div class="valid-feedback">
                      Terisi!
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Volume Target</td>
                  <td>:</td>
                  <td><?= $data->vol . ' ' . $data->sat ?></td>
                  <td>&nbsp;</td>
                </tr>
              </table>
            </div>
            <div class="row">
              <div class="col text-center">
                <h4 style="background-color: #D7D7D7; color: #2D2D2D; padding: 10px; font-weight: 900;">PROGRESS PROYEK</h4>
              </div>
            </div>
            <div class="row">
              <div class="col" id="infoPersentase">
              </div>
            </div>
            <div class="form-group">
              <label>Persentase</label>
              <input type="range" name="persentase" class="form-control-range" value="<?= $data->persentase ?>">
              <span id="rangeval"><?= $data->persentase ?>
            </div>
            <div class="form-group row" style="margin-bottom:0px">
              <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Foto</label>
              <div class="col-xs-12 col-md-6 col-lg-10">
                <div class="form-group col-12">
                  <input type="file" class="form-control foto" name="foto[]" multiple>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col text-right">
                <a href="javascript:void(0);" onclick="goBack()" type="button" id="cancel" class="btn btn-icon icon-left btn-light">
                  <i class="fas fa-arrow-left"></i>
                  Kembali
                </a> &nbsp;
                <button type="submit" id="submit" class="btn btn-icon icon-left btn-primary">
                  <i class="fas fa-save"></i>
                  Simpan
                </button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </section>
</div>
<?php $this->load->view('_partials/footer'); ?>

<script>
  var isSave = JSON.parse('<?= $isSave; ?>');
  var lastPersentase = JSON.parse('<?= $lastPersentase ?>');
  console.log(isSave);
  $('#infoPersentase').html("<div class='alert alert-info'>Persentase Harus Lebih dari " + lastPersentase + "%</div>");
  $('[name=aksi]').val(isSave);
  $(document).on('input', '[name=persentase]', function() {
    $('#rangeval').html($(this).val());
    if (parseInt($(this).val()) <= parseInt(lastPersentase)) {
      $('#submit').addClass('disabled btn-progress');
    } else {
      $('#submit').removeClass('disabled btn-progress');
    }
  });

  var idprogress = '';

  if (isSave === 'save') {
    $(".foto").fileinput({
      overwriteInitial: false,
      allowedFileTypes: ["image"],
      showUpload: false,
      maxFileSize: 1024,
    });
    $('[name=persentase]').val(lastPersentase);
    $('#rangeval').html(lastPersentase);
    $('#submit').addClass('disabled btn-progress');
  } else {
    var edit = JSON.parse('<?= $edit; ?>');
    var loadphoto = JSON.parse('<?= $loadphoto; ?>');
    idprogress = edit.id_progress;
    $('[name=lokasi]').val(edit.lokasi);
    $('[name=ket]').val(edit.ket);
    if (loadphoto.length === 0) {
      $(".foto").fileinput({
        overwriteInitial: false,
        allowedFileTypes: ["image"],
        showUpload: false,
        maxFileSize: 1024,
      });
    } else {
      var initial = [];
      var config = [];
      var key = 1;
      $.each(loadphoto, function(k, v) {
        initial.push("<?= base_url() ?>" + 'assets/img/foto/' + v.photo_name);

        config.push({
          caption: v.photo_name,
          downloadUrl: "<?= base_url() ?>" + 'assets/img/foto/' + v.photo_name,
          url: "<?= base_url() ?>" + "ProgressProyek_c/deletephoto/" + v.photo_name,
          filename: "<?= base_url() ?>" + 'assets/img/foto/' + v.photo_name,
          description: "",
          // size: callphoto.photos[i].ukuran.split(",")[ii],
          width: "120px",
          type: "image",
          key: key++
        });

      });
      $(".foto").fileinput({
        overwriteInitial: false,
        allowedFileTypes: ["image"],
        showUpload: false,
        showRemove: false,
        maxFileSize: 1024,
        initialPreview: initial,
        initialPreviewAsData: true,
        initialPreviewConfig: config,
      });
    }
  }

  $('#update_f').on('submit', function(e) {
    e.preventDefault();
    $('#submit').addClass('disabled btn-progress');
    var form = $(this);
    if (form[0].checkValidity() === false) {
      event.stopPropagation();
      $('#submit').removeClass('disabled btn-progress');
    } else {
      $.ajax({
        method: "POST",
        contentType: false,
        processData: false,
        data: new FormData($("#update_f")[0]),
        dataType: "JSON",
        url: isSave === 'save' ? '<?= base_url() ?>ProgressProyek_c/create' : '<?= base_url() ?>ProgressProyek_c/updateproses/' + idprogress,
        success: function(respon) {
          if (respon.status === 'sukses') {
            notifsukses('Data Progress', 'diupdate');
          } else {
            notifgagal('Data Progress', 'diupdate');
          }
          setTimeout(function() {
            window.location.href = "<?= base_url('ProgressProyek_c/detail/' . $data->id_proyek) ?>";
          }, 1000);
        }
      });
    }
    return false;
  });
</script>