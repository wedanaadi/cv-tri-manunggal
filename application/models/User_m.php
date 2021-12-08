<?php
class User_m extends CI_Model
{
  private $tabel = "m_user";

  function getAll($opsi = 1)
  {
    $this->db->where('is_aktif', 1);
    $this->db->where('hak_akses', $opsi);
    return $this->db->get($this->tabel)->result();
  }

  function getAll_ignited()
  {
    $this->datatables->select("id_user,nama_user,username,hak_akses");
    $this->datatables->where('is_aktif', 1);
    $this->datatables->from($this->tabel);
    $this->datatables->add_column(
      'view',
      '<button class="btn btn-icon icon-left btn-warning" id-pk="$1" id="user_u">
                                                    <i class="fas fa-edit"></i>
                                                    Ubah
                                                </button>
                                                <button class="btn btn-icon icon-left btn-danger" id-pk="$1" id="user_d">
                                                    <i class="fas fa-trash"></i>
                                                    Hapus
                                                </button>',
      'id_user'
    );
    return $this->datatables->generate();
  }

  function insertDB($data)
  {
    $this->db->insert($this->tabel, $data);
  }

  function updateDB($data, $id)
  {
    $this->db->where('id_user', $id);
    $this->db->update($this->tabel, $data);
  }

  function getBy($param, $id)
  {
    $this->db->where($param, $id);
    return $this->db->get($this->tabel)->row();
  }
}
