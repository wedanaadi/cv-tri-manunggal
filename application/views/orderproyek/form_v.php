<table class="table" style="width:100%">
  <tr>
    <td>Nama Konsuen/PPK</td>
    <td></td>
    <td><?= $detil['data']->nama_konsumen ?></td>
  </tr>
  <tr>
    <td>Kegiatan</td>
    <td></td>
    <td><?= $detil['data']->nama_kegiatan ?></td>
  </tr>
  <tr>
    <td>Pekerjaan</td>
    <td></td>
    <td><?= $detil['data']->nama_proyek_pekerjaan ?></td>
  </tr>
  <tr>
    <td>Lokasi</td>
    <td></td>
    <td><?= $detil['data']->lokasi ?></td>
  </tr>
  <tr>
    <td>Tahun Anggaran</td>
    <td></td>
    <td><?= $detil['data']->tahun_anggaran ?></td>
  </tr>
  <tr>
    <td>No Surat Kontrak</td>
    <td></td>
    <td><?= $detil['data']->no_surat_kontrak ?></td>
  </tr>
</table>
<hr>
<table id="appends" class="table table-bordered" style="background-color: #F2F2F2; border-color:#B6B6B6">
  <?php foreach ($detil['detildata'] as $v) : ?>
    <tr>
      <td style="border-color:#B6B6B6" style="padding-bottom: 10px;">
        <div class="form-group row" style="margin-top:15px">
          <div class="col-xs-12 col-md-2 col-lg-2">Jenis Proyek</div>
          <div class="col-xs-12 col-md-4 col-lg-4">
            <?= $v->nama_jenis_proyek ?>
          </div>
          <div class="col-xs-12 col-md-1 col-lg-1">Vol</div>
          <div class="col-xs-12 col-md-2 col-lg-2">
            <?= $v->vol ?>
          </div>
          <div class="col-xs-12 col-md-1 col-lg-1">Sat</div>
          <div class="col-xs-12 col-md-2 col-lg-2">
            <?= $v->sat ?>
          </div>
        </div>
        <div class="form-group row" style="margin-bottom:0px">
          <div class="col-xs-12 col-md-2 col-lg-2">Kepala Proyek</div>
          <div class="col-xs-12 col-md-6 col-lg-6">
            <?= $v->nama_user ?>
          </div>
        </div>
      </td>
    </tr>
  <?php endforeach; ?>
</table>