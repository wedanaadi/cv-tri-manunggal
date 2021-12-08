<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_c extends CI_Controller
{
  function __construct()
  {
    $this->CI = &get_instance();
    parent::__construct();
    if ($this->session->userdata('kodeuser') == null) {
      redirect('Auth_c/login');
    }
    $this->load->library(['Datatables', 'Bcrypt']);
    $this->load->model('User_m');
  }

  public function index()
  {
    $this->userView();
  }

  public function userView()
  {
    $data['title'] = 'User';
    $this->load->view('user/data_v', $data);
  }

  public function ignited_data()
  {
    header('Content-Type: application/json');
    echo $this->User_m->getAll_ignited();
  }

  public function form($id = false)
  {
    $data = [];
    if ($id != false) {
      $data = $this->User_m->getBy('id_user', $id);
    }
    echo json_encode(['data' => $data, 'view' => $this->load->view('user/form_v', null, true)]);
  }

  public function create()
  {
    $data = [
      'id_user' => uniqid(),
      'nama_user' => $this->input->post('nama'),
      'username' => str_replace(' ', '', $this->input->post('username')),
      'password' => $this->bcrypt->hash_password($this->input->post('password')),
      'hak_akses' => $this->input->post('hakakses'),
    ];
    $this->User_m->insertDB($data);
    echo json_encode(['status' => true]);
  }

  public function update($id)
  {
    $data = [
      'nama_user' => $this->input->post('nama'),
      'username' => str_replace(' ', '', $this->input->post('username')),
      'hak_akses' => $this->input->post('hakakses'),
    ];
    if (!empty($this->input->post('password'))) {
      $data['password'] = $this->bcrypt->hash_password($this->input->post('password'));
    }
    $this->User_m->updateDB($data, $id);
    echo json_encode(['status' => true]);
  }

  public function hapus($id)
  {
    $data = [
      'is_aktif' => 0,
    ];
    $this->User_m->updateDB($data, $id);
    echo json_encode(['status' => true]);
  }

  public function select2($id)
  {
    $datas = $this->User_m->getAll($id);
    $k[] = [];
    foreach ($datas as $data) {
      $k[] = ['id' => $data->id_user, 'text' => $data->nama_user];
    }
    echo json_encode($k);
  }
}
