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
          <div class="table-responsive">
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
                <td>Nama Kegiatan</td>
                <td>:</td>
                <td><?= $data->nama_kegiatan ?></td>
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
          </div>
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
              <tfoot>
                <tr>
                  <th colspan="3">
                    <h5>Total Persentase</h5>
                  </th>
                  <th>0</th>
                  <th colspan="7">&nbsp;</th>
                </tr>
              </tfoot>
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

<script>
  let idproyek = '<?= $data->proyek_id; ?>';
  let idjenisproyek = '<?= $data->jenis_proyek_id; ?>';
  let idkegiatan = '<?= $data->kegiatan_id; ?>';

  $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
    return {
      "iStart": oSettings._iDisplayStart,
      "iEnd": oSettings.fnDisplayEnd(),
      "iLength": oSettings._iDisplayLength,
      "iTotal": oSettings.fnRecordsTotal(),
      "iFilteredTotal": oSettings.fnRecordsDisplay(),
      "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
      "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
    };
  };

  var tabel = $("#progress_tabel").DataTable({
    initComplete: function() {
      var api = this.api();
      $('#mytable_filter input').off('.DT').on('input.DT', function() {
        api.search(this.value).draw();
      });
    },
    oLanguage: {
      sProcessing: "loading..."
    },
    processing: false,
    serverSide: true,
    scrollX: true,
    scrollY: false,
    scrollCollapse: true,
    fixedColumns: {
      leftColumns: 0,
      rightColumns: 1,
    },
    ajax: {
      "url": "<?php echo base_url() . 'ProgressProyek_c/ignited_kegiatan_progress' ?>",
      "type": "POST",
      data: {
        proyek: idproyek,
        jenis: idjenisproyek,
        kegiatan: idkegiatan
      }
    },
    columns: [{
        "data": null,
        width: "10px",
        orderable: false,
        searchable: false
      },
      {
        "data": "tanggal",
        render: function(v) {
          var tgl = v.split(' ')[0].split('-');
          return tgl[2] + '-' + tgl[1] + '-' + tgl[0];
        }
      },
      {
        "data": "progress",
        render: function(v) {
          function romanize(num) {
            var lookup = {
                M: 1000,
                CM: 900,
                D: 500,
                CD: 400,
                C: 100,
                XC: 90,
                L: 50,
                XL: 40,
                X: 10,
                IX: 9,
                V: 5,
                IV: 4,
                I: 1
              },
              roman = '',
              i;
            for (i in lookup) {
              while (num >= lookup[i]) {
                roman += i;
                num -= lookup[i];
              }
            }
            return roman;
          }
          return `Progress ${romanize(v)}`;
        },
        class: "text-center"
      },
      {
        "data": "persentase",
        render: function(v) {
          return `<div class="progress mb-3">
                    <div class="progress-bar bg-success" style="color: #2D2D2D; width: ${v}%;" role="progressbar" data-width="${v}%" aria-valuenow="${v}" aria-valuemin="0" aria-valuemax="100">${v} %</div>
                  </div>`;
        }
      },
      {
        "data": "status",
        render: function(v) {
          const ket = v === '0' ? 'Berjalan' : 'Selesai';
          return `<span class="badge badge-info">${ket}</span>`;
        }
      },
      {
        "data": "gambar",
        orderable: false,
        searchable: false,
        class: 'text-center'
      },
      {
        "data": "pengeluaran",
        render: $.fn.dataTable.render.number('.', ',', 0, ''),
        class: "text-right"
      },
      {
        "data": "ket"
      },
      {
        "data": "validasi",
        render: function(v) {
          let ket = "Menunggu Validasi";
          if (v === '1') {
            ket = "Di Terima";
          }
          if (v === '2') {
            ket = "Di Tolak";
          }
          return `<span class="badge badge-primary">${ket}</span>`;
        }
      },
      {
        "data": "validasi_ket"
      },
      {
        "data": "view",
        orderable: false,
        searchable: false,
        class: 'text-center'
      }
    ],
    order: [
      [1, 'asc']
    ],
    rowCallback: function(row, data, iDisplayIndex) {
      var info = this.fnPagingInfo();
      var page = info.iPage;
      var length = info.iLength;
      $('td:eq(0)', row).html(iDisplayIndex + 1 + info.iStart);
      if (data.status === '1') {
        $('.tomboledit', row).css('display', 'none');
        $('td:eq(10)', row).html(`<span class="badge badge-danger">Tidak dapat diubah</span>`);
      }
      const endDate = new Date('<?= $data->endDate ?>');
      const dataTanggal = new Date(data.tanggal);
      if (dataTanggal.valueOf() > endDate.valueOf()) {
        $('td', row).addClass('text-danger');
      }
      if (data.validasi === '2') {
        $('td:eq(3)', row).addClass('ditolak');
        $('td:eq(2)', row).addClass('text-warning');
        $('td:eq(2)', row).css('text-decoration', 'line-through');
      }
    },
    footerCallback: function(row, data, start, end, display) {
      var api = this.api(),
        data;
      var total = 0;

      // Remove the formatting to get integer data for summation
      var intVal = function(i) {
        return typeof i === 'string' ?
          i.replace(/[\$,]/g, '') * 1 :
          typeof i === 'number' ?
          i : 0;
      };

      api
        .cells(null, 3, {
          page: 'current'
        })
        .nodes()
        .each(function(n) {
          // console.log(intVal($(n).text().replace(" %", "")));
          if (!$(n)
            .hasClass('ditolak')) {
            total += intVal($(n).text().replace(" %", ""));
          }
        })

      // total = api
      //   .column(3, {
      //     page: 'current'
      //   })
      //   .data()
      //   .reduce(function(a, b) {
      //     return intVal(a) + intVal(b);
      //   }, 0);

      var numFormat = $.fn.dataTable.render.number('.', '.', 0, '').display;
      $(api.column(3).footer()).html(
        `<h5>${total}%</h5>`
      );
    }
  });

  $(document).on('click', '.fotodetil', function(e) {
    e.preventDefault();
    var pro = $(this).attr('id-pro');
    $.ajax({
      method: "GET",
      dataType: "JSON",
      data: {
        idprogress: pro,
      },
      url: "<?= base_url() ?>ProgressProyek_c/getFoto",
      success: function(respon) {
        console.log(respon);
        $('.modal-body').html("");
        if (respon.status === 'kosong') {
          $('.modal-body').html("<p>Tidak Ada Gambar</p>");
        } else {
          $('.modal-body').html(respon.data);
        }
        $('.modal-title').text('Photo');
        $('#modal').modal('show');
      }
    });
    return false;
  });
</script>