<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<style>
  ul {
    list-style-position: outside;
  }

  .dn {
    display: none;
  }
</style>
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Tambah Jadwal Proyek</h1>
    </div>
    <div class="section-body">
      <div class="col-12">
        <div class="card">
          <form id="jp_f" class="needs-validation" novalidate="" autocomplete="off">
            <div class="card-body">

              <div class="row g-3">
                <div class="form-group col-xs-12 col-md-6 col-lg-6">
                  <label>Nama Konsumen/PPK</label>
                  <select name="nama" class="form-control" style="width: 100%;" required=""></select>
                  <div class="invalid-feedback">
                    Nama Konsumen/PPK ?
                  </div>
                  <div class="valid-feedback">
                    Terisi!
                  </div>
                </div>
                <div class="form-group col-xs-12 col-md-6 col-lg-6">
                  <label>No SPMK</label>
                  <!-- <input type="text" name="spmk" class="form-control" required=""> -->
                  <select name="spmk" class="form-control" style="width: 100%;" required=""></select>
                  <div class="invalid-feedback">
                    No SPMK ?
                  </div>
                  <div class="valid-feedback">
                    Terisi!
                  </div>
                </div>
                <div class="form-group col-xs-12 col-md-6 col-lg-6">
                  <label>Tanggal Mulai</label>
                  <input type="text" name="startdate" class="form-control waktu" required="">
                  <div class="invalid-feedback">
                    Tanggal Mulai ?
                  </div>
                  <div class="valid-feedback">
                    Terisi!
                  </div>
                </div>
                <div class="form-group col-xs-12 col-md-6 col-lg-6">
                  <label>Tanggal Penyelesaian</label>
                  <input type="text" name="enddate" class="form-control waktu" required="">
                  <div class="invalid-feedback">
                    Tanggal Penyelesaian ?
                  </div>
                  <div class="valid-feedback">
                    Terisi!
                  </div>
                </div>
                <div class="form-group col-xs-12 col-md-6 col-lg-6">
                  <label>Keterangan</label>
                  <textarea name="ket" cols="30" rows="10" class="form-control" required=""></textarea>
                  <div class="invalid-feedback">
                    Keterangan ?
                  </div>
                  <div class="valid-feedback">
                    Terisi!
                  </div>
                </div>
                <div id="divjenis" class="hide form-group col-xs-12 col-md-6 col-lg-6">
                  <label>Jenis Proyek</label>
                  <div>
                    <ul id="jenis"></ul>
                  </div>
                </div>
              </div>
              <!-- jenis kegiatan -->
              <div class="alert alert-danger dn" id="msg"></div>
              <div id="loopjenis"></div>
              <!-- end jenis kegiatan -->
            </div>
            <div class="card-footer">
              <div class="row">
                <div class="col text-center">
                  <a href="<?= base_url() ?>JadwalProyek_c/index" type="button" id="cancel" class="btn btn-icon icon-left btn-light">
                    <i class="fas fa-ban"></i>
                    Cancel
                  </a> &nbsp;
                  <button type="submit" id="submit" class="btn btn-icon icon-left btn-primary">
                    <i class="fas fa-save"></i>
                    Simpan
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>
<?php $this->load->view('_partials/footer'); ?>

