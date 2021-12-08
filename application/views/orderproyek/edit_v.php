<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<style>
  .dn {
    display: none;
  }
</style>
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Ubah Order Proyek</h1>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-body">
          <form id="detail_f" autocomplete="off" class="needs-validation" novalidate="">
            <div class="form-group row" style="margin-bottom:0px">
              <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">No SPMK</label>
              <div class="col-xs-12 col-md-6 col-lg-6">
                <div class="form-group col-12">
                  <input type="text" name="spmk" class="form-control" required="">
                  <div class="invalid-feedback">
                    No SPMK ?
                  </div>
                  <div class="valid-feedback">
                    Terisi!
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group row" style="margin-bottom:0px">
              <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Nama Konsumen/PPK</label>
              <div class="col-xs-12 col-md-6 col-lg-6">
                <div class="form-group col-12">
                  <select name="idkonsumen" id="idkonsumen" class="form-control"></select>
                  <input type="hidden" name="nama" class="form-control" required="">
                  <div class="invalid-feedback">
                    Nama Konsumen/PPK ?
                  </div>
                  <div class="valid-feedback">
                    Terisi!
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group row" style="margin-bottom:0px">
              <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Kegiatan</label>
              <div class="col-xs-12 col-md-6 col-lg-6">
                <div class="form-group col-12">
                  <input type="text" name="kegiatan" class="form-control" required="">
                  <div class="invalid-feedback">
                    Kegiatan ?
                  </div>
                  <div class="valid-feedback">
                    Terisi!
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group row" style="margin-bottom:0px">
              <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Pekerjaan</label>
              <div class="col-xs-12 col-md-6 col-lg-6">
                <div class="form-group col-12">
                  <input type="text" name="pekerjaan" class="form-control" required="">
                  <div class="invalid-feedback">
                    Pekerjaan ?
                  </div>
                  <div class="valid-feedback">
                    Terisi!
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group row" style="margin-bottom:0px">
              <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Lokasi</label>
              <div class="col-xs-12 col-md-6 col-lg-6">
                <div class="form-group col-12">
                  <input type="text" name="lokasi" class="form-control" required="">
                  <div class="invalid-feedback">
                    Lokasi ?
                  </div>
                  <div class="valid-feedback">
                    Terisi!
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group row" style="margin-bottom:0px">
              <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Tahun Anggaran</label>
              <div class="col-xs-12 col-md-4 col-lg-4">
                <div class="form-group col-12">
                  <input type="number" name="ta" class="form-control" required="">
                  <div class="invalid-feedback">
                    Tahun Anggaran ?
                  </div>
                  <div class="valid-feedback">
                    Terisi!
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group row" style="margin-bottom:0px">
              <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">No Surat Kontrak</label>
              <div class="col-xs-12 col-md-6 col-lg-6">
                <div class="form-group col-12">
                  <input type="text" name="nosk" class="form-control" required="">
                  <div class="invalid-feedback">
                    No Surat Kontrak ?
                  </div>
                  <div class="valid-feedback">
                    Terisi!
                  </div>
                </div>
              </div>
            </div>
          </form>
          <hr>
          <div class="alert alert-danger dn" id="msg"></div>
          <div id="appends" style="margin-bottom:10px">
            <div class="row">
              <div id="bodyloop1" class="col-11">
                <div style="background-color: #f2f2f2; padding: 15px 15px 0px 15px; border: 1px solid #B6B6B6;">
                  <div class="form-group row" style="margin-bottom:0px; margin-top:15px">
                    <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Jenis Proyek</label>
                    <div class="col-xs-12 col-md-4 col-lg-4">
                      <div class="form-group col-12">
                        <select name="jp[]" class="form-control jp" style="width: 100%;" required=""></select>
                        <div class="invalid-feedback">
                          Jenis Proyek ?
                        </div>
                        <div class="valid-feedback">
                          Terisi!
                        </div>
                      </div>
                    </div>
                    <label class="col-xs-12 col-md-1 col-lg-1 col-form-label">Vol</label>
                    <div class="col-xs-12 col-md-2 col-lg-2">
                      <div class="form-group col-12">
                        <input type="number" name="vol[]" class="form-control" required="">
                        <div class="invalid-feedback">
                          Unit ?
                        </div>
                        <div class="valid-feedback">
                          Terisi!
                        </div>
                      </div>
                    </div>
                    <label class="col-xs-12 col-md-1 col-lg-1 col-form-label">Sat</label>
                    <div class="col-xs-12 col-md-2 col-lg-2">
                      <div class="form-group col-12">
                        <input type="text" name="sat[]" class="form-control" required="">
                        <div class="invalid-feedback">
                          Vol ?
                        </div>
                        <div class="valid-feedback">
                          Terisi!
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row" style="margin-bottom:0px">
                    <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Kepala Proyek</label>
                    <div class="col-xs-12 col-md-6 col-lg-6">
                      <div class="form-group col-12">
                        <select name="kp[]" class="form-control kp" style="width: 100%;" required=""></select>
                        <div class="invalid-feedback">
                          Kepala Proyek ?
                        </div>
                        <div class="valid-feedback">
                          Terisi!
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col text-center">
              <button type="button" id="add" style="margin-left:15px" class="btn btn-icon icon-left btn-info">
                <i class="fas fa-plus"></i>
                Tambah
              </button>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col text-right">
              <a href="<?= base_url() ?>OrderProyek_c/index" type="button" id="cancel" class="btn btn-icon icon-left btn-light">
                <i class="fas fa-ban"></i>
                Cancel
              </a> &nbsp;
              <button type="button" id="submit" class="btn btn-icon icon-left btn-primary">
                <i class="fas fa-save"></i>
                Simpan
              </button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
