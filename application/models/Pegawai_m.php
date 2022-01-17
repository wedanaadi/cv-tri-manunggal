<?php
class Pegawai_m extends CI_Model
{
  private $tabel = "m_pegawai";

  function getAll()
  {
    $this->datatables->where('is_aktif', 1);
    return $this->db->get($this->tabel)->result();
  }

  function getAll_ignited()
  {
    $this->datatables->select("id_pegawai,nama_pegawai,npwp,nik,alamat,jenis_kelamin,no_telp,jabatan,email,CONCAT(tempat_lahir,'/ ',tgl_lahir) AS ttl");
    $this->datatables->where('is_aktif', 1);
    $this->datatables->from($this->tabel);
    $this->datatables->add_column(
      'view',
      '<button class="btn btn-icon icon-left btn-warning" id-pk="$1" id="pegawai_u">
                                                    <i class="fas fa-edit"></i>
                                                    Ubah
                                                </button>
                                                <button class="btn btn-icon icon-left btn-danger" id-pk="$1" id="pegawai_d">
                                                    <i class="fas fa-trash"></i>
                                                    Hapus
                                                </button>',
      'id_pegawai'
    );
    return $this->datatables->generate();
  }

  function insertDB($data)
  {
    $this->db->insert($this->tabel, $data);
  }

  function updateDB($data, $id)
  {
    $this->db->where('id_pegawai', $id);
    $this->db->update($this->tabel, $data);
  }

  function getBy($param, $id)
  {
    $this->db->where($param, $id);
    return $this->db->get($this->tabel)->row();
  }
}
