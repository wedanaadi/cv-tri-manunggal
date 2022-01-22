<div class="table-responsive">
  <table class="table table-bordered" style="white-space: nowrap;">
    <thead>
      <tr>
        <th>#</th>
        <th>Nama Kegiatan</th>
        <th>Pegawai</th>
        <th>Durasi</th>
        <th>Tanggal Mulai</th>
        <th>Tanggal Selesai</th>
        <th>Unit</th>
        <th>Volume</th>
        <th>Harga Satuan</th>
        <th>Jumlah</th>
        <!-- <th>Aksi</th> -->
      </tr>
    </thead>
    <tbody>
      <?php $no = 1;
      foreach ($kegiatan as $k) : ?>
        <tr>
          <td><?= $no; ?></td>
          <td style="width:400px">
            <?= $k->kegiatan ?>
            <input type="hidden" name="kegiatanid[]" id="kegiatan" value="<?= $k->kegiatan_id ?>">
            <input type="hidden" name="jenisproyek[]" id="jenisproyek" value="<?= $k->jenis_proyek ?>">
          </td>
          <td><select style="width:200px" name="pegawai[]" class="form-control pegawai" id="pegawai"></select></td>
          <td>
            <span id="<?= 'durasi' . $k->jenis_proyek . $no ?>">1 Hari</span>
            <input type="hidden" name="durasi[]" value="1" id="<?= 'durasiForm' . $k->jenis_proyek . $no ?>">
          </td>
          <td><input style="width:110px" type="text" class="form-control waktu mulai" name="mulai_pegawai[]" id-no="<?= $k->jenis_proyek . $no ?>" id="<?= 'mulai' . $k->jenis_proyek . $no ?>"></td>
          <td><input style="width:110px" type="text" class="form-control waktu selesai" name="selesai_pegawai[]" id-no="<?= $k->jenis_proyek . $no ?>" id="<?= 'selesai' . $k->jenis_proyek . $no ?>"></td>
          <td><?= $k->unit ?><input type="hidden" name="unit[]" value="<?= $k->unit ?>"></td>
          <td><input style="width:80px" type="text" name="vol[]" class="form-control vol" value="<?= $k->volJadwal ?>" jum-attrs="<?= $k->harga ?>" id-no="<?= $no ?>" id="vol"></td>
          <td>
            <?= number_format($k->harga, 0, ",", ".") ?>
            <input type="hidden" name="harga[]" id="harga" value="<?= $k->harga ?>">
          </td>
          <td>
            <span id="<?= 'harga' . $no ?>"><?= number_format(floor($k->hargaJadwal), 0, ",", ".") ?></span>
            <input type="hidden" name="total[]" value="<?= floor($k->hargaJadwal) ?>" id="<?= 'totalForm' . $no ?>">
          </td>
        </tr>
      <?php $no++;
      endforeach; ?>
    </tbody>
  </table>
</div>

<script>
  $('.waktu').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    // drops: "up",
    locale: {
      format: 'YYYY-MM-DD'
    },
  });

  $(document).on('input', '.vol', function() {
    // console.log($(this).val());
    // console.log($(this).attr('jum-attrs'));
    $(this).val($(this).val().replace(/,/g, '.'));
    let hitung = Math.floor(parseFloat($(this).val().replace(/,/g, '.')) * parseFloat($(this).attr('jum-attrs')));
    $(`#harga${$(this).attr('id-no')}`).text(hitung.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
    $(`#totalForm${$(this).attr('id-no')}`).val(hitung);
  })

  $(document).on('change', '.mulai,.selesai', function() {
    let dateStart = new Date($(`#mulai${$(this).attr('id-no')}`).val());
    let dateEnd = new Date($(`#selesai${$(this).attr('id-no')}`).val());
    dateEnd.setDate(dateEnd.getDate() + 1);
    // One day in milliseconds
    const oneDay = 1000 * 60 * 60 * 24;

    // Calculating the time difference between two dates
    const diffInTime = dateEnd.getTime() - dateStart.getTime();

    // Calculating the no. of days between two dates
    const diffInDays = Math.round(diffInTime / oneDay);
    if (diffInDays <= 0) {
      let today = new Date();
      let date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
      alert('Tanggal Tidak Valid');
      $(`#mulai${$(this).attr('id-no')}`).data('daterangepicker').setDateRange(date, date);
    } else {
      $(`#durasi${$(this).attr('id-no')}`).text(`${diffInDays} Hari`);
      $(`#durasiForm${$(this).attr('id-no')}`).val(diffInDays);
    }
  })

  $('.pegawai').select2({
    placeholder: "Pilih Pegawai",
    ajax: {
      url: "<?= base_url(); ?>Pegawai_c/select2",
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