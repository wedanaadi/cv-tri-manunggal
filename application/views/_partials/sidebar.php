<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <img alt="image" src="<?php echo base_url(); ?>assets/img/logo/logo.png" class="rounded-circle mr-1" style="width: 35px; margin-top:-5px">
      <a href="<?php echo base_url(); ?>dist/index">CV Tri Manunggal</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <img alt="image" src="<?php echo base_url(); ?>assets/img/logo/logo.png" class="rounded-circle mr-1" style="width: 35px; margin-top:-5px">
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">Dashboard</li>
      <li class="<?php echo $this->uri->segment(1) == 'Dashboard_c' && $this->uri->segment(2) == 'index' ? 'active' : ''; ?>"><a class="<?php echo $this->uri->segment(1) == 'Dashboard_c' && $this->uri->segment(2) == 'index' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>Dashboard_c/index"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
      <?php if ($this->session->userdata('hakakses') === '1' || $this->session->userdata('hakakses') === '4') : ?>
        <?php if ($this->session->userdata('hakakses') === '1') : ?>
          <li class="<?php echo $this->uri->segment(1) == 'User_c' && $this->uri->segment(2) == 'index' ? 'active' : ''; ?>"><a class="<?php echo $this->uri->segment(1) == 'User_c' && $this->uri->segment(2) == 'index' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>User_c/index"><i class="fas fa-user"></i> <span>User</span></a></li>
        <?php endif; ?>
        <li class="<?php echo $this->uri->segment(1) == 'Pegawai_c' && $this->uri->segment(2) == 'index' ? 'active' : ''; ?>"><a class="<?php echo $this->uri->segment(1) == 'Pegawai_c' && $this->uri->segment(2) == 'index' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>Pegawai_c/index"><i class="fas fa-users"></i> <span>Pegawai</span></a></li>
        <li class="<?php echo $this->uri->segment(1) == 'Konsumen_c' && $this->uri->segment(2) == 'index' ? 'active' : ''; ?>"><a class="<?php echo $this->uri->segment(1) == 'Konsumen_c' && $this->uri->segment(2) == 'index' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>Konsumen_c/index"><i class="fas fa-id-card"></i> <span>Konsumen</span></a></li>
      <?php endif; ?>

      <?php if ($this->session->userdata('hakakses') === '1' || $this->session->userdata('hakakses') === '4') : ?>
        <li class="<?= $this->uri->segment(1) == 'Kegiatan_c' || $this->uri->segment(1) == 'JenisProyek_c' || $this->uri->segment(1) == 'OrderProyek_c' || $this->uri->segment(1) == 'JadwalProyek_c' || $this->uri->segment(1) == 'BarangUpah_c' ? 'active' : ''; ?>">
          <a href="#" class="nav-link has-dropdown"><i class="fas fa-exclamation-triangle"></i><span>Data Proyek</span></a>
          <ul class="dropdown-menu">
            <li class="<?= $this->uri->segment(1) == 'BarangUpah_c' && $this->uri->segment(2) == 'index' ? 'active' : ''; ?>"><a class="<?= $this->uri->segment(1) == 'BarangUpah_c' && $this->uri->segment(2) == 'index' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>BarangUpah_c/index">Barang dan Upah</a></li>
            <li class="<?= $this->uri->segment(1) == 'Kegiatan_c' && $this->uri->segment(2) == 'index' || $this->uri->segment(2) == 'addKegiatan' || $this->uri->segment(2) == 'ubahKegiatan' ? 'active' : ''; ?>"><a class="<?= $this->uri->segment(1) == 'Kegiatan_c' && $this->uri->segment(2) == 'index' || $this->uri->segment(2) == 'addKegiatan' || $this->uri->segment(2) == 'ubahKegiatan' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>Kegiatan_c/index">Master Data Kegiatan</a></li>
            <li class="<?= $this->uri->segment(1) == 'JenisProyek_c' && $this->uri->segment(2) == 'index' || $this->uri->segment(2) == 'addJN' || $this->uri->segment(2) == 'ubahJN' ? 'active' : ''; ?>"><a class="<?= $this->uri->segment(1) == 'JenisProyek_c' && $this->uri->segment(2) == 'index' || $this->uri->segment(2) == 'addJN' || $this->uri->segment(2) == 'ubahJN' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>JenisProyek_c/index">Jenis Proyek</a></li>
            <li class="<?= $this->uri->segment(1) == 'OrderProyek_c' && $this->uri->segment(2) == 'index' || $this->uri->segment(2) == 'addOP' || $this->uri->segment(2) == 'ubahOP' ? 'active' : ''; ?>"><a class="<?= $this->uri->segment(1) == 'OrderProyek_c' && $this->uri->segment(2) == 'index' || $this->uri->segment(2) == 'addOP' || $this->uri->segment(2) == 'ubahOP' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>OrderProyek_c/index">Data Order Proyek</a></li>
            <li class="<?= $this->uri->segment(1) == 'JadwalProyek_c' ? 'active' : ''; ?>"><a class="<?= $this->uri->segment(1) == 'JadwalProyek_c' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>JadwalProyek_c/index">Data Jadwal Proyek</a></li>
          </ul>
        </li>
      <?php endif; ?>

      <?php if ($this->session->userdata('hakakses') === '3') : ?>
        <li class="<?= $this->uri->segment(1) == 'OrderProyek_c' || $this->uri->segment(1) == 'Boq_c' || $this->uri->segment(1) == 'ProgressProyek_c' ? 'active' : ''; ?>">
          <a href="#" class="nav-link has-dropdown"><i class="fas fa-exclamation-triangle"></i><span>Data Proyek</span></a>
          <ul class="dropdown-menu">
            <li class="<?= $this->uri->segment(1) == 'OrderProyek_c' && $this->uri->segment(2) == 'index' ? 'active' : ''; ?>"><a class="<?= $this->uri->segment(1) == 'OrderProyek_c' && $this->uri->segment(2) == 'index' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>OrderProyek_c/index">Data Order Proyek</a></li>
            <li class="<?= $this->uri->segment(1) == 'Boq_c' && $this->uri->segment(2) == 'boqView' ? 'active' : ''; ?>"><a class="<?= $this->uri->segment(1) == 'Boq_c' && $this->uri->segment(2) == 'boqView' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>Boq_c/boqView">BOQ</a></li>
            <li class="<?= $this->uri->segment(1) == 'ProgressProyek_c' && $this->uri->segment(2) == 'progressProyekDirektur' ? 'active' : ''; ?>"><a class="<?= $this->uri->segment(1) == 'ProgressProyek_c' && $this->uri->segment(2) == 'progressProyekDirektur' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>ProgressProyek_c/progressProyekDirektur">Progress Proyek</a></li>
          </ul>
        </li>
      <?php endif; ?>

      <?php if ($this->session->userdata('hakakses') === '2') : ?>
        <li class="<?= $this->uri->segment(1) == 'ProgressProyek_c' ? 'active' : ''; ?>">
          <a href="#" class="nav-link has-dropdown"><i class="fas fa-exclamation-triangle"></i><span>Data Proyek</span></a>
          <ul class="dropdown-menu">
            <li class="<?= $this->uri->segment(1) == 'ProgressProyek_c' && $this->uri->segment(2) == 'index' ? 'active' : ''; ?>"><a class="<?= $this->uri->segment(1) == 'ProgressProyek_c' && $this->uri->segment(2) == 'index' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>ProgressProyek_c/index">Progress Proyek</a></li>
          </ul>
        </li>
      <?php endif; ?>

      <?php if ($this->session->userdata('hakakses') === '1' || $this->session->userdata('hakakses') === '4') : ?>
        <li class="<?php echo $this->uri->segment(1) == 'Boq_c' && $this->uri->segment(2) == 'index' || $this->uri->segment(2) == 'addBoq' || $this->uri->segment(2) == 'ubahBoq' ? 'active' : ''; ?>"><a class="<?php echo $this->uri->segment(1) == 'Boq_c' && $this->uri->segment(2) == 'index' || $this->uri->segment(2) == 'addBoq' || $this->uri->segment(2) == 'ubahBoq' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>Boq_c/index"><i class="fas fa-columns"></i> <span>BOQ</span></a></li>
      <?php endif; ?>
      <?php if ($this->session->userdata('hakakses') === '1' || $this->session->userdata('hakakses') === '4') : ?>
        <li class="<?php echo $this->uri->segment(2) == 'progressProyekAdmin' || $this->uri->segment(2) == 'detailforadmin' || $this->uri->segment(2) == 'progresslistadmin' || $this->uri->segment(2) == 'detailadmin' ? 'active' : ''; ?>"><a class="<?php echo $this->uri->segment(2) == 'progressProyekAdmin' || $this->uri->segment(2) == 'detailforadmin' || $this->uri->segment(2) == 'progresslistadmin' || $this->uri->segment(2) == 'detailadmin' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>ProgressProyek_c/progressProyekAdmin"><i class="fas fa-hourglass-half"></i> <span>Progress proyek</span></a></li>
      <?php endif; ?>
    </ul>
  </aside>
</div>