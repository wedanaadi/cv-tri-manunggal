<?php
class Progress_m extends CI_Model
{
  private $orderp = "t_order_proyek";
  private $orderd = "t_order_proyek_detail";
  private $progress = "t_progress_proyek";

  function order_ignited($kepala = 'all')
  {
    $this->datatables->select("id_proyek,nama_proyek_pekerjaan, nospmk, status, DATEDIFF(`tanggal_selesai`,`tanggal_mulai`) AS 'durasi', tanggal_selesai, tanggal_mulai,top.nama_konsumen");
    $this->datatables->from($this->orderp . ' top');
    $this->datatables->join('t_order_proyek_detail topd', 'topd.order_proyek_id=top.id_proyek');
    $this->datatables->join('t_jadwal_proyek tjp', 'tjp.proyek_id=top.id_proyek');
    $this->datatables->join('t_boq boq', 'boq.nama_kegiatan=top.id_proyek');
    $this->datatables->where('top.is_aktif', 1);
    $this->datatables->where('boq.is_aktif', 1);
    $this->datatables->where('tjp.is_aktif', 1);
    $this->datatables->group_by('top.id_proyek');
    if ($kepala !== 'all') {
      $this->datatables->where('topd.kepala_proyek', $kepala);
    }
    $this->datatables->add_column(
      'view',
      '<a href="' . base_url() . 'ProgressProyek_c/detail/$1" class="btn btn-icon icon-left btn-success" id-pk="$1" id="k_u">
          <i class="fas fa-eye"></i>
          Detail
      </a>',
      'id_proyek'
    );
    return $this->datatables->generate();
  }

  function order_ignited_foradmin()
  {
    $this->datatables->select("id_proyek,nama_proyek_pekerjaan, nospmk, status, DATEDIFF(`tanggal_selesai`,`tanggal_mulai`) AS 'durasi', tanggal_selesai, tanggal_mulai," . $this->orderp . ".nama_konsumen");
    $this->datatables->from($this->orderp);
    $this->datatables->join('t_order_proyek_detail opd', 'opd.order_proyek_id=' . $this->orderp . '.id_proyek');
    $this->datatables->join('t_jadwal_proyek jdp', 'jdp.proyek_id=' . $this->orderp . '.id_proyek');
    $this->datatables->join('t_boq boq', 'boq.nama_kegiatan=' . $this->orderp . '.id_proyek');
    $this->datatables->where($this->orderp . '.is_aktif', 1);
    $this->datatables->where('jdp.is_aktif', 1);
    $this->datatables->where('boq.is_aktif', 1);
    $this->datatables->group_by($this->orderp . '.id_proyek');
    $this->datatables->add_column(
      'view',
      '<a href="' . base_url() . 'ProgressProyek_c/detailforadmin/$1" class="btn btn-icon icon-left btn-success" id-pk="$1" id="k_u">
          <i class="fas fa-eye"></i>
          Detail
      </a>',
      'id_proyek'
    );
    return $this->datatables->generate();
  }

  function getProyek($id)
  {
    $this->db->select($this->orderp . '.*, sum(od.vol) as volume,count(od.vol) as countvolume, nospmk, jdp.tanggal_mulai, jdp.tanggal_selesai,DATEDIFF(jdp.tanggal_selesai,jdp.tanggal_mulai) as durasi, od.vol as voljenis, od.sat');
    $this->db->where('id_proyek', $id);
    $this->db->where('jdp.is_aktif', 1);
    $this->db->join('t_jadwal_proyek jdp', 'jdp.proyek_id=' . $this->orderp . '.id_proyek');
    $this->db->join($this->orderd . ' od', 'od.order_proyek_id=' . $this->orderp . '.id_proyek');
    $this->db->group_by($this->orderp . '.id_proyek');
    return $this->db->get($this->orderp)->row();
  }

