<?php
class Kegiatan_m extends CI_Model
{
  private $tabel = "m_data_kegiatan";
  private $detil = "m_kegiatan_detail";

  function getAll()
  {
    $this->datatables->where($this->tabel . '.is_aktif', 1);
    return $this->db->get($this->tabel)->result();
  }

  function getAll_ignited()
  {
    $this->datatables->select("id_master_kegiatan,nama_kegiatan");
    $this->datatables->where($this->tabel . '.is_aktif', 1);
    $this->datatables->from($this->tabel);
    $this->datatables->add_column(
      'view',
      '<button class="btn btn-icon icon-left btn-success" id-pk="$1" id="k_detail">
          <i class="fas fa-eye"></i>
          Detail
      </button>
      <a href="' . base_url() . 'Kegiatan_c/ubahKegiatan/$1" class="btn btn-icon icon-left btn-warning" id-pk="$1" id="k_u">
          <i class="fas fa-edit"></i>
          Ubah
      </a>
      <button class="btn btn-icon icon-left btn-danger" id-pk="$1" id="k_d">
          <i class="fas fa-trash"></i>
          Hapus
      </button>',
      'id_master_kegiatan'
    );
    return $this->datatables->generate();
  }

  function getDetail($id)
  {
    $this->db->select($this->detil . ".*, up.nama_barang_upah");
    $this->db->where('kegiatan_id', $id);
    $this->db->join('m_upah_barang up', 'up.id_barang=' . $this->detil . '.upah_bahan_alat', 'left');
    return $this->db->get($this->detil)->result();
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
    $this->db->where('kegiatan_id', $id);
    $this->db->delete($this->detil);

    $this->db->where('id_master_kegiatan', $id);
    $this->db->update($this->tabel, $data);

    $this->db->insert_batch($this->detil, $detil);
    $this->db->trans_complete();
  }

  function hapusDB($data, $id)
  {
    $this->db->where('id_master_kegiatan', $id);
    $this->db->update($this->tabel, $data);
  }

  function getBy($table, $id)
  {
    if ($table === 'tabel') {
      $this->db->where('id_master_kegiatan', $id);
      return $this->db->get($this->tabel)->row();
    } else {
      $this->db->select($this->detil . ".*, up.nama_barang_upah");
      $this->db->where('kegiatan_id', $id);
      $this->db->join('m_upah_barang up', 'up.id_barang=' . $this->detil . '.upah_bahan_alat', 'left');
      return $this->db->get($this->detil)->result();
    }
  }
}
