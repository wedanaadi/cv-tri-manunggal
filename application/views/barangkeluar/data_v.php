<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Data Barang Keluar</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-header">
              <button class="btn btn-icon icon-left btn-primary note-btn" id="bk_t">
                <i class="fas fa-plus"></i>
                Tambah
              </button>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped nowrap" id="bk_tabel" style="width:100%">
                  <thead>
                    <tr>
                      <th>
                        #
                      </th>
                      <th>Tanggal</th>
                      <th>Nama Barang</th>
                      <th>Jenis</th>
                      <th>Merk</th>
                      <th>Ukuran</th>
                      <th>Satuan</th>
                      <th>Jumlah</th>
                      <th>Penerima</th>
                      <th>Keterangan</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Modal -->
  <div class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" id="modal">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="bk_f" class="needs-validation" novalidate="" autocomplete="off">
          <div class="modal-body">
          </div>
          <div class="modal-footer bg-whitesmoke br">
            <button type="button" class="btn btn-icon icon-left btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i>Tutup</button>
            <button type="submit" id="submit" class="btn btn-icon icon-left btn-primary">
              <i class="fas fa-save"></i>
              Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('_partials/footer'); ?>

<script>
  let isSave = 1;
  let idBK = "";
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

  var tabel = $("#bk_tabel").DataTable({
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
    fixedHeader: true,
    crollCollapse: true,
    fixedColumns: {
      leftColumns: 0,
      rightColumns: 1
    },
    ajax: {
      "url": "<?php echo base_url() . 'BarangKeluar_c/ignited_data' ?>",
      "type": "POST"
    },
    columns: [{
        "data": null,
        width: "10px",
        orderable: false,
        searchable: false
      },
      {
        "data": "tanggal"
      },
      {
        "data": "nama_barang_upah"
      },
      {
        "data": "jenis_brg"
      },
      {
        "data": "merk_brg"
      },
      {
        "data": "ukuran_brg"
      },
      {
        "data": "satuan"
      },
      {
        "data": "jumlah"
      },
      {
        "data": "penerima"
      },
      {
        "data": "keterangan"
      },
      {
        "data": "view",
        width: "170px",
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

  $(document).on('click', '#bk_t', function() {
    isSave = 1
    $.ajax({
      type: "GET",
      dataType: "JSON",
      url: "<?= base_url(); ?>BarangKeluar_c/form/",
      success: function(respon) {
        $('#bk_f')[0].reset();
        $('#bk_f').removeClass('was-validated');
        $('#submit').removeClass('disabled btn-progress');
        $(".modal-body").html(respon.view);
        $('.modal-title').text('Tambah Data');
        $('#modal').modal('show');
      }
    });
  });

  $('#bk_f').on('submit', function(e) {
    e.preventDefault();
    $('#submit').addClass('disabled btn-progress');
    var form = $(this);
    if (form[0].checkValidity() === false) {
      event.stopPropagation();
      $('#submit').removeClass('disabled btn-progress');
    } else {
      $.ajax({
        method: "POST",
        contentType: false,
        processData: false,
        data: new FormData($("#bk_f")[0]),
        url: isSave ? '<?= base_url() ?>BarangKeluar_c/create' : '<?= base_url() ?>BarangKeluar_c/update/' + idBK,
        success: function(respon) {
          tabel.ajax.reload();
          setTimeout(function() {
            $('#modal').modal('hide');
            notifsukses('Barang Keluar', isSave ? 'disimpan' : 'diubah');
          }, 200);
        }
      });
    }
    form.addClass('was-validated');
    return false;
  });

  $(document).on('click', '#bk_u', function() {
    isSave = 0;
    const id = $(this).attr('id-pk');
    $.ajax({
      type: "GET",
      dataType: "JSON",
      url: "<?= base_url(); ?>BarangKeluar_c/form/" + id,
      success: function(respon) {
        console.log(respon);
        $('#bk_f')[0].reset();
        $('#bk_f').removeClass('was-validated');
        $('#submit').removeClass('disabled btn-progress');
        $(".modal-body").html(respon.view);
        $('[name=tgl]').val(respon.data.tanggal);
        $('[name=ket]').val(respon.data.keterangan);
        $('[name=jumlah]').val(respon.data.jumlah);
        $('[name=penerima]').val(respon.data.penerima);
        select2isi('[name=barang]', respon.data.barang_id, respon.data.nama_barang_upah);
        idBK = respon.data.id_brg_keluar;
        $('.modal-title').text('Ubah Data');
        $('#modal').modal('show');
      }
    });
  });

  $(document).on('click', '#bk_d', function() {
    console.log('delete');
    isSave = 0;
    const id = $(this).attr('id-pk');
    swal({
        title: 'Hapus data ?',
        text: 'Setelah dihapus, Anda tidak akan dapat memulihkan data ini!',
        icon: 'warning',
        buttons: ["Batal", "Hapus"],
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            type: "POST",
            url: "<?= base_url() ?>BarangKeluar_c/hapus/" + id,
            success: function(respon) {
              tabel.ajax.reload();
              notifsukses('Barang Keluar', 'dihapus');
            }
          });
        } else {
          notifgagal('Barang Keluar');
        }
      });
  });
</script>