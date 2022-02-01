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
<h4 style="text-align:center">REKAPTULASI<br>DAFTAR KUANTITAS DAN HARGA</h4>
<table style="width:100%">
  <tr>
    <td>Kegiatan</td>
    <td>: <?= $lap['atas']->kegiatan ?></td>
  </tr>
  <tr>
    <td>Pekerjaan</td>
    <td>: <?= $lap['atas']->pekerjaan ?></td>
  </tr>
  <tr>
    <td>Lokasi</td>
    <td>: <?= $lap['atas']->lokasi ?></td>
  </tr>
  <tr>
    <td>Tahun Anggaran</td>
    <td>: <?= $lap['atas']->tahun_anggaran ?></td>
  </tr>
</table>

<table id="TabelKonten" width="100%" cellspacing="0" style="border-collapse: collapse; border:none">
  <thead>
    <tr>
      <th style="border: 1px solid; width:5%">No</th>
      <th style="border: 1px solid">Nama Kegiatan</th>
      <th style="border: 1px solid">Satuan</th>
      <th style="border: 1px solid">Volume</th>
      <th style="border: 1px solid">Harga Satuan (Rp)</th>
      <th style="border: 1px solid">Jumlah (Rp)</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1;
    $total = 0;
    foreach ($lap['detil'] as $v) : ?>
      <tr>
        <td style="border: 1px solid"><?= $no ?></td>
        <td style="border: 1px solid"><?= $v->nama_jenis_proyek ?></td>
        <td style="border: 1px solid; text-align:center;"><?= $v->satuan ?></td>
        <td style="border: 1px solid; text-align:center;"><?= $v->volume ?></td>
        <td style="border: 1px solid; text-align:right;"><?= number_format(bulatkan($v->total_harga_satuan), 0, ',', '.') ?></td>
        <td style="border: 1px solid; text-align:right;"><?= number_format($v->volume * bulatkan($v->total_harga_satuan), 0, ',', '.') ?></td>
      </tr>
    <?php $no++;
      $total += (float)($v->volume * bulatkan($v->total_harga_satuan));
    endforeach; ?>
    <tr>
      <td colspan="5" style="text-align: center; border: 1px solid;"><strong>TOTAL</strong></td>
      <td style="text-align: right; border: 1px solid"><strong><?= number_format(bulatkan($total), 0, ',', '.') ?></strong></td>
    </tr>
    <!-- <tr>
      <td colspan="5" style="text-align: center; border: 1px solid;"><strong>TOTAL SESUDAH PEMBULATAN</strong></td>
      <td style="text-align: right; border: 1px solid"><strong><?= number_format(bulatkan($total), 0, ',', '.') ?></strong></td>
    </tr> -->
    <tr>
      <td style="border-left: 1px solid; border-bottom: 1px solid;" colspan="1"></td>
      <!-- <td style="border-left: 1px solid; border-bottom: 1px solid;" colspan="1"><strong>Terbilang : </strong></td> -->
      <td style="border-right: 1px solid; border-bottom: 1px solid;" colspan="5"><strong>Terbilang : # <?= terbilang(bulatkan($total)) ?> Rupiah #</strong></td>
    </tr>
  </tbody>
</table>
<table width="100%" style="font-size:11px; page-break-inside:avoid">
  <tr>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
  </tr>
  <tr>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
    <td align="center"> Denpasar, <?= tgl_only(date('Y-m-d')) ?> </td>
  </tr>

  <tr>
    <td align="center"><strong>&nbsp;</strong> </td>
    <td align="center"><strong>&nbsp;</strong></td>
    <td align="center"><strong>CV. Tri Manunggal</strong></td>
  </tr>
  <tr>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
  </tr>
  <tr>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
  </tr>
  <tr>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
  </tr>
  <tr>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
  </tr>
  <tr>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
  </tr>
  <tr>
    <td align="center" style="width: 35%"> &nbsp; </td>
    <td align="center" style="width: 35%"> &nbsp;</td>
    <td align="center" style="width: 30%"><strong><u><?= $lap['direktur'] ?></u></strong></td>
  </tr>
  <tr>
    <td align="center" style="width: 35%"> &nbsp; </td>
    <td align="center" style="width: 35%"> &nbsp;</td>
    <td align="center" style="width: 30%; font-size: 9px">Direktur</td>
  </tr>
</table>