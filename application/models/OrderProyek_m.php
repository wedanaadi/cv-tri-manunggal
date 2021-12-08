<?php
class OrderProyek_m extends CI_Model
{
  private $tabel = "t_order_proyek";
  private $detil = "t_order_proyek_detail";

  function getAll($param)
  {
    if ($param) {
      $this->db->Where('konsumen_id', $param);
    }
    $this->datatables->where($this->tabel . '.is_aktif', 1);
    return $this->db->get($this->tabel)->result();
  }

  function getAll_ignited()
  {
    $this->datatables->select("id_proyek,nama_konsumen,no_surat_kontrak");
    $this->datatables->where('is_aktif', 1);
    $this->datatables->from($this->tabel);
    $this->datatables->add_column(
      'view',
      '<button class="btn btn-icon icon-left btn-success" id-pk="$1" id="op_detail">
          <i class="fas fa-eye"></i>
          Detail
      </button>
      <a href="' . base_url() . 'OrderProyek_c/ubahOP/$1" class="btn btn-icon icon-left btn-warning" id-pk="$1" id="k_u">
          <i class="fas fa-edit"></i>
          Ubah
      </a>
      <button class="btn btn-icon icon-left btn-danger" id-pk="$1" id="op_d">
          <i class="fas fa-trash"></i>
          Hapus
      </button>',
      'id_proyek'
    );
    return $this->datatables->generate();
  }

  function getAll_ignited_direktur()
  {
    $this->datatables->select("id_proyek,nama_konsumen,no_surat_kontrak");
    $this->datatables->where('is_aktif', 1);
    $this->datatables->from($this->tabel);
    $this->datatables->add_column(
      'view',
      '<button class="btn btn-icon icon-left btn-success" id-pk="$1" id="op_detail">
          <i class="fas fa-eye"></i>
          Detail
      </button>',
      'id_proyek'
    );
    return $this->datatables->generate();
  }

  function getDetail($id)
  {
    $this->db->select($this->detil . ".*, jp.nama_jenis_proyek, u.nama_user");
    $this->db->where('order_proyek_id', $id);
    $this->db->join('m_jenis_proyek jp', 'jp.id_jenis_proyek=' . $this->detil . '.jenis_proyek');
    $this->db->join('m_user u', 'u.id_user=' . $this->detil . '.kepala_proyek');
    $data['detildata'] = $this->db->get($this->detil)->result();

    $this->db->select($this->tabel . ".*");
    $this->db->where('id_proyek', $id);
    // $this->db->join('t_jadwal_proyek jp', 'jp.proyek_id=' . $this->tabel . '.id_proyek');
    $data['data'] = $this->db->get($this->tabel)->row();
    return $data;
  }

  function insertDB($data, $detil)
  {
    $this->db->trans_start();
    $this->db->insert($this->tabel, $data);
    $this->db->insert_batch($this->detil, $detil);
    $this->db->trans_complete();
  }

  function updateDB($data, $detil, $id)
  {
    $this->db->trans_start();
    $this->db->where('order_proyek_id', $id);
    $this->db->delete($this->detil);

    $this->db->where('id_proyek', $id);
    $this->db->update($this->tabel, $data);

    $this->db->insert_batch($this->detil, $detil);
    $this->db->trans_complete();
  }

  function hapusDB($data, $id)
  {
    $this->db->where('id_proyek', $id);
    $this->db->update($this->tabel, $data);
  }

  function getBy($table, $id)
  {
    if ($table === 'tabel') {
      $this->db->select($this->tabel . ".*");
      $this->db->where('id_proyek', $id);
      // $this->db->join('t_jadwal_proyek jp', 'jp.proyek_id=' . $this->tabel . '.id_proyek');
      return $this->db->get($this->tabel)->row();
    } else {
      $this->db->select($this->detil . ".*, jp.nama_jenis_proyek, u.nama_user");
      $this->db->where('order_proyek_id', $id);
      $this->db->join('m_jenis_proyek jp', 'jp.id_jenis_proyek=' . $this->detil . '.jenis_proyek');
      $this->db->join('m_user u', 'u.id_user=' . $this->detil . '.kepala_proyek');
      return $this->db->get($this->detil)->result();
    }
  }

  function getBy2($param, $id)
  {
    $this->db->where($param, $id);
    return $this->db->get($this->tabel);
  }
}
