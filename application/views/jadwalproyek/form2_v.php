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
      foreach ($kegiatan as $k) : ?>
        <tr>
          <td><?= $no; ?></td>
          <td style="width:400px">
            <?= $k->nama_kegiatan ?>
          </td>
          <td><?= $k->nama_pegawai ?></td>
          <td>
            <?= $k->durasi . " Hari" ?>
          </td>
          <td>
            <?= $k->startDate ?>
          </td>
          <td>
            <?= $k->endDate ?>
          </td>
          <td><?= $k->unit ?></td>
          <td><?= $k->vol ?></td>
          <td>
            <?= number_format($k->harga, 0, ",", ".") ?>
          </td>
          <td>
            <?= number_format($k->total, 0, ",", ".") ?>
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