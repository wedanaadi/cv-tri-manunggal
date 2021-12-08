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
      <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
              <i class="fas fa-hammer"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Semua Proyek</h4>
              </div>
              <div class="card-body">
                <?= $cS + $cB ?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
              <i class="fas fa-clipboard-check"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Proyek Selesai</h4>
              </div>
              <div class="card-body">
                <?= $cS ?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
          <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
              <i class="fas fa-dna"></i>
            </div>
            <div class="card-wrap">
              <div class="card-header">
                <h4>Proyek Berjalan</h4>
              </div>
              <div class="card-body">
                <?= $cB ?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped nowrap" id="progress_tabel" style="width:100%">
              <thead>
                <tr>
                  <th>
                    #
                  </th>
                  <th>Konsumen/PPK</th>
                  <th>Kegiatan</th>
                  <th>NO SPMK</th>
                  <th>Durasi</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php $this->load->view('_partials/footer'); ?>

<script>
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
      $('#progress_tabel_filter input').off('.DT').on('input.DT', function() {
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
    fixedHeader: true,
    crollCollapse: true,
    fixedColumns: {
      leftColumns: 0,
      rightColumns: 1
    },
    ajax: {
      "url": "<?php echo base_url() . 'ProgressProyek_c/ignited_data' ?>",
      "type": "POST"
    },
    columns: [{
        "data": null,
        width: "10px",
        orderable: false,
        searchable: false
      },
      {
        "data": "nama_konsumen"
      },
      {
        "data": "nama_proyek_pekerjaan"
      },
      {
        "data": "nospmk"
      },
      {
        "data": "durasi",
        searchable: false,
      },
      {
        "data": "status",
        render: function(d) {
          if (d === '0') {
            return '<span class="badge badge-info">Proyek Berjalan</span>';
          } else {
            return '<span class="badge badge-success">Proyek Selesai</span>';
          }
        }
      },
      {
        "data": "view",
        width: "100px",
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
    }
  });
</script>