<div class="table-responsive">
  <table class="table nowrap table-bordered" style="width:100%" id="detil">
    <thead>
      <tr>
        <th>Nama Bahan/Alat/Upah</th>
        <th>Sat</th>
        <th>Koef</th>
        <th>Harga Satuan</th>
        <th>Upah</th>
        <th>Bahan</th>
        <th>Alat</th>
        <th>Jumlah</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sumUpah = 0;
      $sumBahan = 0;
      $sumTotal = 0;
      foreach ($detil as $v) :
        $bahan = '-';
        $upah = '-';
        $alat = '-';
        $total = 0;

        if ($v->tipe === 'option1') {
          $bahan = $v->harga_upah_bahan_alat;
          $total = $v->harga_upah_bahan_alat;
        } else if ($v->tipe === 'option2') {
          $upah = $v->harga_upah_bahan_alat;
          $total = $v->harga_upah_bahan_alat;
        } else {
          $alat = $v->harga_upah_bahan_alat;
          $total = $v->harga_upah_bahan_alat;
        }

      ?>
        <tr>
          <td><?= $v->tipe !== 'option3' ? $v->nama_barang_upah : $v->upah_bahan_alat; ?></td>
          <td><?= $v->satuan; ?></td>
          <td><?= $v->koef; ?></td>
          <td><?= number_format($v->harga, 0, ',', '.'); ?></td>
          <td><?= $upah === '-' ? $upah : number_format((int)$upah, 0, ',', '.'); ?></td>
          <td><?= $bahan === '-' ? $bahan : number_format((int)$bahan, 0, ',', '.'); ?></td>
          <td><?= $alat === '-' ? $alat : number_format((int)$alat, 0, ',', '.'); ?></td>
          <td><?= number_format((int)$total, 0, ',', '.'); ?></td>
        </tr>
      <?php
        $sumUpah += (int)$upah;
        $sumBahan += (int)$bahan;
        $sumTotal += (int)$total;
      endforeach;
      ?>
    </tbody>
    <tfoot>
      <tr>
        <th>Jumlah</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th><?= number_format((int)$sumUpah, 0, ',', '.'); ?></th>
        <th><?= number_format((int)$sumBahan, 0, ',', '.'); ?></th>
        <th>&nbsp;</th>
        <th><?= number_format((int)$sumTotal, 0, ',', '.'); ?></th>
      </tr>
    </tfoot>
  </table>
</div>

<script>
  $(function() {
    $('#detil').DataTable({
      searching: false,
      paging: false,
      bInfo: false,
      ordering: false,
    });
  });
</script>