  function progressadmin_ignited()
  {
    $this->datatables->select("pp.`proyek_id`, pp.`jenis_proyek`, op.`nama_konsumen`");
    $this->datatables->where('op.is_aktif', 1);
    $this->datatables->join('t_jadwal_proyek jp', 'jp.proyek_id=op.id_proyek');
    $this->datatables->join('t_progress_proyek pp', 'pp.proyek_id=op.id_proyek');
    $this->datatables->from('t_order_proyek op');
    $this->datatables->group_by('pp.proyek_id', 'ASC');
    $this->datatables->group_by('op.nama_konsumen', 'ASC');
    return $this->datatables->generate();
  }

  function progressjn_ignited($p, $j, $k = false)
  {
    $this->datatables->select("id_progress,persentase,ket,tanggal,validasi,validasi_ket");
    $this->datatables->from($this->progress);
    $this->datatables->where($this->progress . '.proyek_id', $p);
    $this->datatables->where($this->progress . '.jenis_proyek', $j);
    if ($k) {
      $this->datatables->where('kepala_proyek', $k);
    }
    $this->datatables->add_column(
      'persentases',
      '<div class="progress mb-3">
        <div class="progress-bar bg-success" style="color: #2D2D2D; width: $1%;" role="progressbar" data-width="$1%" aria-valuenow="$1" aria-valuemin="0" aria-valuemax="100">$1 %</div>
      </div>',
      'persentase'
    );
    $this->datatables->add_column(
      'gambar',
      '<td>
        <button class="btn btn-icon icon-left btn-success fotodetil" id-pro="$1">
          <i class="fas fa-eye"></i>
          Lihat
        </button>
      </td>',
      'id_progress'
    );
    $this->datatables->add_column(
      'view',
      '<a href="' . base_url() . 'ProgressProyek_c/formUbah/$1" class="btn btn-icon icon-left btn-warning tomboledit" id-pk="$1">
          <i class="fas fa-pen"></i>
          Edit
      </a>',
      'id_progress'
    );
    return $this->datatables->generate();
  }

  function jenisproyek_kegiatan_ignited($p, $j)
  {
    $this->datatables->select("id_detail,jadwal_id,jenis_proyek_id,kegiatan_id, k.nama_kegiatan,(
      SELECT IFNULL(SUM(`persentase`),0) 
      FROM `t_progress_proyek` 
      WHERE `proyek_id`=`jpd`.`proyek_id` 
      AND `kegiatan_id`=`jpd`.`kegiatan_id`
      AND `jenis_proyek` = `jpd`.`jenis_proyek_id`
      AND `validasi` = '1'
    ) AS persentase,
    startDate,endDate,durasi,
    (
      SELECT IFNULL(SUM(total_pengeluaran),0)
      FROM t_pengeluaran_progress
      INNER JOIN `t_progress_proyek` ON `t_progress_proyek`.`id_progress` = `t_pengeluaran_progress`.`progress_id`
      WHERE `t_pengeluaran_progress`.`kegiatan_id` = `jpd`.kegiatan_id
      AND `t_pengeluaran_progress`.`proyek_id` = `jpd`.proyek_id
      AND `t_pengeluaran_progress`.`jenis_proyek_id` = `jpd`.jenis_proyek_id
      AND `t_progress_proyek`.`validasi` = '1'
    ) AS realisasi, jpd.proyek_id");
    $this->datatables->from('t_jadwal_proyek_detail jpd');
    $this->datatables->join('m_data_kegiatan k', 'k.id_master_kegiatan=jpd.kegiatan_id');
    $this->datatables->join('t_jadwal_proyek jp', 'jp.id_jadwal=jpd.jadwal_id');
    $this->datatables->where('jpd.proyek_id', $p);
    $this->datatables->where('jpd.jenis_proyek_id', $j);
    $this->datatables->add_column(
      'gambar',
      '<td>
        <button class="btn btn-icon icon-left btn-success fotodetil" id-k="$1">
          <i class="fas fa-eye"></i>
          Lihat
        </button>
      </td>',
      'kegiatan_id'
    );
    $this->datatables->add_column(
      'view',
      '<a href="' . base_url() . 'ProgressProyek_c/updateKegiatan/$1/$2/$3" class="btn btn-icon icon-left btn-warning tomboledit" id-pk="$1">
          <i class="fas fa-sync"></i>
          Update
      </a>',
      'proyek_id,jenis_proyek_id,kegiatan_id'
    );
    return $this->datatables->generate();
  }

