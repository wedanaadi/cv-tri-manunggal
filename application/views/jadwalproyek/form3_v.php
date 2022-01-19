<div class="table-responsive">
  <table class="table table-bordered" id="tabel" style="white-space: nowrap;">
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
      foreach ($edit as $k) : ?>
        <tr>
          <td><?= $no; ?></td>
          <td style="width:400px">
            <?= $k->nama_kegiatan ?>
            <input type="hidden" name="kegiatanid[]" id="kegiatan" value="<?= $k->kegiatan_id ?>">
            <input type="hidden" name="jenisproyek[]" id="jenisproyek" value="<?= $k->jenis_proyek_id ?>">
          </td>
          <td>
            <select style="width:200px" name="pegawai[]" class="form-control pegawai" id="pegawai">
              <?php
              foreach ($pegawai as $p) :
                if ($k->pegawai_id === $p->id_pegawai) :
              ?>
                  <option value="<?= $p->id_pegawai ?>" selected><?= $p->nama_pegawai ?></option>
                <?php else : ?>
                  <option value="<?= $p->id_pegawai ?>"><?= $p->nama_pegawai ?></option>
              <?php
                endif;
              endforeach;
              ?>
            </select>
          </td>
          <td>
            <span id="<?= 'durasi' . $k->jenis_proyek_id . $no ?>"><?= $k->durasi ?> Hari</span>
            <input type="hidden" name="durasi[]" value="<?= $k->durasi ?>" id="<?= 'durasiForm' . $k->jenis_proyek_id . $no ?>">
          </td>
          <td><input style="width:110px" type="text" class="form-control waktu mulai" name="mulai_pegawai[]" id-no="<?= $k->jenis_proyek_id . $no ?>" id="<?= 'mulai' . $k->jenis_proyek_id . $no ?>" value="<?= $k->startDate ?>"></td>
          <td><input style="width:110px" type="text" class="form-control waktu selesai" name="selesai_pegawai[]" id-no="<?= $k->jenis_proyek_id . $no ?>" id="<?= 'selesai' . $k->jenis_proyek_id . $no ?>" value="<?= $k->endDate ?>"></td>
          <td><?= $k->unit ?><input type="hidden" name="unit[]" value="<?= $k->unit ?>"></td>
          <td><input style="width:80px" type="text" name="vol[]" class="form-control vol" value="<?= $k->vol ?>" jum-attrs="<?= $k->harga ?>" id-no="<?= $no ?>" id="vol"></td>
          <td>
            <?= number_format($k->harga, 0, ",", ".") ?>
            <input type="hidden" name="harga[]" id="harga" value="<?= $k->harga ?>">
          </td>
          <td>
            <span id="<?= 'harga' . $no ?>"><?= number_format(floor($k->total), 0, ",", ".") ?></span>
            <input type="hidden" name="total[]" value="<?= floor($k->total) ?>" id="<?= 'totalForm' . $no ?>">
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
    let diffDays = dateEnd.getDate() - dateStart.getDate();
    if (diffDays < 0) {
      alert('Tanggal Tidak Valid');
    } else {
      $(`#durasi${$(this).attr('id-no')}`).text(`${diffDays} Hari`);
      $(`#durasiForm${$(this).attr('id-no')}`).val(diffDays);
    }
  })

  $('.pegawai').select2();
</script>