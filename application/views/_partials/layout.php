<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <!-- <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li> -->
          </ul>
          <!-- <div class="search-element">
            <input class="form-control" type="search" placeholder="Search" aria-label="Search" data-width="250">
            <button class="btn" type="submit"><i class="fas fa-search"></i></button>
            <div class="search-backdrop"></div>
            <div class="search-result">
              <div class="search-header">
                Histories
              </div>
              <div class="search-item">
                <a href="#">How to hack NASA using CSS</a>
                <a href="#" class="search-close"><i class="fas fa-times"></i></a>
              </div>
              <div class="search-item">
                <a href="#">Kodinger.com</a>
                <a href="#" class="search-close"><i class="fas fa-times"></i></a>
              </div>
              <div class="search-item">
                <a href="#">#Stisla</a>
                <a href="#" class="search-close"><i class="fas fa-times"></i></a>
              </div>
              <div class="search-header">
                Result
              </div>
              <div class="search-item">
                <a href="#">
                  <img class="mr-3 rounded" width="30" src="<?php echo base_url(); ?>assets/img/products/product-3-50.png" alt="product">
                  oPhone S9 Limited Edition
                </a>
              </div>
              <div class="search-item">
                <a href="#">
                  <img class="mr-3 rounded" width="30" src="<?php echo base_url(); ?>assets/img/products/product-2-50.png" alt="product">
                  Drone X2 New Gen-7
                </a>
              </div>
              <div class="search-item">
                <a href="#">
                  <img class="mr-3 rounded" width="30" src="<?php echo base_url(); ?>assets/img/products/product-1-50.png" alt="product">
                  Headphone Blitz
                </a>
              </div>
              <div class="search-header">
                Projects
              </div>
              <div class="search-item">
                <a href="#">
                  <div class="search-icon bg-danger text-white mr-3">
                    <i class="fas fa-code"></i>
                  </div>
                  Stisla Admin Template
                </a>
              </div>
              <div class="search-item">
                <a href="#">
                  <div class="search-icon bg-primary text-white mr-3">
                    <i class="fas fa-laptop"></i>
                  </div>
                  Create a new Homepage Design
                </a>
              </div>
            </div>
          </div> -->
        </form>
        <ul class="navbar-nav navbar-right">
          <?php if ($this->session->userdata('hakakses') === '1' || $this->session->userdata('hakakses') === '4') : ?>
            <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg <?= count($this->session->userdata('notif')) > 0 ? 'beep' : '' ?>"><i class="far fa-bell"></i></a>
              <div class="dropdown-menu dropdown-list dropdown-menu-right">
                <div class="dropdown-header">Notifikasi Validasi Progress Proyek
                  <div class="float-right">
                    <!-- <a href="#">Mark All As Read</a> -->
                  </div>
                </div>
                <div class="dropdown-list-content dropdown-list-icons">
                  <?php if (count($this->session->userdata('notif')) === 0) : ?>
                    <span style="padding: 15px">Tidak Ada Notifikasi</span>
                  <?php endif; ?>
                  <?php foreach ($this->session->userdata('notif') as $n) : ?>
                    <a href="<?= base_url('ProgressProyek_c/detailadmin/' . $n->id_progress) ?>" class="dropdown-item dropdown-item-unread">
                      <!-- <div class="dropdown-item-icon bg-success text-white">
                        <i class="fas fa-exclamation-triangle"></i>
                      </div> -->
                      <div class="dropdown-item-desc">
                        <?= $n->nama_konsumen . ' | ' . $n->no_surat_kontrak . " (" . $n->persentase . "%)" ?>
                        <!-- Robert Taufan Nur Rahman, ST, M.Si. | 003/VI/BPL/2021 (15%) -->
                        <div class="time text-primary"><?= $n->nama_jenis_proyek . ' | ' . $n->nama_kegiatan ?></div>
                      </div>
                    </a>
                  <?php endforeach; ?>
                </div>
                <div class="dropdown-footer text-center">
                  <!-- <a href="#">View All <i class="fas fa-chevron-right"></i></a> -->
                  &nbsp;
                </div>
              </div>
            </li>
          <?php endif; ?>
          <?php if ($this->session->userdata('hakakses') === '2') : ?>
            <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg <?= count($this->session->userdata('notif')) > 0 ? 'beep' : '' ?>"><i class="far fa-bell"></i></a>
              <div class="dropdown-menu dropdown-list dropdown-menu-right">
                <div class="dropdown-header">Notifikasi Progress Proyek
                  <div class="float-right">
                    <!-- <a href="#">Mark All As Read</a> -->
                  </div>
                </div>
                <div class="dropdown-list-content dropdown-list-icons">
                  <?php if (count($this->session->userdata('notif')) === 0) : ?>
                    <span style="padding: 15px">Tidak Ada Notifikasi</span>
                  <?php endif; ?>
                  <?php foreach ($this->session->userdata('notif') as $n) : ?>
                    <a href="<?= base_url('ProgressProyek_c/formUbah/' . $n->id_progress . '/' . true) ?>" class="dropdown-item dropdown-item-unread">
                      <!-- <div class="dropdown-item-icon bg-success text-white">
                        <i class="fas fa-exclamation-triangle"></i>
                      </div> -->
                      <div class="dropdown-item-desc">
                        <?= $n->nama_konsumen . ' | ' . $n->no_surat_kontrak . " (" . $n->persentase . "%)" ?>
                        <!-- Robert Taufan Nur Rahman, ST, M.Si. | 003/VI/BPL/2021 (15%) -->
                        <div class="time text-primary"><?= $n->nama_jenis_proyek . ' | ' . $n->nama_kegiatan . ' | ' ?><?= $n->validasi === '2' ? '<span class="text-danger">ditolak</span>' : '<span class="text-warning">diterima</span>' ?></div>
                      </div>
                    </a>
                  <?php endforeach; ?>
                </div>
                <div class="dropdown-footer text-center">
                  &nbsp;
                </div>
              </div>
            </li>
          <?php endif; ?>
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
              <img alt="image" src="<?php echo base_url(); ?>assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
              <div class="d-sm-none d-lg-inline-block">Hi, <?= $this->session->userdata('namauser') ?></div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <div class="dropdown-title">
                <?php
                function time_elapsed_string($datetime, $full = false)
                {
                  $now = new DateTime;
                  $ago = new DateTime($datetime);
                  $diff = $now->diff($ago);

                  $diff->w = floor($diff->d / 7);
                  $diff->d -= $diff->w * 7;

                  $string = array(
                    'y' => 'year',
                    'm' => 'month',
                    'w' => 'week',
                    'd' => 'day',
                    'h' => 'hour',
                    'i' => 'minute',
                    's' => 'second',
                  );
                  foreach ($string as $k => &$v) {
                    if ($diff->$k) {
                      $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                    } else {
                      unset($string[$k]);
                    }
                  }

                  if (!$full) $string = array_slice($string, 0, 1);
                  return $string ? implode(', ', $string) . ' ago' : 'just now';
                }
                echo time_elapsed_string($this->session->userdata('timeago')); ?>
              </div>
              <div class="dropdown-divider"></div>
              <a href="<?= base_url('Auth_c/logout') ?>" class="dropdown-item has-icon text-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
              </a>
            </div>
          </li>
        </ul>
      </nav>