  function jenisproyek_kegiatan_ignited_admin($p, $j)
  {
    $this->datatables->select("id_detail,jadwal_id,jpd.proyek_id,jenis_proyek_id,kegiatan_id, k.nama_kegiatan,(
      SELECT IFNULL(SUM(`persentase`),0) 
      FROM `t_progress_proyek` 
      WHERE `proyek_id`=`jpd`.`proyek_id` 
      AND `kegiatan_id`=`jpd`.`kegiatan_id`
      AND `jenis_proyek` = `jpd`.`jenis_proyek_id`
      AND `validasi` = '1'
    ) AS persentase,
    startDate,endDate,durasi,
    (
      SELECT IFNULL(SUM(total_pengeluaran),0)
      FROM t_pengeluaran_progress
      INNER JOIN `t_progress_proyek` ON `t_progress_proyek`.`id_progress` = `t_pengeluaran_progress`.`progress_id`
      WHERE `t_pengeluaran_progress`.`kegiatan_id` = `jpd`.kegiatan_id
      AND `t_pengeluaran_progress`.`proyek_id` = `jpd`.proyek_id
      AND `t_pengeluaran_progress`.`jenis_proyek_id` = `jpd`.jenis_proyek_id
      AND `t_progress_proyek`.`validasi` = '1'
    ) AS realisasi,
    (
      SELECT COUNT(id_progress) FROM t_progress_proyek
      WHERE proyek_id = `jpd`.proyek_id 
      AND jenis_proyek = `jpd`.jenis_proyek_id
      AND kegiatan_id = `jpd`.kegiatan_id
      AND validasi = '0'
    ) AS countprogress");
    $this->datatables->from('t_jadwal_proyek_detail jpd');
    $this->datatables->join('m_data_kegiatan k', 'k.id_master_kegiatan=jpd.kegiatan_id');
    $this->datatables->join('t_jadwal_proyek jp', 'jp.id_jadwal=jpd.jadwal_id');
    $this->datatables->where('jpd.proyek_id', $p);
    $this->datatables->where('jpd.jenis_proyek_id', $j);
    $this->datatables->add_column(
      'gambar',
      '<td>
        <button class="btn btn-icon icon-left btn-success fotodetil" id-k="$1">
          <i class="fas fa-eye"></i>
          Lihat
        </button>
      </td>',
      'kegiatan_id'
    );
    $this->datatables->add_column(
      'view',
      '<a href="' . base_url() . 'ProgressProyek_c/updateKegiatan/$1/$2/$3" class="btn btn-icon icon-left btn-info tomboledit" id-pk="$1">
          <i class="fas fa-tasks"></i>
          Progress
      </a>',
      'proyek_id,jenis_proyek_id,kegiatan_id'
    );
    return $this->datatables->generate();
  }

