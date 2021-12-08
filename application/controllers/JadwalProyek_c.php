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

  public function form($id = false)
  {
    $data = [];
    if ($id != false) {
      $data['edit'] = $this->JadwalProyek_m->getBy('id_jadwal', $id);
      // detail
      $this->db->select('t_order_proyek_detail.*, jn.nama_jenis_proyek');
      $this->db->where('order_proyek_id', $data['edit']->proyek_id);
      $this->db->join('m_jenis_proyek jn', 'jn.id_jenis_proyek=t_order_proyek_detail.jenis_proyek');
      $data['detail'] = $this->db->get('t_order_proyek_detail')->result();
    }
    echo json_encode(['data' => $data, 'view' => $this->load->view('jadwalproyek/form_v', null, true)]);
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
    $this->JadwalProyek_m->insertDB($data);
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
