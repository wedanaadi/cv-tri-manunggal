<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProgressProyek_c extends CI_Controller
{
  function __construct()
  {
    $this->CI = &get_instance();
    parent::__construct();
    if ($this->session->userdata('kodeuser') == null) {
      redirect('Auth_c/login');
    }
    $this->load->library('Datatables');
    $this->load->model('Progress_m');
  }

  public function index()
  {
    $this->progressView();
  }

  public function progressView()
  {
    $kepalaproyek = $this->session->userdata('kodeuser');
    $data['title'] = 'Progress Proyek';
    $checkProses = $this->db->query("SELECT * FROM t_progress_proyek WHERE kepala_proyek ='$kepalaproyek'")->num_rows();
    if ($checkProses > 0) {
      $detilorder = $this->db->query("SELECT * FROM t_order_proyek_detail opd
                                      INNER JOIN `t_jadwal_proyek` jd ON jd.`proyek_id` = opd.`order_proyek_id`
                                      WHERE kepala_proyek = '$kepalaproyek' AND jd.`is_aktif` = '1'")->result();
      $berjalan = 0;
      $selesai = 0;
      foreach ($detilorder as $v) {
        $progress = $this->db->query("SELECT * FROM t_progress_proyek WHERE jenis_proyek ='$v->jenis_proyek' AND proyek_id ='$v->order_proyek_id' AND status='1'");
        if ($progress->num_rows() > 0) {
          if ($progress->row()->status === '1') {
            $selesai += 1;
          } else {
            $berjalan += 1;
          }
        } else {
          $berjalan += 1;
        }
      }
      $data['cB'] = $berjalan;
      $data['cS'] = $selesai;
    } else {
      $data['cB'] = $this->db->query("SELECT COUNT(`jenis_proyek`) AS 'c' FROM `t_order_proyek` top
                                      INNER JOIN `t_order_proyek_detail` topd ON topd.`order_proyek_id` = top.`id_proyek`
                                      INNER JOIN `t_jadwal_proyek` jd ON jd.`proyek_id` = top.`id_proyek`
                                      WHERE `kepala_proyek` = '$kepalaproyek' AND jd.`is_aktif` = '1'")->row()->c;
      $data['cS'] = 0;
    }
    $this->load->view('progress/data_v', $data);
  }

  public function ignited_data()
  {
    $id = $this->session->userdata('kodeuser');
    header('Content-Type: application/json');
    echo $this->Progress_m->order_ignited($id);
  }

  public function ignited_admin()
  {
    header('Content-Type: application/json');
    echo $this->Progress_m->order_ignited_foradmin();
  }

  public function detail($id)
  {
    $data['title'] = 'Progress Proyek Detail';
    $data['data'] = $this->Progress_m->getProyek($id);

    $kepalaproyek = $this->session->userdata('kodeuser');

    $checkProses = $this->db->query("SELECT * FROM t_progress_proyek WHERE kepala_proyek ='$kepalaproyek' AND proyek_id ='$id'")->num_rows();
    if ($checkProses > 0) {
      $sqlOrder = " SELECT `order_proyek_id`, `jenis_proyek`, `nama_jenis_proyek`, 
                    DATEDIFF(tanggal_selesai,tanggal_mulai) AS 'durasi', opd.`vol`
                    FROM t_order_proyek_detail opd
                    INNER JOIN `t_order_proyek`op ON op.`id_proyek` = opd.`order_proyek_id`
                    INNER JOIN `t_jadwal_proyek` jp ON jp.`proyek_id` = op.`id_proyek`
                    INNER JOIN `m_jenis_proyek` jn ON jn.`id_jenis_proyek` = opd.`jenis_proyek`
                    WHERE kepala_proyek ='$kepalaproyek' AND id_proyek = '$id' AND jp.`is_aktif` = '1'";
      $detilOrder = $this->db->query($sqlOrder)->result();
      foreach ($detilOrder as $order) {
        $executeSql = $this->db->query("SELECT * FROM t_progress_proyek WHERE proyek_id='$order->order_proyek_id' AND jenis_proyek='$order->jenis_proyek'");
        $sttProyeksql = $this->db->query("SELECT * FROM t_progress_proyek WHERE proyek_id='$order->order_proyek_id' AND jenis_proyek='$order->jenis_proyek' AND status='1' AND validasi != '2'");
        $validasiProyeksql = $this->db->query("SELECT * FROM t_progress_proyek WHERE proyek_id='$order->order_proyek_id' AND jenis_proyek='$order->jenis_proyek' AND status='1' AND validasi='1'");
        $checkProses = $executeSql->num_rows();
        if ($checkProses > 0) {
          $findProses = $executeSql->row();
          $sqlpersentase = "SELECT * FROM t_progress_proyek WHERE proyek_id='$order->order_proyek_id' AND jenis_proyek='$order->jenis_proyek' AND validasi='1' ORDER BY `tanggal` DESC";
          $dataprogress  = $this->db->query($sqlpersentase)->result();
          $persentase = 0;
          for ($i = 0; $i < count($dataprogress); $i++) {
            $index = count($dataprogress) - 1;
            if ($i ===  $index) {
              $persentase += $dataprogress[$i]->persentase;
            } else {
              $persentase += ($dataprogress[$i]->persentase - $dataprogress[$i + 1]->persentase);
            }
          }
          $a[] = (object)[
            'id_proyek' => $findProses->proyek_id,
            'jenis_proyek' => $findProses->jenis_proyek,
            'durasi' => $order->durasi,
            'nama_jenis_proyek' => $order->nama_jenis_proyek,
            'progress' => $persentase,
            'status' => $sttProyeksql->num_rows() > 0 ? 1 : 0,
            'validasi' => $validasiProyeksql->num_rows() > 0 ? 1 : 0,
            'vol' => $order->vol,
          ];
        } else {
          $a[] = (object)[
            'id_proyek' => $order->order_proyek_id,
            'jenis_proyek' => $order->jenis_proyek,
            'durasi' => $order->durasi,
            'nama_jenis_proyek' => $order->nama_jenis_proyek,
            'validasi' => '0',
            'progress' => 0,
            'status' => 0,
            'vol' => $order->vol,
          ];
        }
      }
      $data['array'] = $a;
    } else {
      $sql = "SELECT id_proyek, `nama_jenis_proyek`, DATEDIFF(tanggal_selesai,tanggal_mulai) AS 'durasi',
      0 AS 'validasi', 0 as progress, opd.`jenis_proyek`, 0 AS 'status', opd.`vol`,
      (SELECT total FROM `t_boq` WHERE `nama_kegiatan`=op.`id_proyek` AND `jenis_proyek`=opd.`jenis_proyek`) AS 'boq'
      FROM `t_order_proyek` op
      INNER JOIN `t_order_proyek_detail` opd ON opd.`order_proyek_id` = op.`id_proyek`
      INNER JOIN `m_jenis_proyek` jp ON jp.`id_jenis_proyek` = opd.`jenis_proyek`
      INNER JOIN `t_jadwal_proyek` jd ON jd.`proyek_id` = op.`id_proyek`
      -- LEFT JOIN `t_progress_proyek` pp ON pp.`proyek_id` = op.`id_proyek`
      WHERE opd.`kepala_proyek` = '$kepalaproyek' AND id_proyek = '$id' AND jd.`is_aktif` ='1'";
      $data['array'] = $this->db->query($sql)->result();
    }
    $sql = "SELECT pp.`id_progress`, op.`nama_konsumen`, op.`no_surat_kontrak`, jn.`nama_jenis_proyek`, 
            pp.`persentase`, pp.`validasi`, opd.`order_proyek_id`,opd.`jenis_proyek`
            FROM `t_progress_proyek` pp
            INNER JOIN `t_order_proyek` op ON op.`id_proyek` = pp.`proyek_id`
            INNER JOIN `t_order_proyek_detail` opd ON opd.`order_proyek_id` = op.`id_proyek`
            INNER JOIN `m_jenis_proyek` jn ON jn.`id_jenis_proyek` = pp.`jenis_proyek`
            WHERE pp.`validasi` IN('1','2') AND op.`status` = '0' AND opd.`kepala_proyek` = '$kepalaproyek'
            GROUP BY pp.`id_progress`
            ORDER BY pp.`id_progress` ASC, pp.`tanggal` DESC";
    $this->session->set_userdata('notif', $this->db->query($sql)->result());
    $this->load->view('progress/detail_v', $data);
  }

  public function detailforadmin($id)
  {
    $data['title'] = 'Progress Proyek Detail';
    $data['data'] = $this->Progress_m->getProyek($id);

    $checkProses = $this->db->query("SELECT * FROM t_progress_proyek WHERE proyek_id ='$id'")->num_rows();
    if ($checkProses > 0) {
      $sqlOrder = " SELECT `order_proyek_id`, `jenis_proyek`, `nama_jenis_proyek`, 
                    DATEDIFF(tanggal_selesai,tanggal_mulai) AS 'durasi', u.`nama_user`, `opd`.`vol`
                    FROM t_order_proyek_detail opd
                    INNER JOIN `t_order_proyek`op ON op.`id_proyek` = opd.`order_proyek_id`
                    INNER JOIN `t_jadwal_proyek` jp ON jp.`proyek_id` = op.`id_proyek`
                    INNER JOIN `m_jenis_proyek` jn ON jn.`id_jenis_proyek` = opd.`jenis_proyek`
                    INNER JOIN m_user u ON u.`id_user` = opd.`kepala_proyek`
                    WHERE id_proyek = '$id' AND jp.`is_aktif` ='1'";
      $detilOrder = $this->db->query($sqlOrder)->result();
      foreach ($detilOrder as $order) {
        $executeSql = $this->db->query("SELECT * FROM t_progress_proyek WHERE proyek_id='$order->order_proyek_id' AND jenis_proyek='$order->jenis_proyek'");
        $sttProyeksql = $this->db->query("SELECT * FROM t_progress_proyek WHERE proyek_id='$order->order_proyek_id' AND jenis_proyek='$order->jenis_proyek' AND status='1' AND validasi != '2'");
        $validasiProyeksql = $this->db->query("SELECT * FROM t_progress_proyek WHERE proyek_id='$order->order_proyek_id' AND jenis_proyek='$order->jenis_proyek' AND status='1' AND validasi='1'");
        $checkProses = $executeSql->num_rows();
        if ($checkProses > 0) {
          $findProses = $executeSql->row();
          $sqlpersentase = "SELECT * FROM t_progress_proyek WHERE proyek_id='$order->order_proyek_id' AND jenis_proyek='$order->jenis_proyek' AND validasi='1' ORDER BY `tanggal` DESC";
          $dataprogress  = $this->db->query($sqlpersentase)->result();
          $persentase = 0;
          for ($i = 0; $i < count($dataprogress); $i++) {
            $index = count($dataprogress) - 1;
            if ($i ===  $index) {
              $persentase += $dataprogress[$i]->persentase;
            } else {
              $persentase += ($dataprogress[$i]->persentase - $dataprogress[$i + 1]->persentase);
            }
          }
          $a[] = (object)[
            'id_proyek' => $findProses->proyek_id,
            'jenis_proyek' => $findProses->jenis_proyek,
            'durasi' => $order->durasi,
            'nama_jenis_proyek' => $order->nama_jenis_proyek,
            'nama_user' => $order->nama_user,
            'progress' => $persentase,
            'status' => $sttProyeksql->num_rows() > 0 ? 1 : 0,
            'validasi' => $validasiProyeksql->num_rows() > 0 ? 1 : 0,
            'vol' => $order->vol,
          ];
        } else {
          $a[] = (object)[
            'id_proyek' => $order->order_proyek_id,
            'jenis_proyek' => $order->jenis_proyek,
            'durasi' => $order->durasi,
            'nama_jenis_proyek' => $order->nama_jenis_proyek,
            'nama_user' => $order->nama_user,
            'validasi' => '0',
            'progress' => 0,
            'status' => 0,
            'vol' => $order->vol,
          ];
        }
      }
      $data['array'] = $a;
    } else {
      $sql = "SELECT id_proyek, `nama_jenis_proyek`, DATEDIFF(tanggal_selesai,tanggal_mulai) AS 'durasi',
      0 AS 'validasi', 0 as progress, opd.`jenis_proyek`, 0 AS 'status', u.`nama_user`, opd.`vol`
      FROM `t_order_proyek` op
      INNER JOIN `t_order_proyek_detail` opd ON opd.`order_proyek_id` = op.`id_proyek`
      INNER JOIN `m_jenis_proyek` jp ON jp.`id_jenis_proyek` = opd.`jenis_proyek`
      INNER JOIN `t_jadwal_proyek` jd ON jd.`proyek_id` = op.`id_proyek`
      INNER JOIN m_user u ON u.`id_user` = opd.`kepala_proyek`
      WHERE id_proyek = '$id' AND jd.`is_aktif` ='1'";
      $data['array'] = $this->db->query($sql)->result();
    }
    $this->load->view('progress/detail_admin_v', $data);
  }

  public function progresslist($proyek, $jenis)
  {

    $data['title'] = 'Jenis Proyek Detail';
    $data['data'] = $this->Progress_m->getProyek($proyek);
    $data['jenisproyek'] = $this->db->query("SELECT * FROM m_jenis_proyek WHERE id_jenis_proyek='$jenis'")->row();
    $data['jenisproyekstatus'] = $this->db->query("SELECT * FROM t_progress_proyek WHERE jenis_proyek='$jenis' AND proyek_id='$proyek' AND status='1' AND validasi='1'")->num_rows();
    $data['boq'] = $this->db->query("SELECT * FROM t_boq WHERE jenis_proyek='$jenis' AND nama_kegiatan='$proyek' AND is_aktif='1'")->row();
    $data['idproyek'] = json_encode($proyek);
    $data['idjenis'] = json_encode($jenis);
    $this->load->view('progress/progress_v', $data);
  }

  public function progresslistadmin($proyek, $jenis)
  {

    $data['title'] = 'Jenis Proyek Detail';
    $data['data'] = $this->Progress_m->getProyek($proyek);
    $data['jenisproyek'] = $this->db->query("SELECT * FROM m_jenis_proyek WHERE id_jenis_proyek='$jenis'")->row();
    $data['jenisproyekstatus'] = $this->db->query("SELECT * FROM t_progress_proyek WHERE jenis_proyek='$jenis' AND proyek_id='$proyek' AND status='1' AND validasi='1'")->num_rows();
    $data['idproyek'] = json_encode($proyek);
    $data['idjenis'] = json_encode($jenis);
    $this->load->view('progress/progress_admin_v', $data);
  }

  public function updateKegiatan($proyek, $jenis, $kegiatan)
  {
    $data['title'] = "Progress Proyek";
    $sqldata =  "SELECT jpd.*, jp.`nama_jenis_proyek`, TRIM(jpd.`vol`)+0 AS 'voljadwal',
                dk.`nama_kegiatan`,
                (
                  SELECT vol FROM `m_jenis_proyek_detail` jnpd 
                  WHERE jnpd.`kegiatan_id` = jpd.`kegiatan_id` 
                  AND jnpd.`jenis_proyek_id`=jpd.`jenis_proyek_id`
                ) AS 'volkegiatan',
                (
                  SELECT vol FROM `t_order_proyek_detail` topd 
                  WHERE `jenis_proyek` = jpd.`jenis_proyek_id`
                  AND `order_proyek_id` = jpd.`proyek_id`
                ) AS 'volorder', 
                (
                  SELECT sat FROM `t_order_proyek_detail` topd 
                  WHERE `jenis_proyek` = jpd.`jenis_proyek_id`
                  AND `order_proyek_id` = jpd.`proyek_id`
                ) AS 'satjadwal', 
                op.`nama_konsumen`, op.`no_surat_kontrak`, op.`nospmk`
                FROM `t_jadwal_proyek_detail` jpd
                INNER JOIN `m_jenis_proyek` jp ON jp.`id_jenis_proyek` = jpd.`jenis_proyek_id`
                INNER JOIN m_data_kegiatan dk ON dk.`id_master_kegiatan` = jpd.`kegiatan_id`
                INNER JOIN `t_order_proyek` op ON op.`id_proyek` = jpd.`proyek_id`
                WHERE jpd.`kegiatan_id` = '$kegiatan'
                AND jpd.`proyek_id` = '$proyek'
                AND jpd.`jenis_proyek_id` = '$jenis'";
    $data['data'] = $this->db->query($sqldata)->row();
    $data['boq'] = $this->db->query("SELECT * FROM t_boq WHERE jenis_proyek='$jenis' AND nama_kegiatan='$proyek' AND is_aktif='1'")->row();
    $data['jenisproyek'] = $this->db->query("SELECT * FROM m_jenis_proyek WHERE id_jenis_proyek='$jenis'")->row();
    $data['jenisproyekstatus'] = $this->db->query("SELECT * FROM t_progress_proyek WHERE jenis_proyek='$jenis' AND proyek_id='$proyek' AND status='1' AND validasi='1'")->num_rows();
    $data['idkegiatan'] = $kegiatan;
    $this->load->view('progress/progress_kegiatan_v', $data);
  }

  public function formAdd($proyek, $jn, $kegiatan)
  {
    $kepalaproyek = $this->session->userdata('kodeuser');
    $data['isSave'] = json_encode('save');
    $sql = "SELECT id_proyek, `nama_jenis_proyek`, `nama_proyek_pekerjaan`,
              `tanggal_mulai`, `tanggal_selesai`, opd.`vol`,opd.`sat`, 0 as 'status', 
              0 as 'persentase', id_jenis_proyek
              FROM `t_order_proyek` op
              INNER JOIN `t_order_proyek_detail` opd ON opd.`order_proyek_id` = op.`id_proyek`
              INNER JOIN `m_jenis_proyek` jp ON jp.`id_jenis_proyek` = opd.`jenis_proyek`
              INNER JOIN `t_jadwal_proyek` jd ON jd.`proyek_id` = op.`id_proyek`
              LEFT JOIN `t_progress_proyek` pp ON pp.`proyek_id` = op.`id_proyek`
              WHERE opd.`kepala_proyek` = '$kepalaproyek' AND opd.`jenis_proyek` = '$jn' AND jd.`is_aktif` = '1'
              AND opd.`order_proyek_id` = '$proyek'";
    $data['edit'] = json_encode([]);
    $data['loadphoto'] = json_encode([]);
    $data['title'] = 'Progress Proyek Detail';
    $data['data'] = $this->db->query($sql)->row();
    $cekLastPersentase = $this->db->query("SELECT * FROM `t_progress_proyek` WHERE proyek_id = '$proyek' AND `jenis_proyek` = '$jn' AND validasi != '2'
                                            ORDER BY tanggal DESC");
    $data['lastPersentase'] = json_encode($cekLastPersentase->num_rows() > 0 ? $cekLastPersentase->row()->persentase : 0);
    // print_r($sql);
    // exit();
    $this->load->view('progress/update_v', $data);
  }

  public function formUbah($idprogress)
  {
    $kepalaproyek = $this->session->userdata('kodeuser');
    $data['isSave'] = json_encode('update');
    $sql = "SELECT id_proyek, `nama_jenis_proyek`, `nama_proyek_pekerjaan`,
              `tanggal_mulai`, `tanggal_selesai`, opd.`vol`,opd.`sat`,pp.status, 
              persentase as 'persentase', id_jenis_proyek
              FROM `t_order_proyek` op
              INNER JOIN `t_order_proyek_detail` opd ON opd.`order_proyek_id` = op.`id_proyek`
              INNER JOIN `m_jenis_proyek` jp ON jp.`id_jenis_proyek` = opd.`jenis_proyek`
              INNER JOIN `t_jadwal_proyek` jd ON jd.`proyek_id` = op.`id_proyek`
              LEFT JOIN `t_progress_proyek` pp ON pp.`proyek_id` = op.`id_proyek`
              WHERE opd.`kepala_proyek` = '$kepalaproyek' AND pp.`id_progress` = '$idprogress' AND jd.`is_aktif` = '1'";

    $sqledit = "SELECT * FROM t_progress_proyek WHERE id_progress='$idprogress'";
    $progressFind = $this->db->query($sqledit)->row();
    $data['edit'] = json_encode($progressFind);
    $idprogress = $progressFind->id_progress;
    $data['loadphoto'] = json_encode($this->db->query("SELECT * FROM t_progress_gambar WHERE progress_id = '$idprogress'")->result());
    $data['title'] = 'Progress Proyek Detail';
    $data['data'] = $this->db->query($sql)->row();
    $proyekid = $data['data']->id_proyek;
    $jnid = $data['data']->id_jenis_proyek;
    $cekLastPersentase = $this->db->query("SELECT * FROM `t_progress_proyek` WHERE proyek_id = '$proyekid' AND `jenis_proyek` = '$jnid' AND validasi != '2'
                                            ORDER BY tanggal DESC");
    $data['lastPersentase'] = json_encode($cekLastPersentase->num_rows() > 0 ? $cekLastPersentase->row()->persentase : 0);
    $this->load->view('progress/update_v', $data);
  }

  public function update($proyek, $jn)
  {
    $kepalaproyek = $this->session->userdata('kodeuser');
    $checkProses = $this->db->query("SELECT * FROM t_progress_proyek WHERE proyek_id='$proyek' AND jenis_proyek='$jn'")->num_rows();
    if ($checkProses > 0) {
      $data['isSave'] = json_encode('update');
      $sql = "SELECT id_proyek, `nama_jenis_proyek`, `nama_proyek_pekerjaan`,
              `tanggal_mulai`, `tanggal_selesai`, opd.`vol`,opd.`sat`,pp.status, 
              persentase as 'persentase', id_jenis_proyek
              FROM `t_order_proyek` op
              INNER JOIN `t_order_proyek_detail` opd ON opd.`order_proyek_id` = op.`id_proyek`
              INNER JOIN `m_jenis_proyek` jp ON jp.`id_jenis_proyek` = opd.`jenis_proyek`
              INNER JOIN `t_jadwal_proyek` jd ON jd.`proyek_id` = op.`id_proyek`
              LEFT JOIN `t_progress_proyek` pp ON pp.`proyek_id` = op.`id_proyek`
              WHERE opd.`kepala_proyek` = '$kepalaproyek' AND opd.`jenis_proyek` = '$jn' 
              AND opd.`order_proyek_id` = '$proyek'";

      $sqledit = "SELECT * FROM t_progress_proyek WHERE jenis_proyek = '$jn' 
              AND proyek_id = '$proyek'";
      $progressFind = $this->db->query($sqledit)->row();
      $data['edit'] = json_encode($progressFind);
      $idprogress = $progressFind->id_progress;
      $data['loadphoto'] = json_encode($this->db->query("SELECT * FROM t_progress_gambar WHERE progress_id = '$idprogress'")->result());
    } else {
      $data['isSave'] = json_encode('save');
      $sql = "SELECT id_proyek, `nama_jenis_proyek`, `nama_proyek_pekerjaan`,
              `tanggal_mulai`, `tanggal_selesai`, opd.`vol`,opd.`sat`, 0 as 'status', 
              0 as 'persentase', id_jenis_proyek
              FROM `t_order_proyek` op
              INNER JOIN `t_order_proyek_detail` opd ON opd.`order_proyek_id` = op.`id_proyek`
              INNER JOIN `m_jenis_proyek` jp ON jp.`id_jenis_proyek` = opd.`jenis_proyek`
              INNER JOIN `t_jadwal_proyek` jd ON jd.`proyek_id` = op.`id_proyek`
              LEFT JOIN `t_progress_proyek` pp ON pp.`proyek_id` = op.`id_proyek`
              WHERE opd.`kepala_proyek` = '$kepalaproyek' AND opd.`jenis_proyek` = '$jn' 
              AND opd.`order_proyek_id` = '$proyek'";
      $data['edit'] = json_encode([]);
      $data['loadphoto'] = json_encode([]);
    }
    $data['title'] = 'Progress Proyek Detail';
    $data['data'] = $this->db->query($sql)->row();
    $this->load->view('progress/update_v', $data);
  }

  public function create()
  {
    $kepalaproyek = $this->session->userdata('kodeuser');
    $data = [
      'id_progress' => uniqid(),
      'proyek_id' => $this->input->post('proyekid'),
      'jenis_proyek' => $this->input->post('jpid'),
      'persentase' => $this->input->post('persentase'),
      'status' => (int)$this->input->post('persentase') === 100 ? '1' : '0',
      'lokasi' => $this->input->post('lokasi'),
      'ket' => $this->input->post('ket'),
      'kepala_proyek' => $kepalaproyek,
      'tanggal' => date('Y-m-d H:i:s'),
      'validasi_ket' => '-'
    ];
    $dataPhoto = [];
    $count = count($_FILES['foto']['name']);
    $config['upload_path'] = 'assets/img/foto/';
    $config['allowed_types'] = 'jpg|jpeg|png|gif';
    $config['max_size'] = 1024;
    // $config['overwrite'] = true;
    for ($i = 0; $i < $count; $i++) {
      if (!empty($_FILES['foto']['name'][$i])) {
        $_FILES['file']['name'] = $_FILES['foto']['name'][$i];
        $_FILES['file']['type'] = $_FILES['foto']['type'][$i];
        $_FILES['file']['tmp_name'] = $_FILES['foto']['tmp_name'][$i];
        $_FILES['file']['error'] = $_FILES['foto']['error'][$i];
        $_FILES['file']['size'] = $_FILES['foto']['size'][$i];

        $config['file_name'] = 'proyek_' . $this->input->post('proyekid') . '_jp_' . $this->input->post('jpid') . '_' . uniqid();

        $this->load->library('upload', $config);
        if ($this->upload->do_upload('file')) {
          $uploadData = $this->upload->data();
          $uploadData = $this->upload->data();
          $filename = $uploadData['file_name'];

          $dataPhoto[] = [
            'id' => uniqid(),
            'progress_id' =>  $data['id_progress'],
            'photo_name' => $filename
          ];
        }
      }
    }
    $this->db->trans_start();
    $this->db->insert('t_progress_proyek', $data);
    if (count($dataPhoto) > 0) {
      $this->db->insert_batch('t_progress_gambar', $dataPhoto);
    }
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      echo json_encode(['status' => 'gagal']);
    } else {
      echo json_encode(['status' => 'sukses']);
    }
  }

  public function deletephoto($name)
  {
    $path = 'assets/img/foto/' . $name;
    unlink($path);
    $this->db->query("DELETE FROM t_progress_gambar WHERE photo_name='$name'");
    echo json_encode(['status' => 'sukses']);
  }

  public function updateproses($id)
  {
    $kepalaproyek = $this->session->userdata('kodeuser');
    $data = [
      'proyek_id' => $this->input->post('proyekid'),
      'jenis_proyek' => $this->input->post('jpid'),
      'persentase' => $this->input->post('persentase'),
      'status' => $this->input->post('status'),
      'lokasi' => $this->input->post('lokasi'),
      'ket' => $this->input->post('ket'),
      'kepala_proyek' => $kepalaproyek,
      'tanggal' => date('Y-m-d H:i:s'),
      'validasi' => 0,
      'validasi_ket' => '-'
    ];

    $dataPhoto = [];
    $count = count($_FILES['foto']['name']);
    $config['upload_path'] = 'assets/img/foto/';
    $config['allowed_types'] = 'jpg|jpeg|png|gif';
    $config['max_size'] = 1024;
    // $config['overwrite'] = true;
    for ($i = 0; $i < $count; $i++) {
      if (!empty($_FILES['foto']['name'][$i])) {
        $_FILES['file']['name'] = $_FILES['foto']['name'][$i];
        $_FILES['file']['type'] = $_FILES['foto']['type'][$i];
        $_FILES['file']['tmp_name'] = $_FILES['foto']['tmp_name'][$i];
        $_FILES['file']['error'] = $_FILES['foto']['error'][$i];
        $_FILES['file']['size'] = $_FILES['foto']['size'][$i];

        $config['file_name'] = 'proyek_' . $this->input->post('proyekid') . '_jp_' . $this->input->post('jpid') . '_' . uniqid();

        $this->load->library('upload', $config);
        if ($this->upload->do_upload('file')) {
          $uploadData = $this->upload->data();
          $filename = $uploadData['file_name'];

          $dataPhoto[] = [
            'id' => uniqid(),
            'progress_id' =>  $id,
            'photo_name' => $filename
          ];
        }
      }
    }
    $this->db->trans_start();
    $this->db->where('id_progress', $id);
    $this->db->update('t_progress_proyek', $data);

    $this->db->where('id_proyek', $this->input->post('proyekid'));
    $this->db->update('t_order_proyek', ['status' => 0]);
    if (count($dataPhoto) > 0) {
      $this->db->insert_batch('t_progress_gambar', $dataPhoto);
    }
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      echo json_encode(['status' => 'gagal']);
    } else {
      echo json_encode(['status' => 'sukses']);
    }
  }

  public function getFoto()
  {
    $pro = $this->input->get('idprogress');
    $progress = $this->db->query("SELECT * FROM `t_progress_gambar` WHERE `progress_id` = '$pro'");
    if ($progress->num_rows() > 0) {
      // $id = $progress->row()->id_progress;
      $data['photo'] = $this->db->query("SELECT * FROM t_progress_gambar WHERE progress_id='$pro'")->result();
      echo json_encode(['status' => 'ada', 'data' => $this->load->view('progress/photo_v', $data, true)]);
    } else {
      echo json_encode(['status' => 'kosong']);
    }
  }

  public function progressProyekAdmin()
  {
    $data['title'] = 'Progress Proyek';
    $this->load->view('progress/admin_v', $data);
  }

  public function progressProyekDirektur()
  {
    $data['title'] = 'Progress Proyek';
    $this->load->view('progress/admin_v', $data);
  }

  public function ignited_progressadmin()
  {
    header('Content-Type: application/json');
    echo $this->Progress_m->progressadmin_ignited();
  }

  public function ignited_progress()
  {
    $idproyek = $this->input->post('proyek');
    $idjenis = $this->input->post('jenis');
    header('Content-Type: application/json');
    echo $this->Progress_m->progressjn_ignited($idproyek, $idjenis, $this->session->userdata('kodeuser'));
  }

  public function ignited_jenisproyek_kegiatan()
  {
    $idproyek = $this->input->post('proyek');
    $idjenis = $this->input->post('jenis');
    header('Content-Type: application/json');
    echo $this->Progress_m->jenisproyek_kegiatan_ignited($idproyek, $idjenis);
  }

  public function ignited_progress_admin()
  {
    $idproyek = $this->input->post('proyek');
    $idjenis = $this->input->post('jenis');
    header('Content-Type: application/json');
    echo $this->Progress_m->progressjn_ignited2($idproyek, $idjenis);
  }

  public function getDetailJP()
  {
    $id = $this->input->get('konsumen');
    $data = $this->Progress_m->getDetail($id);
    echo json_encode($data);
  }

  public function detailadmin($id)
  {
    $data['title'] = 'Progress Proyek';
    $sql = "SELECT pp.*, op.`nama_konsumen`, jn.`nama_jenis_proyek`, opd.`vol`, opd.`sat`,
            u.`nama_user`, nospmk, pp.`status`, pp.`persentase`, pp.`tanggal`, pp.`validasi`
            FROM `t_progress_proyek` pp
            INNER JOIN `t_order_proyek`op ON op.`id_proyek` = pp.`proyek_id`
            INNER JOIN `t_jadwal_proyek` jp ON jp.`proyek_id` = op.`id_proyek`
            INNER JOIN `t_order_proyek_detail` opd ON opd.`jenis_proyek` = pp.`jenis_proyek`
            INNER JOIN `m_jenis_proyek` jn ON jn.`id_jenis_proyek` = pp.`jenis_proyek`
            INNER JOIN m_user u ON u.`id_user` = pp.`kepala_proyek`
            WHERE `id_progress` = '$id'";
    $data['progress'] = $this->db->query($sql)->row();
    $data['photo'] = $this->db->query("SELECT * FROM t_progress_gambar WHERE progress_id='$id'")->result();
    $sql = "SELECT pp.`id_progress`, op.`nama_konsumen`, op.`no_surat_kontrak`, jn.`nama_jenis_proyek`, 
            pp.`persentase`, pp.`validasi`, opd.`order_proyek_id`,opd.`jenis_proyek`
            FROM `t_progress_proyek` pp
            INNER JOIN `t_order_proyek` op ON op.`id_proyek` = pp.`proyek_id`
            INNER JOIN `t_order_proyek_detail` opd ON opd.`order_proyek_id` = op.`id_proyek`
            INNER JOIN `m_jenis_proyek` jn ON jn.`id_jenis_proyek` = pp.`jenis_proyek`
            WHERE pp.`validasi` = '0'
            GROUP BY pp.`id_progress`
            ORDER BY pp.`id_progress` ASC, pp.`tanggal` DESC";
    $this->session->set_userdata('notif', $this->db->query($sql)->result());
    $this->load->view('progress/admindetail_v', $data);
  }

  public function updateValidasi($id, $index)
  {
    $data = [
      'validasi' => $index,
      'validasi_ket' => $this->input->post('alasan')
    ];

    $this->db->trans_start();
    $this->db->where('id_progress', $id);
    $this->db->update('t_progress_proyek', $data);
    $this->db->trans_complete();

    if ($this->db->trans_status() === TRUE) {
      $idProyek = $this->db->query("SELECT * FROM t_progress_proyek WHERE id_progress = '$id'")->row()->proyek_id;
      $sql = "SELECT * FROM t_order_proyek_detail WHERE order_proyek_id ='$idProyek'";
      $detilorder = $this->db->query($sql)->result();
      foreach ($detilorder as $do) {
        $executeSql = $this->db->query("SELECT * FROM t_progress_proyek WHERE proyek_id='$do->order_proyek_id' AND jenis_proyek='$do->jenis_proyek' ORDER BY tanggal ASC");
        if ($executeSql->num_rows() > 0) {
          foreach ($executeSql->result() as $v) {
            if ($v->status === '1') {
              $data = ['status' => 1];
            } else {
              $data = ['status' => 0];
            }
          }
        } else {
          $data = ['status' => 0];
          break;
        }
      }
      $this->db->where('id_proyek', $idProyek);
      $this->db->update('t_order_proyek', $data);
      echo json_encode(['status' => 'sukses']);
    }
  }
}
