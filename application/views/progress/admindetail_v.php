<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<style>
  #gallerywrapper {
    width: 640px;
    height: 450px;
    margin: 0;
    position: relative;
    font-family: verdana, arial, sans-serif;
  }

  #gallerywrapper #gallery {
    position: absolute;
    left: 0;
    top: 0;
    height: 450px;
    width: 640px;
    overflow: hidden;
    text-align: center;
  }

  #gallerywrapper #gallery div {
    width: 640px;
    height: 900px;
    padding-top: 10px;
    position: relative;
  }

  #gallerywrapper #gallery div img {
    clear: both;
    display: block;
    margin: 0;
    border: 0;
  }

  #gallerywrapper #gallery div h3 {
    padding: 10px 0 0 0;
    margin: 0;
    font-size: 18px;
  }

  #gallerywrapper #gallery div p {
    padding: 5px 0;
    margin: 0;
    font-size: 12px;
    line-height: 18px;
  }

  #gallery .previous {
    display: inline;
    float: left;
    margin-left: 0;
    text-decoration: none;
  }

  #gallery .next {
    display: inline;
    float: right;
    margin-right: 140px;
    text-decoration: none;
  }
</style>
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Progress Proyek </h1>
      <?php if ($progress->validasi !== '0') : ?>
        &nbsp;<span class="badge badge-primary"><?= $progress->validasi === '1' ? 'Diterima' : 'Ditolak' ?></span>
      <?php endif; ?>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-body">
              <div class="form-group row" style="margin-bottom:0px">
                <label class="col-xs-12 col-md-4 col-lg-4 col-form-label">Konsumen / Pejabat Pembuat Komitmen</label>
                <div class="col-xs-12 col-md-8 col-lg-8 col-form-label">
                  <?= $progress->nama_konsumen ?>
                </div>
              </div>
              <div class="form-group row" style="margin-bottom:0px">
                <label class="col-xs-12 col-md-4 col-lg-4 col-form-label">NO. SPMK</label>
                <div class="col-xs-12 col-md-8 col-lg-8 col-form-label">
                  <span style="color: red"><?= $progress->nospmk ?></span>
                </div>
              </div>
              <div class="form-group row" style="margin-bottom:0px">
                <label class="col-xs-12 col-md-4 col-lg-4 col-form-label">Tanggal Prosses</label>
                <div class="col-xs-12 col-md-8 col-lg-8 col-form-label">
                  <?= tgl_waktu($progress->tanggal) ?> WITA
                </div>
              </div>
              <div class="form-group row" style="margin-bottom:0px">
                <label class="col-xs-12 col-md-4 col-lg-4 col-form-label">Jenis Proyek</label>
                <div class="col-xs-12 col-md-8 col-lg-8 col-form-label">
                  <?= $progress->nama_jenis_proyek ?> <span class="font-weight-bold">( <?= "Kegiatan : " . $progress->nama_kegiatan ?> )</span>
                </div>
              </div>
              <div class="form-group row" style="margin-bottom:0px">
                <label class="col-xs-12 col-md-4 col-lg-4 col-form-label">Volume Proyek</label>
                <div class="col-xs-12 col-md-8 col-lg-8 col-form-label">
                  <?= $progress->volorder ?> x <?= $progress->volkegiatan ?> = <?= $progress->voljadwal ?>
                </div>
              </div>
              <div class="form-group row" style="margin-bottom:0px">
                <label class="col-xs-12 col-md-4 col-lg-4 col-form-label">Kepala Proyek</label>
                <div class="col-xs-12 col-md-8 col-lg-8 col-form-label">
                  <?= $progress->kapro ?>
                </div>
              </div>
              <div class="form-group row" style="margin-bottom:0px">
                <label class="col-xs-12 col-md-4 col-lg-4 col-form-label">Pengeluaran</label>
                <div class="col-xs-12 col-md-8 col-lg-8 col-form-label">
                  <?= number_format($progress->pengeluaran, 0, ',', '.') ?>
                </div>
              </div>
              <div class="form-group row" style="margin-bottom:0px">
                <label class="col-xs-12 col-md-4 col-lg-4 col-form-label">Keterangan</label>
                <div class="col-xs-12 col-md-8 col-lg-8 col-form-label">
                  <?= $progress->ket ?>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-12 col-md-6 col-lg-6">
                  <?php if (count($photo) > 0) : ?>
                    <div id="gallerywrapper">
                      <div id="gallery">
                        <?php $no = 1;
                        foreach ($photo as $p) : ?>
                          <div id="pic<?= $no ?>">
                            <img src="<?= base_url('assets/img/foto/' . $p->photo_name) ?>" height="350" width="500" alt="Image 1">
                            <?php
                            if ($no === count($photo)) {
                              $p = $no - 1;
                            } else {
                              if ($no === 1) {
                                $p = count($photo);
                              } else {
                                $p = 1;
                              }
                            }

                            ?>
                            <a class="previous" href="#pic<?= $p ?>">&lt;</a>
                            <a class="next" href="#pic<?= $no === count($photo) ? 1 : $no + 1 ?>">&gt;</a>
                            <p><?= $no ?> of <?= count($photo) ?></p>
                          </div>
                        <?php $no++;
                        endforeach; ?>
                      </div>
                    </div>
                  <?php else : ?>
                    <div class="bagde badge-dark">Tidak Ada Photo</div>
                  <?php endif; ?>
                </div>
                <div class="col-xs-12 col-md-6 col-lg-6">
                  <div class="form-group row" style="margin-bottom:0px">
                    <label class="col-xs-12 col-md-3 col-lg-3 col-form-label">Persentase</label>
                    <div class="col-xs-12 col-md-8 col-lg-8 col-form-label">
                      <div class="progress mb-3">
                        <div class="progress-bar bg-success" style="color: #2D2D2D" role="progressbar" data-width="<?= $progress->persentase . '%' ?>" aria-valuenow="<?= $progress->persentase ?>" aria-valuemin="0" aria-valuemax="100" style="width: 50%;"><?= $progress->persentase ?>%</div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row" style="margin-bottom:0px">
                    <label class="col-xs-12 col-md-3 col-lg-3 col-form-label">Status</label>
                    <div class="col-xs-12 col-md-8 col-lg-8 col-form-label">
                      <?= $progress->status === '0' ? 'Proyek Berjalan' : 'Proyek Selesai' ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <div class="row">
                <div class="col text-right">
                  <a href="<?= base_url('ProgressProyek_c/updateKegiatan/') . $progress->proyek_id . '/' . $progress->jenis_proyek . '/' . $progress->kegiatan_id ?>" onclick="goBack()" type="button" id="cancel" class="btn btn-icon icon-left btn-light">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                  </a> &nbsp;
                  <?php
                  if ($this->session->userdata('hakakses') === '1' || $this->session->userdata('hakakses') === '4') :
                    if ($progress->validasi === '0') :
                  ?>
                      <button type="button" id="tolak" class="btn btn-icon icon-left btn-danger">
                        <i class="fas fa-save"></i>
                        Tolak
                      </button>
                      <button type="button" id="terima" class="btn btn-icon icon-left btn-info">
                        <i class="fas fa-save"></i>
                        Terima
                      </button>
                  <?php
                    endif;
                  endif;
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </section>
</div>
<?php $this->load->view('_partials/footer'); ?>

