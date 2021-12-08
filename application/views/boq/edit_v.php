<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Bill Of Quantity (BOQ)</h1>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <div class="card-title">
            <h4>Ubah BOQ</h4>
          </div>
        </div>
        <form id="boq_f" autocomplete="off" class="needs-validation" novalidate="">
          <div class="card-body">
            <div class="form-group row" style="margin-bottom:0px">
              <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Nama Konsumen/PPK</label>
              <div class="col-xs-12 col-md-10 col-lg-10 form-group">
                <select name="nama" class="form-control" style="width: 100%;" required=""></select>
                <div class="invalid-feedback">
                  Nama Konsumen/PPK ?
                </div>
                <div class="valid-feedback">
                  Terisi!
                </div>
              </div>
            </div>

            <div class="form-group row" style="margin-bottom:0px">
              <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Jenis Proyek</label>
              <div class="col-xs-12 col-md-10 col-lg-10 form-group">
                <select name="jp" class="form-control" style="width: 100%;" required="" disabled></select>
                <div class="invalid-feedback">
                  Jenis Proyek ?
                </div>
                <div class="valid-feedback">
                  Terisi!
                </div>
              </div>
            </div>

            <div class="form-group row" style="margin-bottom:0px">
              <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Jumlah</label>
              <div class="col-xs-12 col-md-3 col-lg-3 form-group">
                <input type="text" name="jumlah" class="form-control formuang" required="" disabled>
                <div class="invalid-feedback">
                  Jumlah ?
                </div>
                <div class="valid-feedback">
                  Terisi!
                </div>
              </div>
            </div>

            <div class="form-group row" style="margin-bottom:0px">
              <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">PPN 10%</label>
              <div class="col-xs-12 col-md-3 col-lg-3 form-group">
                <input type="text" name="ppn" class="form-control formuang" required="" disabled>
                <div class="invalid-feedback">
                  PPN ?
                </div>
                <div class="valid-feedback">
                  Terisi!
                </div>
              </div>
            </div>

            <div class="form-group row" style="margin-bottom:0px">
              <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Total Harga Satuan</label>
              <div class="col-xs-12 col-md-3 col-lg-3 form-group">
                <input type="text" name="ths" class="form-control formuang" required="" disabled>
                <div class="invalid-feedback">
                  Total Harga Satuan ?
                </div>
                <div class="valid-feedback">
                  Terisi!
                </div>
              </div>
            </div>

            <div class="form-group row" style="margin-bottom:0px">
              <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Volume</label>
              <div class="col-xs-12 col-md-2 col-lg-2 form-group">
                <input type="text" name="vol" class="form-control" required="" disabled>
                <div class="invalid-feedback">
                  Volume ?
                </div>
                <div class="valid-feedback">
                  Terisi!
                </div>
              </div>
              <label class="col-xs-12 col-md-1 col-lg-1 col-form-label">Satuan</label>
              <div class="col-xs-12 col-md-2 col-lg-2 form-group">
                <input type="text" name="sat" class="form-control" required="">
                <div class="invalid-feedback">
                  Satuan ?
                </div>
                <div class="valid-feedback">
                  Terisi!
                </div>
              </div>
            </div>

            <div class="form-group row" style="margin-bottom:0px">
              <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Jumlah Total</label>
              <div class="col-xs-12 col-md-3 col-lg-3 form-group">
                <input type="text" name="totals" class="form-control formuang" required="" disabled>
                <div class="invalid-feedback">
                  Jumlah Total ?
                </div>
                <div class="valid-feedback">
                  Terisi!
                </div>
              </div>
            </div>

          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col text-right">
                <a href="<?= base_url() ?>Boq_c/index" type="button" id="cancel" class="btn btn-icon icon-left btn-light">
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
  </section>
</div>

<?php $this->load->view('_partials/footer'); ?>

