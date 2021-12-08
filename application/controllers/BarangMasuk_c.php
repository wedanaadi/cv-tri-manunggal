<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BarangMasuk_c extends CI_Controller
{
  function __construct()
  {
    $this->CI = &get_instance();
    parent::__construct();
    if ($this->session->userdata('kodeuser') == null) {
      redirect('Auth_c/login');
    }
    $this->load->library('Datatables');
    $this->load->model(['BarangMasuk_m', 'PersediaanBarang_m']);
  }

  public function index()
  {
    $this->barangMView();
  }

  public function barangMView()
  {
    $data['title'] = 'Barang Masuk';
    $this->load->view('barangmasuk/data_v', $data);
  }

  public function ignited_data()
  {
    header('Content-Type: application/json');
    echo $this->BarangMasuk_m->getAll_ignited();
  }

  public function form($id = false)
  {
    $data = [];
    if ($id != false) {
      $data = $this->BarangMasuk_m->getBy('id_brg_masuk', $id);
    }
    echo json_encode(['data' => $data, 'view' => $this->load->view('barangmasuk/form_v', null, true)]);
  }

  public function create()
  {
    $data = [
      'id_brg_masuk' => uniqid(),
      'tgl' => $this->input->post('tgl'),
      'jumlah' => $this->input->post('jumlah'),
      'ket' => $this->input->post('ket'),
      'barang_id' => $this->input->post('barang'),
    ];
    $stokbarang = $this->PersediaanBarang_m->getBy('barang_id', $this->input->post('barang'))->stok;
    $stok = [
      'stok' => $stokbarang + $this->input->post('jumlah'),
      'lokasi_brg' => $this->input->post('lokasi'),
    ];
    $this->db->trans_start();
    $this->BarangMasuk_m->insertDB($data);
    $this->PersediaanBarang_m->updateDB($stok, $this->input->post('barang'));
    $this->db->trans_complete();
    echo json_encode(['status' => true]);
  }

  public function update($id)
  {
    $data = [
      'tgl' => $this->input->post('tgl'),
      'jumlah' => $this->input->post('jumlah'),
      'ket' => $this->input->post('ket'),
      'barang_id' => $this->input->post('barang'),
    ];
    $stokold = $this->BarangMasuk_m->getBy('id_brg_masuk', $id)->jumlah;
    $stokbarang = $this->PersediaanBarang_m->getBy('barang_id', $this->input->post('barang'))->stok;
    $stok = [
      'stok' => ($stokbarang - $stokold) + $this->input->post('jumlah'),
      'lokasi_brg' => $this->input->post('lokasi'),
    ];
    $this->db->trans_start();
    $this->BarangMasuk_m->updateDB($data, $id);
    $this->PersediaanBarang_m->updateDB($stok, $this->input->post('barang'));
    $this->db->trans_complete();
    echo json_encode(['status' => true]);
  }

  public function hapus($id)
  {
    $data = [
      'is_aktif' => 0,
    ];
    $bm = $this->BarangMasuk_m->getBy('id_brg_masuk', $id);
    $stokold = $bm->jumlah;
    $stokbarang = $this->PersediaanBarang_m->getBy('barang_id', $bm->barang_id)->stok;
    $stok = [
      'stok' => ($stokbarang - $stokold),
    ];
    $this->db->trans_start();
    $this->BarangMasuk_m->updateDB($data, $id);
    $this->PersediaanBarang_m->updateDB($stok, $bm->barang_id);
    $this->db->trans_complete();
    echo json_encode(['status' => true]);
  }
}
