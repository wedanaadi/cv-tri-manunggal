<div class="form-group row" style="margin-bottom:0px">
  <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Tanggal</label>
  <div class="col-xs-12 col-md-10 col-lg-10">
    <div class="form-group col-12">
      <div class="input-group">
        <input type="text" name="tgl" class="form-control" id="waktu" required="">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fas fa-calendar"></i>
          </div>
        </div>
      </div>
      <div class="invalid-feedback">
        Tanggal ?
      </div>
      <div class="valid-feedback">
        Terisi!
      </div>
    </div>
  </div>
</div>
<div class="form-group row" style="margin-bottom:0px">
  <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Barang</label>
  <div class="col-xs-12 col-md-10 col-lg-10">
    <div class="form-group col-12">
      <select name="barang" class="form-control" required="" style="width: 100%;"></select>
      <div class="invalid-feedback">
        Barang ?
      </div>
      <div class="valid-feedback">
        Terisi!
      </div>
    </div>
  </div>
</div>
<div class="form-group row" style="margin-bottom:0px">
  <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Jumlah</label>
  <div class="col-xs-12 col-md-10 col-lg-10">
    <div class="form-group col-12">
      <input type="number" name="jumlah" class="form-control" required="">
      <div class="invalid-feedback">
        Jumlah ?
      </div>
      <div class="valid-feedback">
        Terisi!
      </div>
    </div>
  </div>
</div>
<div class="form-group row" style="margin-bottom:0px">
  <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Keterangan</label>
  <div class="col-xs-12 col-md-10 col-lg-10">
    <div class="form-group col-12">
      <input type="text" name="ket" class="form-control" required="">
      <div class="invalid-feedback">
        Keterangan ?
      </div>
      <div class="valid-feedback">
        Terisi!
      </div>
    </div>
  </div>
</div>
<div class="form-group row" style="margin-bottom:0px">
  <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Lokasi Barang</label>
  <div class="col-xs-12 col-md-10 col-lg-10">
    <div class="form-group col-12">
      <input type="text" name="lokasi" class="form-control" required="">
      <div class="invalid-feedback">
        Lokasi Barang ?
      </div>
      <div class="valid-feedback">
        Terisi!
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(function() {
    $('#waktu').daterangepicker({
      singleDatePicker: true,
      showDropdowns: true,
      locale: {
        format: 'YYYY-MM-DD'
      },
    });
  });

  $('select[name=barang]').select2({
    placeholder: "Pilih Barang",
    ajax: {
      url: "<?= base_url(); ?>BarangUpah_c/select2/",
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
</script>