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
          <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
              <div class="p-2" style="width: 55%">
                <div class="table-responsive">
                  <table style="width:100%">
                    <tr>
                      <td>Jenis Proyek</td>
                      <td>:</td>
                      <td>
                        <?= $data->nama_jenis_proyek ?>
                        <input type="hidden" name="jenisid" value="<?= $data->jenis_proyek_id ?>">
                        <input type="hidden" name="progresscount" value="<?= $data->progresscount + 1 ?>">
                      </td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>Nama Konsumen/PPK</td>
                      <td>:</td>
                      <td>
                        <?= $data->nama_konsumen ?>
                        <input type="hidden" name="proyekid" value="<?= $data->proyek_id ?>">
                      </td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>No SPMK</td>
                      <td>:</td>
                      <td><?= $data->nospmk ?></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>No SPK</td>
                      <td>:</td>
                      <td><?= $data->no_surat_kontrak ?></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>Nama Kegiatan</td>
                      <td>:</td>
                      <td>
                        <?= $data->nama_kegiatan ?>
                        <input type="hidden" name="kegiatanid" value="<?= $data->kegiatan_id ?>">
                      </td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>Tanggal Mulai Kegiatan</td>
                      <td>:</td>
                      <td><?= $data->startDate ?></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>Tanggal Selesai Kegiatan</td>
                      <td>:</td>
                      <td><?= $data->endDate ?></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>Volume Target</td>
                      <td>:</td>
                      <td><?= $data->volorder . ' ' . $data->satjadwal ?></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>Volume Kegiatan</td>
                      <td>:</td>
                      <td><?= $data->volkegiatan . ' ' . $data->unit ?></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>Total Volume Kegiatan</td>
                      <td>:</td>
                      <td><?= $data->voljadwal . ' ' . $data->unit ?></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>Harga Satuan Proyek</td>
                      <td>:</td>
                      <td>Rp. <?= number_format(bulatkan($boq->total_harga_satuan), 0, ',', '.') ?></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>Total Boq Jenis Proyek</td>
                      <td>:</td>
                      <td>Rp. <?= number_format(bulatkan($boq->total), 0, ',', '.') ?></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>Keterangan</td>
                      <td>&nbsp;</td>
                      <td>
                        <textarea name="ket" cols="30" rows="10" class="form-control" required></textarea>
                        <div class="invalid-feedback">
                          Keterangan ?
                        </div>
                        <div class="valid-feedback">
                          Terisi!
                        </div>
                      </td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>Keterangan</td>
                      <td>&nbsp;</td>
                      <td>
                        <select name="status" class="form-control">
                          <option value="0">Proyek Berjalan</option>
                          <option value="1">Proyek Selesai</option>
                        </select>
                        <div class="invalid-feedback">
                          Nama Konsumen/PPK ?
                        </div>
                        <div class="valid-feedback">
                          Terisi!
                        </div>
                      </td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                  </table>
                </div>
              </div>
              <div class="p-2" style="width: 40%">
                <div class="row">
                  <div class="col-12">
                    <h5>Pengeluaran</h5>
                  </div>
                </div>
                <div class="row">
                  <div class="table-responsive">
                    <form action="#" method="post" id="frmPengeluaran">
                      <table class="table table-bordered nowrap" id="t_pengeluaran" style="width:100%">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Nama Bahan/Alat/Upah</th>
                            <th>Sat</th>
                            <th>Koef</th>
                            <th>Vol</th>
                            <th>Harga Satuan</th>
                            <th>Pengeluaran</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $i = 0;
                          foreach ($BUA as $v) : ?>
                            <tr>
                              <td class="align-middle"><?= $i + 1; ?></td>
                              <td class="align-middle">
                                <?= $v->nama_barang_upah ?>
                                <input type="hidden" name="pb[]" value="<?= $v->id ?>">
                              </td>
                              <td class="align-middle">
                                <?= $v->satuan ?>
                                <input type="hidden" name="sat[]" value="<?= $v->satuan ?>">
                              </td>
                              <td class="align-middle">
                                <?= $v->koef ?>
                                <input type="hidden" id-i="<?= $i ?>" name="koef[]" value="<?= $v->koef ?>" id="<?= 'koef' . $i ?>">
                              </td>
                              <td><input style="width:80px" id-i="<?= $i ?>" type="text" class="form-control volp" name="volp[]" value="<?= json_decode($isSave) === 'save' ? 0 : $v->vol ?>" id="<?= 'vol' . $i; ?>"></td>
                              <td><input type="text" id-i="<?= $i ?>" class="form-control formuang hargap" name="harga[]" value="<?= json_decode($isSave) === 'save' ? $v->harga : $v->harga ?>" id="<?= 'hs' . $i ?>"></td>
                              <td style="vertical-align: middle; text-align:right;">
                                <span id-i="<?= $i ?>" id="<?= 'stot' . $i ?>"><?= json_decode($isSave) === 'save' ? 0 : number_format($v->total, 0, ',', '.') ?></span>
                                <input type="hidden" id-i="<?= $i ?>" type="text" name="tot[]" id="<?= 'tot' . $i ?>" value="<?= json_decode($isSave) === 'save' ? 0 : $v->total ?>">
                              </td>
                            </tr>
                          <?php $i++;
                          endforeach; ?>
                        </tbody>
                        <tfoot>
                          <tr>
                            <th colspan="6">Total Pengeluaran</th>
                            <th class="text-right" id="footerTotal"><?= json_decode($isSave) === 'save' ? 0 : number_format($TPT, 0, ',', '.') ?></th>
                          </tr>
                        </tfoot>
                      </table>
                    </form>
                  </div>
                </div>
              </div>
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
              <input type="range" name="persentase" class="form-control-range" value="0">
              <span id="rangeval">0</span>
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
  var globalTotalPengeluaran = 0;
  var isSave = JSON.parse('<?= $isSave; ?>');
  var lastPersentase = JSON.parse('<?= $lastPersentase ?>');
  let t_pengeluaran = $('#t_pengeluaran').DataTable({
    paging: false,
    searching: false,
    fixedColumns: {
      leftColumns: 0,
      rightColumns: 1,
    },
    info: false,
    // columnDefs: [{
    //   targets: [1],
    //   render: $.fn.dataTable.render.number('.', ',', 0, '')
    // }]
  });

  $(document).on('input', '.volp,.hargap', function(e) {
    const koef = parseFloat($(`#koef${$(this).attr('id-i')}`).val());
    const vol = parseFloat($(`#vol${$(this).attr('id-i')}`).val());
    const hs = parseFloat($(`#hs${$(this).attr('id-i')}`).inputmask('unmaskedvalue'));
    let hitung = parseInt((koef * hs)) * parseInt(vol);
    $(`#stot${$(this).attr('id-i')}`).text(hitung.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
    $(`#tot${$(this).attr('id-i')}`).val(hitung);

    let totalpengeluaran = 0;

    $("[name='tot[]']").map(function() {
      totalpengeluaran += parseInt($(this).val());
    }).get();

    globalTotalPengeluaran = totalpengeluaran;

    $('#footerTotal').text(totalpengeluaran.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
  });
  console.log(isSave);
  $('[name=aksi]').val(isSave);
  $(document).on('input', '[name=persentase]', function() {
    $('#rangeval').html($(this).val());
    if (isSave === 'save') {
      if (parseInt($(this).val()) <= parseInt(lastPersentase)) {
        $('#submit').addClass('disabled btn-progress');
      } else {
        $('#submit').removeClass('disabled btn-progress');
      }
    } else {
      if (parseInt($(this).val()) === 0) {
        $('#submit').addClass('disabled btn-progress');
      } else {
        $('#submit').removeClass('disabled btn-progress');
      }
    }
  });

  $('.formuang').inputmask("numeric", {
    groupSeparator: ".",
    digits: 0,
    autoGroup: true,
    rightAlign: false,
    removeMaskOnSubmit: true,
    allowMinus: false
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
    $('#infoPersentase').html("<div class='alert alert-info'>Persentase Harus Lebih dari " + lastPersentase + "%</div>");
  } else {
    var edit = JSON.parse('<?= $edit; ?>');
    var pengeluaran = JSON.parse('<?= $pengeluaran; ?>');
    var loadphoto = JSON.parse('<?= $loadphoto; ?>');
    let btn = '<button type="button" id="ubah" class="btn btn-icon btn-sm btn-warning"><i class="fas fa-pen"></i></button>' +
      ' <button type="button" id="hapus" class="btn btn-icon btn-sm btn-danger"><i class="fas fa-trash"></i></button>';
    // $.each(pengeluaran, function(k, v) {
    //   t_pengeluaran.row.add([
    //     v.nama_pengeluaran,
    //     v.harga_pengeluaran,
    //     btn
    //   ]).draw();
    // });
    $('[name=persentase]').val(edit.persentase);
    $('#rangeval').html(edit.persentase);
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
      if (t_pengeluaran.rows().count() === 0) {
        alert('Tambah Pengeluaran!');
      }
      $('#submit').removeClass('disabled btn-progress');
    } else {
      let idbarang = $("[name='pb[]']").map(function() {
        return $(this).val();
      }).get();
      let koef = $("[name='koef[]']").map(function() {
        return $(this).val();
      }).get();
      let vol = $("[name='volp[]']").map(function() {
        return $(this).val();
      }).get();
      let hsp = $("[name='harga[]']").map(function() {
        return $(this).val();
      }).get();
      let total = $("[name='tot[]']").map(function() {
        return $(this).val();
      }).get();
      let sat = $("[name='sat[]']").map(function() {
        return $(this).val();
      }).get();
      let formdata = new FormData($("#update_f")[0]);
      formdata.append('idbarang', JSON.stringify(idbarang));
      formdata.append('koef', JSON.stringify(koef));
      formdata.append('vol', JSON.stringify(vol));
      formdata.append('hs', JSON.stringify(hsp));
      formdata.append('sat', JSON.stringify(sat));
      formdata.append('total', JSON.stringify(total));
      formdata.append('gtotal', globalTotalPengeluaran);
      $.ajax({
        method: "POST",
        contentType: false,
        processData: false,
        data: formdata,
        dataType: "JSON",
        url: isSave === 'save' ? '<?= base_url() ?>ProgressProyek_c/create' : '<?= base_url() ?>ProgressProyek_c/updateproses/' + idprogress,
        success: function(respon) {
          if (respon.status === 'sukses') {
            notifsukses('Data Progress', 'diupdate');
          } else {
            notifgagal('Data Progress', 'diupdate');
          }
          setTimeout(function() {
            window.location.href = "<?= base_url('ProgressProyek_c/updateKegiatan/' . $data->proyek_id . '/' . $data->jenis_proyek_id . '/' . $data->kegiatan_id) ?>";
          }, 1000);
        }
      });
    }
    return false;
  });
</script>