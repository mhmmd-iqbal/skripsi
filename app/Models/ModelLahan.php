<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelLahan extends Model
{
    protected $table      = 'tb_lahan';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'uid',
        'id_desa',
        'tahun',
        'tbm',
        'tm',
        'ttm',
        'jumlah',
        'produksi',
        'produktivitas',
        'jml_petani',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    private function _get_query()
    {
        $db    = \Config\Database::connect()->table($this->table);
        $column_order = array(null, 'desa', null);
        $column_search = array('desa');
        $order = array('created_at' => 'desc');
        $db->from($this->table);
        $i = 0;
        foreach ($column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $db->groupStart();
                    $db->like($item, $_POST['search']['value']);
                } else {
                    $db->orLike($item, $_POST['search']['value']);
                }

                if (count($column_search) - 1 == $i)
                    $db->groupEnd();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $db->orderBy($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($order)) {
            $db->orderBy(key($order), $order[key($order)]);
        }
    }

    function get_datatables($year = null)
    {
        $db    = \Config\Database::connect()->table($this->table)
            ->select('tb_lahan.*')
            ->select('tb_desa.desa')
            ->select('tb_desa.id_kecamatan')
            ->select('tb_kecamatan.kecamatan')
            ->join('tb_desa', 'tb_desa.id = tb_lahan.id_desa')
            ->join('tb_kecamatan', 'tb_kecamatan.id = tb_desa.id_kecamatan');
        if ($year != null) {
            $db->where('tb_lahan.tahun', $year);
        }
        if (($_POST['kecamatan'] != null) && ($_POST['kecamatan'] != '')) {
            $db->where('tb_desa.id_kecamatan', $_POST['kecamatan']);
        }
        $this->_get_query();

        if ($_POST['length'] != -1) {
            $db->limit($_POST['length'], $_POST['start']);
        }
        $query = $db->get();
        return $query->getResultObject();
    }

    function count_filtered($year = null)
    {
        $db    = \Config\Database::connect()->table($this->table)
            ->select('tb_lahan.*')
            ->select('tb_desa.desa')
            ->select('tb_desa.id_kecamatan')
            ->select('tb_kecamatan.kecamatan')
            ->join('tb_desa', 'tb_desa.id = tb_lahan.id_desa')
            ->join('tb_kecamatan', 'tb_kecamatan.id = tb_desa.id_kecamatan');
        if ($year != null) {
            $db->where('tb_lahan.tahun', $year);
        }
        if (($_POST['kecamatan'] != null) && ($_POST['kecamatan'] != '')) {
            $db->where('tb_desa.id_kecamatan', $_POST['kecamatan']);
        }
        $this->_get_query();
        return $db->countAllResults();
    }

    public function count_all($year = null)
    {
        $db    = \Config\Database::connect()->table($this->table)->join('tb_desa', 'tb_desa.id = tb_lahan.id_desa')->join('tb_kecamatan', 'tb_kecamatan.id = tb_desa.id_kecamatan');
        if ($year != null) {
            $db->where('tb_lahan.tahun', $year);
        }
        if (($_POST['kecamatan'] != null) && ($_POST['kecamatan'] != '')) {
            $db->where('tb_desa.id_kecamatan', $_POST['kecamatan']);
        }
        return $db->countAllResults();
    }
}
