<h3><?= $jn->nama_jenis_proyek ?></h3>
<hr>
<div class="table-responsive">
  <table class="table nowrap table-bordered" style="width:100%" id="detil">
    <thead>
      <tr>
        <th>Nama Kegiatan</th>
        <th>Unit</th>
        <th>Vol</th>
        <th>Harga Satuan</th>
        <th>Jumlah</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($detil as $v) :
      ?>
        <tr>
          <td><?= $v->nama_kegiatan; ?></td>
          <td><?= $v->unit; ?></td>
          <td><?= $v->vol; ?></td>
          <td><?= number_format($v->harga, 0, ',', '.'); ?></td>
          <td><?= number_format($v->jumlah, 0, ',', '.'); ?></td>
        </tr>
      <?php
      endforeach;
      ?>
    </tbody>
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