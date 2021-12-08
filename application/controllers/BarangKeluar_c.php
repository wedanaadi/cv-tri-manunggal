<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BarangKeluar_c extends CI_Controller
{
  function __construct()
  {
    $this->CI = &get_instance();
    parent::__construct();
    if ($this->session->userdata('kodeuser') == null) {
      redirect('Auth_c/login');
    }
    $this->load->library('Datatables');
    $this->load->model(['BarangKeluar_m', 'PersediaanBarang_m']);
  }

  public function index()
  {
    $this->barangKView();
  }

  public function barangKView()
  {
    $data['title'] = 'Barang Keluar';
    $this->load->view('barangkeluar/data_v', $data);
  }

  public function ignited_data()
  {
    header('Content-Type: application/json');
    echo $this->BarangKeluar_m->getAll_ignited();
  }

  public function form($id = false)
  {
    $data = [];
    if ($id != false) {
      $data = $this->BarangKeluar_m->getBy('id_brg_keluar', $id);
    }
    echo json_encode(['data' => $data, 'view' => $this->load->view('barangkeluar/form_v', null, true)]);
  }

  public function create()
  {
    $data = [
      'id_brg_keluar' => uniqid(),
      'tanggal' => $this->input->post('tgl'),
      'jumlah' => $this->input->post('jumlah'),
      'keterangan' => $this->input->post('ket'),
      'barang_id' => $this->input->post('barang'),
      'penerima' => $this->input->post('penerima'),
    ];
    $stokbarang = $this->PersediaanBarang_m->getBy('barang_id', $this->input->post('barang'))->stok;
    $stok = [
      'stok' => $stokbarang - $this->input->post('jumlah'),
    ];
    $this->db->trans_start();
    $this->BarangKeluar_m->insertDB($data);
    $this->PersediaanBarang_m->updateDB($stok, $this->input->post('barang'));
    $this->db->trans_complete();
    echo json_encode(['status' => true]);
  }

  public function update($id)
  {
    $data = [
      'tanggal' => $this->input->post('tgl'),
      'jumlah' => $this->input->post('jumlah'),
      'keterangan' => $this->input->post('ket'),
      'barang_id' => $this->input->post('barang'),
      'penerima' => $this->input->post('penerima'),
    ];
    $stokold = $this->BarangKeluar_m->getBy('id_brg_keluar', $id)->jumlah;
    $stokbarang = $this->PersediaanBarang_m->getBy('barang_id', $this->input->post('barang'))->stok;
    $stok = [
      'stok' => ($stokbarang + $stokold) - $this->input->post('jumlah'),
    ];
    $this->db->trans_start();
    $this->BarangKeluar_m->updateDB($data, $id);
    $this->PersediaanBarang_m->updateDB($stok, $this->input->post('barang'));
    $this->db->trans_complete();
    echo json_encode(['status' => true]);
  }

  public function hapus($id)
  {
    $data = [
      'is_aktif' => 0,
    ];
    $bm = $this->BarangKeluar_m->getBy('id_brg_keluar', $id);
    $stokold = $bm->jumlah;
    $stokbarang = $this->PersediaanBarang_m->getBy('barang_id', $bm->barang_id)->stok;
    $stok = [
      'stok' => ($stokbarang + $stokold),
    ];
    $this->db->trans_start();
    $this->BarangKeluar_m->updateDB($data, $id);
    $this->PersediaanBarang_m->updateDB($stok, $bm->barang_id);
    $this->db->trans_complete();
    echo json_encode(['status' => true]);
  }
}