<script>
  var id = "<?= $progress->id_progress ?>";

  $(document).on('click', '#tolak', function() {
    Swal.fire({
      title: 'Tolak Progress ?',
      html: 'Alasan<br><textarea name="alasan" class="form-control" required/>',
      showDenyButton: true,
      showCancelButton: false,
      confirmButtonText: 'Tolak',
      denyButtonText: `Batal`,
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        if ($('[name=alasan]').val() === '') {
          notifgagal('', 'Alasan Harus diisi');
        } else {
          $.ajax({
            type: "POST",
            data: {
              alasan: $('[name=alasan]').val()
            },
            url: "<?= base_url() ?>ProgressProyek_c/updateValidasi/" + id + '/' + 2,
            success: function(respon) {
              notifsukses('Progress Proyek', 'ditolak');
              setTimeout(function() {
                window.location.href = "<?= base_url() ?>ProgressProyek_c/detailadmin/" + id;
              }, 1000);
            }
          });
        }
      } else if (result.isDenied) {
        Swal.fire('Data Tidak disimpan', '', 'info')
      }
    })
  });

  $(document).on('click', '#terima', function() {
    Swal.fire({
      title: 'Terima Progress ?',
      html: 'Alasan<br><textarea name="alasan" class="form-control" required/>',
      showDenyButton: true,
      showCancelButton: false,
      confirmButtonText: 'Terima',
      denyButtonText: `Batal`,
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        const alasan = $('[name=alasan]').val() === '' ? '-' : $('[name=alasan]').val();
        $.ajax({
          type: "POST",
          data: {
            alasan
          },
          url: "<?= base_url() ?>ProgressProyek_c/updateValidasi/" + id + '/' + 1,
          success: function(respon) {
            notifsukses('Progress Proyek', 'diterima');
            setTimeout(function() {
              window.location.href = "<?= base_url() ?>ProgressProyek_c/detailadmin/" + id;
            }, 1000);
          }
        });
      } else if (result.isDenied) {
        Swal.fire('Data Tidak disimpan', '', 'info')
      }
    })
  });
</script>