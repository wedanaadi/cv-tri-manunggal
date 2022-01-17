<?php
defined('BASEPATH') or exit('No direct script access allowed');

class JadwalProyek_c extends CI_Controller
{
  function __construct()
  {
    $this->CI = &get_instance();
    parent::__construct();
    if ($this->session->userdata('kodeuser') == null) {
      redirect('Auth_c/login');
    }
    $this->load->library('Datatables');
    $this->load->model('JadwalProyek_m');
  }

  public function index()
  {
    $this->jadwalProyek();
  }

  public function jadwalProyek()
  {
    $data['title'] = 'Jadwal Proyek';
    $this->load->view('jadwalproyek/data_v', $data);
  }

  public function ignited_data()
  {
    header('Content-Type: application/json');
    echo $this->JadwalProyek_m->getAll_ignited();
  }

  public function form()
  {
    $jenis = $this->input->get('jenis');
    if ($this->input->get('isSave') === 'true') {
      $volJenis = $this->input->get('vol');
      $sql = "SELECT JPD.*, K.`nama_kegiatan` FROM `m_jenis_proyek_detail` jpd
            INNER JOIN `m_data_kegiatan` k ON k.`id_master_kegiatan` = jpd.`kegiatan_id`
            WHERE jpd.`jenis_proyek_id` = '$jenis'";
      $kegQuery = $this->db->query($sql)->result();
      foreach ($kegQuery as $value) {
        $keg[] = (object)[
          'jenis_proyek' => $value->jenis_proyek_id,
          'kegiatan_id' => $value->kegiatan_id,
          'kegiatan' => $value->nama_kegiatan,
          'vol' => $value->vol,
          'unit' => $value->unit,
          'harga' => $value->harga,
          'volJadwal' => $value->vol * $volJenis,
          'hargaJadwal' => ($value->vol * $volJenis) * $value->harga,
        ];
      }
      $data['kegiatan'] = $keg;
      echo json_encode(['view' => $this->load->view('jadwalproyek/form_v', $data, true)]);
    } else {
      $proyekid = $this->input->get('proyek');
      $sql = "SELECT jpd.*, k.`nama_kegiatan`, p.`nama_pegawai` 
              FROM `t_jadwal_proyek_detail` jpd
              INNER JOIN `m_data_kegiatan` k ON k.`id_master_kegiatan` = jpd.`kegiatan_id`
              INNER JOIN `m_pegawai` p ON p.`id_pegawai` = jpd.`pegawai_id`
              WHERE `proyek_id` = '$proyekid' AND `jenis_proyek_id` = '$jenis'";
      $data['kegiatan'] = $this->db->query($sql)->result();
      echo json_encode(['view' => $this->load->view('jadwalproyek/form2_v', $data, true)]);
    }
  }

  public function addForm()
  {
    $data['title'] = 'Jadwal Proyek';
    $this->load->view('jadwalproyek/add_v', $data);
  }

  public function create()
  {
    $data = [
      'id_jadwal' => uniqid(),
      'nama_konsumen' => $this->input->post('nama'),
      'tanggal_mulai' => $this->input->post('startdate'),
      'tanggal_selesai' => $this->input->post('enddate'),
      'proyek_id' => $this->input->post('spmk'),
      'ket' => $this->input->post('ket'),
    ];
    for ($i = 0; $i < count($this->input->post('kegiatanid')); $i++) {
      $detail[] = [
        'id_detail' => uniqid(),
        'jadwal_id' => $data['id_jadwal'],
        'proyek_id' => $this->input->post('spmk'),
        'jenis_proyek_id' => $this->input->post('jenisproyek')[$i],
        'kegiatan_id' => $this->input->post('kegiatanid')[$i],
        'pegawai_id' => $this->input->post('pegawai')[$i],
        'durasi' => $this->input->post('durasi')[$i],
        'startDate' => $this->input->post('mulai_pegawai')[$i],
        'endDate' => $this->input->post('selesai_pegawai')[$i],
        'unit' => $this->input->post('unit')[$i],
        'vol' => $this->input->post('vol')[$i],
        'harga' => $this->input->post('harga')[$i],
        'total' => $this->input->post('total')[$i],
      ];
    }
    $this->JadwalProyek_m->insertDB($data, $detail);
    echo json_encode(['status' => true]);
  }

  public function update($id)
  {
    $data = [
      'nama_konsumen' => $this->input->post('nama'),
      'tanggal_mulai' => $this->input->post('startdate'),
      'tanggal_selesai' => $this->input->post('enddate'),
      'proyek_id' => $this->input->post('spmk'),
      'ket' => $this->input->post('ket'),
    ];
    $this->JadwalProyek_m->updateDB($data, $id);
    echo json_encode(['status' => true]);
  }

  public function hapus($id)
  {
    $data = [
      'is_aktif' => 0,
    ];
    $this->db->trans_start();
    $this->JadwalProyek_m->updateDB($data, $id);
    $this->db->trans_complete();

    if ($this->db->trans_status() === TRUE) {
      $this->db->where('id_jadwal', $id);
      $proyekid = $this->db->get('t_jadwal_proyek')->row()->proyek_id;
      $progress = $this->db->query("SELECT * FROM t_progress_proyek WHERE proyek_id ='$proyekid'");
      if ($progress->num_rows() > 0) {
        foreach ($progress->result() as $value) {
          $photo = $this->db->query("SELECT * FROM t_progress_gambar WHERE progress_id='$value->id_progress'");
          if ($photo->num_rows() > 0) {
            foreach ($photo->result() as $p) {
              $path = 'assets/img/foto/' . $p->photo_name;
              unlink($path);
              $this->db->query("DELETE FROM t_progress_gambar WHERE photo_name='$p->photo_name'");
            }
          }
        }
        $this->db->query("DELETE FROM t_progress_proyek WHERE proyek_id='$proyekid'");
      }
      echo json_encode(['status' => true]);
    }
  }

  public function select2()
  {
    $datas = $this->JadwalProyek_m->getAll();
    $k[] = [];
    foreach ($datas as $data) {
      $k[] = ['id' => $data->id_jadwal, 'text' => $data->nospmk];
    }
    echo json_encode($k);
  }

  public function getJson()
  {
    echo json_encode($this->JadwalProyek_m->getBy('id_jadwal', $this->input->get('id')));
  }
}
