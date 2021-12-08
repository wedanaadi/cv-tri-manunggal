<style>
  .hide {
    display: none;
  }
</style>
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
    <div id="jenis">
    </div>
  </div>
</div>

<script>
  $(function() {
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
          if (respon.count === 1) {
            select2isi('[name=spmk]', respon.order.id_proyek, respon.order.nospmk);
            $('#jenis').html("");
            $('#jenis').append("<ul>");
            if (respon.checkJadwal > 0) {
              $('#jenis').append("<span style='color:#dc3545'>Order sudah mempunyai jadwal</span>")
              $('#submit').addClass('disabled btn-progress');
            } else {
              $('#submit').removeClass('disabled btn-progress');
            }
            $.each(respon.detail, function(k, v) {
              $('#jenis').append("<li>" + v.nama_jenis_proyek + "</li>");
            });
            $('#jenis').append("</ul>");
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
          $('#jenis').append("<ul>");
          if (respon.count > 0) {
            $('#jenis').append("<span style='color:#dc3545'>Order sudah mempunyai jadwal</span>")
            $('#submit').addClass('disabled btn-progress');
          } else {
            $('#submit').removeClass('disabled btn-progress');
          }
          $.each(respon.detail, function(k, v) {
            $('#jenis').append("<li>" + v.nama_jenis_proyek + "</li>");
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
  });
</script>