<style>
  table thead th {
    text-align: center;
  }
</style>
<div class="table-responsive">
  <table class="table table-bordered" style="white-space: nowrap;">
    <thead>
      <tr>
        <th>#</th>
        <th>Jenis Proyek</th>
        <th>Kegiatan</th>
        <th>Pegawai</th>
        <th>Tanggal Mulai</th>
        <th>Tanggal Selesai</th>
        <th>Durasi</th>
        <th>Unit</th>
        <th>Volume</th>
        <th>Harga</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1;
      $jum = 1;
      $total = 0;
      foreach ($detail as $d) : ?>
        <tr>
          <?php if ($jum <= 1) : ?>
            <td rowspan="<?= $d->rowspan ?>"><?= $no; ?></td>
            <td rowspan="<?= $d->rowspan ?>"><?= $d->nama_jenis_proyek ?></td>
            <?php
            $jum = $d->rowspan;
            $no++;
            ?>
          <?php else : ?>
            <?php
            $jum = $jum - 1;
            ?>
          <?php endif; ?>
          <td><?= $d->nama_kegiatan ?></td>
          <td><?= $d->nama_pegawai ?></td>
          <td><?= $d->startDate ?></td>
          <td><?= $d->endDate ?></td>
          <td><?= $d->durasi ?> Hari</td>
          <td><?= $d->unit ?></td>
          <td><?= $d->vol ?></td>
          <td class="text-right"><?= number_format($d->harga, 0, ',', '.') ?></td>
          <td class="text-right"><?= number_format($d->total, 0, ',', '.') ?></td>
        </tr>
      <?php
        $total += $d->total;
      endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="10" class="text-center font-weight-bold">TOTAL</td>
        <td class="text-right font-weight-bold"><?= number_format($total, 0, ',', '.') ?></td>
      </tr>
    </tfoot>
  </table>
</div>