<?php
class PersediaanBarang_m extends CI_Model
{
  private $tabel = "m_persediaan_barang";
  private $tabelstok = "m_persediaan_barang";

  function getAll_ignited()
  {
    $this->datatables->select("id_stok,nama_brg,jenis_brg,merk_brg,ukuran_brg,satuan_brg,stok,lokasi_brg");
    $this->datatables->where('is_aktif', 1);
    $this->datatables->from($this->tabel);
    $this->datatables->add_column(
      'view',
      '<button class="btn btn-icon icon-left btn-warning" id-pk="$1" id="persediaan_u">
                                                    <i class="fas fa-edit"></i>
                                                    Ubah
                                                </button>
                                                <button class="btn btn-icon icon-left btn-danger" id-pk="$1" id="persediaan_d">
                                                    <i class="fas fa-trash"></i>
                                                    Hapus
                                                </button>',
      'id_stok'
    );
    return $this->datatables->generate();
  }

  function insertDB($data, $stok, $tipe)
  {
    $this->db->insert($this->tabel, $data);
    if ($tipe === '1') {
      $this->db->insert($this->tabelstok, $stok);
    }
  }

  function updateDB($data, $id)
  {
    $this->db->where('id_stok', $id);
    $this->db->update($this->tabel, $data);
  }

  function getBy($param, $id)
  {
    $this->db->where($param, $id);
    return $this->db->get($this->tabel)->row();
  }

  function getAll()
  {
    $this->db->where('is_aktif', 1);
    $this->db->where('tipe', 1);
    return $this->db->get($this->tabel)->result();
  }
}
