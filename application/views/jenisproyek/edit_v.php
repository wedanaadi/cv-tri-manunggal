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
      <h1>Ubah Jenis Proyek</h1>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-body">
          <form id="detail_f" autocomplete="off" class="needs-validation" novalidate="">
            <div class="form-group row" style="margin-bottom:0px">
              <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Nama Jenis Proyek</label>
              <div class="col-xs-12 col-md-4 col-lg-4">
                <div class="form-group col-12">
                  <input type="text" name="jnp" class="form-control" id="waktu" required="">
                  <div class="invalid-feedback">
                    Nama Jenis Proyek ?
                  </div>
                  <div class="valid-feedback">
                    Terisi!
                  </div>
                </div>
              </div>
            </div>
          </form>
          <div class="form-group row" style="margin-bottom:0px">
            <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Nama Kegiatan</label>
            <div class="col-xs-12 col-md-4 col-lg-4">
              <div class="form-group col-12">
                <select name="kegiatan" class="form-control" style="width: 100%;" required=""></select>
                <div class="invalid-feedback">
                  Nama Kegiatan ?
                </div>
                <div class="valid-feedback">
                  Terisi!
                </div>
              </div>
            </div>
            <label class="col-xs-12 col-md-1 col-lg-1 col-form-label">Unit</label>
            <div class="col-xs-12 col-md-2 col-lg-2">
              <div class="form-group col-12">
                <input type="text" name="unit" class="form-control" required="">
                <div class="invalid-feedback">
                  Unit ?
                </div>
                <div class="valid-feedback">
                  Terisi!
                </div>
              </div>
            </div>
            <label class="col-xs-12 col-md-1 col-lg-1 col-form-label">Vol</label>
            <div class="col-xs-12 col-md-2 col-lg-2">
              <div class="form-group col-12">
                <input type="number" name="vol" class="form-control" required="">
                <div class="invalid-feedback">
                  Vol ?
                </div>
                <div class="valid-feedback">
                  Terisi!
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
          <hr>
          <div class="alert alert-danger dn" id="msg"></div>
          <div class="table-responsive">
            <table class="table table-striped nowrap" id="detail_tabel" style="width:100%">
              <thead>
                <tr>
                  <th class="dn">#</th>
                  <th>Nama Kegiatan</th>
                  <th>Unit</th>
                  <th>Vol</th>
                  <th>Harga Satuan</th>
                  <th>Jumlah</th>
                  <th>Action</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="col text-center">
              <a href="<?= base_url() ?>JenisProyek_c/index" type="button" id="cancel" class="btn btn-icon icon-left btn-light">
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
    var jumlahKegiatan = 0;
    var btn = '<button type="button" id="ubah" class="btn btn-icon btn-sm btn-warning"><i class="fas fa-pen"></i></button>' +
      ' <button type="button" id="hapus" class="btn btn-icon btn-sm btn-danger"><i class="fas fa-trash"></i></button>';

    var tabel = $('#detail_tabel').DataTable({
      searching: false,
      paging: false,
      rowCallback: function(row, data, iDisplayIndex) {
        // var info = this.fnPagingInfo();
        // var page = info.iPage;
        // var length = info.iLength;
        var numFormat = $.fn.dataTable.render.number('.', '.', 0, '').display;
        $('td:eq(4)', row).html(numFormat(data[4]));
        $('td:eq(5)', row).html(numFormat(data[5]));
        $('td:eq(0)', row).addClass('dn');
        // $('td:eq(9)', row).addClass('dn');
        $('td:eq(6)', row).addClass('text-center');
      }
    });

    $(document).on('click', '#hapus', function(e) {
      tr = $(this).closest('tr');
      tabel.row(tr).remove().draw();
    });

    $('select[name=kegiatan]').select2({
      placeholder: "Pilih Kegiatan",
      ajax: {
        url: "<?= base_url(); ?>Kegiatan_c/select2",
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

    // editload
    var dataedit = JSON.parse('<?= $edit; ?>');
    var editedit = JSON.parse('<?= $editdetil; ?>');

    $('[name=jnp]').val(dataedit.nama_jenis_proyek);

    $.each(editedit, function(k, v) {
      tabel.row.add([
        v.kegiatan_id,
        v.nama_kegiatan,
        v.unit,
        v.vol,
        v.harga,
        v.jumlah,
        btn
      ]).draw();
    });

    $('select[name=kegiatan]').on('select2:select', function(e) {
      $.ajax({
        method: "GET",
        dataType: "JSON",
        url: "<?= base_url() ?>Kegiatan_c/getKegiatanHarga",
        data: {
          id: $(this).val()
        },
        success: function(respon) {
          jumlahKegiatan = parseFloat(respon);
        }
      });
    });

    $(document).on('click', '#add', function(e) {
      e.preventDefault();
      var kegiatan = $('[name=kegiatan]').val();
      var unit = $('[name=unit]').val();
      var vol = $('[name=vol]').val();

      if (kegiatan === '' || unit === '' || vol === '') {
        notifgagal('Detail', "Data Belum Lengkap", 0);
      } else {
        tabel.row.add([
          $('[name=kegiatan]').val(),
          $('[name=kegiatan]').text(),
          $('[name=unit]').val(),
          $('[name=vol]').val(),
          jumlahKegiatan,
          jumlahKegiatan * $('[name=vol]').val(),
          btn
        ]).draw();
        $('[name=unit]').val('');
        $('[name=vol]').val('');
        $("[name=kegiatan]").val('').trigger('change');
      }
      return false;
    });

    $(document).on('click', '#ubah', function(e) {
      tb = tabel.row($(this).parents('tr')).data();
      $('[name=unit]').val(tb[2]);
      $('[name=vol]').val(tb[3]);
      // $('[name=kegiatan]').val(tb[0]).trigger('change');
      select2isi('[name=kegiatan]', tb[0], tb[1]);
      jumlahKegiatan = parseFloat(tb[4]);
      tr = $(this).closest('tr');
      tabel.row(tr).remove().draw();
    });

    $(document).on('click', '#submit', function(e) {
      e.preventDefault();
      var tb = tabel.rows().count();
      var detailtabel = tb === 0 ? null : tabel.rows().data().toArray();

      $('#msg').html("");

      var form = $('#detail_f');
      if (form[0].checkValidity() === false || detailtabel === null) {
        event.preventDefault();
        event.stopPropagation();
        if (detailtabel !== null) {
          $('#msg').hide();
        } else {
          $('#msg').show();
          $('#msg').html("<p>Tabel Kegiatan Kosong</p>")
        }
      } else {
        $('#msg').hide();
        console.log('save');
        $.ajax({
          method: "POST",
          dataType: "JSON",
          data: {
            jenis: $('[name=jnp]').val(),
            detail: detailtabel
          },
          url: "<?= base_url() ?>JenisProyek_c/update/" + dataedit.id_jenis_proyek,
          success: function(respon) {
            notifsukses('Data Jenis Proyek', 'diubah');
            setTimeout(function() {
              window.location.href = "<?= base_url('JenisProyek_c') ?>";
            }, 1000);
          }
        });
      }
      form.addClass('was-validated');
      return false;
    });
  });
</script>