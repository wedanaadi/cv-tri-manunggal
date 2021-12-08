<div class="form-group row" style="margin-bottom:0px">
  <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Kegiatan</label>
  <div class="col-xs-12 col-md-10 col-lg-10 col-form-label">
    <?= $atas->kegiatan ?>
  </div>
</div>
<div class="form-group row" style="margin-bottom:0px">
  <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Pekerjaan</label>
  <div class="col-xs-12 col-md-10 col-lg-10 col-form-label">
    <?= $atas->pekerjaan ?>
  </div>
</div>
<div class="form-group row" style="margin-bottom:0px">
  <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Jenis Proyek</label>
  <div class="col-xs-12 col-md-10 col-lg-10 col-form-label">
    <?= $atas->nama_jenis_proyek ?>
  </div>
</div>
<div class="form-group row" style="margin-bottom:0px">
  <label class="col-xs-12 col-md-2 col-lg-2 col-form-label">Tahun Anggaran</label>
  <div class="col-xs-12 col-md-10 col-lg-10 col-form-label">
    <?= $atas->tahun_anggaran ?>
  </div>
</div>

<table class="table table-bordered">
  <thead>
    <tr>
      <th>Nama Kegiatan</th>
      <th>Unit</th>
      <th>Vol</th>
      <th>Harga Satuan</th>
      <th style="width: 150px">Jumlah</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($jn as $v) : ?>
      <tr>
        <td><?= $v->nama_kegiatan ?></td>
        <td><?= $v->unit ?></td>
        <td><?= $v->vol ?></td>
        <td><?= number_format($v->harga, 0, ',', '.') ?></td>
        <td><?= number_format($v->jumlah, 0, ',', '.') ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
  </thead>
</table>

<table class="table table-bordered" border="1">
  <tbody>
    <tr>
      <td><strong>JUMLAH</strong></td>
      <td style="width: 150px"><?= number_format($atas->jumlah_harga, 2, ',', '.') ?></td>
    </tr>
    <tr>
      <td><strong>PPN 10%</strong></td>
      <td><?= number_format($atas->ppn, 2, ',', '.') ?></td>
    </tr>
    <tr>
      <td><strong>Jumlah Total</strong></td>
      <td><?= number_format($atas->total_harga_satuan, 2, ',', '.') ?></td>
    </tr>
    <tr>
      <td><strong>Harga Per- Buah (PEMBULATAN)</strong></td>
      <td><?= number_format(bulatkan($atas->total_harga_satuan), 0, ',', '.') ?></td>
    </tr>
    <tr>
      <td><strong>Volume ( Unit )</strong></td>
      <td><?= $atas->volume . " (" . $atas->satuan . ")" ?></td>
    </tr>
    <tr>
      <td><strong>Total Harga</strong></td>
      <td><?= number_format($atas->total, 2, ',', '.') ?></td>
    </tr>
    <tr>
      <td><strong>Total Harga (PEMBULATAN)</strong></td>
      <td><?= number_format(bulatkan($atas->total), 0, ',', '.') ?></td>
    </tr>
  </tbody>
  </thead>
</table>