<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kegiatan_c extends CI_Controller
{
  function __construct()
  {
    $this->CI = &get_instance();
    parent::__construct();
    if ($this->session->userdata('kodeuser') == null) {
      redirect('Auth_c/login');
    }
    $this->load->library('Datatables');
    $this->load->model(['Kegiatan_m']);
  }

  public function index()
  {
    $this->kegiatanView();
  }

  public function kegiatanView()
  {
    $data['title'] = 'Kegiatan';
    $this->load->view('kegiatan/data_v', $data);
  }

  public function ignited_data()
  {
    header('Content-Type: application/json');
    echo $this->Kegiatan_m->getAll_ignited();
  }

  public function form($id = false)
  {
    $data['detil'] = $this->Kegiatan_m->getDetail($id);
    echo json_encode(['view' => $this->load->view('kegiatan/form_v', $data, true)]);
  }

  public function addKegiatan()
  {
    $data['title'] = 'Kegiatan';
    $this->load->view('kegiatan/add_v', $data);
  }

  public function ubahKegiatan($id)
  {
    $data['title'] = 'Kegiatan';
    $data['edit'] = json_encode($this->Kegiatan_m->getBy('tabel', $id));
    $data['editdetil'] = json_encode($this->Kegiatan_m->getBy('detil', $id));
    $this->load->view('kegiatan/edit_v', $data);
  }

  public function create()
  {
    $data = [
      'id_master_kegiatan' => uniqid(),
      'nama_kegiatan' => $this->input->post('kegiatan'),
    ];
    foreach ($this->input->post('detail') as $v) {
      $upb = '';
      if ($v[5] !== '-') {
        $upb = $v[5];
      } elseif ($v[6] !== '-') {
        $upb = $v[6];
      } else {
        $upb = $v[7];
      }
      $detail[] = [
        'id' => uniqid(),
        'kegiatan_id' => $data['id_master_kegiatan'],
        'satuan' => $v[2],
        'koef' => $v[3],
        'harga' => $v[4],
        'tipe' => $v[0],
        'harga_upah_bahan_alat' => $upb,
        'upah_bahan_alat' => $v[9],
      ];
    }
    $this->Kegiatan_m->insertDB($data, $detail);
    echo json_encode(['status' => true]);
  }

  public function update($id)
  {
    $data = [
      'nama_kegiatan' => $this->input->post('kegiatan'),
    ];
    foreach ($this->input->post('detail') as $v) {
      $upb = '';
      if ($v[5] !== '-') {
        $upb = $v[5];
      } elseif ($v[6] !== '-') {
        $upb = $v[6];
      } else {
        $upb = $v[7];
      }
      $detail[] = [
        'id' => uniqid(),
        'kegiatan_id' => $id,
        'satuan' => $v[2],
        'koef' => $v[3],
        'harga' => $v[4],
        'tipe' => $v[0],
        'harga_upah_bahan_alat' => $upb,
        'upah_bahan_alat' => $v[9],
      ];
    }
    $this->Kegiatan_m->updateDB($data, $detail, $id);
    echo json_encode(['status' => true]);
  }

  public function hapus($id)
  {
    $data = [
      'is_aktif' => 0,
    ];
    $this->Kegiatan_m->hapusDB($data, $id);
    echo json_encode(['status' => true]);
  }

  public function select2()
  {
    $datas = $this->Kegiatan_m->getAll();
    $k[] = [];
    foreach ($datas as $data) {
      $k[] = ['id' => $data->id_master_kegiatan, 'text' => $data->nama_kegiatan];
    }
    echo json_encode($k);
  }

  public function getKegiatanHarga()
  {
    $id = $this->input->get('id');
    $this->db->select('SUM(harga_upah_bahan_alat) AS jumlahkegiatan');
    $this->db->where('kegiatan_id', $id);
    $data = $this->db->get('m_kegiatan_detail')->row();
    echo json_encode($data->jumlahkegiatan);
  }
}
