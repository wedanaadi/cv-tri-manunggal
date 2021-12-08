<?php
class Konsumen_m extends CI_Model
{
  private $tabel = "m_konsumen";

  function getAll()
  {
    $this->datatables->where('is_aktif', 1);
    return $this->db->get($this->tabel)->result();
  }

  function getAll_ignited()
  {
    $this->datatables->select("id_konsumen,alamat,no_telp,email,nama_konsumen");
    $this->datatables->where('is_aktif', 1);
    // $this->datatables->join('m_konsumen k', 'k.id_konsumen=' . $this->tabel . '.nama_konsumen');
    $this->datatables->from($this->tabel);
    $this->datatables->add_column(
      'view',
      '<button class="btn btn-icon icon-left btn-warning" id-pk="$1" id="konsumen_u">
                                                    <i class="fas fa-edit"></i>
                                                    Ubah
                                                </button>
                                                <button class="btn btn-icon icon-left btn-danger" id-pk="$1" id="konsumen_d">
                                                    <i class="fas fa-trash"></i>
                                                    Hapus
                                                </button>',
      'id_konsumen'
    );
    return $this->datatables->generate();
  }

  function insertDB($data)
  {
    $this->db->insert($this->tabel, $data);
  }

  function updateDB($data, $id)
  {
    $this->db->where('id_konsumen', $id);
    $this->db->update($this->tabel, $data);
  }

  function getBy($param, $id)
  {
    $this->db->where($param, $id);
    return $this->db->get($this->tabel)->row();
  }
}
