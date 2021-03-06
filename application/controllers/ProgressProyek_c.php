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
    $cSelesai = 0;
    $cBerjalan = 0;
    $sqlProyek = "SELECT top.*, topd.`jenis_proyek`,topd.`kepala_proyek` 
                  FROM `t_order_proyek` top
                  INNER JOIN `t_order_proyek_detail` topd ON topd.`order_proyek_id` = top.`id_proyek`
                  INNER JOIN `t_jadwal_proyek` tjp ON tjp.`proyek_id` = top.`id_proyek`
                  INNER JOIN `t_boq` boq ON boq.`nama_satker` = top.`konsumen_id`
                  WHERE top.`is_aktif` = '1'
                  AND boq.`is_aktif` = '1'
                  AND tjp.`is_aktif` = '1'
                  AND topd.`kepala_proyek` = '$kepalaproyek'
                  GROUP BY top.`id_proyek`,topd.`jenis_proyek`";
    $dataProyek = $this->db->query($sqlProyek);
    if ($dataProyek->num_rows() > 0) {
      foreach ($dataProyek->result() as $v) {
        $sqlpersentase = "SELECT IFNULL(SUM(persentase),0) as 'totalPersentase' FROM t_progress_proyek WHERE proyek_id='$v->id_proyek' AND jenis_proyek='$v->jenis_proyek' AND validasi='1' ORDER BY `tanggal` DESC";
        $dataprogress  = $this->db->query($sqlpersentase)->row()->totalPersentase;
        $countkegiatan = $this->db->query("SELECT COUNT(`id_detail`) AS 'jumlahKegiatan' FROM `t_jadwal_proyek_detail` jpd
                              INNER JOIN `t_jadwal_proyek` jp ON jp.`id_jadwal` = jpd.`jadwal_id`
                              WHERE jpd.`proyek_id` = '$v->id_proyek'
                              AND jpd.`jenis_proyek_id` = '$v->jenis_proyek'")->row()->jumlahKegiatan;
        (int)$dataprogress === ((int)$countkegiatan * 100) ? $cSelesai++ : $cBerjalan++;
      }
    }
    $data['cB'] = $cBerjalan;
    $data['cS'] = $cSelesai;
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
        $kegiatancount = $this->db->query("SELECT COUNT(`id_detail`) AS 'c' FROM `t_jadwal_proyek_detail`
                        INNER JOIN `t_jadwal_proyek` ON `t_jadwal_proyek`.`id_jadwal` = `t_jadwal_proyek_detail`.`jadwal_id`
                        WHERE `t_jadwal_proyek_detail`.`proyek_id` = '$order->order_proyek_id'")->row()->c;
        $persentaseProgressJenis = $this->db->query("SELECT SUM(`persentase`) AS 'p' FROM `t_progress_proyek` tpp
                                    WHERE tpp.`proyek_id` = '$order->order_proyek_id'
                                    AND tpp.`jenis_proyek` = '$order->jenis_proyek'")->row()->p;
        $validasiProyeksql = $this->db->query("SELECT * FROM t_progress_proyek WHERE proyek_id='$order->order_proyek_id' AND jenis_proyek='$order->jenis_proyek' AND status='1' AND validasi='1'");
        $checkProses = $executeSql->num_rows();
        $boq = $this->db->query("SELECT * FROM t_boq WHERE nama_kegiatan='$order->order_proyek_id' AND jenis_proyek='$order->jenis_proyek' AND is_aktif='1'")->row();
        $realisasi = $this->db->query("SELECT IFNULL(SUM(total_pengeluaran),0) AS 'pengeluaran' 
                                        FROM t_pengeluaran_progress 
                                        INNER JOIN `t_progress_proyek` ON `t_progress_proyek`.`id_progress` = `t_pengeluaran_progress`.`progress_id`
                                        WHERE `t_pengeluaran_progress`.`proyek_id`='$order->order_proyek_id' 
                                        AND `t_pengeluaran_progress`.`jenis_proyek_id`='$order->jenis_proyek'
                                        AND `t_progress_proyek`.`validasi`")->row();
        if ($checkProses > 0) {
          $findProses = $executeSql->row();
          $sqlpersentase = "SELECT SUM(persentase) as 'totalPersentase' FROM t_progress_proyek WHERE proyek_id='$order->order_proyek_id' AND jenis_proyek='$order->jenis_proyek' AND validasi='1' ORDER BY `tanggal` DESC";
          $dataprogress  = $this->db->query($sqlpersentase)->row()->totalPersentase;
          $countkegiatan = $this->db->query("SELECT COUNT(`id_detail`) AS 'jumlahKegiatan' FROM `t_jadwal_proyek_detail` jpd
                            INNER JOIN `t_jadwal_proyek` jp ON jp.`id_jadwal` = jpd.`jadwal_id`
                            WHERE jpd.`proyek_id` = '$order->order_proyek_id'
                            AND jpd.`jenis_proyek_id` = '$order->jenis_proyek'")->row()->jumlahKegiatan;
          $persentase = round(($dataprogress / $countkegiatan), 2);
          $a[] = (object)[
            'id_proyek' => $findProses->proyek_id,
            'jenis_proyek' => $findProses->jenis_proyek,
            'durasi' => $order->durasi,
            'nama_jenis_proyek' => $order->nama_jenis_proyek,
            'progress' => $persentase,
            'status' => (int)$persentaseProgressJenis === ((int)$kegiatancount * 100) ? 1 : 0,
            'validasi' => $validasiProyeksql->num_rows() > 0 ? 1 : 0,
            'vol' => $order->vol,
            'boq' => $boq->total,
            'realisasi' => $realisasi->pengeluaran
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
            'boq' => $boq->total,
            'realisasi' => $realisasi->pengeluaran
          ];
        }
      }
      $data['array'] = $a;
    } else {
      $sqlarray = "SELECT id_proyek, `nama_jenis_proyek`, DATEDIFF(tanggal_selesai,tanggal_mulai) AS 'durasi',
      0 AS 'validasi', 0 as progress, opd.`jenis_proyek`, 0 AS 'status', opd.`vol`,
      (SELECT total FROM `t_boq` WHERE `nama_kegiatan`=op.`id_proyek` AND `jenis_proyek`=opd.`jenis_proyek`) AS 'boq',
      (
        SELECT IFNULL(SUM(`total_pengeluaran`),0) FROM `t_pengeluaran_progress`
        INNER JOIN `t_progress_proyek` ON `t_progress_proyek`.`id_progress` = `t_pengeluaran_progress`.`progress_id`
        WHERE `t_pengeluaran_progress`.`proyek_id` = op.`id_proyek`
        AND `t_pengeluaran_progress`.`jenis_proyek_id` = opd.`jenis_proyek`
        AND `t_progress_proyek`.`validasi` = '1'
      ) AS 'realisasi'
      FROM `t_order_proyek` op
      INNER JOIN `t_order_proyek_detail` opd ON opd.`order_proyek_id` = op.`id_proyek`
      INNER JOIN `m_jenis_proyek` jp ON jp.`id_jenis_proyek` = opd.`jenis_proyek`
      INNER JOIN `t_jadwal_proyek` jd ON jd.`proyek_id` = op.`id_proyek`
      -- LEFT JOIN `t_progress_proyek` pp ON pp.`proyek_id` = op.`id_proyek`
      WHERE opd.`kepala_proyek` = '$kepalaproyek' AND id_proyek = '$id' AND jd.`is_aktif` ='1'";
      $data['array'] = $this->db->query($sqlarray)->result();
    }
    $sql = "SELECT pp.`id_progress`, op.`nama_konsumen`, op.`no_surat_kontrak`, jn.`nama_jenis_proyek`, 
            pp.`persentase`, pp.`validasi`, opd.`order_proyek_id`,opd.`jenis_proyek`, k.`nama_kegiatan`,
            pp.`kegiatan_id`
            FROM `t_progress_proyek` pp
            INNER JOIN `t_order_proyek` op ON op.`id_proyek` = pp.`proyek_id`
            INNER JOIN `t_order_proyek_detail` opd ON opd.`order_proyek_id` = op.`id_proyek`
            INNER JOIN `m_jenis_proyek` jn ON jn.`id_jenis_proyek` = pp.`jenis_proyek`
            INNER JOIN `m_data_kegiatan` k ON k.`id_master_kegiatan` = pp.`kegiatan_id`
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
        $kegiatancount = $this->db->query("SELECT COUNT(`id_detail`) AS 'c' FROM `t_jadwal_proyek_detail`
                        INNER JOIN `t_jadwal_proyek` ON `t_jadwal_proyek`.`id_jadwal` = `t_jadwal_proyek_detail`.`jadwal_id`
                        WHERE `t_jadwal_proyek_detail`.`proyek_id` = '$order->order_proyek_id'")->row()->c;
        $persentaseProgressJenis = $this->db->query("SELECT SUM(`persentase`) AS 'p' FROM `t_progress_proyek` tpp
                                    WHERE tpp.`proyek_id` = '$order->order_proyek_id'
                                    AND tpp.`jenis_proyek` = '$order->jenis_proyek'")->row()->p;
        $validasiProyeksql = $this->db->query("SELECT * FROM t_progress_proyek WHERE proyek_id='$order->order_proyek_id' AND jenis_proyek='$order->jenis_proyek' AND status='1' AND validasi='1'");
        $checkProses = $executeSql->num_rows();
        $boq = $this->db->query("SELECT * FROM t_boq WHERE nama_kegiatan='$order->order_proyek_id' AND jenis_proyek='$order->jenis_proyek' AND is_aktif='1'")->row();
        $realisasi = $this->db->query("SELECT IFNULL(SUM(total_pengeluaran),0) AS 'pengeluaran' 
                                        FROM t_pengeluaran_progress 
                                        INNER JOIN `t_progress_proyek` ON `t_progress_proyek`.`id_progress` = `t_pengeluaran_progress`.`progress_id`
                                        WHERE `t_pengeluaran_progress`.`proyek_id`='$order->order_proyek_id' 
                                        AND `t_pengeluaran_progress`.`jenis_proyek_id`='$order->jenis_proyek'
                                        AND `t_progress_proyek`.`validasi`")->row();
        if ($checkProses > 0) {
          $findProses = $executeSql->row();
          $sqlpersentase = "SELECT SUM(persentase) AS p FROM t_progress_proyek WHERE proyek_id='$order->order_proyek_id' AND jenis_proyek='$order->jenis_proyek' AND validasi='1' ORDER BY `tanggal` DESC";
          $dataprogress  = $this->db->query($sqlpersentase)->row()->p;
          $countkegiatan = $this->db->query("SELECT COUNT(`id_detail`) AS 'jumlahKegiatan' FROM `t_jadwal_proyek_detail` jpd 
                            INNER JOIN `t_jadwal_proyek` jp ON jp.`id_jadwal` = jpd.`jadwal_id`
                            WHERE jpd.`proyek_id` = '$order->order_proyek_id'
                            AND jpd.`jenis_proyek_id` = '$order->jenis_proyek'")->row()->jumlahKegiatan;
          $persentase = round(($dataprogress / $countkegiatan), 2);
          $a[] = (object)[
            'id_proyek' => $findProses->proyek_id,
            'jenis_proyek' => $findProses->jenis_proyek,
            'durasi' => $order->durasi,
            'nama_jenis_proyek' => $order->nama_jenis_proyek,
            'nama_user' => $order->nama_user,
            'progress' => $persentase,
            'status' => (int)$persentaseProgressJenis === ((int)$kegiatancount * 100) ? 1 : 0,
            'validasi' => $validasiProyeksql->num_rows() > 0 ? 1 : 0,
            'vol' => $order->vol,
            'boq' => $boq->total,
            'realisasi' => $realisasi->pengeluaran
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
            'boq' => $boq->total,
            'realisasi' => $realisasi->pengeluaran
          ];
        }
      }
      $data['array'] = $a;
    } else {
      $sql = "SELECT id_proyek, `nama_jenis_proyek`, DATEDIFF(tanggal_selesai,tanggal_mulai) AS 'durasi',
      0 AS 'validasi', 0 as progress, opd.`jenis_proyek`, 0 AS 'status', opd.`vol`,
      (SELECT total FROM `t_boq` WHERE `nama_kegiatan`=op.`id_proyek` AND `jenis_proyek`=opd.`jenis_proyek`) AS 'boq',
      (
        SELECT IFNULL(SUM(`total_pengeluaran`),0) FROM `t_pengeluaran_progress`
        INNER JOIN `t_progress_proyek` ON `t_progress_proyek`.`id_progress` = `t_pengeluaran_progress`.`progress_id`
        WHERE `t_pengeluaran_progress`.`proyek_id` = op.`id_proyek`
        AND `t_pengeluaran_progress`.`jenis_proyek_id` = opd.`jenis_proyek`
        AND `t_progress_proyek`.`validasi` = '1'
      ) AS 'realisasi',
      (SELECT nama_user FROM m_user WHERE id_user=opd.`kepala_proyek`) AS 'nama_user'
      FROM `t_order_proyek` op
      INNER JOIN `t_order_proyek_detail` opd ON opd.`order_proyek_id` = op.`id_proyek`
      INNER JOIN `m_jenis_proyek` jp ON jp.`id_jenis_proyek` = opd.`jenis_proyek`
      INNER JOIN `t_jadwal_proyek` jd ON jd.`proyek_id` = op.`id_proyek`
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
    $sqlPersentaseJenisProyek = $this->db->query("SELECT IFNULL(SUM(`persentase`),0) AS 'persentase',
                                                  (
                                                    SELECT COUNT(`id_detail`) FROM `t_jadwal_proyek_detail` jpd
                                                    INNER JOIN `t_jadwal_proyek` jp ON jp.`id_jadwal` = jpd.`jadwal_id`
                                                    WHERE jpd.`proyek_id` = prop.`proyek_id`
                                                    AND jpd.`jenis_proyek_id` = prop.`jenis_proyek`
                                                  ) AS 'kegiatan'
                                                  FROM `t_progress_proyek` prop
                                                  WHERE prop.`proyek_id` = '$proyek'
                                                  AND prop.`jenis_proyek` = '$jenis'
                                                  AND prop.`validasi` = '1'")->row();
    if ($sqlPersentaseJenisProyek->kegiatan !== "0") {
      $hitung = $sqlPersentaseJenisProyek->persentase / $sqlPersentaseJenisProyek->kegiatan;
    } else {
      $hitung = 0;
    }
    $data['jenisproyekstatus'] = (float)$hitung < 100 ? 0 : 1;
    $data['persentaseJenis'] = $hitung;
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
    $sqlPersentaseJenisProyek = $this->db->query("SELECT IFNULL(SUM(`persentase`),0) AS 'persentase',
                                                  (
                                                    SELECT COUNT(`id_detail`) FROM `t_jadwal_proyek_detail` jpd
                                                    INNER JOIN `t_jadwal_proyek` jp ON jp.`id_jadwal` = jpd.`jadwal_id`
                                                    WHERE jpd.`proyek_id` = prop.`proyek_id`
                                                    AND jpd.`jenis_proyek_id` = prop.`jenis_proyek`
                                                  ) AS 'kegiatan'
                                                  FROM `t_progress_proyek` prop
                                                  WHERE prop.`proyek_id` = '$proyek'
                                                  AND prop.`jenis_proyek` = '$jenis'")->row();
    if ($sqlPersentaseJenisProyek->kegiatan !== "0") {
      $hitung = $sqlPersentaseJenisProyek->persentase / $sqlPersentaseJenisProyek->kegiatan;
    } else {
      $hitung = 0;
    }
    $data['jenisproyekstatus'] = (float)$hitung < 100 ? 0 : 1;
    $data['persentaseJenis'] = $hitung;
    $data['boq'] = $this->db->query("SELECT * FROM t_boq WHERE jenis_proyek='$jenis' AND nama_kegiatan='$proyek' AND is_aktif='1'")->row();
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
    // $data['jenisproyek'] = $this->db->query("SELECT * FROM m_jenis_proyek WHERE id_jenis_proyek='$jenis'")->row();
    // $data['jenisproyekstatus'] = $this->db->query("SELECT * FROM t_progress_proyek WHERE jenis_proyek='$jenis' AND proyek_id='$proyek' AND status='1' AND validasi='1'")->num_rows();
    $persentaseValid =  $this->db->query("SELECT IFNULL(SUM(`persentase`),0) AS 'persentase' FROM `t_progress_proyek`
                                          WHERE `jenis_proyek` = '$jenis'
                                          AND `proyek_id` = '$proyek'
                                          AND kegiatan_id = '$kegiatan'
                                          AND `validasi` = '1'")->row();
    $data['kegiatanstatus'] = (float)$persentaseValid->persentase < 100 ? 0 : 1;
    $data['idkegiatan'] = $kegiatan;
    if ($this->session->userdata('hakakses') === '2') {
      $this->load->view('progress/progress_kegiatan_v', $data);
    } else {
      $this->load->view('progress/validasi_progress_kegiatan_v', $data);
    }
  }

  public function formAdd($proyek, $jenis, $kegiatan)
  {
    $kepalaproyek = $this->session->userdata('kodeuser');
    $data['isSave'] = json_encode('save');
    $data['isNotif'] = json_encode('0');
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
                (
                  SELECT COUNT(`id_progress`) FROM `t_progress_proyek` 
                  WHERE `jenis_proyek` = jpd.`jenis_proyek_id`
                  AND `proyek_id` = jpd.`proyek_id`
                  AND `kegiatan_id` = jpd.`kegiatan_id` 
                  AND `kepala_proyek` = '$kepalaproyek'
                ) AS 'progresscount',
                op.`nama_konsumen`, op.`no_surat_kontrak`, op.`nospmk`
                FROM `t_jadwal_proyek_detail` jpd
                INNER JOIN `m_jenis_proyek` jp ON jp.`id_jenis_proyek` = jpd.`jenis_proyek_id`
                INNER JOIN m_data_kegiatan dk ON dk.`id_master_kegiatan` = jpd.`kegiatan_id`
                INNER JOIN `t_order_proyek` op ON op.`id_proyek` = jpd.`proyek_id`
                WHERE jpd.`kegiatan_id` = '$kegiatan'
                AND jpd.`proyek_id` = '$proyek'
                AND jpd.`jenis_proyek_id` = '$jenis'";
    $data['boq'] = $this->db->query("SELECT * FROM t_boq WHERE jenis_proyek='$jenis' AND nama_kegiatan='$proyek' AND is_aktif='1'")->row();
    $data['edit'] = json_encode([]);
    $data['pengeluaran'] = json_encode([]);
    $data['loadphoto'] = json_encode([]);
    $data['title'] = 'Progress Proyek Detail';
    $data['data'] = $this->db->query($sqldata)->row();
    $sqlBUAPengeluaran = "SELECT kd.`id`,up.`nama_barang_upah`, kd.`koef`,kd.`satuan`, kd.`harga_upah_bahan_alat`, kd.`harga`
                          FROM m_data_kegiatan k
                          INNER JOIN `m_kegiatan_detail` kd ON kd.`kegiatan_id` = k.`id_master_kegiatan`
                          INNER JOIN `m_upah_barang` up ON up.`id_barang` = kd.`upah_bahan_alat`
                          WHERE k.`id_master_kegiatan` = '$kegiatan'
                          ORDER BY kd.`id`";
    $data['BUA'] = $this->db->query($sqlBUAPengeluaran)->result();
    $lastpersentase = $this->db->query("SELECT IFNULL(SUM(persentase),0) AS 'persentase' FROM `t_progress_proyek` 
                                        WHERE `proyek_id` = '$proyek' 
                                        AND `jenis_proyek` = '$jenis' 
                                        AND `kegiatan_id` = '$kegiatan'
                                        AND validasi != '2'
                                        ORDER BY tanggal DESC")->row()->persentase;
    $data['lastPersentase'] = json_encode($lastpersentase);
    $this->load->view('progress/update_v', $data);
  }

  public function formUbah($idprogress, $notif = false)
  {
    if ($notif) {
      $data['isNotif'] = json_encode('1');
    } else {
      $data['isNotif'] = json_encode('0');
    }
    $kepalaproyek = $this->session->userdata('kodeuser');
    $data['isSave'] = json_encode('update');
    $sqledit = "SELECT * FROM t_progress_proyek WHERE id_progress='$idprogress'";
    $sqlpengeluaran = "SELECT * FROM t_pengeluaran_progress WHERE progress_id='$idprogress'";
    $progressFind = $this->db->query($sqledit)->row();
    $progressPengeluaran = $this->db->query($sqlpengeluaran)->result();
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
                (
                  SELECT COUNT(`id_progress`) FROM `t_progress_proyek` 
                  WHERE `jenis_proyek` = jpd.`jenis_proyek_id`
                  AND `proyek_id` = jpd.`proyek_id`
                  AND `kegiatan_id` = jpd.`kegiatan_id` 
                  AND `kepala_proyek` = '$kepalaproyek'
                ) AS 'progresscount',
                op.`nama_konsumen`, op.`no_surat_kontrak`, op.`nospmk`
                FROM `t_jadwal_proyek_detail` jpd
                INNER JOIN `m_jenis_proyek` jp ON jp.`id_jenis_proyek` = jpd.`jenis_proyek_id`
                INNER JOIN m_data_kegiatan dk ON dk.`id_master_kegiatan` = jpd.`kegiatan_id`
                INNER JOIN `t_order_proyek` op ON op.`id_proyek` = jpd.`proyek_id`
                WHERE jpd.`kegiatan_id` = '$progressFind->kegiatan_id'
                AND jpd.`proyek_id` = '$progressFind->proyek_id'
                AND jpd.`jenis_proyek_id` = '$progressFind->jenis_proyek'";
    $data['edit'] = json_encode($progressFind);
    $data['pengeluaran'] = json_encode($progressPengeluaran);
    $idprogress = $progressFind->id_progress;
    $data['loadphoto'] = json_encode($this->db->query("SELECT * FROM t_progress_gambar WHERE progress_id = '$idprogress'")->result());
    $data['title'] = 'Progress Proyek Detail';
    $data['data'] = $this->db->query($sqldata)->row();
    $kegiatan = $data['data']->kegiatan_id;
    $sqlBUAPengeluaran = "SELECT kd.`id`,up.`nama_barang_upah`, kd.`koef`,kd.`satuan`, kd.`harga_upah_bahan_alat`, kd.`harga`
                          FROM m_data_kegiatan k
                          INNER JOIN `m_kegiatan_detail` kd ON kd.`kegiatan_id` = k.`id_master_kegiatan`
                          INNER JOIN `m_upah_barang` up ON up.`id_barang` = kd.`upah_bahan_alat`
                          WHERE k.`id_master_kegiatan` = '$kegiatan'
                          ORDER BY kd.`id`";
    $bua = $this->db->query($sqlBUAPengeluaran)->result();
    $totalPengeluanTable = 0;
    foreach ($bua as $v) {
      $findPengeluaran = $this->db->query("SELECT * FROM t_pengeluaran_progress WHERE progress_id = '$idprogress' AND nama_pengeluaran='$v->id'");
      if ($findPengeluaran->num_rows() > 0) {
        $array[] = (object)[
          'id' => $v->id,
          'nama_barang_upah' => $v->nama_barang_upah,
          'koef' => $v->koef,
          'satuan' => $v->satuan,
          'vol' => $findPengeluaran->row()->vol,
          'harga' => $findPengeluaran->row()->harga_pengeluaran,
          'total' => $findPengeluaran->row()->total_pengeluaran,
        ];
        $totalPengeluanTable += $findPengeluaran->row()->total_pengeluaran;
      } else {
        $array[] = (object)[
          'id' => $v->id,
          'nama_barang_upah' => $v->nama_barang_upah,
          'koef' => $v->koef,
          'satuan' => $v->satuan,
          'vol' => 0,
          'harga' => $v->harga,
          'total' => 0,
        ];
      }
    }
    $data['TPT'] = $totalPengeluanTable;
    $data['BUA'] = $array;
    $data['boq'] = $this->db->query("SELECT * FROM t_boq WHERE jenis_proyek='$progressFind->jenis_proyek' AND nama_kegiatan='$progressFind->proyek_id' AND is_aktif='1'")->row();
    // $proyekid = $data['data']->proyek_id;
    // $jnid = $data['data']->id_jenis_proyek;
    // $kid = $data['data']->id_jenis_proyek;
    $lastpersentase = $this->db->query("SELECT IFNULL(SUM(persentase),0) AS 'persentase' FROM `t_progress_proyek` 
                                        WHERE `proyek_id` = '$progressFind->proyek_id' 
                                        AND `jenis_proyek` = '$progressFind->jenis_proyek' 
                                        AND `kegiatan_id` = '$progressFind->kegiatan_id'
                                        AND validasi != '2'
                                        ORDER BY tanggal DESC")->row()->persentase;
    $data['lastPersentase'] = json_encode($lastpersentase);
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
    $pb = json_decode($this->input->post('idbarang'));
    $pk = json_decode($this->input->post('koef'));
    $pv = json_decode($this->input->post('vol'));
    $ps = json_decode($this->input->post('sat'));
    $ph = json_decode($this->input->post('hs'));
    $pt = json_decode($this->input->post('total'));
    for ($i = 0; $i < count($pb); $i++) {
      $dummy[] = [
        'nama_pengeluaran' => $pb[$i],
        'koef' => $pk[$i],
        'vol' => $pv[$i],
        'sat' => $ps[$i],
        'harga_pengeluaran' => $ph[$i],
        'total' => $pt[$i],
      ];
    }
    $dataPengeluaran = array_filter($dummy, function ($var) {
      return ($var['vol'] > 0);
    });
    $kepalaproyek = $this->session->userdata('kodeuser');
    $sqlPersentase = "SELECT IFNULL(SUM(persentase),0) AS 'persentase' FROM `t_progress_proyek`
                      WHERE `kegiatan_id` = '" . $this->input->post('kegiatanid') . "'
                      AND `proyek_id` = '" . $this->input->post('proyekid') . "'
                      AND `jenis_proyek` = '" . $this->input->post('jenisid') . "'
                      AND `validasi` != '2'";
    $persentaseDb = $this->db->query($sqlPersentase)->row();
    $data = [
      'id_progress' => uniqid(),
      'proyek_id' => $this->input->post('proyekid'),
      'jenis_proyek' => $this->input->post('jenisid'),
      'kegiatan_id' => $this->input->post('kegiatanid'),
      'persentase' => (int)$this->input->post('persentase') - (int)$persentaseDb->persentase,
      'status' => (int)$this->input->post('persentase') === 100 ? '1' : '0',
      'ket' => $this->input->post('ket'),
      'kepala_proyek' => $kepalaproyek,
      'tanggal' => date('Y-m-d H:i:s'),
      'validasi_ket' => '-',
      'progress' => $this->input->post('progresscount'),
    ];
    if (count($dataPengeluaran) > 0) {
      for ($i = 0; $i < count($dataPengeluaran); $i++) {
        $p = array_values($dataPengeluaran);
        $pengeluaran[] = [
          'id_pengeluaran' => uniqid(),
          'progress_id' => $data['id_progress'],
          'proyek_id' => $this->input->post('proyekid'),
          'jenis_proyek_id' => $this->input->post('jenisid'),
          'kegiatan_id' => $this->input->post('kegiatanid'),
          'nama_pengeluaran' => $p[$i]['nama_pengeluaran'],
          'koef' => $p[$i]['koef'],
          'vol' => $p[$i]['vol'],
          'sat' => $p[$i]['sat'],
          'harga_pengeluaran' => $p[$i]['harga_pengeluaran'],
          'total_pengeluaran' => $p[$i]['total'],
        ];
      }
    }
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

        $config['file_name'] = 'proyek_' . $this->input->post('proyekid') . '_jp_' . $this->input->post('jenisid') . '_k_' . $this->input->post('kegiatan_id') . '_' . uniqid();

        $this->load->library('upload', $config);
        if ($this->upload->do_upload('file')) {
          $uploadData = $this->upload->data();
          $uploadData = $this->upload->data();
          $filename = $uploadData['file_name'];

          $dataPhoto[] = [
            'id' => uniqid(),
            'progress_id' =>  $data['id_progress'],
            'proyek_id' => $this->input->post('proyekid'),
            'jenis_proyek_id' => $this->input->post('jenisid'),
            'kegiatan_id' => $this->input->post('kegiatanid'),
            'photo_name' => $filename
          ];
        }
      }
    }
    $this->db->trans_start();
    $this->db->insert('t_progress_proyek', $data);
    if (count($dataPengeluaran) > 0) {
      $this->db->insert_batch('t_pengeluaran_progress', $pengeluaran);
    }
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
    $pb = json_decode($this->input->post('idbarang'));
    $pk = json_decode($this->input->post('koef'));
    $pv = json_decode($this->input->post('vol'));
    $ps = json_decode($this->input->post('sat'));
    $ph = json_decode($this->input->post('hs'));
    $pt = json_decode($this->input->post('total'));
    for ($i = 0; $i < count($pb); $i++) {
      $dummy[] = [
        'nama_pengeluaran' => $pb[$i],
        'koef' => $pk[$i],
        'vol' => $pv[$i],
        'sat' => $ps[$i],
        'harga_pengeluaran' => $ph[$i],
        'total' => $pt[$i],
      ];
    }
    $dataPengeluaran = array_filter($dummy, function ($var) {
      return ($var['vol'] > 0);
    });
    $kepalaproyek = $this->session->userdata('kodeuser');
    $data = [
      'id_progress' => $id,
      'proyek_id' => $this->input->post('proyekid'),
      'jenis_proyek' => $this->input->post('jenisid'),
      'kegiatan_id' => $this->input->post('kegiatanid'),
      'persentase' => (int)$this->input->post('persentase'),
      'status' => (int)$this->input->post('persentase') === 100 ? '1' : '0',
      'ket' => $this->input->post('ket'),
      'kepala_proyek' => $kepalaproyek,
      // 'tanggal' => date('Y-m-d H:i:s'),
      'validasi_ket' => '-',
      'validasi' => 0,
      // 'progress' => $this->input->post('progresscount'),
    ];
    // $datapengeluaran = json_decode($this->input->post('pengeluaran'));
    // if (count($datapengeluaran) > 0) {
    //   for ($i = 0; $i < count($datapengeluaran); $i++) {
    //     $pengeluaran[] = [
    //       'id_pengeluaran' => uniqid(),
    //       'progress_id' => $data['id_progress'],
    //       'proyek_id' => $this->input->post('proyekid'),
    //       'jenis_proyek_id' => $this->input->post('jenisid'),
    //       'kegiatan_id' => $this->input->post('kegiatanid'),
    //       'nama_pengeluaran' => $datapengeluaran[$i][0],
    //       'harga_pengeluaran' => $datapengeluaran[$i][1],
    //     ];
    //   }
    // }
    if (count($dataPengeluaran) > 0) {
      for ($i = 0; $i < count($dataPengeluaran); $i++) {
        $p = array_values($dataPengeluaran);
        $pengeluaran[] = [
          'id_pengeluaran' => uniqid(),
          'progress_id' => $data['id_progress'],
          'proyek_id' => $this->input->post('proyekid'),
          'jenis_proyek_id' => $this->input->post('jenisid'),
          'kegiatan_id' => $this->input->post('kegiatanid'),
          'nama_pengeluaran' => $p[$i]['nama_pengeluaran'],
          'koef' => $p[$i]['koef'],
          'vol' => $p[$i]['vol'],
          'sat' => $p[$i]['sat'],
          'harga_pengeluaran' => $p[$i]['harga_pengeluaran'],
          'total_pengeluaran' => $p[$i]['total'],
        ];
      }
    }

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

        $config['file_name'] = 'proyek_' . $this->input->post('proyekid') . '_jp_' . $this->input->post('jenisid') . '_k_' . $this->input->post('kegiatan_id') . '_' . uniqid();

        $this->load->library('upload', $config);
        if ($this->upload->do_upload('file')) {
          $uploadData = $this->upload->data();
          $filename = $uploadData['file_name'];

          $dataPhoto[] = [
            'id' => uniqid(),
            'progress_id' =>  $id,
            'proyek_id' => $this->input->post('proyekid'),
            'jenis_proyek_id' => $this->input->post('jenisid'),
            'kegiatan_id' => $this->input->post('kegiatanid'),
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

    if (count($dataPengeluaran) > 0) {
      $this->db->where('progress_id', $id);
      $this->db->delete('t_pengeluaran_progress');

      $this->db->insert_batch('t_pengeluaran_progress', $pengeluaran);
    }

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

  public function getFotoKeg()
  {
    $pro = $this->input->get('idkegiatan');
    $progress = $this->db->query("SELECT * FROM `t_progress_gambar` WHERE `kegiatan_id` = '$pro'");
    if ($progress->num_rows() > 0) {
      // $id = $progress->row()->id_progress;
      $data['photo'] = $this->db->query("SELECT * FROM t_progress_gambar WHERE kegiatan_id='$pro'")->result();
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
    if ($this->session->userdata('hakakses') === '2') {
      echo $this->Progress_m->jenisproyek_kegiatan_ignited($idproyek, $idjenis);
    } else {
      echo $this->Progress_m->jenisproyek_kegiatan_ignited_admin($idproyek, $idjenis);
    }
  }

  public function ignited_kegiatan_progress()
  {
    $idproyek = $this->input->post('proyek');
    $idjenis = $this->input->post('jenis');
    $idkegiatan = $this->input->post('kegiatan');
    header('Content-Type: application/json');
    if ($this->session->userdata('hakakses') === '2') {
      echo $this->Progress_m->progress_kegiatan_ignited($idproyek, $idjenis, $idkegiatan);
    } else {
      echo $this->Progress_m->progress_kegiatan_ignited_admin($idproyek, $idjenis, $idkegiatan);
    }
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
    $sql1 = "SELECT ppr.*, opr.*, k.`nama_konsumen` AS 'konsumen', nama_user AS 'kapro',
            (
              SELECT vol FROM `t_order_proyek_detail` oprd 
                    WHERE `jenis_proyek` = ppr.`jenis_proyek`
                    AND `order_proyek_id` = ppr.`proyek_id`
            ) AS 'volorder',
            (
              SELECT sat FROM `t_order_proyek_detail` oprd 
                    WHERE `jenis_proyek` = ppr.`jenis_proyek`
                    AND `order_proyek_id` = ppr.`proyek_id`
            ) AS 'unitorder',
            (
                    SELECT vol FROM `m_jenis_proyek_detail` jnpd 
                    WHERE jnpd.`kegiatan_id` = ppr.`kegiatan_id`
                    AND jnpd.`jenis_proyek_id`=ppr.`jenis_proyek`
            ) AS 'volkegiatan',
            (
              SELECT vol FROM `t_jadwal_proyek_detail` jprd
              INNER JOIN `t_jadwal_proyek` sjp ON sjp.`id_jadwal` = jprd.`jadwal_id`
              WHERE jprd.`proyek_id` = ppr.`proyek_id`
              AND jprd.`jenis_proyek_id` = ppr.`jenis_proyek`
              AND jprd.`kegiatan_id` = ppr.`kegiatan_id`
            ) AS 'voljadwal',
            (
              SELECT unit FROM `t_jadwal_proyek_detail` jprd
              INNER JOIN `t_jadwal_proyek` sjp ON sjp.`id_jadwal` = jprd.`jadwal_id`
              WHERE jprd.`proyek_id` = ppr.`proyek_id`
              AND jprd.`jenis_proyek_id` = ppr.`jenis_proyek`
              AND jprd.`kegiatan_id` = ppr.`kegiatan_id`
            ) AS 'unitjadwal',
            (
              SELECT IFNULL(SUM(`total_pengeluaran`),0) 
              FROM `t_pengeluaran_progress` pepro
              WHERE pepro.`progress_id` = ppr.`id_progress`
            ) AS 'pengeluaran',
            jpr.`nama_jenis_proyek`, dke.`nama_kegiatan`
            FROM `t_progress_proyek` ppr
            INNER JOIN `t_order_proyek` opr ON opr.`id_proyek` = ppr.`proyek_id`
            INNER JOIN `m_konsumen` k ON k.`id_konsumen` = opr.`konsumen_id`
            INNER JOIN m_user u ON u.`id_user` = ppr.`kepala_proyek`
            INNER JOIN `m_jenis_proyek` jpr ON jpr.`id_jenis_proyek` = ppr.`jenis_proyek`
            INNER JOIN `m_data_kegiatan` dke ON dke.`id_master_kegiatan` = ppr.`kegiatan_id`
            WHERE ppr.`id_progress` = '$id'";
    $data['progress'] = $this->db->query($sql1)->row();
    $data['photo'] = $this->db->query("SELECT * FROM t_progress_gambar WHERE progress_id='$id'")->result();
    $sql = "SELECT pp.`id_progress`, op.`nama_konsumen`, op.`no_surat_kontrak`, jn.`nama_jenis_proyek`, 
            pp.`persentase`, pp.`validasi`, opd.`order_proyek_id`,opd.`jenis_proyek`, k.`nama_kegiatan`,
            pp.`kegiatan_id`
            FROM `t_progress_proyek` pp
            INNER JOIN `t_order_proyek` op ON op.`id_proyek` = pp.`proyek_id`
            INNER JOIN `t_order_proyek_detail` opd ON opd.`order_proyek_id` = op.`id_proyek`
            INNER JOIN `m_jenis_proyek` jn ON jn.`id_jenis_proyek` = pp.`jenis_proyek`
            INNER JOIN `m_data_kegiatan` k ON k.`id_master_kegiatan` = pp.`kegiatan_id`
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
      $progress = $this->db->query("SELECT * FROM t_progress_proyek WHERE id_progress = '$id'")->row();
      $kegiatancount = $this->db->query("SELECT COUNT(`id_detail`) AS 'c' FROM `t_jadwal_proyek_detail`
                        INNER JOIN `t_jadwal_proyek` ON `t_jadwal_proyek`.`id_jadwal` = `t_jadwal_proyek_detail`.`jadwal_id`
                        WHERE `t_jadwal_proyek_detail`.`proyek_id` = '$progress->proyek_id'")->row()->c;
      $sql = "SELECT IFNULL(SUM(persentase),0) AS 'p' 
              FROM t_progress_proyek 
              WHERE proyek_id = '$progress->proyek_id'
              -- AND jenis_proyek = '$progress->jenis_proyek'
              -- AND kegiatan_id = '$progress->kegiatan_id'
              AND validasi = '1'";
      $persentase = $this->db->query($sql)->row()->p;
      $parameter = (int)$kegiatancount * 100;
      if ((float)$persentase === (int)$parameter and (int)$index === 1) {
        $this->db->where('id_proyek', $progress->proyek_id);
        $this->db->update('t_order_proyek', ['status' => 1]);
      }
      echo json_encode(['status' => 'sukses']);
    }
  }
}
