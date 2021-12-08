<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BarangUpah_c extends CI_Controller
{
  function __construct()
  {
    $this->CI = &get_instance();
    parent::__construct();
    if ($this->session->userdata('kodeuser') == null) {
      redirect('Auth_c/login');
    }
    $this->load->library('Datatables');
    $this->load->model('BarangUpah_m');
  }

  public function index()
  {
    $this->barangView();
  }

  public function barangView()
  {
    $data['title'] = 'Barang Upah';
    $this->load->view('barangupah/data_v', $data);
  }

  public function ignited_data()
  {
    header('Content-Type: application/json');
    echo $this->BarangUpah_m->getAll_ignited();
  }

  public function form($id = false)
  {
    $data = [];
    if ($id != false) {
      $data = $this->BarangUpah_m->getBy('id_barang', $id);
    }
    echo json_encode(['data' => $data, 'view' => $this->load->view('barangupah/form_v', null, true)]);
  }

  public function create()
  {
    $data = [
      'id_barang' => uniqid(),
      'nama_barang_upah' => $this->input->post('bu'),
      'jenis_brg' => $this->input->post('jenis'),
      'merk_brg' => $this->input->post('merk'),
      'ukuran_brg' => $this->input->post('ukuran'),
      'satuan' => $this->input->post('satuan'),
      'tipe' => $this->input->post('tipe'),
    ];
    $stok = [
      'id_stok' => uniqid(),
      'nama_brg' => $this->input->post('bu'),
      'jenis_brg' => $this->input->post('jenis'),
      'merk_brg' => $this->input->post('merk'),
      'ukuran_brg' => $this->input->post('ukuran'),
      'stok' => 0,
      'satuan_brg' => $this->input->post('satuan'),
      'lokasi_brg' => '-',
      'barang_id' => $data['id_barang'],
    ];
    $this->BarangUpah_m->insertDB($data, $stok, $this->input->post('tipe'));
    echo json_encode(['status' => true]);
  }

  public function update($id)
  {
    $data = [
      'nama_barang_upah' => $this->input->post('bu'),
      'jenis_brg' => $this->input->post('jenis'),
      'merk_brg' => $this->input->post('merk'),
      'ukuran_brg' => $this->input->post('ukuran'),
      'satuan' => $this->input->post('satuan'),
      'tipe' => $this->input->post('tipe'),
    ];
    $stok = [
      'nama_brg' => $this->input->post('bu'),
      'jenis_brg' => $this->input->post('jenis'),
      'merk_brg' => $this->input->post('merk'),
      'ukuran_brg' => $this->input->post('ukuran'),
      // 'stok' => 0,
      'satuan_brg' => $this->input->post('satuan'),
      'lokasi_brg' => '-',
    ];
    $this->BarangUpah_m->updateDB($data, $id, $stok, $this->input->post('tipe'));
    echo json_encode(['status' => true]);
  }

  public function hapus($id)
  {
    $data = [
      'is_aktif' => 0,
    ];
    $tipe = $this->db->query("SELECT tipe FROM m_upah_barang WHERE id_barang='$id'")->row()->tipe;
    $this->BarangUpah_m->updateDB($data, $id, $data, $tipe);
    echo json_encode(['status' => true]);
  }

  public function select2($tipe = 1)
  {
    $datas = $this->BarangUpah_m->getAll($tipe);
    $bu[] = [];
    foreach ($datas as $data) {
      $bu[] = ['id' => $data->id_barang, 'text' => $data->nama_barang_upah];
    }
    echo json_encode($bu);
  }
}
