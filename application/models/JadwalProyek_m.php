<?php
class JadwalProyek_m extends CI_Model
{
  private $tabel = "t_jadwal_proyek";

  function getAll()
  {
    $this->datatables->where($this->tabel . '.is_aktif', 1);
    return $this->db->get($this->tabel)->result();
  }

  function getAll_ignited()
  {
    $this->datatables->select("id_jadwal,k.nama_konsumen,k.id_konsumen,op.nospmk,tanggal_mulai,tanggal_selesai,ket,proyek_id");
    $this->datatables->where($this->tabel . '.is_aktif', 1);
    $this->datatables->join('m_konsumen k', 'k.id_konsumen=' . $this->tabel . '.nama_konsumen');
    $this->datatables->join('t_order_proyek op', 'op.id_proyek=' . $this->tabel . '.proyek_id');
    $this->datatables->from($this->tabel);
    $this->datatables->add_column(
      'view',
      '<button class="btn btn-icon icon-left btn-warning" id-pk="$1" id="jp_u">
                                                    <i class="fas fa-edit"></i>
                                                    Ubah
                                                </button>
                                                <button class="btn btn-icon icon-left btn-danger" id-pk="$1" id="jp_d">
                                                    <i class="fas fa-trash"></i>
                                                    Hapus
                                                </button>',
      'id_jadwal'
    );
    return $this->datatables->generate();
  }

  function insertDB($data)
  {
    $this->db->insert($this->tabel, $data);
  }

  function updateDB($data, $id)
  {
    $this->db->where('id_jadwal', $id);
    $this->db->update($this->tabel, $data);
  }

  function getBy($param, $id)
  {
    $this->db->select('id_jadwal,tanggal_mulai,tanggal_selesai,ket,op.nospmk,k.id_konsumen,k.nama_konsumen,proyek_id');
    $this->db->where($param, $id);
    $this->db->join('m_konsumen k', 'k.id_konsumen=' . $this->tabel . '.nama_konsumen');
    $this->db->join('t_order_proyek op', 'op.id_proyek=' . $this->tabel . '.proyek_id');
    return $this->db->get($this->tabel)->row();
  }
}
