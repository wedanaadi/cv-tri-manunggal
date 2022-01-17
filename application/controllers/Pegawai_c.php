<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pegawai_c extends CI_Controller
{
  function __construct()
  {
    $this->CI = &get_instance();
    parent::__construct();
    if ($this->session->userdata('kodeuser') == null) {
      redirect('Auth_c/login');
    }
    $this->load->library('Datatables');
    $this->load->model('Pegawai_m');
  }

  public function index()
  {
    $this->pegawaiView();
  }

  public function pegawaiView()
  {
    $data['title'] = 'Pegawai';
    $this->load->view('pegawai/data_v', $data);
  }

  public function ignited_data()
  {
    header('Content-Type: application/json');
    echo $this->Pegawai_m->getAll_ignited();
  }

  public function form($id = false)
  {
    $data = [];
    if ($id != false) {
      $data = $this->Pegawai_m->getBy('id_pegawai', $id);
    }
    echo json_encode(['data' => $data, 'view' => $this->load->view('pegawai/form_v', null, true)]);
  }

  public function create()
  {
    $data = [
      'id_pegawai' => uniqid(),
      'nama_pegawai' => $this->input->post('nama'),
      'npwp' => $this->input->post('npwp'),
      'nik' => $this->input->post('nik'),
      'alamat' => $this->input->post('alamat'),
      'tempat_lahir' => $this->input->post('tl'),
      'tgl_lahir' => $this->input->post('tgl'),
      'jenis_kelamin' => $this->input->post('jk'),
      'jabatan' => $this->input->post('jabatan'),
      'no_telp' => $this->input->post('telp'),
      'email' => $this->input->post('email'),
    ];
    $this->Pegawai_m->insertDB($data);
    echo json_encode(['status' => true]);
  }

  public function update($id)
  {
    $data = [
      'nama_pegawai' => $this->input->post('nama'),
      'npwp' => $this->input->post('npwp'),
      'nik' => $this->input->post('nik'),
      'alamat' => $this->input->post('alamat'),
      'tempat_lahir' => $this->input->post('tl'),
      'tgl_lahir' => $this->input->post('tgl'),
      'jenis_kelamin' => $this->input->post('jk'),
      'jabatan' => $this->input->post('jabatan'),
      'no_telp' => $this->input->post('telp'),
      'email' => $this->input->post('email'),
    ];
    $this->Pegawai_m->updateDB($data, $id);
    echo json_encode(['status' => true]);
  }

  public function hapus($id)
  {
    $data = [
      'is_aktif' => 0,
    ];
    $this->Pegawai_m->updateDB($data, $id);
    echo json_encode(['status' => true]);
  }

  public function select2()
  {
    $datas = $this->Pegawai_m->getAll();
    $k[] = [];
    foreach ($datas as $data) {
      $k[] = ['id' => $data->id_pegawai, 'text' => $data->nama_pegawai];
    }
    echo json_encode($k);
  }
}
