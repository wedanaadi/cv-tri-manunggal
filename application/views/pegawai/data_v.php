<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Data Pegawai</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-header">
              <button class="btn btn-icon icon-left btn-primary note-btn" id="pegawai_t">
                <i class="fas fa-plus"></i>
                Tambah
              </button>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped nowrap" id="pegawai_tabel" style="width:100%">
                  <thead>
                    <tr>
                      <th>
                        #
                      </th>
                      <th>Nama Pegawai</th>
                      <th>NPWP</th>
                      <th>NIK</th>
                      <th>Alamat</th>
                      <th>Tempat Tangal Lahir</th>
                      <th>Jenis Kelamin</th>
                      <th>Telepon</th>
                      <th>Jabatan</th>
                      <th>Email</th>
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
        <form id="pegawai_f" class="needs-validation" novalidate="" autocomplete="off">
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
  let idpegawai = "";
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

  var tabel = $("#pegawai_tabel").DataTable({
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
      "url": "<?php echo base_url() . 'Pegawai_c/ignited_data' ?>",
      "type": "POST"
    },
    columns: [{
        "data": null,
        width: "10px",
        orderable: false,
        searchable: false
      },
      {
        "data": "nama_pegawai"
      },
      {
        "data": "npwp"
      },
      {
        "data": "nik"
      },
      {
        "data": "alamat"
      },
      {
        "data": "ttl",
        render: function(v) {
          var tempat = v.split('/')[0];
          var tgl = v.split('/')[1].split('-');
          var gabung = tempat + ', ' + tgl[2] + ' ' + month[parseInt(tgl[1])] + ' ' + tgl[0];
          return gabung;
        }
      },
      {
        "data": "jenis_kelamin",
        render: function(v) {
          var jk = '';
          if (v === 'L') {
            jk = 'Laki - Laki';
          } else {
            jk = 'Perempuan';
          }
          return jk;
        }
      },
      {
        "data": "no_telp"
      },
      {
        "data": "jabatan"
      },
      {
        "data": "email"
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

  $(document).on('click', '#pegawai_t', function() {
    isSave = 1
    $.ajax({
      type: "GET",
      dataType: "JSON",
      url: "<?= base_url(); ?>Pegawai_c/form/",
      success: function(respon) {
        $('#pegawai_f')[0].reset();
        $('#pegawai_f').removeClass('was-validated');
        $('#submit').removeClass('disabled btn-progress');
        $(".modal-body").html(respon.view);
        $('.modal-title').text('Tambah Data');
        $('#modal').modal('show');
      }
    });
  });

  $('#pegawai_f').on('submit', function(e) {
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
        data: new FormData($("#pegawai_f")[0]),
        url: isSave ? '<?= base_url() ?>Pegawai_c/create' : '<?= base_url() ?>Pegawai_c/update/' + idpegawai,
        success: function(respon) {
          tabel.ajax.reload();
          setTimeout(function() {
            $('#modal').modal('hide');
            notifsukses('Data Pegawai', isSave ? 'disimpan' : 'diubah');
          }, 200);
        }
      });
    }
    form.addClass('was-validated');
    return false;
  });

  $(document).on('click', '#pegawai_u', function() {
    isSave = 0;
    const id = $(this).attr('id-pk');
    $.ajax({
      type: "GET",
      dataType: "JSON",
      url: "<?= base_url(); ?>Pegawai_c/form/" + id,
      success: function(respon) {
        $('#pegawai_f')[0].reset();
        $('#pegawai_f').removeClass('was-validated');
        $('#submit').removeClass('disabled btn-progress');
        $(".modal-body").html(respon.view);
        $('[name=nama]').val(respon.data.nama_pegawai);
        $('[name=npwp]').val(respon.data.npwp);
        $('[name=nik]').val(respon.data.nik);
        $('[name=jk]').val(respon.data.jenis_kelamin);
        $('[name=tl]').val(respon.data.tempat_lahir);
        $('[name=tgl]').val(respon.data.tgl_lahir);
        $('[name=telp]').val(respon.data.no_telp);
        $('[name=email]').val(respon.data.email);
        $('[name=jabatan]').val(respon.data.jabatan);
        $('[name=alamat]').val(respon.data.alamat);
        idpegawai = respon.data.id_pegawai;
        $('.modal-title').text('Ubah Data');
        $('#modal').modal('show');
      }
    });
  });

  $(document).on('click', '#pegawai_d', function() {
    isSave = 0;
    const id = $(this).attr('id-pk');
    Swal.fire({
      title: 'Hapus data ?',
      text: "Setelah dihapus, Anda tidak akan dapat memulihkan data ini!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Hapus',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "POST",
          url: "<?= base_url() ?>Pegawai_c/hapus/" + id,
          success: function(respon) {
            tabel.ajax.reload();
            notifsukses('Data Pegawai', 'dihapus');
          }
        });
      } else {
        notifgagal('Data Pegawai', 'dihapus');
      }
    })
  });
</script>