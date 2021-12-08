<?php
defined('BASEPATH') or exit('No direct script access allowed');

class JenisProyek_c extends CI_Controller
{
  function __construct()
  {
    $this->CI = &get_instance();
    parent::__construct();
    if ($this->session->userdata('kodeuser') == null) {
      redirect('Auth_c/login');
    }
    $this->load->library('Datatables');
    $this->load->model('JenisProyek_m');
  }

  public function index()
  {
    $this->jenisproyekView();
  }

  public function jenisproyekView()
  {
    $data['title'] = 'Jenis Proyek';
    $this->load->view('jenisproyek/data_v', $data);
  }

  public function ignited_data()
  {
    header('Content-Type: application/json');
    echo $this->JenisProyek_m->getAll_ignited();
  }

  public function addJN()
  {
    $data['title'] = 'Jenis Proyek';
    $this->load->view('jenisproyek/add_v', $data);
  }

  public function ubahJN($id)
  {
    $data['title'] = 'Jenis Proyek';
    $data['edit'] = json_encode($this->JenisProyek_m->getBy('tabel', $id));
    $data['editdetil'] = json_encode($this->JenisProyek_m->getBy('detil', $id));
    $this->load->view('jenisproyek/edit_v', $data);
  }

  public function form($id = false)
  {
    $data['jn'] = $this->db->query("SELECT * FROM m_jenis_proyek WHERE id_jenis_proyek ='$id'")->row();
    $data['detil'] = $this->JenisProyek_m->getDetail($id);
    echo json_encode(['view' => $this->load->view('jenisproyek/form_v', $data, true)]);
  }

  public function create()
  {
    $data = [
      'id_jenis_proyek' => uniqid(),
      'nama_jenis_proyek' => $this->input->post('jenis'),
    ];
    foreach ($this->input->post('detail') as $v) {
      $detail[] = [
        'id' => uniqid(),
        'jenis_proyek_id' => $data['id_jenis_proyek'],
        'kegiatan_id' => $v[0],
        'unit' => $v[2],
        'vol' => $v[3],
        'harga' => $v[4],
        'jumlah' => $v[5],
      ];
    }
    $this->JenisProyek_m->insertDB($data, $detail);
    echo json_encode(['status' => true]);
  }

  public function update($id)
  {
    $data = [
      'nama_jenis_proyek' => $this->input->post('jenis'),
    ];
    foreach ($this->input->post('detail') as $v) {
      $detail[] = [
        'id' => uniqid(),
        'jenis_proyek_id' => $id,
        'kegiatan_id' => $v[0],
        'unit' => $v[2],
        'vol' => $v[3],
        'harga' => $v[4],
        'jumlah' => $v[5],
      ];
    }
    $this->JenisProyek_m->updateDB($data, $detail, $id);
    echo json_encode(['status' => true]);
  }

  public function hapus($id)
  {
    $data = [
      'is_aktif' => 0,
    ];
    $this->JenisProyek_m->hapusDB($data, $id);
    echo json_encode(['status' => true]);
  }

  public function select2()
  {
    $datas = $this->JenisProyek_m->getAll();
    $k[] = [];
    foreach ($datas as $data) {
      $k[] = ['id' => $data->id_jenis_proyek, 'text' => $data->nama_jenis_proyek];
    }
    echo json_encode($k);
  }
}
