<?php
class Boq_m extends CI_Model
{
  private $tabel = "t_boq";

  function getAll()
  {
    $this->datatables->where($this->tabel . '.is_aktif', 1);
    return $this->db->get($this->tabel)->result();
  }

  function getAll_ignited()
  {
    $this->datatables->select("id_boq,nama_satker,nama_kegiatan,nama_konsumen");
    $this->datatables->where($this->tabel . '.is_aktif', 1);
    $this->datatables->join('m_konsumen k', 'k.id_konsumen=' . $this->tabel . '.nama_satker');
    $this->datatables->group_by('nama_satker');
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
      'id_boq'
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

  function insertDB($data)
  {
    $this->db->trans_start();
    $this->db->insert($this->tabel, $data);
    $this->db->trans_complete();
  }

  function updateDB($data, $id)
  {
    $this->db->trans_start();
    $this->db->where('id_boq', $id);
    $this->db->update($this->tabel, $data);
    $this->db->trans_complete();
  }

  function hapusDB($data, $id)
  {
    $this->db->where('id_master_kegiatan', $id);
    $this->db->update($this->tabel, $data);
  }

  function getBy($id)
  {
    $this->db->select($this->tabel . ".*,k.nama_konsumen,jn.nama_jenis_proyek,op.nama_kegiatan as kegiatan");
    $this->db->where('id_boq', $id);
    $this->db->join('m_konsumen k', 'k.id_konsumen=' . $this->tabel . '.nama_satker');
    $this->db->join('m_jenis_proyek jn', 'jn.id_jenis_proyek=' . $this->tabel . '.jenis_proyek');
    $this->db->join('t_order_proyek op', 'op.id_proyek=' . $this->tabel . '.nama_kegiatan');
    return $this->db->get($this->tabel)->row();
  }

  function getJsonDetail($k)
  {
    $sql = "SELECT `t_boq`.`id_boq`, jn.`nama_jenis_proyek` FROM `t_boq`
            INNER JOIN `m_jenis_proyek` jn ON jn.`id_jenis_proyek` = `t_boq`.`jenis_proyek`
            WHERE `nama_kegiatan` = '$k' AND `t_boq`.`is_aktif` = '1'";
    return $this->db->query($sql)->result();
  }

  function getJsonProyek($id)
  {
    $this->db->select("op.`id_proyek`, op.`nama_kegiatan`,op.`nama_proyek_pekerjaan`,op.`lokasi`,op.`tahun_anggaran`");
    $this->db->where($this->tabel . ".nama_satker", $id);
    $this->db->where($this->tabel . ".is_aktif", 1);
    $this->db->join('t_order_proyek op', 'op.id_proyek=' . $this->tabel . ".nama_kegiatan");
    $this->db->group_by($this->tabel . '.nama_kegiatan');
    $this->db->from($this->tabel);
    return $this->db->get()->result();
  }
}
