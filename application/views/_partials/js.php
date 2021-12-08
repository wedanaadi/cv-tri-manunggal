<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- General JS Scripts -->
<script src="<?php echo base_url(); ?>assets/modules/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/popper.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/tooltip.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/stisla.js"></script>

<script src="<?php echo base_url(); ?>assets/modules/datatables/DataTables-1.11.3/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/datatables/DataTables-1.11.3/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/datatables/FixedColumns-4.0.0/js/dataTables.fixedColumns.min.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/datatables/FixedHeader-3.2.0/js/fixedHeader.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/iziToast/js/iziToast.min.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/sweetalert/sweetalert2.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/select2/dist/js/select2.full.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/page/modules-toastr.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/inputmask/min/inputmask/inputmask.min.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/inputmask/min/inputmask/inputmask.extensions.min.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/inputmask/min/inputmask/inputmask.numeric.extensions.min.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/inputmask/min/inputmask/jquery.inputmask.min.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/inputfile/js/plugins/piexif.min.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/inputfile/js/plugins/sortable.min.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/inputfile/js/fileinput.min.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/inputfile/themes/explorer-fas/theme.min.js"></script>
<script src="<?php echo base_url(); ?>assets/modules/chart.min.js"></script>

<!-- JS Libraies -->

<!-- Page Specific JS File -->

<!-- Template JS File -->
<script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
<script src="<?php echo base_url(); ?>assets/js/custom.js"></script>

<script>
  function select2isi(el, id, text) {
    if ($(el).find("option[value='" + id + "']").length) {
      $(el).val(id).trigger('change');
    } else {
      var newOption = new Option(text, id, true, true);
      $(el).append(newOption).trigger('change');
    }
  }

  var month = ['list', 'Januari',
    'Februari', 'Maret',
    'April', 'Mei',
    'Juni', 'Juli',
    'Agustus', 'September',
    'Oktober', 'November', 'Desember'
  ];

  function goBack() {
    window.history.back();
  }
</script>
</body>

</html>