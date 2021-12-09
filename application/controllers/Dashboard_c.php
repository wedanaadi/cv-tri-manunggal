<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_c extends CI_Controller
{
  function __construct()
  {
    $this->CI = &get_instance();
    parent::__construct();
    if ($this->session->userdata('kodeuser') == null) {
      redirect('Auth_c/login');
    }
    $this->load->library(['Datatables', 'Bcrypt']);
  }

  public function index()
  {
    $this->dashboard();
  }

  public function dashboard()
  {
    $data['title'] = 'Dashboard';

    if ($this->session->userdata('hakakses') !== '2') {
      $checkProses = $this->db->query("SELECT * FROM t_progress_proyek")->num_rows();

      if ($checkProses > 0) {
        $detilorder = $this->db->query("SELECT * FROM t_order_proyek_detail opd
                                      INNER JOIN `t_jadwal_proyek` jd ON jd.`proyek_id` = opd.`order_proyek_id`
                                      WHERE jd.`is_aktif` = '1'")->result();
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
                                        INNER JOIN `t_jadwal_proyek` jd ON jd.`proyek_id` = top.`id_proyek` WHERE jd.`is_aktif` = '1'")->row()->c;
        $data['cS'] = 0;
      }
      $data['chart'] = json_encode($this->grafikbaru());
      // $data['chart'] = json_encode($this->chart());
      $data['tabel'] = $this->tabeldetil();
      // $data['notif'] = $this->notifvalidasi();
      $this->session->set_userdata('notif', $this->notifvalidasi());
    } else {
      $id = $this->session->userdata('kodeuser');
      $checkProses = $this->db->query("SELECT * FROM t_progress_proyek WHERE kepala_proyek ='$id'")->num_rows();
      if ($checkProses > 0) {
        $detilorder = $this->db->query("SELECT * FROM t_order_proyek_detail opd
                                      INNER JOIN `t_jadwal_proyek` jd ON jd.`proyek_id` = opd.`order_proyek_id`
                                      WHERE kepala_proyek = '$id' AND jd.`is_aktif` = '1'")->result();
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
                                        WHERE `kepala_proyek` = '$id' AND jd.`is_aktif` = '1'")->row()->c;
        $data['cS'] = 0;
      }
      $data['chart'] = json_encode($this->grafikbaru($id));
      // $data['chart'] = json_encode($this->chart($id));
      $data['tabel'] = $this->tabeldetil($id);
      $this->session->set_userdata('notif', $this->notifvalidasi());
    }
    $this->load->view('dashboard_v', $data);
  }

  public function chart($id = 'kosong')
  {
    $lanjut = '';
    $lanjut2 = '';
    if ($id !== 'kosong') {
      $lanjut = " AND `kepala_proyek` = '$id'";
      // $lanjut2 = " WHERE opd.`kepala_proyek` = '$id'";
    }
    $sql = "SELECT SUM(p) AS 'jumlah',COUNT(`jenis_proyek`) AS 'count' FROM (
              SELECT `jenis_proyek`, `p`
              FROM(
                    SELECT proyek_id, MAX(persentase) AS 'p', jenis_proyek
                    FROM `t_progress_proyek`
                    WHERE `validasi` = '1' $lanjut
                    GROUP BY jenis_proyek,proyek_id
                  ) AS t
            ) AS tabeljadi";
    if ($this->db->query($sql)->num_rows() > 0) {
      $datas = $this->db->query($sql)->row();
      if ($datas->jumlah > 0) {
        $data['chart'] = floor($datas->jumlah / $datas->count);
        $data['proyek'] = $datas->count;
      } else {
        $data['chart'] = 0;
        $data['proyek'] = 0;
      }
    } else {
      $data['chart'] = 0;
      $data['proyek'] = 0;
    }

    return $data;
  }

  public function tabeldetil($id = 'kosong')
  {
    $lanjut = "";
    if ($id !== 'kosong') {
      $lanjut = " WHERE `kepala_proyek` = '$id'";
    }
    $sql = "SELECT id_proyek, op.`nama_kegiatan`, `nama_konsumen`, `jenis_proyek`,`nama_jenis_proyek`, kepala_proyek, nama_user
            FROM `t_order_proyek` op 
            INNER JOIN `t_order_proyek_detail` opd ON opd.`order_proyek_id` = op.`id_proyek`
            INNER JOIN `m_jenis_proyek` jp ON jp.`id_jenis_proyek` = opd.`jenis_proyek`
            INNER JOIN `m_user` u ON u.`id_user` = opd.`kepala_proyek`
            $lanjut
            ORDER BY `nama_konsumen` ASC, `nama_kegiatan` ASC";
    $dataproyek = $this->db->query($sql)->result();
    foreach ($dataproyek as $p) {
      $sqlboq = "SELECT * FROM t_boq WHERE nama_kegiatan='$p->id_proyek' AND jenis_proyek='$p->jenis_proyek' AND is_aktif='1'";
      $boq = $this->db->query($sqlboq);
      if ($boq->num_rows() > 0) {
        $sqlProgress = "SELECT proyek_id, MAX(persentase) AS 'p', jenis_proyek, status
                        FROM `t_progress_proyek` 
                        WHERE `validasi` = '1' AND proyek_id='$p->id_proyek' AND jenis_proyek='$p->jenis_proyek'
                        HAVING MAX(persentase) is not null";
        $proyekprogress = $this->db->query("$sqlProgress");
        if ($proyekprogress->num_rows() > 0) {
          $array[] =  (object)[
            'idproyek' => $p->id_proyek,
            'kegiatan' => $p->nama_kegiatan,
            'konsumen' => $p->nama_konsumen,
            'jenis' => $p->nama_jenis_proyek,
            'kepro' => $p->nama_user,
            'harga' => bulatkan($boq->row()->total),
            'persentase' => $proyekprogress->row()->p,
            'status' => $proyekprogress->row()->status,
          ];
        } else {
          $array[] =  (object)[
            'idproyek' => $p->id_proyek,
            'kegiatan' => $p->nama_kegiatan,
            'konsumen' => $p->nama_konsumen,
            'jenis' => $p->nama_jenis_proyek,
            'kepro' => $p->nama_user,
            'harga' => bulatkan($boq->row()->total),
            'persentase' => "0",
            'status' => "0"
          ];
        }
      }
    }
    return $array;
  }

  public function grafikbaru($id = 'kosong')
  {
    $detil = $this->tabeldetil($id);
    $idproyek = '';
    $array = [];
    $SUMpersentase = 0;
    $bagi = 0;
    $jumlahPALL = 0;
    foreach ($detil as $d) {
      $countP2 = 0;
      if ($idproyek !== $d->idproyek) {
        $SUMpersentase = 0;
        $idproyek = $d->idproyek;
        foreach ($detil as $v) {
          if ($idproyek === $v->idproyek) {
            $countP2++;
          }
        }
        $bagi = $countP2;
      }
      $SUMpersentase += $d->persentase;
      $array[$idproyek] = (object)['p' => $SUMpersentase, 'b' => $bagi];
    }


    $total = 0;
    foreach ($array as $a) {
      if ($a->p !== 0) {
        $total += $a->p / $a->b;
      }
    }
    $data['total'] = $total;
    $data['chart'] = round($total / count($array), 2);
    $data['proyek'] = count($array);
    $data['jn'] = count($detil);
    return $data;
  }

  public function notifvalidasi()
  {
    if ($this->session->userdata('hakakses') === '2') {
      $kepalaproyek = $this->session->userdata('kodeuser');
      $where = "WHERE pp.`validasi` IN('1','2') AND op.`status` = '0' AND opd.`kepala_proyek` = '$kepalaproyek'";
    } else {
      $where = "WHERE pp.`validasi` = '0'";
    }
    $sql = "SELECT pp.`id_progress`, op.`nama_konsumen`, op.`no_surat_kontrak`, jn.`nama_jenis_proyek`, 
            pp.`persentase`, pp.`validasi`, pp.`proyek_id`,pp.`jenis_proyek`
            FROM `t_progress_proyek` pp
            INNER JOIN `t_order_proyek` op ON op.`id_proyek` = pp.`proyek_id`
            INNER JOIN `t_order_proyek_detail` opd ON opd.`order_proyek_id` = op.`id_proyek`
            INNER JOIN `m_jenis_proyek` jn ON jn.`id_jenis_proyek` = pp.`jenis_proyek`
            $where";
    return $this->db->query($sql)->result();
  }
}
