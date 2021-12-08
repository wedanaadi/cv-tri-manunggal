<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Konsumen_c extends CI_Controller
{
  function __construct()
  {
    $this->CI = &get_instance();
    parent::__construct();
    if ($this->session->userdata('kodeuser') == null) {
      redirect('Auth_c/login');
    }
    $this->load->library('Datatables');
    $this->load->model('Konsumen_m');
  }

  public function index()
  {
    $this->konsumenView();
  }

  public function konsumenView()
  {
    $data['title'] = 'Konsumen';
    $this->load->view('konsumen/data_v', $data);
  }

  public function ignited_data()
  {
    header('Content-Type: application/json');
    echo $this->Konsumen_m->getAll_ignited();
  }

  public function form($id = false)
  {
    $data = [];
    if ($id != false) {
      $data = $this->Konsumen_m->getBy('id_konsumen', $id);
    }
    echo json_encode(['data' => $data, 'view' => $this->load->view('konsumen/form_v', null, true)]);
  }

  public function create()
  {
    $data = [
      'id_konsumen' => uniqid(),
      'nama_konsumen' => $this->input->post('nama'),
      'alamat' => $this->input->post('alamat'),
      'no_telp' => $this->input->post('telp'),
      'email' => $this->input->post('email'),
    ];
    $this->Konsumen_m->insertDB($data);
    echo json_encode(['status' => true]);
  }

  public function update($id)
  {
    $data = [
      'nama_konsumen' => $this->input->post('nama'),
      'alamat' => $this->input->post('alamat'),
      'no_telp' => $this->input->post('telp'),
      'email' => $this->input->post('email'),
    ];
    $this->Konsumen_m->updateDB($data, $id);
    echo json_encode(['status' => true]);
  }

  public function hapus($id)
  {
    $data = [
      'is_aktif' => 0,
    ];
    $this->Konsumen_m->updateDB($data, $id);
    echo json_encode(['status' => true]);
  }

  public function select2()
  {
    $datas = $this->Konsumen_m->getAll();
    $k[] = [];
    foreach ($datas as $data) {
      $k[] = ['id' => $data->id_konsumen, 'text' => $data->nama_konsumen];
    }
    echo json_encode($k);
  }
}
