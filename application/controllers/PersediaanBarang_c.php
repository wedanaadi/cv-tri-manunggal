<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PersediaanBarang_c extends CI_Controller
{
  function __construct()
  {
    $this->CI = &get_instance();
    parent::__construct();
    if ($this->session->userdata('kodeuser') == null) {
      redirect('Auth_c/login');
    }
    $this->load->library(['Datatables', 'create_pdf']);
    $this->load->model(['PersediaanBarang_m', 'BarangUpah_m']);
  }

  public function index()
  {
    $this->persediaanBarang();
  }

  public function persediaanBarang()
  {
    $data['title'] = 'Persediaan Barang';
    $this->load->view('persediaan/data_v', $data);
  }

  public function ignited_data()
  {
    header('Content-Type: application/json');
    echo $this->PersediaanBarang_m->getAll_ignited();
  }

  public function form($id = false)
  {
    $data = [];
    if ($id != false) {
      $data = $this->PersediaanBarang_m->getBy('id_stok', $id);
    }
    echo json_encode(['data' => $data, 'view' => $this->load->view('persediaan/form_v', null, true)]);
  }

  public function cetakHeader()
  {
    $data = $this->load->view('_partials/header_laporan', false, true);
    return $data;
  }

  public function getLap()
  {
    $this->db->where('is_aktif', 1);
    return $this->db->get('m_persediaan_barang')->result();
  }

  public function cetak()
  {
    $data['header'] = $this->cetakHeader();
    $data['lap'] = $this->getLap();
    $html = $this->load->view('persediaan/cetak_v', $data, TRUE);
    $this->create_pdf->load($html, 'Persediaan Barang', 'A4-L');
  }

  public function update($id)
  {
    $stok = [
      'stok' => $this->input->post('jumlah'),
      'lokasi_brg' => $this->input->post('lokasi'),
    ];
    $this->PersediaanBarang_m->updateDB($stok, $id);
    echo json_encode(['status' => true]);
  }

  public function hapus($id)
  {
    $data = [
      'is_aktif' => 0,
    ];
    $barang_id = $this->db->query("SELECT * FROM m_persediaan_barang WHERE id_stok = '$id'")->row()->barang_id;
    $this->db->trans_start();
    $this->db->where('id_barang', $barang_id);
    $this->db->update('m_upah_barang', $data);
    $this->PersediaanBarang_m->updateDB($data, $id);
    $this->db->trans_complete();
    echo json_encode(['status' => true]);
  }
}
