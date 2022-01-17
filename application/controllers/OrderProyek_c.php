<?php
defined('BASEPATH') or exit('No direct script access allowed');

class OrderProyek_c extends CI_Controller
{
  function __construct()
  {
    $this->CI = &get_instance();
    parent::__construct();
    if ($this->session->userdata('kodeuser') == null) {
      redirect('Auth_c/login');
    }
    $this->load->library('Datatables');
    $this->load->model('OrderProyek_m');
  }

  public function index()
  {
    $this->OrderView();
  }

  public function OrderView()
  {
    $data['title'] = 'Order Proyek';
    if ($this->session->userdata('hakakses') === '1' || $this->session->userdata('hakakses') === '4') {
      $this->load->view('orderproyek/data_v', $data);
    } else {
      $this->load->view('orderproyek/datadirektur_v', $data);
    }
  }

  public function form($id = false)
  {
    $data['detil'] = $this->OrderProyek_m->getDetail($id);
    echo json_encode(['view' => $this->load->view('orderproyek/form_v', $data, true)]);
  }

  public function ignited_data()
  {
    header('Content-Type: application/json');
    if ($this->session->userdata('hakakses') === '1' || $this->session->userdata('hakakses') === '4') {
      echo $this->OrderProyek_m->getAll_ignited();
    } else {
      echo $this->OrderProyek_m->getAll_ignited_direktur();
    }
  }

  public function addOP()
  {
    $data['title'] = 'Order Proyek';
    $this->load->view('orderproyek/add_v', $data);
  }

  public function ubahOP($id)
  {
    $data['title'] = 'Order Proyek';
    $data['edit'] = json_encode($this->OrderProyek_m->getBy('tabel', $id));
    $data['editdetil'] = json_encode($this->OrderProyek_m->getBy('detil', $id));
    $this->load->view('orderproyek/edit_v', $data);
  }

  public function create()
  {
    $data = [
      'id_proyek' => uniqid(),
      'nama_konsumen' => $this->input->post('konsumen'),
      'konsumen_id' => $this->input->post('idkon'),
      'nama_kegiatan' => $this->input->post('kegiatan'),
      'nama_proyek_pekerjaan' => $this->input->post('pekerjaan'),
      'lokasi' => $this->input->post('lokasi'),
      'tahun_anggaran' => $this->input->post('ta'),
      'no_surat_kontrak' => $this->input->post('nosk'),
      'nospmk' => $this->input->post('spmk'),
    ];

    for ($i = 0; $i < count($this->input->post('jp')); $i++) {
      $detail[] = [
        'id' => uniqid(),
        'order_proyek_id' => $data['id_proyek'],
        'jenis_proyek' => $this->input->post('jp')[$i],
        'vol' => $this->input->post('vol')[$i],
        'sat' => $this->input->post('sat')[$i],
        'kepala_proyek' => $this->input->post('kp')[$i],
      ];
    }

    $this->OrderProyek_m->insertDB($data, $detail);
    echo json_encode(['status' => true]);
  }

  public function update($id)
  {
    $data = [
      'nama_konsumen' => $this->input->post('konsumen'),
      'konsumen_id' => $this->input->post('idkon'),
      'nama_kegiatan' => $this->input->post('kegiatan'),
      'nama_proyek_pekerjaan' => $this->input->post('pekerjaan'),
      'lokasi' => $this->input->post('lokasi'),
      'tahun_anggaran' => $this->input->post('ta'),
      'no_surat_kontrak' => $this->input->post('nosk'),
      'nospmk' => $this->input->post('spmk'),
    ];

    for ($i = 0; $i < count($this->input->post('jp')); $i++) {
      $detail[] = [
        'id' => uniqid(),
        'order_proyek_id' => $id,
        'jenis_proyek' => $this->input->post('jp')[$i],
        'vol' => $this->input->post('vol')[$i],
        'sat' => $this->input->post('sat')[$i],
        'kepala_proyek' => $this->input->post('kp')[$i],
      ];
    }
    $this->OrderProyek_m->updateDB($data, $detail, $id);
    echo json_encode(['status' => true]);
  }

  public function hapus($id)
  {
    $data = [
      'is_aktif' => 0,
    ];
    $this->OrderProyek_m->hapusDB($data, $id);
    echo json_encode(['status' => true]);
  }

  public function select2SPMK($param = false)
  {
    $datas = $this->OrderProyek_m->getAll($param);
    $k[] = [];
    foreach ($datas as $data) {
      $k[] = ['id' => $data->id_proyek, 'text' => $data->nospmk];
    }
    echo json_encode($k);
  }

  public function getJsonOder()
  {
    $id = $this->input->get('id');
    // order
    $this->db->where('id_proyek', $id);
    $data['order'] = $this->db->get('t_order_proyek')->row();
    // detail
    $this->db->select('t_order_proyek_detail.*, jn.nama_jenis_proyek,u.nama_user');
    $this->db->where('order_proyek_id', $id);
    $this->db->join('m_jenis_proyek jn', 'jn.id_jenis_proyek=t_order_proyek_detail.jenis_proyek');
    $this->db->join('m_user u', 'u.id_user=t_order_proyek_detail.kepala_proyek');
    $data['detail'] = $this->db->get('t_order_proyek_detail')->result();
    // jadwal
    $this->db->where('proyek_id', $id);
    $this->db->where('is_aktif', 1);
    $data['count'] = $this->db->get('t_jadwal_proyek')->num_rows();
    // kegiatan count
    $whereIn = "'" . implode("','", array_column($data['detail'], 'jenis_proyek')) . "'";
    $sqlCountKegiatan = "SELECT * FROM `m_jenis_proyek_detail`
                           WHERE `jenis_proyek_id` IN (" . $whereIn . ")";
    $data['countKegiatan'] = $this->db->query($sqlCountKegiatan)->num_rows();
    echo json_encode($data);
  }

  public function getJsonByKonsumen()
  {
    $idkon = $this->input->get('idKon');
    $data['count'] = $this->OrderProyek_m->getBy2('konsumen_id', $idkon)->num_rows();
    if ($data['count'] > 1) {
      $data['order'] = $this->OrderProyek_m->getBy2('konsumen_id', $idkon)->result();
    } else {
      $data['order'] = $this->OrderProyek_m->getBy2('konsumen_id', $idkon)->row();
      $this->db->select('t_order_proyek_detail.*, jn.nama_jenis_proyek, u.nama_user');
      $this->db->where('order_proyek_id', $data['order']->id_proyek);
      $this->db->join('m_jenis_proyek jn', 'jn.id_jenis_proyek=t_order_proyek_detail.jenis_proyek');
      $this->db->join('m_user u', 'u.id_user=t_order_proyek_detail.kepala_proyek');
      $data['detail'] = $this->db->get('t_order_proyek_detail')->result();
      $this->db->where('proyek_id', $data['order']->id_proyek);
      $this->db->where('is_aktif', 1);
      $data['checkJadwal'] = $this->db->get('t_jadwal_proyek')->num_rows();
      $whereIn = "'" . implode("','", array_column($data['detail'], 'jenis_proyek')) . "'";
      $sqlCountKegiatan = "SELECT * FROM `m_jenis_proyek_detail`
                           WHERE `jenis_proyek_id` IN (" . $whereIn . ")";
      $data['countKegiatan'] = $this->db->query($sqlCountKegiatan)->num_rows();
    }
    echo json_encode($data);
  }
}
