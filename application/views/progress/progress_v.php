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
          <div class="form-group row" style="margin-bottom:0px">
            <div class="col-xs-12 col-md-12 col-lg-12 col-form-label">
              <h5><?= $data->nama_proyek_pekerjaan ?></h5>
            </div>
          </div>
          <table style="width:100%">
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
              <td>Harga Satuan Proyek</td>
              <td>:</td>
              <td>Rp. <?= number_format(bulatkan($boq->total_harga_satuan), 0, ',', '.') ?></td>
            </tr>
            <tr>
              <td>No SPK</td>
              <td>:</td>
              <td><?= $data->no_surat_kontrak ?></td>
              <td>&nbsp;</td>
              <td>Total BOQ per Jenis Proyek</td>
              <td>:</td>
              <td>Rp. <?= number_format(bulatkan($boq->total), 0, ',', '.') ?></td>
            </tr>
            <tr>
              <td>Tanggal Mulai</td>
              <td>:</td>
              <td><?= $data->tanggal_mulai ?></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Tanggal Selesai</td>
              <td>:</td>
              <td><?= $data->tanggal_selesai ?></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Volume Target</td>
              <td>:</td>
              <td><?= $data->voljenis . ' ' . $data->sat ?></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
          <hr>
          <div class="row">
            <div class="col text-center">
              <h4 style="background-color: #D7D7D7; color: #2D2D2D; padding: 10px; font-weight: 900;"><?= $jenisproyek->nama_jenis_proyek ?> <?= $jenisproyekstatus !== 0 ? '<button type="button" class="btn btn-primary btn-icon icon-left"> Proyek Selesai <span class="badge badge-transparent"><i class="far fa-check-circle fa-10x"></i></span></button>' : '<button type="button" class="btn btn-warning btn-icon icon-left">' . round($persentaseJenis, 2) . '%</button>' ?></h4>
            </div>
          </div>
          <hr>
          <div class="table-responsive">
            <table class="table table-striped nowrap" id="progress_tabel" style="width:100%">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Kegiatan</th>
                  <th>Tanggal Mulai</th>
                  <th>Tanggal Selesai</th>
                  <th>Durasi</th>
                  <th>Realisasi</th>
                  <th>Persentase</th>
                  <th>Gambar</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th colspan="5" class="text-right">
                    <h5>Total Realisasi :</h5>
                  </th>
                  <th class="text-right">
                    0
                  </th>
                  <th colspan="3">&nbsp;</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="col text-right">
              <a href="<?= base_url('ProgressProyek_c/detail/') . json_decode($idproyek) ?>" type="button" id="cancel" class="btn btn-icon icon-left btn-light">
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
  var idproyek = JSON.parse('<?= $idproyek ?>');
  var idjenis = JSON.parse('<?= $idjenis ?>');
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
      "url": "<?php echo base_url() . 'ProgressProyek_c/ignited_jenisproyek_kegiatan' ?>",
      "type": "POST",
      data: {
        proyek: idproyek,
        jenis: idjenis
      }
    },
    columns: [{
        "data": null,
        width: "10px",
        orderable: false,
        searchable: false
      },
      {
        "data": "nama_kegiatan"
      },
      {
        "data": "startDate",
        render: function(v) {
          var tgl = v.split(' ')[0].split('-');
          return tgl[2] + '-' + tgl[1] + '-' + tgl[0];
        }
      },
      {
        "data": "endDate",
        render: function(v) {
          var tgl = v.split(' ')[0].split('-');
          return tgl[2] + '-' + tgl[1] + '-' + tgl[0];
        }
      },
      {
        "data": "durasi",
        render: function(v) {
          return `${v} Hari`;
        }
      },
      {
        "data": "realisasi",
        render: $.fn.dataTable.render.number('.', ',', 0, ''),
        class: "text-right"
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
        "data": "gambar",
        orderable: false,
        searchable: false,
        class: 'text-center'
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
      // if (data.validasi === '1') {
      // $('.tomboledit', row).css('display', 'none');
      // $('td:eq(7)', row).text('Tidak Bisa Diubah');
      // }
    },
    footerCallback: function(row, data, start, end, display) {
      var api = this.api(),
        data;

      // Remove the formatting to get integer data for summation
      var intVal = function(i) {
        return typeof i === 'string' ?
          i.replace(/[\$,]/g, '') * 1 :
          typeof i === 'number' ?
          i : 0;
      };

      total = api
        .column(5, {
          page: 'current'
        })
        .data()
        .reduce(function(a, b) {
          return intVal(a) + intVal(b);
        }, 0);

      var numFormat = $.fn.dataTable.render.number('.', '.', 0, '').display;
      $(api.column(5).footer()).html(
        `<h5>${numFormat(total)}</h5>`
      );
    }
  });

  $(document).on('click', '.fotodetil', function(e) {
    e.preventDefault();
    var keg = $(this).attr('id-k');
    $.ajax({
      method: "GET",
      dataType: "JSON",
      data: {
        idkegiatan: keg,
      },
      url: "<?= base_url() ?>ProgressProyek_c/getFotoKeg",
      success: function(respon) {
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