<script>
  $(function() {

    // editload
    var dataedit = JSON.parse('<?= $edit; ?>');

    select2isi("select[name=nama]", dataedit.nama_satker, dataedit.nama_konsumen);
    select2isi("select[name=jp]", dataedit.nama_kegiatan + '-' + dataedit.jenis_proyek, dataedit.nama_jenis_proyek + ' | ' + dataedit.kegiatan);
    $('[name=jp]').removeAttr('disabled');
    $('[name=vol]').removeAttr('disabled');
    $('[name=vol]').val(dataedit.volume);
    $('[name=jumlah]').val(dataedit.jumlah_harga);
    $('[name=sat]').val(dataedit.satuan);
    $('[name=ppn]').val(dataedit.ppn);
    $('[name=ths]').val(dataedit.total_harga_satuan);
    $('[name=totals]').val(dataedit.total);
    $('select[name=jp]').select2({
      placeholder: "Jenis Proyek",
      ajax: {
        url: "<?= base_url(); ?>Boq_c/select2JenisProyek/" + $('[name=nama]').val(),
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
    // 

    $('select[name=nama]').select2({
      placeholder: "Konsumen",
      ajax: {
        url: "<?= base_url(); ?>Konsumen_c/select2/" + 1,
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
      $('select[name=jp]').removeAttr('disabled');
      $('select[name=jp]').select2({
        placeholder: "Jenis Proyek",
        ajax: {
          url: "<?= base_url(); ?>Boq_c/select2JenisProyek/" + $(this).val(),
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
      $('select[name=jp]').val('').trigger('change');
    });

    $('[name=jp]').on('select2:select', function(e) {
      $.ajax({
        method: "GET",
        dataType: "JSON",
        url: "<?= base_url() ?>Boq_c/checked",
        data: {
          param: $(this).val()
        },
        success: function(respon) {
          if (respon === 1) {
            $('#submit').addClass('disabled btn-progress');
            $('#jenis').html("<span style='color:#dc3545'>Data Sudah di tambahkan</span>");
          } else {
            $('#submit').removeClass('disabled btn-progress');
            $('#jenis').html("");
          }
        }
      });
    });

    $('.formuang').inputmask("numeric", {
      groupSeparator: ".",
      digits: 2,
      digitsOptional: true,
      radixPoint: ",",
      autoGroup: true,
      rightAlign: false,
      removeMaskOnSubmit: true,
      allowMinus: false
    });

    $('[name=jp]').on('select2:select', function() {
      $('input[name=vol]').removeAttr('disabled');
      $.ajax({
        method: "GET",
        dataType: "JSON",
        url: "<?= base_url() ?>Boq_c/getJumlah",
        data: {
          id: $(this).val().split('-')[1]
        },
        success: function(respon) {
          $('[name=jumlah]').val(respon.jumlah);
          $('[name=ppn]').val(respon.ppn);
          $('[name=ths]').val(respon.total);
        }
      });
    });

    $('[name=vol]').on('input', function(e) {
      console.log(parseFloat($('[name=vol]').val()));
      let hitung = parseFloat($('[name=ths]').inputmask('unmaskedvalue')) * parseFloat($('[name=vol]').val());
      console.log(hitung);
      $('[name=totals]').val(hitung);
    });

    $('#boq_f').on('submit', function(e) {
      e.preventDefault();
      $('#submit').addClass('disabled btn-progress');
      var form = $(this);
      if (form[0].checkValidity() === false) {
        event.stopPropagation();
        $('#submit').removeClass('disabled btn-progress');
      } else {
        $.ajax({
          method: "POST",
          // contentType: false,
          // processData: false,
          data: {
            nama: $('[name=nama]').val(),
            jp: $('[name=jp]').val(),
            jumlah: $('[name=jumlah]').val(),
            ppn: $('[name=ppn]').val(),
            ths: $('[name=ths]').val(),
            vol: $('[name=vol]').val(),
            sat: $('[name=sat]').val(),
            totals: $('[name=totals]').val(),
          },
          url: '<?= base_url() ?>Boq_c/update/' + dataedit.id_boq,
          success: function(respon) {
            notifsukses('Data BOQ', 'diubah');
            setTimeout(function() {
              window.location.href = "<?= base_url('Boq_c') ?>";
            }, 1000);
          }
        });
      }
      form.addClass('was-validated');
      return false;
    });
  });
</script>