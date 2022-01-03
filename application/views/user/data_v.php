<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Data User</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-header">
              <button class="btn btn-icon icon-left btn-primary note-btn" id="user_t">
                <i class="fas fa-plus"></i>
                Tambah
              </button>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped" id="konsumen_tabel" style="width:100%">
                  <thead>
                    <tr>
                      <th>
                        #
                      </th>
                      <th>Nama User</th>
                      <th>Username</th>
                      <th>Hak Akses</th>
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
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="user_f" class="needs-validation" novalidate="" autocomplete="off">
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
  let iduser = "";
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

  var tabel = $("#konsumen_tabel").DataTable({
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
    ajax: {
      "url": "<?php echo base_url() . 'User_c/ignited_data' ?>",
      "type": "POST"
    },
    columns: [{
        "data": null,
        width: "10px",
        orderable: false,
        searchable: false
      },
      {
        "data": "nama_user"
      },
      {
        "data": "username"
      },
      {
        "data": "hak_akses",
        render: function(v) {
          var hk = '';
          if (v === '1') {
            hk = 'Admin';
          } else if (v === '2') {
            hk = 'Kepala Proyek';
          } else if (v === '3') {
            hk = 'Direktur';
          } else {
            hk = 'Admin Proyek';
          }
          return hk;
        }
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

  $(document).on('click', '#user_t', function() {
    isSave = 1
    $.ajax({
      type: "GET",
      dataType: "JSON",
      url: "<?= base_url(); ?>User_c/form/",
      success: function(respon) {
        $('#user_f')[0].reset();
        $('#user_f').removeClass('was-validated');
        $('#submit').removeClass('disabled btn-progress');
        $(".modal-body").html(respon.view);
        $('#fg-password label').text('Password');
        $('.modal-title').text('Tambah Data');
        $('#modal').modal('show');
      }
    });
  });

  $('#user_f').on('submit', function(e) {
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
        data: new FormData($("#user_f")[0]),
        url: isSave ? '<?= base_url() ?>User_c/create' : '<?= base_url() ?>User_c/update/' + iduser,
        success: function(respon) {
          tabel.ajax.reload();
          setTimeout(function() {
            $('#modal').modal('hide');
            notifsukses('Data User', isSave ? 'disimpan' : 'diubah');
          }, 200);
        }
      });
    }
    form.addClass('was-validated');
    return false;
  });

  $(document).on('click', '#user_u', function() {
    isSave = 0;
    const id = $(this).attr('id-pk');
    $.ajax({
      type: "GET",
      dataType: "JSON",
      url: "<?= base_url(); ?>User_c/form/" + id,
      success: function(respon) {
        $('#user_f')[0].reset();
        $('#user_f').removeClass('was-validated');
        $('#submit').removeClass('disabled btn-progress');
        $(".modal-body").html(respon.view);
        $('[name=password]').removeAttr('required');
        $('#fg-password label').html('Password <span style="color:#dc3545">* Kosongkan jika tidak ingin mengubah</span>');
        $('[name=nama]').val(respon.data.nama_user);
        $('[name=username]').val(respon.data.username);
        $('[name=hakakses]').val(respon.data.hak_akses);
        iduser = respon.data.id_user;
        $('.modal-title').text('Ubah Data');
        $('#modal').modal('show');
      }
    });
  });

  $(document).on('click', '#user_d', function() {
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
          url: "<?= base_url() ?>User_c/hapus/" + id,
          success: function(respon) {
            tabel.ajax.reload();
            notifsukses('Data User', 'dihapus');
          }
        });
      } else {
        notifgagal('Data User', 'dihapus');
      }
    })
  });
</script>