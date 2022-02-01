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
    border-left: 1 px solid;
    border-right: 1 px solid;
  }

  tr.noBorder td {
    border: 0;
  }
</style>

<h4 style="text-align:center">Bill Of Quntity (BOQ)</h4>
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
    <td>Jenis Proyek</td>
    <td>: <?= $lap['atas']->nama_jenis_proyek ?></td>
  </tr>
  <tr>
    <td>Tahun Anggaran</td>
    <td>: <?= $lap['atas']->tahun_anggaran ?></td>
  </tr>
</table>

<table id="TabelKonten" width="100%" cellspacing="0" style="border-collapse: collapse; border:none">
  <thead>
    <tr>
      <th style="border: 1px solid">Nama Kegiatan</th>
      <th style="border: 1px solid">Unit</th>
      <th style="border: 1px solid">Vol</th>
      <th style="border: 1px solid">Harga Satuan</th>
      <th style="width: 150px; border: 1px solid">Jumlah</th>
      <th style="width: 150px; border: 1px solid">Realisasi</th>
    </tr>
  </thead>
  <tbody>
    <?php $realisasi = 0;
    foreach ($lap['jn'] as $v) : ?>
      <tr>
        <td style="border-left: 1px solid; border-right: 1px solid;"><?= $v->nama_kegiatan ?></td>
        <td style="border-left: 1px solid; border-right: 1px solid;"><?= $v->unit ?></td>
        <td style="border-left: 1px solid; border-right: 1px solid;"><?= $v->vol ?></td>
        <td style="border-left: 1px solid; border-right: 1px solid; text-align:right;"><?= number_format($v->harga, 0, ',', '.') ?></td>
        <td style="border-left: 1px solid; border-right: 1px solid; text-align:right;"><?= number_format($v->jumlah, 0, ',', '.') ?></td>
        <td style="border-left: 1px solid; border-right: 1px solid; text-align:right;"><?= number_format($v->realisasi / $lap['atas']->volume, 0, ',', '.') ?></td>
      </tr>
    <?php $realisasi += $v->realisasi / $lap['atas']->volume;
    endforeach; ?>
    <tr>
      <td colspan="4" style="border: 1px solid"><strong>JUMLAH</strong></td>
      <td style="width: 140px; border: 1px solid; text-align:right;"><?= number_format($lap['atas']->jumlah_harga, 2, ',', '.') ?></td>
      <td style="width: 140px; border: 1px solid; text-align:right;"><?= number_format($realisasi, 2, ',', '.') ?></td>
    </tr>
    <tr>
      <td style="border: 1px solid" colspan=" 4"><strong>PPN 10%</strong></td>
      <td style="border: 1px solid; text-align:right"><?= number_format($lap['atas']->ppn, 2, ',', '.') ?></td>
      <td style="border: 1px solid; text-align:right"><?= number_format(($realisasi * 0.1), 2, ',', '.') ?></td>
    </tr>
    <tr>
      <td style="border: 1px solid" colspan=" 4"><strong>Jumlah Total</strong></td>
      <td style="border: 1px solid; text-align:right"><?= number_format($lap['atas']->total_harga_satuan, 2, ',', '.') ?></td>
      <td style="border: 1px solid; text-align:right"><?= number_format(($realisasi + ($realisasi * 0.1)), 2, ',', '.') ?></td>
    </tr>
    <tr>
      <td style="border: 1px solid" colspan=" 4"><strong>Harga Per- Bulah (PEMBULATAN)</strong></td>
      <td style="border: 1px solid; text-align:right"><?= number_format(bulatkan($lap['atas']->total_harga_satuan), 0, ',', '.') ?></td>
      <td style="border: 1px solid; text-align:right"><?= number_format(bulatkan(($realisasi + ($realisasi * 0.1))), 0, ',', '.') ?></td>
    </tr>
    <tr>
      <td style="border: 1px solid" colspan=" 4"><strong>Volume ( Unit )</strong></td>
      <td style="border: 1px solid; text-align:right"><?= $lap['atas']->volume . " (" . $lap['atas']->satuan . ")" ?></td>
      <td style="border: 1px solid; text-align:right"><?= $lap['atas']->volume . " (" . $lap['atas']->satuan . ")" ?></td>
    </tr>
    <tr>
      <td style="border: 1px solid" colspan=" 4"><strong>Total Harga</strong></td>
      <td style="border: 1px solid; text-align:right"><?= number_format($lap['atas']->total, 2, ',', '.') ?></td>
      <td style="border: 1px solid; text-align:right"><?= number_format(($lap['atas']->volume * ($realisasi + ($realisasi * 0.1))), 2, ',', '.') ?></td>
    </tr>
    <tr>
      <td style="border: 1px solid" colspan=" 4"><strong>Total Harga (PEMBULATAN)</strong></td>
      <td style="border: 1px solid; text-align:right"><?= number_format(bulatkan($lap['atas']->total), 0, ',', '.') ?></td>
      <td style="border: 1px solid; text-align:right"><?= number_format(bulatkan(($lap['atas']->volume * ($realisasi + ($realisasi * 0.1)))), 0, ',', '.') ?></td>
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
    <td align="center" style="width: 30%"><strong><?= $lap['direktur'] ?></strong></td>
  </tr>
  <tr>
    <td align="center" style="width: 35%"> &nbsp; </td>
    <td align="center" style="width: 35%"> &nbsp;</td>
    <td align="center" style="width: 30%; font-size: 9px">Direktur</td>
  </tr>
</table>