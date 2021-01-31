<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\ModelDesa;
use App\Models\ModelKecamatan;
use App\Models\ModelPenjualan;
use CodeIgniter\API\ResponseTrait;
use Ramsey\Uuid\Uuid;


class KecamatanController extends BaseController
{
    use ResponseTrait;
    protected $session;
    function __construct()
    {
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->db = new ModelKecamatan();
        $this->dbDesa = new ModelDesa();
        $this->dbPenjualan   = new ModelPenjualan();
    }


    public function show($id)
    {
        $data['judul'] = 'MASTER DATA | KECAMATAN';
        $data['username'] = $this->session->username;
        $data['active'] = 'calculate';
        $data['kecamatan'] = $this->db->where('uid', $id)->first();
        $data['totalDesa'] = $this->dbDesa->where('id_kecamatan', $data['kecamatan']['id'])->countAllResults();


        $data['tahunMulai'] = $this->dbPenjualan->orderBy('tahun', 'ASC')->first();
        $data['tahunMulai'] = $data['tahunMulai']['tahun'] ?? date('Y');

        $i = 0;
        for ($tahun = (int) $data['tahunMulai']; $tahun <= date('Y'); $tahun++) {
            $pendapatan[$tahun] = 0;
            $produksi[$tahun] = 0;
            $harga[$tahun] = 0;
            $iterasi[$tahun] = 0;
            $hargaRata[$tahun] = 0;
            $year[$i] = $tahun;
            $i++;
        }

        $allDesa = $this->dbDesa
            ->where('id_kecamatan', $data['kecamatan']['id'])
            ->asObject()
            ->findAll();

        foreach ($allDesa as $desa) {
            for ($tahun = $data['tahunMulai']; $tahun <= date('Y'); $tahun++) {
                $penjualan = $this->dbPenjualan
                    ->where([
                        'id_desa'   => $desa->id,
                        'tahun'     => $tahun
                    ])
                    ->first();
                if ($penjualan !== null) {
                    if ($penjualan['harga'] !== '0' && $penjualan['harga'] !== 0) {
                        $harga[$tahun]     += $penjualan['harga'];
                        $iterasi[$tahun]++;
                    }
                    $produksi[$tahun]   += $penjualan['total_produksi'];
                    $pendapatan[$tahun] += $penjualan['total_pendapatan'];
                }
            }
        }
        foreach ($year as $tahun) {
            $hargaRata[$tahun] =  $iterasi[$tahun] !== 0 ? $harga[$tahun] / $iterasi[$tahun] : 0;
        }
        $data['hargaRata']          = $hargaRata;
        $data['totalProduksi']      = $produksi;
        $data['totalPendapatan']    = $pendapatan;

        $data['tahun']              = $year;

        return view('user/konten/detailKecamatan', $data);
    }
}