</div>
<?php $this->load->view('_partials/footer'); ?>

<script>
  $(function() {
    var index = 0;
    var dataedit = JSON.parse('<?= $edit; ?>');
    var editedit = JSON.parse('<?= $editdetil; ?>');

    // select2isi('[name=spmk]', dataedit.id_jadwal, dataedit.nospmk)
    $('[name=nama]').val(dataedit.nama_konsumen);
    $('[name=kegiatan]').val(dataedit.nama_kegiatan);
    $('[name=pekerjaan]').val(dataedit.nama_proyek_pekerjaan);
    $('[name=lokasi]').val(dataedit.lokasi);
    $('[name=ta]').val(dataedit.tahun_anggaran);
    $('[name=nosk]').val(dataedit.no_surat_kontrak);
    $('[name=spmk]').val(dataedit.nospmk);
    select2isi('[name=idkonsumen]', dataedit.konsumen_id, dataedit.nama_konsumen);

    $('#appends tr').remove();

    var editIndex = 0;
    $('#bodyloop1').remove();
    $.each(editedit, function(k, v) {
      index += 1;
      if (index === 1) {
        var style = "style='display:none'";
      } else {
        var style = '';
      }
      formappend = '<div class="row" id="bodyloop' + index + '">' +
        '<div class="col-11">' +
        '<div style="background-color: #f2f2f2; padding: 15px 15px 0px 15px; border: 1px solid #B6B6B6;">' +
        '<div class="form-group row" style="margin-bottom:0px; margin-top:15px">' +
        '<label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Jenis Proyek</label>' +
        '<div class="col-xs-12 col-md-4 col-lg-4">' +
        '<div class="form-group col-12">' +
        '<select id="jp' + editIndex + '" name="jp[]" class="form-control jp" style="width: 100%;" required=""></select>' +
        '<div class="invalid-feedback">' +
        'Jenis Proyek ?' +
        '</div>' +
        '<div class="valid-feedback">' +
        'Terisi!' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<label class="col-xs-12 col-md-1 col-lg-1 col-form-label">Vol</label>' +
        '<div class="col-xs-12 col-md-2 col-lg-2">' +
        '<div class="form-group col-12">' +
        '<input type="number" name="vol[]" class="form-control" value="' + v.vol + '" required="">' +
        '<div class="invalid-feedback">' +
        'Unit ?' +
        '</div>' +
        '<div class="valid-feedback">' +
        'Terisi!' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<label class="col-xs-12 col-md-1 col-lg-1 col-form-label">Sat</label>' +
        '<div class="col-xs-12 col-md-2 col-lg-2">' +
        '<div class="form-group col-12">' +
        '<input type="text" name="sat[]" class="form-control" value="' + v.sat + '" required="">' +
        '<div class="invalid-feedback">' +
        'Vol ?' +
        '</div>' +
        '<div class="valid-feedback">' +
        'Terisi!' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="form-group row" style="margin-bottom:0px">' +
        '<label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Kepala Proyek</label>' +
        '<div class="col-xs-12 col-md-6 col-lg-6">' +
        '<div class="form-group col-12">' +
        '<select id="kp' + editIndex + '" name="kp[]" class="form-control kp" style="width: 100%;" required=""></select>' +
        '<div class="invalid-feedback">' +
        'Kepala Proyek ?' +
        '</div>' +
        '<div class="valid-feedback">' +
        'Terisi!' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="col-1">' +
        '<button id-pk="' + index + '" class="btn btn-danger hapusappends" ' + style + ' ><i class="fas fa-trash"></i></button>' +
        '</div>' +
        '</div>';

      $('#appends').append(formappend);


      const namejp = '#jp' + editIndex;
      const namekp = '#kp' + editIndex;
      // console.log(name);
      select2isi(namejp, v.jenis_proyek, v.nama_jenis_proyek);
      select2isi(namekp, v.kepala_proyek, v.nama_user);
      editIndex++;
    });

    $('select[name=idkonsumen]').select2({
      placeholder: "Pilih Nama",
      ajax: {
        url: "<?= base_url(); ?>Konsumen_c/select2",
        dataType: 'json',
        data: function(params) {
          return {
            q: $.trim(params.term)
          };
        },
        processResults: function(data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });

    $('[name=idkonsumen]').on('select2:select', function() {
      $('[name=nama]').val($('[name=idkonsumen]').find(':selected').text());
    });

    $(document).on('click', '#add', function() {
      index += 1;
      formappend = '<div class="row" id="bodyloop' + index + '">' +
        '<div class="col-11">' +
        '<div style="background-color: #f2f2f2; padding: 15px 15px 0px 15px; border: 1px solid #B6B6B6;">' +
        '<div class="form-group row" style="margin-bottom:0px; margin-top:15px">' +
        '<label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Jenis Proyek</label>' +
        '<div class="col-xs-12 col-md-4 col-lg-4">' +
        '<div class="form-group col-12">' +
        '<select name="jp[]" class="form-control jp" style="width: 100%;" required=""></select>' +
        '<div class="invalid-feedback">' +
        'Jenis Proyek ?' +
        '</div>' +
        '<div class="valid-feedback">' +
        'Terisi!' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<label class="col-xs-12 col-md-1 col-lg-1 col-form-label">Vol</label>' +
        '<div class="col-xs-12 col-md-2 col-lg-2">' +
        '<div class="form-group col-12">' +
        '<input type="number" name="vol[]" class="form-control" required="">' +
        '<div class="invalid-feedback">' +
        'Unit ?' +
        '</div>' +
        '<div class="valid-feedback">' +
        'Terisi!' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<label class="col-xs-12 col-md-1 col-lg-1 col-form-label">Sat</label>' +
        '<div class="col-xs-12 col-md-2 col-lg-2">' +
        '<div class="form-group col-12">' +
        '<input type="text" name="sat[]" class="form-control" required="">' +
        '<div class="invalid-feedback">' +
        'Vol ?' +
        '</div>' +
        '<div class="valid-feedback">' +
        'Terisi!' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="form-group row" style="margin-bottom:0px">' +
        '<label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Kepala Proyek</label>' +
        '<div class="col-xs-12 col-md-6 col-lg-6">' +
        '<div class="form-group col-12">' +
        '<select name="kp[]" class="form-control kp" style="width: 100%;" required=""></select>' +
        '<div class="invalid-feedback">' +
        'Kepala Proyek ?' +
        '</div>' +
        '<div class="valid-feedback">' +
        'Terisi!' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="col-1">' +
        '<button id-pk="' + index + '" class="btn btn-danger hapusappends"><i class="fas fa-trash"></i></button>' +
        '</div>' +
        '</div>';
      $('#appends').append(formappend);

      $('.jp').select2({
        placeholder: "Pilih Jenis Proyek",
        ajax: {
          url: "<?= base_url(); ?>JenisProyek_c/select2",
          dataType: 'json',
          data: function(params) {
            return {
              q: $.trim(params.term)
            };
          },
          processResults: function(data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });

      $('.kp').select2({
        placeholder: "Kepala Proyek",
        ajax: {
          url: "<?= base_url(); ?>User_c/select2/" + 2,
          dataType: 'json',
          data: function(params) {
            return {
              q: $.trim(params.term)
            };
          },
          processResults: function(data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });

    });

    $(document).on('click', '.hapusappends', function(e) {
      var indexs = $(this).attr('id-pk');
      const name = "#bodyloop" + indexs;
      $(name).remove();
    });

    $('.jp').select2({
      placeholder: "Pilih Jenis Proyek",
      ajax: {
        url: "<?= base_url(); ?>JenisProyek_c/select2",
        dataType: 'json',
        data: function(params) {
          return {
            q: $.trim(params.term)
          };
        },
        processResults: function(data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });

    $('.kp').select2({
      placeholder: "Kepala Proyek",
      ajax: {
        url: "<?= base_url(); ?>User_c/select2/" + 2,
        dataType: 'json',
        data: function(params) {
          return {
            q: $.trim(params.term)
          };
        },
        processResults: function(data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });

    // submit
    $(document).on('click', '#submit', function(e) {
      $('#submit').addClass('disabled btn-progress');
      var dataValidJP = true;
      var dataValidVOL = true;
      var dataValidSAT = true;
      var dataValidKP = true;
      var valuesJP = $("select[name='jp[]']")
        .map(function() {
          if ($(this).val() === null) {
            dataValidJP = false;
          } else {
            return $(this).val();
          }
        }).get();
      var valuesVOL = $("[name='vol[]']")
        .map(function() {
          if ($(this).val() === '') {
            dataValidVOL = false;
          } else {
            return $(this).val();
          }
        }).get();
      var valuesSAT = $("input[name='sat[]']")
        .map(function() {
          if ($(this).val() === '') {
            dataValidSAT = false;
          } else {
            return $(this).val();
          }
        }).get();
      var valuesKP = $("select[name='kp[]']")
        .map(function() {
          if ($(this).val() === null) {
            dataValidKP = false;
          } else {
            return $(this).val();
          }
        }).get();

      $('#msg').html("");

      var form = $('#detail_f');
      if (form[0].checkValidity() === false || dataValidJP === false || dataValidKP === false || dataValidVOL === false || dataValidSAT === false) {
        event.preventDefault();
        event.stopPropagation();
        $('#msg').html("")
        if (dataValidJP === false || dataValidKP === false || dataValidVOL === false || dataValidSAT === false) {
          $('#msg').show();
          $('#msg').html("<p>Data Tidak Lengkap</p>")
        } else {
          $('#msg').hide();
        }
      } else {
        $('#msg').hide();
        $.ajax({
          method: "POST",
          dataType: "JSON",
          data: {
            id_jadwal: $('[name=spk]').val(),
            kegiatan: $('[name=kegiatan]').val(),
            pekerjaan: $('[name=pekerjaan]').val(),
            lokasi: $('[name=lokasi]').val(),
            konsumen: $('[name=nama]').val(),
            idkon: $('[name=idkonsumen]').val(),
            ta: $('[name=ta]').val(),
            nosk: $('[name=nosk]').val(),
            spmk: $('[name=spmk]').val(),
            jp: valuesJP,
            vol: valuesVOL,
            sat: valuesSAT,
            kp: valuesKP,
          },
          url: "<?= base_url() ?>OrderProyek_c/update/" + dataedit.id_proyek,
          success: function(respon) {
            notifsukses('Data Order Proyek', 'diubah');
            setTimeout(function() {
              window.location.href = "<?= base_url('OrderProyek_c') ?>";
            }, 1000);
          }
        });
      }
      form.addClass('was-validated');
      return false;
    });
  });
</script>