<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Progress Proyek</h1>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-body">
          <table style="width:100%">
            <tr>
              <td>Jenis Proyek</td>
              <td>:</td>
              <td><?= $data->nama_jenis_proyek ?></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Nama Konsumen/PPK</td>
              <td>:</td>
              <td><?= $data->nama_konsumen ?></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>No SPMK</td>
              <td>:</td>
              <td><?= $data->nospmk ?></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>No SPK</td>
              <td>:</td>
              <td><?= $data->no_surat_kontrak ?></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Tanggal Mulai Kegiatan</td>
              <td>:</td>
              <td><?= $data->startDate ?></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Tanggal Selesai Kegiatan</td>
              <td>:</td>
              <td><?= $data->endDate ?></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Volume Target</td>
              <td>:</td>
              <td><?= $data->volorder . ' ' . $data->satjadwal ?></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Volume Kegiatan</td>
              <td>:</td>
              <td><?= $data->volkegiatan . ' ' . $data->unit ?></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Total Volume Kegiatan</td>
              <td>:</td>
              <td><?= $data->voljadwal . ' ' . $data->unit ?></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Harga Satuan Proyek</td>
              <td>:</td>
              <td>Rp. <?= number_format(bulatkan($boq->total_harga_satuan), 0, ',', '.') ?></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Total Boq Jenis Proyek</td>
              <td>:</td>
              <td>Rp. <?= number_format(bulatkan($boq->total), 0, ',', '.') ?></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
          <hr>
          <div class="row">
            <div class="col text-center">
              <h4 style="background-color: #D7D7D7; color: #2D2D2D; padding: 10px; font-weight: 900;"><?= $jenisproyek->nama_jenis_proyek ?> <?= $jenisproyekstatus !== 0 ? '<button type="button" class="btn btn-primary btn-icon icon-left"> Proyek Selesai <span class="badge badge-transparent"><i class="far fa-check-circle fa-10x"></i></span></button>' : '' ?></h4>
            </div>
          </div>
          <?php if ($jenisproyekstatus === 0) : ?>
            <a href="<?= base_url('ProgressProyek_c/formAdd/' . $data->proyek_id . '/' . $data->jenis_proyek_id . '/' . $idkegiatan) ?>" class="btn btn-icon icon-left btn-primary note-btn" id="pegawai_t">
              <i class="fas fa-plus"></i>
              Tambah
            </a>
          <?php endif; ?>
          <hr>
          <div class="table-responsive">
            <table class="table table-striped nowrap" id="progress_tabel" style="width:100%">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Tanggal</th>
                  <th>Progress Proyek</th>
                  <th>Persentase</th>
                  <th>Status Progress</th>
                  <th>Gambar</th>
                  <th>Total Pengeluaran</th>
                  <th>Keterangan</th>
                  <th>Status Pengeluaran</th>
                  <th>Validasi Keterangan</th>
                  <th>Aksi</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="col text-right">
              <a href="javascript:void(0);" onclick="goBack()" type="button" id="cancel" class="btn btn-icon icon-left btn-light">
                <i class="fas fa-arrow-left"></i>
                Kembali
              </a> &nbsp;
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <div class="modal fade" data-backdrop="static" role="dialog" id="modal">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('_partials/footer'); ?>