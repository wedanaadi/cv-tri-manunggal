<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<style>
  #TabelKonten tr td {
    padding-right: 7px;
    padding-left: 7px;
    font-size: 15px;
  }

  tr.noBorder td {
    border-left: 1px solid #ffffff !important;
    border-right: 1px solid #ffffff !important;
  }
</style>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Dashboard</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-success">
              <i class="fas fa-hammer"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Semua Jenis Proyek</h4>
              </div>
              <div class="card-body">
                <?= $cB + $cS ?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-success">
              <i class="fas fa-clipboard-check"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Jenis Proyek Selesai</h4>
              </div>
              <div class="card-body">
                <?= $cS ?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-success">
              <i class="fas fa-dna"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Jenis Proyek Berjalan</h4>
              </div>
              <div class="card-body">
                <?= $cB ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
          <div class="card">
            <div class="card-header">
              <div class="card-title" style="width:100%">
                <a style="float:right" data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                <br>
                <h4>Progress Proyek</h4>
                <span id="chartspan"></span>
              </div>
            </div>
            <div class="card-body">
              <canvas id="chartProgress" width="300px" height="200"></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Tabel Detail Grafik</h4>
              <div class="card-header-action">

              </div>
            </div>
            <div class="collapse" id="mycard-collapse">
              <div class="card-body">
                <div class="table-responsive">
                  <table id="TabelKonten" class="table table-bordered">
                    <tbody>
                      <?php
                      $no = 1;
                      $konsumen = '';
                      $count = 0;
                      $idproyek1 = '';
                      $idproyek2 = '';
                      $kon2 = '';
                      $sumPersentase = 0;
                      $sumHarga = 0;
                      $banyakJNPro = 0;
                      $arrper = [];
                      $fixpersen = 0;
                      foreach ($tabel as $t) :
                      ?>
                        <?php
                        if ($konsumen !== $t->konsumen) :
                          // $no = 1;
                          $countP1 = 0;
                          $idproyek1 = $t->idproyek;
                          foreach ($tabel as $v) {
                            if ($idproyek1 === $v->idproyek) {
                              $countP1++;
                            }
                          }
                          $banyakJNPro = $countP1;
                        ?>
                          <?php
                          if ($count !== 0) :
                          ?>
                            <tr>
                              <th colspan="<?= $this->session->userdata('hakakses') !== '2' ? 5 : 4 ?>" class="text-center">Total Persentase Proyek</th>
                              <th class="text-center" style="background-color: #8EAADB; color:black"><?= $fixpersen ?>%</th>
                              <th class="text-center">Total Harga</th>
                              <th class="text-center" style="background-color: #FFC000; color:black"><?= number_format($sumHarga, '0', ',', '.') ?></th>
                            </tr>
                            <tr class="noBorder">
                              <td colspan="8">&nbsp;</td>
                            </tr>
                          <?php endif; ?>
                          <tr class="text-center" style="background-color: #F5F5F5;">
                            <th>NO</th>
                            <th>Konsumen/PPK</th>
                            <th>Nama Proyek</th>
                            <th>Jenis Proyek</th>
                            <?php if ($this->session->userdata('hakakses') !== '2') : ?>
                              <th>Kepala Proyek</th>
                            <?php endif; ?>
                            <th>Persentase</th>
                            <th>Status Proyek</th>
                            <th>Harga (Rp)</th>
                          </tr>
                        <?php endif; ?>
                        <?php
                        $countP2 = 0;
                        $countK2 = 0;
                        if ($idproyek2 !== $t->idproyek) :
                          $sumPersentase = 0;
                          $idproyek2 = $t->idproyek;
                          $kon2 = $t->konsumen;
                          foreach ($tabel as $v) {
                            if ($idproyek2 === $v->idproyek) {
                              $countP2++;
                            }
                            if ($kon2 === $v->konsumen) {
                              $countK2++;
                            }
                          }
                          $banyakJNPro = $countP2;
                        ?>
                          <tr>
                            <?php
                            if ($konsumen !== $t->konsumen) :
                              $sumHarga = 0;
                            ?>
                              <td class="text-center" rowspan="<?= $countK2 ?>"><?= $no ?></td>
                              <td rowspan="<?= $countK2 ?>"><?= $t->konsumen ?></td>
                            <?php
                              $no++;
                            endif;
                            ?>
                            <td rowspan="<?= $countP2 ?>"><?= $t->kegiatan ?></td>
                            <td><?= $t->jenis ?></td>
                            <?php if ($this->session->userdata('hakakses') !== '2') : ?>
                              <td class="text-center"><?= $t->kepro ?></td>
                            <?php endif; ?>
                            <td class="text-center"><?= $t->persentase ?>%</td>
                            <td class="text-center"><?= $t->status === '0'  ? 'Proyek Berjalan' : 'Proyek Selesai' ?></td>
                            <td class="text-center"><?= number_format($t->harga, 0, ',', '.') ?></td>
                          </tr>
                        <?php else : ?>
                          <tr>
                            <td><?= $t->jenis ?></td>
                            <?php if ($this->session->userdata('hakakses') !== '2') : ?>
                              <td class="text-center"><?= $t->kepro ?></td>
                            <?php endif; ?>
                            <td class="text-center"><?= $t->persentase ?>%</td>
                            <td class="text-center"><?= $t->status === '0'  ? 'Proyek Berjalan' : 'Proyek Selesai' ?></td>
                            <td class="text-center"><?= number_format($t->harga, 0, ',', '.') ?></td>
                          </tr>
                        <?php endif; ?>
                      <?php
                        $konsumen = $t->konsumen;
                        $count++;
                        $sumPersentase += $t->persentase;
                        $arrper[$t->konsumen][$t->idproyek] = $sumPersentase / $banyakJNPro;
                        $sumHarga += $t->harga;
                        $fixpersen = 0;
                        foreach ($arrper[$t->konsumen] as $v) {
                          $fixpersen += $v;
                        }
                      endforeach;
                      ?>
                      <?php if ($count !== 0) : ?>
                        <tr>
                          <th colspan="<?= $this->session->userdata('hakakses') !== '2' ? 5 : 4 ?>" class="text-center">Total Persentase Proyek</th>
                          <th class="text-center" style="background-color: #8EAADB; color:black"><?= $fixpersen ?>%</th>
                          <th class="text-center">Total Harga</th>
                          <th class="text-center" style="background-color: #FFC000; color:black"><?= number_format($sumHarga, '0', ',', '.') ?></th>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
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
  var progress = JSON.parse('<?= $chart ?>');
  $('#chartspan').html("<strong>Total dalam " + progress.proyek + " Proyek(" + progress.jn + " Jenis Proyek)</strong>");

  var myChartCircle = new Chart('chartProgress', {
    type: 'doughnut',
    data: {
      datasets: [{
        label: 'Progress Proyek',
        percent: progress.chart,
        backgroundColor: ['#5283ff']
      }]
    },
    plugins: [{
        beforeInit: (chart) => {
          const dataset = chart.data.datasets[0];
          chart.data.labels = [dataset.label];
          dataset.data = [dataset.percent, 100 - dataset.percent];
        }
      },
      {
        beforeDraw: (chart) => {
          var width = chart.chart.width,
            height = chart.chart.height,
            ctx = chart.chart.ctx;
          ctx.restore();
          var fontSize = (height / 150).toFixed(2);
          ctx.font = fontSize + "em sans-serif";
          ctx.fillStyle = "#9b9b9b";
          ctx.textBaseline = "middle";
          var text = chart.data.datasets[0].percent + "%",
            textX = Math.round((width - ctx.measureText(text).width) / 2),
            textY = height / 2;
          ctx.fillText(text, textX, textY);
          ctx.save();
        }
      }
    ],
    options: {
      maintainAspectRatio: false,
      cutoutPercentage: 85,
      rotation: Math.PI / 2,
      legend: {
        display: false,
      },
      tooltips: {
        filter: tooltipItem => tooltipItem.index == 0
      }
    }
  });
</script>