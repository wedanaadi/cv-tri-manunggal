<?php
class BarangMasuk_m extends CI_Model
{
  private $tabel = "t_barang_masuk";

  function getAll_ignited()
  {
    $this->datatables->select("id_brg_masuk,jumlah,ket,tgl,nama_barang_upah,jenis_brg,merk_brg,ukuran_brg,satuan,tipe");
    $this->datatables->where($this->tabel . '.is_aktif', 1);
    $this->datatables->from($this->tabel);
    $this->datatables->join('m_upah_barang up', "up.id_barang=" . $this->tabel . ".barang_id");
    $this->datatables->add_column(
      'view',
      '<button class="btn btn-icon icon-left btn-warning" id-pk="$1" id="bm_u">
                                                    <i class="fas fa-edit"></i>
                                                    Ubah
                                                </button>
                                                <button class="btn btn-icon icon-left btn-danger" id-pk="$1" id="bm_d">
                                                    <i class="fas fa-trash"></i>
                                                    Hapus
                                                </button>',
      'id_brg_masuk'
    );
    return $this->datatables->generate();
  }

  function insertDB($data)
  {
    $this->db->insert($this->tabel, $data);
  }

  function updateDB($data, $id)
  {
    $this->db->where('id_brg_masuk', $id);
    $this->db->update($this->tabel, $data);
  }

  function getBy($param, $id)
  {
    $this->db->select($this->tabel . ".*,pb.lokasi_brg,ub.nama_barang_upah");
    $this->db->where($param, $id);
    $this->db->join('m_persediaan_barang pb', 'pb.barang_id=' . $this->tabel . ".barang_id");
    $this->db->join('m_upah_barang ub', 'ub.id_barang=' . $this->tabel . ".barang_id");
    return $this->db->get($this->tabel)->row();
  }
}
