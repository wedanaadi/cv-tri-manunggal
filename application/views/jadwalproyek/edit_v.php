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
      <h1>Ubah Jadwal Proyek</h1>
    </div>
    <div class="section-body">
      <div class="col-12">
        <div class="card">
          <form id="jp_f" class="needs-validation" novalidate="" autocomplete="off">
            <div class="card-body">

              <div class="row g-3">
                <div class="form-group col-xs-12 col-md-6 col-lg-6">
                  <label>Nama Konsumen/PPK</label>
                  <select name="nama" class="form-control" style="width: 100%;" required="" disabled></select>
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
                  <select name="spmk" class="form-control" style="width: 100%;" required="" disabled></select>
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
    // start edit
    let editJadwal = JSON.parse('<?= $jadwal ?>');
    let detailProyek = JSON.parse('<?= $detailProyek ?>');
    let idjadwal = JSON.parse('<?= $id_jadwal ?>');
    select2isi('[name=nama]', editJadwal.konsumen_id, editJadwal.nama_konsumen);
    select2isi('[name=spmk]', editJadwal.proyek_id, editJadwal.nospmk);
    $('[name=startdate]').val(editJadwal.tanggal_mulai);
    $('[name=enddate]').val(editJadwal.tanggal_selesai);
    $('[name=ket]').val(editJadwal.ket);

    $('#jenis').html("");
    $('#loopjenis').html("");
    $('#jenis').append("<ul>");
    countKegiatan = JSON.parse('<?= $countKegiatan ?>');
    $.each(detailProyek, function(k, v) {
      $('#jenis').append(`<li>${v.nama_jenis_proyek} [${v.nama_user}]</li>`);
      $('#loopjenis').append(
        /*html*/
        `
        <div class="card card-success">
          <div class="card-header">
            <div class="card-header-action pr-3">
              <button class="btn btn-primary collapseTombol" id-jn="${v.jenis_proyek}" vol-jenis="${v.vol}" type="button" data-toggle="collapse" data-target="#collapse-${v.jenis_proyek}" aria-expanded="true" aria-controls="collapseExample">
                <i class="fas fa-minus"></i>
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
          <div class="collapse show" id="collapse-${v.jenis_proyek}">
            <div class="card-body" id="card-${v.jenis_proyek}">
              You can show or hide this card - ${v.jenis_proyek}.
            </div>
          </div>
        </div>`
      );
      $.ajax({
        type: "GET",
        dataType: "JSON",
        url: "<?= base_url(); ?>JadwalProyek_c/formUbah/",
        data: {
          'jenis': v.jenis_proyek,
          'proyek': $('[name=spmk]').val(),
          idjadwal
        },
        success: function(respon) {
          $(`#card-${v.jenis_proyek}`).html("");
          $(`#card-${v.jenis_proyek}`).html(respon.view);
        }
      });
    });
    $('#jenis').append("</ul>");
    $('#divjenis').removeClass('hide');

    // end edit

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
        url: "<?= base_url(); ?>JadwalProyek_c/formUbah/",
        data: {
          jenis,
          vol,
          isSave,
          'proyek': $('[name=spmk]').val(),
          idjadwal
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
        let dataForm = new FormData($("#jp_f")[0]);
        dataForm.append('konsumen', $('[name=nama]').val());
        dataForm.append('proyek', $('[name=spmk]').val());
        $.ajax({
          method: "POST",
          contentType: false,
          processData: false,
          data: dataForm,
          url: '<?= base_url() ?>JadwalProyek_c/update/' + idjadwal,
          success: function(respon) {
            notifsukses('Data Jadwal Proyek', 'diubah');
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