  public function progress_kegiatan_ignited($proyek, $jenis, $kegiatan)
  {
    $this->datatables->select("id_progress,proyek_id,jenis_proyek,kegiatan_id,tanggal,ket,persentase,status,
                                validasi_ket,validasi,progress,
                                (
                                  SELECT IFNULL(SUM(`total_pengeluaran`),0) 
                                  FROM `t_pengeluaran_progress` 
                                  WHERE `progress_id` = pp.`id_progress`
                                ) AS 'pengeluaran'");
    $this->datatables->from('t_progress_proyek pp');
    $this->datatables->where('pp.proyek_id', $proyek);
    $this->datatables->where('pp.jenis_proyek', $jenis);
    $this->datatables->where('pp.kegiatan_id', $kegiatan);
    $this->datatables->add_column(
      'gambar',
      '<td>
        <button class="btn btn-icon icon-left btn-success fotodetil" id-pro="$1">
          <i class="fas fa-eye"></i>
          Lihat
        </button>
      </td>',
      'id_progress'
    );
    $this->datatables->add_column(
      'view',
      '<a href="' . base_url() . 'ProgressProyek_c/formUbah/$1" class="btn btn-icon icon-left btn-warning tomboledit" id-pk="$1">
          <i class="fas fa-pen"></i>
          Edit
      </a>',
      'id_progress'
    );
    return $this->datatables->generate();
  }

  public function progress_kegiatan_ignited_admin($proyek, $jenis, $kegiatan)
  {
    $this->datatables->select("id_progress,proyek_id,jenis_proyek,kegiatan_id,tanggal,ket,persentase,status,
                                validasi_ket,validasi,progress,
                                (
                                  SELECT IFNULL(SUM(`total_pengeluaran`),0) 
                                  FROM `t_pengeluaran_progress` 
                                  WHERE `progress_id` = pp.`id_progress`
                                ) AS 'pengeluaran'");
    $this->datatables->from('t_progress_proyek pp');
    $this->datatables->where('pp.proyek_id', $proyek);
    $this->datatables->where('pp.jenis_proyek', $jenis);
    $this->datatables->where('pp.kegiatan_id', $kegiatan);
    $this->datatables->add_column(
      'gambar',
      '<td>
        <button class="btn btn-icon icon-left btn-success fotodetil" id-pro="$1">
          <i class="fas fa-eye"></i>
          Lihat
        </button>
      </td>',
      'id_progress'
    );
    $this->datatables->add_column(
      'view',
      '<a href="' . base_url() . 'ProgressProyek_c/detailadmin/$1" class="btn btn-icon icon-left btn-primary tomboledit" id-pk="$1">
          <i class="fas fa-check"></i>
          Validasi
      </a>',
      'id_progress'
    );
    return $this->datatables->generate();
  }

  function getDetail($id)
  {
    $this->db->select("pp.`id_progress`, pp.`proyek_id`, pp.`jenis_proyek`, jn.nama_jenis_proyek");
    $this->db->where('proyek_id', $id);
    $this->db->join('m_jenis_proyek jn', 'jn.id_jenis_proyek=pp.jenis_proyek');
    return $this->db->get($this->progress . ' pp')->result();
  }

  function progressjn_ignited2($p, $j)
  {
    $this->datatables->select("id_progress,persentase,ket,tanggal,validasi");
    $this->datatables->from($this->progress);
    $this->datatables->where($this->progress . '.proyek_id', $p);
    $this->datatables->where($this->progress . '.jenis_proyek', $j);
    $this->datatables->add_column(
      'persentases',
      '<div class="progress mb-3">
        <div class="progress-bar bg-success" style="color: #2D2D2D; width: $1%;" role="progressbar" data-width="$1%" aria-valuenow="$1" aria-valuemin="0" aria-valuemax="100">$1 %</div>
      </div>',
      'persentase'
    );
    $this->datatables->add_column(
      'gambar',
      '<td>
        <button class="btn btn-icon icon-left btn-success fotodetil" id-pro="$1">
          <i class="fas fa-eye"></i>
          Lihat
        </button>
      </td>',
      'id_progress'
    );
    $this->datatables->add_column(
      'view',
      '<a href="' . base_url() . 'ProgressProyek_c/detailadmin/$1" class="btn btn-icon icon-left btn-primary" id-pk="$1">
          <i class="fas fa-eye"></i>
          Detail
      </a>',
      'id_progress'
    );
    return $this->datatables->generate();
  }
}
