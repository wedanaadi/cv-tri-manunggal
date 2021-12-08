<?php
class JenisProyek_m extends CI_Model
{
  private $tabel = "m_jenis_proyek";
  private $detil = "m_jenis_proyek_detail";

  function getAll()
  {
    $this->datatables->where('is_aktif', 1);
    return $this->db->get($this->tabel)->result();
  }

  function getAll_ignited()
  {
    $this->datatables->select("id_jenis_proyek,nama_jenis_proyek");
    $this->datatables->where('is_aktif', 1);
    $this->datatables->from($this->tabel);
    $this->datatables->add_column(
      'view',
      '<button class="btn btn-icon icon-left btn-success" id-pk="$1" id="jn_detail">
          <i class="fas fa-eye"></i>
          Detail
      </button>
      <a href="' . base_url() . 'JenisProyek_c/ubahJN/$1" class="btn btn-icon icon-left btn-warning" id-pk="$1" id="k_u">
          <i class="fas fa-edit"></i>
          Ubah
      </a>
      <button class="btn btn-icon icon-left btn-danger" id-pk="$1" id="jn_d">
          <i class="fas fa-trash"></i>
          Hapus
      </button>',
      'id_jenis_proyek'
    );
    return $this->datatables->generate();
  }

  function getDetail($id)
  {
    $this->db->select($this->detil . ".*, k.nama_kegiatan");
    $this->db->where('jenis_proyek_id', $id);
    $this->db->join('m_data_kegiatan k', 'k.id_master_kegiatan=' . $this->detil . '.kegiatan_id');
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
    $this->db->where('jenis_proyek_id', $id);
    $this->db->delete($this->detil);

    $this->db->where('id_jenis_proyek', $id);
    $this->db->update($this->tabel, $data);

    $this->db->insert_batch($this->detil, $detil);
    $this->db->trans_complete();
  }

  function hapusDB($data, $id)
  {
    $this->db->where('id_jenis_proyek', $id);
    $this->db->update($this->tabel, $data);
  }

  function getBy($table, $id)
  {
    if ($table === 'tabel') {
      $this->db->where('id_jenis_proyek', $id);
      return $this->db->get($this->tabel)->row();
    } else {
      $this->db->select($this->detil . ".*, k.nama_kegiatan");
      $this->db->where('jenis_proyek_id', $id);
      $this->db->join('m_data_kegiatan k', 'k.id_master_kegiatan=' . $this->detil . '.kegiatan_id');
      return $this->db->get($this->detil)->result();
    }
  }
}
