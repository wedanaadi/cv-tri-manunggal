<?php
class BarangUpah_m extends CI_Model
{
  private $tabel = "m_upah_barang";
  private $tabelstok = "m_persediaan_barang";

  function getAll_ignited()
  {
    $this->datatables->select("id_barang,nama_barang_upah,jenis_brg,merk_brg,ukuran_brg,satuan,tipe");
    $this->datatables->where('is_aktif', 1);
    $this->datatables->from($this->tabel);
    $this->datatables->add_column(
      'view',
      '<button class="btn btn-icon icon-left btn-warning" id-pk="$1" id="bu_u">
                                                    <i class="fas fa-edit"></i>
                                                    Ubah
                                                </button>
                                                <button class="btn btn-icon icon-left btn-danger" id-pk="$1" id="bu_d">
                                                    <i class="fas fa-trash"></i>
                                                    Hapus
                                                </button>',
      'id_barang'
    );
    return $this->datatables->generate();
  }

  function insertDB($data, $stok, $tipe)
  {
    $this->db->insert($this->tabel, $data);
    // if ($tipe === '1') {
    //   $this->db->insert($this->tabelstok, $stok);
    // }
  }

  function updateDB($data, $id, $stok, $tipe)
  {
    $this->db->where('id_barang', $id);
    $this->db->update($this->tabel, $data);
    // if ($tipe === '1') {
    //   $this->db->where('barang_id', $id);
    //   $count = $this->db->get($this->tabelstok)->num_rows();
    //   if ($count > 0) {
    //     $this->db->where('barang_id', $id);
    //     $this->db->update($this->tabelstok, $stok);
    //   } else {
    //     $stok['barang_id'] = $id;
    //     $stok['id_stok'] = uniqid();
    //     $this->db->insert($this->tabelstok, $stok);
    //   }
    // } else {
    //   $this->db->where('barang_id', $id);
    //   $count = $this->db->get($this->tabelstok)->num_rows();
    //   if ($count > 0) {
    //     $this->db->where('barang_id', $id);
    //     $this->db->delete($this->tabelstok);
    //   }
    // }
  }

  function getBy($param, $id)
  {
    $this->db->where($param, $id);
    return $this->db->get($this->tabel)->row();
  }

  function getAll($tipe)
  {
    $this->db->where('is_aktif', 1);
    $this->db->where('tipe', $tipe);
    return $this->db->get($this->tabel)->result();
  }
}
