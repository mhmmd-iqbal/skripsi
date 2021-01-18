<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ModelDesa;
use App\Models\ModelKecamatan;
use App\Models\ModelPenjualan;
use CodeIgniter\API\ResponseTrait;
use App\Controllers\Traits\AlgoritmaTrait;

class CalculateController extends BaseController
{
    use ResponseTrait;
    use AlgoritmaTrait;
    protected $session;
    function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = new ModelPenjualan();
        $this->desa = new ModelDesa();
        $this->kecamatan = new ModelKecamatan();
    }

    public function laporan()
    {
        // $data['kecamatan'] = $this->kecamatan->get()->getResultObject();
        // $data['id'] = $this->request->getVar('id') ?? $data['kecamatan'][0]->id;

        // $data['tahunMulai'] = $this->db->orderBy('tahun', 'ASC')->first();
        // $data['tahunMulai'] = $data['tahunMulai']['tahun'] ?? date('Y');


        // $desa = $this->desa
        //     ->where('id_kecamatan', $data['id'])
        //     ->get()
        //     ->getResultObject();
        // $data['data'] = [];
        // for ($tahun = $data['tahunMulai']; $tahun <= date('Y'); $tahun++) {
        //     $data['tahun'][$tahun]['produksi'] = 0;
        //     $data['tahun'][$tahun]['harga'] = 0;
        // }
        // foreach ($desa as $i => $d) {
        //     $penjualan = $this->db->where('id_desa', $d->id)->get()->getResultObject();
        //     for ($tahun = $data['tahunMulai']; $tahun <= date('Y'); $tahun++) {
        //         foreach ($penjualan as $ii => $dd) {
        //             $data['data'][$i]['tahun'][$dd->tahun]['produksi'] = (int) $dd->total_produksi;
        //             $data['data'][$i]['tahun'][$dd->tahun]['harga'] = (int) $dd->harga;
        //             $data['data'][$i]['desa'] = $d->desa;
        //             if ($tahun != $dd->tahun) {
        //                 $data['data'][$i]['tahun'][$tahun]['produksi'] = 0;
        //                 $data['data'][$i]['tahun'][$tahun]['harga'] = 0;
        //             }
        //             $data['tahun'][$tahun]['produksi'] += $data['data'][$i]['tahun'][$tahun]['produksi'];
        //             $data['tahun'][$tahun]['harga'] = $data['data'][$i]['tahun'][$tahun]['harga'];
        //         }
        //     }
        // }

        // // return $this->respond($data, 200);

        // $data['judul'] = 'DATA MINING | LAPORAN';
        // $data['username'] = $this->session->username;
        // $data['active'] = 'laporan';
        // return view('admin/konten/laporan', $data);
    }

    public function index()
    {

        $data['raw']        = $this->tableData();
        $data['itemSet']    = $this->itemSet($data['raw']);
        $data['support']    = $this->support($data['itemSet']);
        $data['newItemSet'] = $this->newItemSet($data['support']);
        $data['support']    = $this->support($data['newItemSet']);
        $data['confidence'] = $this->confidence($data['support']);
        // return $this->respond($data, 200);

        $data['judul'] = 'DATA MINING | Algoritma';
        $data['username'] = $this->session->username;
        $data['active'] = 'calculate';
        return view('admin/konten/kalkulasi', $data);
    }
}
