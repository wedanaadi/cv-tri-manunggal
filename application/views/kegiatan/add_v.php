<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<style>
  .p-r-0 {
    padding-right: 0 !important;
  }

  .p-l-0 {
    padding-left: 0 !important;
  }

  .mr-sm-4 {
    margin-right: .4rem !important;
  }

  .mr-0 {
    margin-right: 0 !important;
  }

  .dn {
    display: none;
  }
</style>
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Tambah Data Kegiatan</h1>
    </div>

    <div class="section-body">
      <div class="card">
        <div class="card-body">
          <form id="detail_f" autocomplete="off" class="needs-validation" novalidate="">
            <div class="form-inline">
              <!-- <label class="mb-2 mr-sm-2">Nama Kegiatan</label> -->
              <div class="input-group mb-2 col-md-2 col-form-label">
                <label>Nama Kegiatan</label>
              </div>
              <div class="input-group mb-2 mr-sm-2 col-md-6">
                <input type="text" name="kegiatan" class="form-control" required=''>
                <div class="invalid-feedback">
                  Kegiatan ?
                </div>
                <div class="valid-feedback">
                  Terisi!
                </div>
              </div>
            </div>
          </form>
          <form id="detilinput_f" autocomplete="off">
            <div class="form-inline">
              <div class="input-group mb-2 col-md-2">
                <div class="form-check form-check-inline">
                  <input class="form-check-input check" type="checkbox" name="tipe" value="option1">
                  <label class="form-check-label">Bahan</label>
                </div>
              </div>
              <div class="input-group mb-2 mr-sm-2 col-md-3 mr-0">
                <select name="barang" class="form-control" required="" style="width: 100%;"></select>
              </div>
              <!-- <div id="bagian-barang"> -->
              <div id="lbs" class="input-group mr-sm-2 mb-2 dn">
                <label>Satuan</label>
              </div>
              <div id="fbs" class="input-group mb-2 col-md-1 dn p-l-0 p-r-0" style="margin-right: .8rem !important">
                <input type="text" name="satuanb" class="form-control">
              </div>
              <div id="lbk" class="input-group mb-2 mr-sm-2 dn">
                <label>Koefisien</label>
              </div>
              <div id="fbk" class="input-group dn mb-2 mr-sm-2 col-md-1 p-l-0 p-r-0" style="margin-right: .8rem !important">
                <input type="number" name="koeb" class="form-control">
              </div>
              <div id="lbhs" class="input-group dn mb-2 mr-sm-2">
                <label>Harga Satuan</label>
              </div>
              <div id="fbhs" class="input-group dn mb-2 mr-sm-2 col-md-2">
                <input type="text" name="hsb" class="form-control formuang">
              </div>
              <!-- </div> -->
            </div>
            <!-- bagian upah -->
            <div class="form-inline">
              <div class="input-group mb-2 col-md-2">
                <div class="form-check form-check-inline">
                  <input class="form-check-input check" type="checkbox" name="tipe" value="option2">
                  <label class="form-check-label">Upah</label>
                </div>
              </div>
              <div class="input-group mb-2 mr-sm-2 col-md-3 mr-0">
                <select name="upah" class="form-control" required="" style="width: 100%;"></select>
              </div>
              <!-- <div id="bagian-barang"> -->
              <div id="lus" class="input-group mr-sm-2 mb-2 dn">
                <label>Satuan</label>
              </div>
              <div id="fus" class="input-group mb-2 dn col-md-1 p-l-0 p-r-0" style="margin-right: .8rem !important">
                <input type="text" name="satuanu" class="form-control">
              </div>
              <div id="luk" class="input-group mb-2 dn mr-sm-2">
                <label>Koefisien</label>
              </div>
              <div id="fuk" class="input-group dn mb-2 mr-sm-2 col-md-1 p-l-0 p-r-0" style="margin-right: .8rem !important">
                <input type="text" name="koeu" class="form-control">
              </div>
              <div id="luhs" class="input-group dn mb-2 mr-sm-2">
                <label>Harga Satuan</label>
              </div>
              <div id="fuhs" class="input-group dn mb-2 mr-sm-2 col-md-2">
                <input type="text" name="hsu" class="form-control formuang">
              </div>
              <!-- </div> -->
            </div>
            <!-- alat -->
            <div class="form-inline">
              <div class="input-group mb-2 col-md-2">
                <div class="form-check form-check-inline">
                  <input class="form-check-input check" type="checkbox" name="tipe" value="option3">
                  <label class="form-check-label">Alat</label>
                </div>
              </div>
              <div class="input-group mb-2 mr-sm-2 col-md-3 mr-0">
                <input type="text" name="alat" class="form-control" required=''>
              </div>
              <!-- <div id="bagian-barang"> -->
              <div id="las" class="input-group dn mb-2 mr-sm-2">
                <label>Satuan</label>
              </div>
              <div id="fas" class="input-group dn mb-2 col-md-1 p-l-0 p-r-0" style="margin-right: .8rem !important">
                <input type="text" name="satuana" class="form-control">
              </div>
              <div id="lak" class="input-group dn mb-2 mr-sm-2">
                <label>Koefisien</label>
              </div>
              <div id="fak" class="input-group dn mb-2 mr-sm-2 col-md-1 p-l-0 p-r-0" style="margin-right: .8rem !important">
                <input type="text" name="koea" class="form-control">
              </div>
              <div id="lahs" class="input-group dn mb-2 mr-sm-2">
                <label>Harga Satuan</label>
              </div>
              <div id="fahs" class="input-group dn mb-2 mr-sm-2 col-md-2">
                <input type="text" name="hsa" class="form-control formuang">
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
          </form>
          <hr>
          <div class="alert alert-danger dn" id="msg"></div>
          <div class="table-responsive">
            <table class="table table-striped nowrap" id="detail_tabel" style="width:100%">
              <thead>
                <tr>
                  <th class="dn">#</th>
                  <th>Nama Bahan/Upah/Alat</th>
                  <th>Satuan</th>
                  <th>Koefisien</th>
                  <th>Harga Satuan</th>
                  <th>Upah</th>
                  <th>Bahan</th>
                  <th>Alat</th>
                  <th>Jumlah</th>
                  <th class="dn">id_upah_barang</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>Jumlah</th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="col text-center">
              <a href="<?= base_url() ?>Kegiatan_c/index" type="button" id="cancel" class="btn btn-icon icon-left btn-light">
                <i class="fas fa-ban"></i>
                Cancel
              </a>
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

    $('.formuang').inputmask("numeric", {
      groupSeparator: ".",
      digits: 0,
      autoGroup: true,
      rightAlign: false,
      removeMaskOnSubmit: true,
      allowMinus: false
    });

    var tabel = $('#detail_tabel').DataTable({
      searching: false,
      paging: false,
      rowCallback: function(row, data, iDisplayIndex) {
        // var info = this.fnPagingInfo();
        // var page = info.iPage;
        // var length = info.iLength;
        var numFormat = $.fn.dataTable.render.number('.', '.', 0, '').display;

        if (data[6] !== '-') {
          $('td:eq(6)', row).html(numFormat(data[6]));
        }
        if (data[5] !== '-') {
          $('td:eq(5)', row).html(numFormat(data[5]));
        }
        if (data[7] !== '-') {
          $('td:eq(7)', row).html(numFormat(data[7]));
        }
        $('td:eq(4)', row).html(numFormat(data[4]));
        $('td:eq(8)', row).html(numFormat(data[8]));
        $('td:eq(0)', row).addClass('dn');
        $('td:eq(9)', row).addClass('dn');
        $('td:eq(10)', row).addClass('text-center');
      },
      footerCallback: function(row, data, iDisplayIndex) {
        $('th', row).css('background-color', '#F5F5F5');
        var numFormat = $.fn.dataTable.render.number('.', '.', 0, '').display;
        var api = this.api(),
          data;

        // Remove the formatting to get integer data for summation
        var intVal = function(i) {
          return typeof i === 'string' ?
            i.replace(/[\$,]/g, '') * 1 :
            typeof i === 'number' ?
            i : 0;
        };

        hs = api
          .column(4, {
            page: 'current'
          })
          .data()
          .reduce(function(a, b) {
            return intVal(a) + intVal(b);
          }, 0);

        $(api.column(3).footer()).html(
          numFormat(hs)
        );

        upah = api
          .column(5, {
            page: 'current'
          })
          .data()
          .reduce(function(a, b) {
            if (b !== '-') {
              return intVal(a) + intVal(b);
            }
          }, 0);

        $(api.column(4).footer()).html(
          numFormat(upah)
        );

        barang = api
          .column(6, {
            page: 'current'
          })
          .data()
          .reduce(function(a, b) {
            if (b !== '-') {
              return intVal(a) + intVal(b);
            }
          }, 0);

        $(api.column(5).footer()).html(
          numFormat(barang)
        );

        total = api
          .column(8, {
            page: 'current'
          })
          .data()
          .reduce(function(a, b) {
            return intVal(a) + intVal(b);
          }, 0);

        $(api.column(7).footer()).html(
          numFormat(total)
        );
      }
    });

    $('[type=checkbox]').on('click', function() {
      $('[type=checkbox]').not(this).prop('checked', false);
      $('[name=upah],[name=alat],[name=barang]').prop('disabled', false);
      var aksi = $(this).val();
      if (aksi === 'option1') {
        $('#lbs').removeClass('dn');
        $('#fbs').removeClass('dn');
        $('#lbk').removeClass('dn');
        $('#fbk').removeClass('dn');
        $('#lbhs').removeClass('dn');
        $('#fbhs').removeClass('dn');
        $('#lus').addClass('dn');
        $('#fus').addClass('dn');
        $('#fus').addClass('dn');
        $('#luk').addClass('dn');
        $('#fuk').addClass('dn');
        $('#luhs').addClass('dn');
        $('#fuhs').addClass('dn');
        $('#las').addClass('dn');
        $('#fas').addClass('dn');
        $('#lak').addClass('dn');
        $('#fak').addClass('dn');
        $('#lahs').addClass('dn');
        $('#fahs').addClass('dn');
        $('[name=satuanb]').val('');
        $('[name=koeb]').val('');
        $('[name=hsb]').val('');
        $('[name=upah],[name=alat]').prop('disabled', true);
        $("[name=upah]").val('').trigger('change');
        $("[name=alat]").val('');
      } else if (aksi === 'option2') {
        $('#lus').removeClass('dn');
        $('#fus').removeClass('dn');
        $('#luk').removeClass('dn');
        $('#fuk').removeClass('dn');
        $('#luhs').removeClass('dn');
        $('#fuhs').removeClass('dn');
        $('#lbs').addClass('dn');
        $('#fbs').addClass('dn');
        $('#lbk').addClass('dn');
        $('#fbk').addClass('dn');
        $('#lbhs').addClass('dn');
        $('#fbhs').addClass('dn');
        $('#las').addClass('dn');
        $('#fas').addClass('dn');
        $('#lak').addClass('dn');
        $('#fak').addClass('dn');
        $('#lahs').addClass('dn');
        $('#fahs').addClass('dn');
        $('[name=satuanu]').val('');
        $('[name=koeu]').val('');
        $('[name=hsu]').val('');
        $('[name=barang],[name=alat]').prop('disabled', true);
        $("[name=barang]").val('').trigger('change');
        $("[name=alat]").val('');
      } else {
        $('#las').removeClass('dn');
        $('#fas').removeClass('dn');
        $('#lak').removeClass('dn');
        $('#fak').removeClass('dn');
        $('#lahs').removeClass('dn');
        $('#fahs').removeClass('dn');
        $('#lus').addClass('dn');
        $('#fus').addClass('dn');
        $('#luk').addClass('dn');
        $('#fuk').addClass('dn');
        $('#luhs').addClass('dn');
        $('#fuhs').addClass('dn');
        $('#lbs').addClass('dn');
        $('#fbs').addClass('dn');
        $('#lbk').addClass('dn');
        $('#fbk').addClass('dn');
        $('#lbhs').addClass('dn');
        $('#fbhs').addClass('dn');
        $('[name=satuana]').val('');
        $('[name=koea]').val('');
        $('[name=hsa]').val('');
        $('[name=upah],[name=barang]').prop('disabled', true);
        $("[name=barang]").val('').trigger('change');
        $("[name=upah]").val('').trigger('change');
      }
    });

    $(document).on('click', '#hapus', function(e) {
      tr = $(this).closest('tr');
      tabel.row(tr).remove().draw();
    });

    $(document).on('click', '#ubah', function(e) {
      tb = tabel.row($(this).parents('tr')).data();
      $('[name=tipe]').prop('checked', false);
      $('[name=upah],[name=alat],[name=barang]').prop('disabled', false);
      if (tb[0] === 'option1') {
        $('[value=option1]').prop('checked', true);
        $('#lbs').removeClass('dn');
        $('#fbs').removeClass('dn');
        $('#lbk').removeClass('dn');
        $('#fbk').removeClass('dn');
        $('#lbhs').removeClass('dn');
        $('#fbhs').removeClass('dn');
        $('#lus').addClass('dn');
        $('#fus').addClass('dn');
        $('#fus').addClass('dn');
        $('#luk').addClass('dn');
        $('#fuk').addClass('dn');
        $('#luhs').addClass('dn');
        $('#fuhs').addClass('dn');
        $('#las').addClass('dn');
        $('#fas').addClass('dn');
        $('#lak').addClass('dn');
        $('#fak').addClass('dn');
        $('#lahs').addClass('dn');
        $('#fahs').addClass('dn');

        $('[name=satuanb]').val(tb[2]);
        $('[name=koeb]').val(tb[3]);
        $('[name=hsb]').val(tb[4]);
        $("[name=barang]").val(tb[9]).trigger('change');
        $("[name=upah]").val('').trigger('change');
        $("[name=alat]").val('');
        $('[name=upah],[name=alat]').prop('disabled', true);
      } else if (tb[0] === 'option2') {
        $('[value=option2]').prop('checked', true);
        $('#lus').removeClass('dn');
        $('#fus').removeClass('dn');
        $('#luk').removeClass('dn');
        $('#fuk').removeClass('dn');
        $('#luhs').removeClass('dn');
        $('#fuhs').removeClass('dn');
        $('#lbs').addClass('dn');
        $('#fbs').addClass('dn');
        $('#lbk').addClass('dn');
        $('#fbk').addClass('dn');
        $('#lbhs').addClass('dn');
        $('#fbhs').addClass('dn');
        $('#las').addClass('dn');
        $('#fas').addClass('dn');
        $('#lak').addClass('dn');
        $('#fak').addClass('dn');
        $('#lahs').addClass('dn');
        $('#fahs').addClass('dn');

        $('[name=satuanu]').val(tb[2]);
        $('[name=koeu]').val(tb[3]);
        $('[name=hsu]').val(tb[4]);
        $("[name=upah]").val(tb[9]).trigger('change');
        $("[name=barang]").val('').trigger('change');
        $("[name=alat]").val('');
        $('[name=barang],[name=alat]').prop('disabled', true);
      } else {
        $('[value=option3]').prop('checked', true);
        $('#las').removeClass('dn');
        $('#fas').removeClass('dn');
        $('#lak').removeClass('dn');
        $('#fak').removeClass('dn');
        $('#lahs').removeClass('dn');
        $('#fahs').removeClass('dn');
        $('#lus').addClass('dn');
        $('#fus').addClass('dn');
        $('#luk').addClass('dn');
        $('#fuk').addClass('dn');
        $('#luhs').addClass('dn');
        $('#fuhs').addClass('dn');
        $('#lbs').addClass('dn');
        $('#fbs').addClass('dn');
        $('#lbk').addClass('dn');
        $('#fbk').addClass('dn');
        $('#lbhs').addClass('dn');
        $('#fbhs').addClass('dn');

        $('[name=satuana]').val(tb[2]);
        $('[name=koea]').val(tb[3]);
        $('[name=hsa]').val(tb[4]);
        $("[name=alat]").val(tb[9]);
        $("[name=upah]").val('').trigger('change');
        $("[name=barang]").val('');
        $('[name=barang],[name=upah]').prop('disabled', true);
      }
      tr = $(this).closest('tr');
      tabel.row(tr).remove().draw();
    });

    $(document).on('click', '#add', function(e) {
      var aksi = $('[name=tipe]:checked').val();
      var btn = '<button type="button" id="ubah" class="btn btn-icon btn-sm btn-warning"><i class="fas fa-pen"></i></button>' +
        ' <button type="button" id="hapus" class="btn btn-icon btn-sm btn-danger"><i class="fas fa-trash"></i></button>';
      if (aksi === 'option1') {
        var barang = $('[name=barang]').val();
        var satuan = $('[name=satuanb]').val();
        var koe = parseFloat($('[name=koeb]').val());
        var hs = parseInt($('[name=hsb]').inputmask('unmaskedvalue'));
        if (barang === '' || satuan === '' || koe === '' || hs === '') {
          notifgagal('Detail', "Data Belum Lengkap", 0);
        } else {
          tabel.row.add([
            aksi,
            $('[name=barang]').text(),
            satuan,
            koe,
            hs,
            '-',
            parseInt(koe * hs),
            '-',
            parseInt(koe * hs),
            barang,
            btn
          ]).draw();
          $('[name=satuanb]').val('');
          $('[name=koeb]').val('');
          $('[name=hsb]').val('');
          $("[name=barang]").val('').trigger('change');
          $('#lbs').addClass('dn');
          $('#fbs').addClass('dn');
          $('#lbk').addClass('dn');
          $('#fbk').addClass('dn');
          $('#lbhs').addClass('dn');
          $('#fbhs').addClass('dn');
        }
      } else if (aksi === 'option2') {
        var barang = $('[name=upah]').val();
        var satuan = $('[name=satuanu]').val();
        var koe = parseFloat($('[name=koeu]').val());
        var hs = parseInt($('[name=hsu]').inputmask('unmaskedvalue'));
        if (barang === '' || satuan === '' || koe === '' || hs === '') {
          notifgagal('Detail', "Data Belum Lengkap", 0);
        } else {
          tabel.row.add([
            aksi,
            $('[name=upah]').text(),
            satuan,
            koe,
            hs,
            parseInt(koe * hs),
            '-',
            '-',
            parseInt(koe * hs),
            barang,
            btn
          ]).draw();
          $('[name=satuanu]').val('');
          $('[name=koeu]').val('');
          $('[name=hsu]').val('');
          $("[name=upah]").val('').trigger('change');
          $('#lus').addClass('dn');
          $('#fus').addClass('dn');
          $('#luk').addClass('dn');
          $('#fuk').addClass('dn');
          $('#luhs').addClass('dn');
          $('#fuhs').addClass('dn');
        }
      } else if (aksi === 'option3') {
        var barang = $('[name=alat]').val();
        var satuan = $('[name=satuana]').val();
        var koe = parseFloat($('[name=koea]').val());
        var hs = parseInt($('[name=hsa]').inputmask('unmaskedvalue'));
        if (barang === '' || satuan === '' || koe === '' || hs === '') {
          notifgagal('Detail', "Data Belum Lengkap", 0);
        } else {
          tabel.row.add([
            aksi,
            $('[name=alat]').val(),
            satuan,
            koe,
            hs,
            '-',
            '-',
            parseInt(koe * hs),
            parseInt(koe * hs),
            barang,
            btn
          ]).draw();
          $('[name=alat]').val('');
          $('[name=satuana]').val('');
          $('[name=koea]').val('');
          $('[name=hsa]').val('');
          $('#las').addClass('dn');
          $('#fas').addClass('dn');
          $('#lak').addClass('dn');
          $('#fak').addClass('dn');
          $('#lahs').addClass('dn');
          $('#fahs').addClass('dn');
        }
      } else {
        notifgagal('Detail', "Pilih Bahan/Upah/Alat", 0);
      }
      $('[name=tipe]:checked').prop('checked', false);
      $('[name=upah],[name=alat],[name=barang]').prop('disabled', false);
    });

    $('select[name=barang]').select2({
      placeholder: "Pilih Barang",
      ajax: {
        url: "<?= base_url(); ?>BarangUpah_c/select2/" + 1,
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

    $('select[name=upah]').select2({
      placeholder: "Pilih Upah",
      ajax: {
        url: "<?= base_url(); ?>BarangUpah_c/select2/" + 2,
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
          $('#msg').html("<p>Tabel Bahan, Upah, Alat Kosong</p>")
        }
      } else {
        $('#msg').hide();
        console.log('save');
        $.ajax({
          method: "POST",
          dataType: "JSON",
          data: {
            kegiatan: $('[name=kegiatan]').val(),
            detail: detailtabel
          },
          url: "<?= base_url() ?>Kegiatan_c/create",
          success: function(respon) {
            notifsukses('Data Kegiatan', 'ditambah');
            setTimeout(function() {
              window.location.href = "<?= base_url('Kegiatan_c') ?>";
            }, 1000);
          }
        });
      }
      form.addClass('was-validated');
      return false;
    });
  });
</script>