<script>
  $(function() {
    let countKegiatan = 0;
    let isSave = 'true';
    $('select[name=nama]').select2({
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

    $('[name=nama]').on('select2:select', function(e) {
      e.preventDefault();
      $('[name=spmk]').select2({
        placeholder: "No SPMK",
        ajax: {
          url: "<?= base_url(); ?>OrderProyek_c/select2SPMK/" + $(this).val(),
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
      $.ajax({
        method: "GET",
        dataType: "JSON",
        data: {
          idKon: $(this).val()
        },
        url: "<?= base_url('OrderProyek_c/getJsonByKonsumen') ?>",
        success: function(respon) {
          $('#jenis').html("");
          $('#loopjenis').html("");
          $("[name=spmk]").val('').trigger('change');
          if (respon.count === 1) {
            select2isi('[name=spmk]', respon.order.id_proyek, respon.order.nospmk);
            if (respon.checkJadwal > 0) {
              $('#jenis').append("<span style='color:#dc3545'>Order sudah mempunyai jadwal</span>")
              $('#submit').addClass('disabled btn-progress');
              isSave = 'false';
            } else {
              isSave = 'true';
              $('#submit').removeClass('disabled btn-progress');
            }
            countKegiatan = respon.countKegiatan;
            $.each(respon.detail, function(k, v) {
              $('#jenis').append(`<li>${v.nama_jenis_proyek} [${v.nama_user}]</li>`);
              $('#loopjenis').append(
                /*html*/
                `<div class="card card-success">
                  <div class="card-header">
                    <div class="card-header-action pr-3">
                      <button class="btn btn-primary collapseTombol" id-jn="${v.jenis_proyek}" vol-jenis="${v.vol}" type="button" data-toggle="collapse" data-target="#collapse-${v.jenis_proyek}" aria-expanded="false" aria-controls="collapseExample">
                        <i class="fas fa-plus"></i>
                      </button>
                    </div>
                    
                        <table border="0" style="width:50%; font-weight: bold">
                        <tr>
                          <td colspan="3">${v.nama_jenis_proyek}</td>
                        </tr>
                        <tr>
                          <td style="width:25%">Kepala Proyek</td>
                          <td style="width:2%">:</td>
                          <td>${v.nama_user}</td>
                        </tr>
                        <tr>
                          <td style="width:25%">Volume</td>
                          <td style="width:2%">:</td>
                          <td>${v.vol}</td>
                        </tr>
                        </table>
                  </div>
                  <div class="collapse" id="collapse-${v.jenis_proyek}">
                    <div class="card-body" id="card-${v.jenis_proyek}">
                      You can show or hide this card - ${v.jenis_proyek}.
                    </div>
                  </div>
                </div>`
              );
            });
            $('#divjenis').removeClass('hide');
          }
        }
      });
      return false;
    });

    $('[name=spmk]').select2({
      placeholder: "No SPMK",
      ajax: {
        url: "<?= base_url(); ?>OrderProyek_c/select2SPMK",
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

    $('[name=spmk]').on('select2:select', function(e) {
      $.ajax({
        method: "GET",
        dataType: "JSON",
        data: {
          id: $(this).val()
        },
        url: "<?= base_url('OrderProyek_c/getJsonOder') ?>",
        success: function(respon) {
          select2isi('[name=nama]', respon.order.konsumen_id, respon.order.nama_konsumen);
          $('#jenis').html("");
          $('#loopjenis').html("");
          $('#jenis').append("<ul>");
          if (respon.count > 0) {
            $('#jenis').append("<span style='color:#dc3545'>Order sudah mempunyai jadwal</span>")
            $('#submit').addClass('disabled btn-progress');
            isSave = 'false';
          } else {
            isSave = 'true';
            $('#submit').removeClass('disabled btn-progress');
          }
          countKegiatan = respon.countKegiatan;
          $.each(respon.detail, function(k, v) {
            $('#jenis').append(`<li>${v.nama_jenis_proyek}[${v.nama_user}]</li>`);
            $('#loopjenis').append(
              /*html*/
              `<div class="card card-success">
                  <div class="card-header">
                    <div class="card-header-action pr-3">
                      <button class="btn btn-primary collapseTombol" id-jn="${v.jenis_proyek}" vol-jenis="${v.vol}" type="button" data-toggle="collapse" data-target="#collapse-${v.jenis_proyek}" aria-expanded="false" aria-controls="collapseExample">
                        <i class="fas fa-plus"></i>
                      </button>
                    </div>
                    
                        <table border="0" style="width:50%; font-weight: bold">
                        <tr>
                          <td colspan="3">${v.nama_jenis_proyek}</td>
                        </tr>
                        <tr>
                          <td style="width:25%">Kepala Proyek</td>
                          <td style="width:2%">:</td>
                          <td>${v.nama_user}</td>
                        </tr>
                        <tr>
                          <td style="width:25%">Volume</td>
                          <td style="width:2%">:</td>
                          <td>${v.vol}</td>
                        </tr>
                        </table>
                  </div>
                  <div class="collapse" id="collapse-${v.jenis_proyek}">
                    <div class="card-body" id="card-${v.jenis_proyek}">
                      You can show or hide this card - ${v.jenis_proyek}.
                    </div>
                  </div>
                </div>`
            );
          });
          $('#jenis').append("</ul>");
          $('#divjenis').removeClass('hide');
        }
      });
    });

    $('.waktu').daterangepicker({
      singleDatePicker: true,
      showDropdowns: true,
      // drops: "up",
      locale: {
        format: 'YYYY-MM-DD'
      },
    });

    $(document).on('click', '.collapseTombol', function() {
      const jenis = $(this).attr('id-jn');
      const vol = $(this).attr('vol-jenis');
      $.ajax({
        type: "GET",
        dataType: "JSON",
        url: "<?= base_url(); ?>JadwalProyek_c/form/",
        data: {
          jenis,
          vol,
          isSave,
          'proyek': $('[name=spmk]').val()
        },
        success: function(respon) {
          $(`#card-${jenis}`).html("");
          $(`#card-${jenis}`).html(respon.view);
        }
      });
    });

    $('#jp_f').on('submit', function(e) {
      e.preventDefault();
      $('#submit').addClass('disabled btn-progress');
      console.log(countKegiatan);
      let dataPegawai = true;
      let dataTglMulai = true;
      let dataTglSelesai = true;
      let dataVol = true;
      var valuesPegawai = $("select[name='pegawai[]']").map(function() {
        if ($(this).val() === null) {
          dataPegawai = false;
        } else {
          return $(this).val();
        }
      }).get();
      if (countKegiatan === 0 || valuesPegawai.length !== countKegiatan) {
        dataPegawai = false;
      }
      var valuesDateStart = $("input[name='mulai_pegawai[]']").map(function() {
        if ($(this).val() === null || $(this).val() === '') {
          dataTglMulai = false;
        } else {
          return $(this).val();
        }
      }).get();
      if (countKegiatan === 0 || valuesDateStart.length !== countKegiatan) {
        dataTglMulai = false;
      }
      var valuesDateEnd = $("input[name='selesai_pegawai[]']").map(function() {
        if ($(this).val() === null || $(this).val() === '') {
          dataTglSelesai = false;
        } else {
          return $(this).val();
        }
      }).get();
      if (countKegiatan === 0 || valuesDateEnd.length !== countKegiatan) {
        dataTglSelesai = false;
      }
      var valuesVol = $("input[name='vol[]']").map(function() {
        if ($(this).val() === null || $(this).val() === '') {
          dataVol = false;
        } else {
          return $(this).val();
        }
      }).get();
      if (countKegiatan === 0 || valuesVol.length !== countKegiatan) {
        dataVol = false;
      }
      var valuesKegiatan = $("input[name='kegiatanid[]']").map(function() {
        return $(this).val();
      }).get();
      var valuesDurasi = $("input[name='durasi[]']").map(function() {
        return $(this).val();
      }).get();
      var valuesUnit = $("input[name='unit[]']").map(function() {
        return $(this).val();
      }).get();
      var valuesHarga = $("input[name='harga[]']").map(function() {
        return $(this).val();
      }).get();
      var valuesTotal = $("input[name='total[]']").map(function() {
        return $(this).val();
      }).get();
      var form = $(this);
      if (form[0].checkValidity() === false || dataPegawai === false || dataTglMulai === false || dataTglSelesai === false || dataVol === false) {
        event.stopPropagation();
        $('#submit').removeClass('disabled btn-progress');
        $('#msg').html("")
        if (dataPegawai === false || dataTglMulai === false || dataTglSelesai === false || dataVol === false) {
          $('#msg').show();
          $('#msg').html( /*html*/ `<p>Data Tidak Lengkap, mohon dilengkapi data jadwal kegiatan dari masing-masing Jenis Proyek</p>`)
        } else {
          $('#msg').hide();
        }
      } else {
        $('#msg').hide();
        $.ajax({
          method: "POST",
          contentType: false,
          processData: false,
          data: new FormData($("#jp_f")[0]),
          url: '<?= base_url() ?>JadwalProyek_c/create',
          success: function(respon) {
            notifsukses('Data Jadwal Proyek', 'ditambah');
            setTimeout(function() {
              window.location.href = "<?= base_url('JadwalProyek_c/index') ?>";
            }, 1000);
          }
        });
      }
      return false;
    });
  });
</script>