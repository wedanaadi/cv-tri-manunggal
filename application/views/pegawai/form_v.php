<div class="row g-3">
  <div class="form-group col-xs-12 col-md-8 col-lg-8">
    <label>Nama Pegawai</label>
    <input type="text" name="nama" class="form-control" required="">
    <div class="invalid-feedback">
      Nama Pegawai ?
    </div>
    <div class="valid-feedback">
      Terisi!
    </div>
  </div>
  <div class="form-group col-xs-12 col-md-4 col-lg-4">
    <label>NPWP</label>
    <input type="text" name="npwp" class="form-control" required="">
    <div class="invalid-feedback">
      NPWP ?
    </div>
    <div class="valid-feedback">
      Terisi!
    </div>
  </div>
  <div class="form-group col-xs-12 col-md-4 col-lg-4">
    <label>NIK</label>
    <input type="number" name="nik" class="form-control" required="">
    <div class="invalid-feedback">
      NIK ?
    </div>
    <div class="valid-feedback">
      Terisi!
    </div>
  </div>
  <div class="form-group col-xs-12 col-md-3 col-lg-3">
    <label>Jenis Kelamin</label>
    <select name="jk" class="form-control">
      <option value="L">Laki - Laki</option>
      <option value="P">Perempuan</option>
    </select>
    <div class="invalid-feedback">
      Jenis Kelamin ?
    </div>
    <div class="valid-feedback">
      Terisi!
    </div>
  </div>
  <div class="form-group col-xs-12 col-md-5 col-lg-5">
    <label>Tempat Lahir</label>
    <input type="text" name="tl" class="form-control" required="">
    <div class="invalid-feedback">
      Tempat Lahir ?
    </div>
    <div class="valid-feedback">
      Terisi!
    </div>
  </div>
  <div class="form-group col-xs-12 col-md-4 col-lg-4">
    <label>Tanggal Lahir</label>
    <input type="text" name="tgl" class="form-control" id="waktu" value="" required="">
    <div class="invalid-feedback">
      Tanggal Lahir ?
    </div>
    <div class="valid-feedback">
      Terisi!
    </div>
  </div>
  <div class="form-group col-xs-12 col-md-4 col-lg-4">
    <label>Telepon</label>
    <input type="number" name="telp" class="form-control" required="">
    <div class="invalid-feedback">
      Telepon ?
    </div>
    <div class="valid-feedback">
      Terisi!
    </div>
  </div>
  <div class="form-group col-xs-12 col-md-4 col-lg-4">
    <label>Email</label>
    <input type="email" name="email" class="form-control" required="">
    <div class="invalid-feedback">
      Email ?
    </div>
    <div class="valid-feedback">
      Terisi!
    </div>
  </div>
  <div class="form-group col-xs-12 col-md-6 col-lg-6">
    <label>Jabatan</label>
    <input type="text" name="jabatan" class="form-control" required="">
    <!-- <select name="jabatan" class="form-control">
      <option value="1">Admin</option>
      <option value="2">Kepala Proyek</option>
      <option value="3">Direktur</option>
    </select> -->
    <div class="invalid-feedback">
      Jabatan ?
    </div>
    <div class="valid-feedback">
      Terisi!
    </div>
  </div>
  <div class="form-group col-6">
    <label>Alamat</label>
    <textarea name="alamat" cols="30" rows="10" class="form-control" required=""></textarea>
    <div class="invalid-feedback">
      Alamat ?
    </div>
    <div class="valid-feedback">
      Terisi!
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
</script>