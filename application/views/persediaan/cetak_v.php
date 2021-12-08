<?= $header ?>
<style>
  .text-center {
    text-align: center;
  }

  .text-right {
    text-align: right;
  }

  #TabelKonten tr td {
    padding-right: 7px;
    padding-left: 7px;
    font-size: 15px;
  }

  tr.noBorder td {
    border: 0;
  }
</style>

<h4 style="text-align:center">Persediaan Barang</h4>
<table id="TabelKonten" border="1" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
  <thead>
    <tr>
      <th>Nama Barang</th>
      <th>Jenis</th>
      <th>Merk</th>
      <th>Ukuran</th>
      <th>Satuan</th>
      <th>Stok</th>
      <th>Lokasi</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($lap as $v) : ?>
      <tr>
        <td><?= $v->nama_brg ?></td>
        <td><?= $v->jenis_brg ?></td>
        <td><?= $v->merk_brg ?></td>
        <td><?= $v->ukuran_brg ?></td>
        <td><?= $v->satuan_brg ?></td>
        <td><?= $v->stok ?></td>
        <td><?= $v->lokasi_brg ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
  </thead>
</table>