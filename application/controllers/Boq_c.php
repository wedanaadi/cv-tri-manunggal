<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Boq_c extends CI_Controller
{
  function __construct()
  {
    $this->CI = &get_instance();
    parent::__construct();
    if ($this->session->userdata('kodeuser') == null) {
      redirect('Auth_c/login');
    }
    $this->load->library(['Datatables', 'create_pdf']);
    $this->load->helper(['Terbilang']);
    $this->load->model(['Boq_m']);
  }

  public function index()
  {
    $this->boqView();
  }

  public function boqView()
  {
    $data['title'] = 'BOQ';
    if ($this->session->userdata('hakakses') === '1' || $this->session->userdata('hakakses') === '4') {
      $this->load->view('boq/data_v', $data);
    } else {
      $this->load->view('boq/datadirektur_v', $data);
    }
  }

  public function addBoq()
  {
    $data['title'] = 'BOQ';
    $this->load->view('boq/add_v', $data);
  }

  public function ubahBoq($id)
  {
    $data['title'] = 'BOQ';
    $data['edit'] = json_encode($this->Boq_m->getBy($id));
    $this->load->view('boq/edit_v', $data);
  }

  public function select2JenisProyek($id)
  {
    $sql = "SELECT op.*, opd.`jenis_proyek`, jn.`nama_jenis_proyek` FROM `t_order_proyek` op
            INNER JOIN `t_jadwal_proyek` jp ON jp.`proyek_id` = op.`id_proyek`
            INNER JOIN `t_order_proyek_detail` opd ON opd.`order_proyek_id` = op.`id_proyek`
            INNER JOIN `m_jenis_proyek` jn ON jn.`id_jenis_proyek` = opd.`jenis_proyek`
            WHERE jp.`nama_konsumen` = '$id' AND op.is_aktif = '1'
            GROUP BY op.`id_proyek`, opd.`jenis_proyek`";
    $datas = $this->db->query($sql)->result();
    $k[] = [];
    foreach ($datas as $data) {
      $k[] = ['id' => $data->id_proyek . '-' . $data->jenis_proyek, 'text' => $data->nama_jenis_proyek . ' | ' . $data->nama_kegiatan];
    }
    echo json_encode($k);
  }

  public function getJumlah()
  {
    $jp = $this->input->get('id');
    $sql = "SELECT SUM(jumlah) AS jumlah FROM `m_jenis_proyek`
            INNER JOIN `m_jenis_proyek_detail` ON `m_jenis_proyek_detail`.`jenis_proyek_id` = `m_jenis_proyek`.`id_jenis_proyek`
            WHERE `m_jenis_proyek`.`id_jenis_proyek` = '$jp'";
    $data['jumlah'] = $this->db->query($sql)->row()->jumlah;
    $data['ppn'] = 0.1 * $data['jumlah'];
    $data['total'] = $data['ppn'] + $data['jumlah'];
    echo json_encode($data);
  }

  public function checked()
  {
    $data = $this->input->get('param');
    $ex = explode('-', $data);
    $checked = $this->db->query("SELECT * FROM t_boq WHERE nama_kegiatan='$ex[0]' AND jenis_proyek='$ex[1]' AND is_aktif='1'")->num_rows();
    echo json_encode($checked);
  }

  public function create()
  {
    $proyek = explode('-', $this->input->post('jp'));
    $data = [
      'id_boq' => uniqid(),
      'nama_satker' => $this->input->post('nama'),
      'nama_kegiatan' => $proyek[0],
      'jenis_proyek' => $proyek[1],
      'satuan' => $this->input->post('sat'),
      'volume' => $this->input->post('vol'),
      'jumlah_harga' => str_replace(",", ".", $this->input->post('jumlah')),
      'ppn' => str_replace(",", ".", $this->input->post('ppn')),
      'total_harga_satuan' => str_replace(",", ".", $this->input->post('ths')),
      'total ' => str_replace(",", ".", $this->input->post('totals')),
    ];

    $this->Boq_m->insertDB($data);
    echo json_encode(['status' => true]);
  }

  public function update($id)
  {
    $proyek = explode('-', $this->input->post('jp'));
    $data = [
      'nama_satker' => $this->input->post('nama'),
      'nama_kegiatan' => $proyek[0],
      'jenis_proyek' => $proyek[1],
      'satuan' => $this->input->post('sat'),
      'volume' => $this->input->post('vol'),
      'jumlah_harga' => str_replace(",", ".", $this->input->post('jumlah')),
      'ppn' => str_replace(",", ".", $this->input->post('ppn')),
      'total_harga_satuan' => str_replace(",", ".", $this->input->post('ths')),
      'total ' => str_replace(",", ".", $this->input->post('totals')),
    ];

    $this->Boq_m->updateDB($data, $id);
    echo json_encode(['status' => true]);
  }

  public function hapus($id)
  {
    $data = [
      'is_aktif' => 0,
    ];
    $this->Boq_m->updateDB($data, $id);
    echo json_encode(['status' => true]);
  }

  public function ignited_boq()
  {
    header('Content-Type: application/json');
    echo $this->Boq_m->getAll_ignited();
  }

  public function getDetailJP()
  {
    $id = $this->input->get('idproyek');
    $data = $this->Boq_m->getJsonDetail($id);
    echo json_encode($data);
  }

  public function getProyekKonsumen()
  {
    $id = $this->input->get('konsumen');
    $data = $this->Boq_m->getJsonProyek($id);
    echo json_encode($data);
  }

  public function form($id)
  {
    $sql = "SELECT op.`nama_kegiatan` as kegiatan, op.`nama_proyek_pekerjaan` as pekerjaan, jn.`nama_jenis_proyek`, op.`tahun_anggaran`, b.* 
            FROM `t_boq` b
            INNER JOIN `t_order_proyek` op ON op.`id_proyek` = b.`nama_kegiatan`
            INNER JOIN `m_jenis_proyek` jn ON jn.`id_jenis_proyek` = b.`jenis_proyek`
            WHERE `id_boq` = '$id'";
    $data['atas'] = $this->db->query($sql)->row();
    $this->db->select('m_jenis_proyek_detail.*, dk.nama_kegiatan');
    $this->db->where('jenis_proyek_id', $data['atas']->jenis_proyek);
    $this->db->join('m_data_kegiatan dk', 'dk.id_master_kegiatan=m_jenis_proyek_detail.kegiatan_id');
    $data['jn'] = $this->db->get('m_jenis_proyek_detail')->result();
    echo json_encode(['view' => $this->load->view('boq/form_v', $data, true)]);
  }

  public function cetakHeader()
  {
    $data = $this->load->view('_partials/header_laporan', false, true);
    return $data;
  }

  public function getBoqLap($id)
  {
    $sql = "SELECT op.`nama_kegiatan` as kegiatan, op.`nama_proyek_pekerjaan` as pekerjaan, jn.`nama_jenis_proyek`, op.`tahun_anggaran`, b.* 
            FROM `t_boq` b
            INNER JOIN `t_order_proyek` op ON op.`id_proyek` = b.`nama_kegiatan`
            INNER JOIN `m_jenis_proyek` jn ON jn.`id_jenis_proyek` = b.`jenis_proyek`
            WHERE `id_boq` = '$id'";
    $data['atas'] = $this->db->query($sql)->row();
    $this->db->select('m_jenis_proyek_detail.*, dk.nama_kegiatan');
    $this->db->where('jenis_proyek_id', $data['atas']->jenis_proyek);
    $this->db->join('m_data_kegiatan dk', 'dk.id_master_kegiatan=m_jenis_proyek_detail.kegiatan_id');
    $data['jn'] = $this->db->get('m_jenis_proyek_detail')->result();
    $data['direktur'] = $this->db->query('SELECT * FROM m_user WHERE hak_akses="3"')->row()->nama_user;
    return $data;
  }

  public function cetak($id)
  {
    $data['header'] = $this->cetakHeader();
    $data['lap'] = $this->getBoqLap($id);
    $html = $this->load->view('boq/cetak_v', $data, TRUE);
    $this->create_pdf->load($html, 'BOQ-' . $data['lap']['atas']->nama_jenis_proyek, 'A4-P');
  }

  public function getRekapLap($id)
  {
    $sql = "SELECT op.`nama_kegiatan` as kegiatan, op.`nama_proyek_pekerjaan` as pekerjaan, jn.`nama_jenis_proyek`, op.`tahun_anggaran`, op.`lokasi`, b.* 
            FROM `t_boq` b
            INNER JOIN `t_order_proyek` op ON op.`id_proyek` = b.`nama_kegiatan`
            INNER JOIN `m_jenis_proyek` jn ON jn.`id_jenis_proyek` = b.`jenis_proyek`
            WHERE b.`nama_kegiatan` = '$id'";
    $data['atas'] = $this->db->query($sql)->row();
    $this->db->select('b.*,jn.nama_jenis_proyek');
    $this->db->where('b.nama_kegiatan', $id);
    $this->db->where('b.is_aktif', 1);
    $this->db->join('m_jenis_proyek jn', 'jn.id_jenis_proyek=b.jenis_proyek');
    $this->db->from('t_boq b');
    $data['detil'] = $this->db->get()->result();
    $data['direktur'] = $this->db->query('SELECT * FROM m_user WHERE hak_akses="3"')->row()->nama_user;
    return $data;
  }

  public function rekap($idproyek)
  {
    $data['header'] = $this->cetakHeader();
    $data['lap'] = $this->getRekapLap($idproyek);
    $html = $this->load->view('boq/cetak_rekap_v', $data, TRUE);
    $this->create_pdf->load($html, "Rekaptulasi-" . $data['lap']['atas']->kegiatan, 'A4-P');
  }
}
