<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\ModelDesa;
use App\Models\ModelKecamatan;
use App\Models\ModelPenjualan;
use CodeIgniter\API\ResponseTrait;
use App\Controllers\Traits\AlgoritmaTrait;
use Mpdf\Mpdf;

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
        $data['analist']    = false;
        $data['supportSearch']  = 0;
        $data['limitSearch']    = 0;
        if ($this->request->getMethod() === 'post') {
            $data['supportSearch'] = $this->request->getVar('support');
            $data['limitSearch'] = $this->request->getVar('limit');
            $data['analist']    = true;
            $data['itemSet']    = $this->itemSet($data['raw'], $data['limitSearch']);
            $data['support']    = $this->support($data['itemSet'], $data['supportSearch']);
            $data['newItemSet'] = $this->newItemSet($data['support']);
            $data['support']    = $this->support($data['newItemSet'], $data['supportSearch']);
            $data['confidence'] = $this->confidence($data['support']);
        }


        $data['judul'] = 'DATA MINING | Algoritma';
        $data['username'] = $this->session->username;
        $data['active'] = 'calculate';
        return view('user/konten/kalkulasi', $data);
    }

    public function exportPdf()
    {
        $data['raw']        = $this->tableData();
        $data['supportSearch'] = $this->request->getVar('support');
        $data['limitSearch'] = $this->request->getVar('limit');

        $data['itemSet']    = $this->itemSet($data['raw'], $data['limitSearch']);
        $data['support']    = $this->support($data['itemSet'], $data['supportSearch']);
        $data['newItemSet'] = $this->newItemSet($data['support']);
        $data['support']    = $this->support($data['newItemSet'], $data['supportSearch']);
        $data['confidence'] = $this->confidence($data['support']);

        // return view('admin/konten/pdfKalkulasi', $data);
        $mpdf = new Mpdf(['debug' => FALSE, 'mode' => 'utf-8', 'orientation' => 'l']);
        $mpdf->WriteHTML(view('user/konten/pdfKalkulasi', $data));
        $mpdf->Output('Analisa Penjualan.pdf', 'I');
        exit;
    }

    public function kecamatan($uid)
    {
        $data['judul'] = 'MASTER DATA | KECAMATAN';
        $data['username'] = $this->session->username;
        $data['active'] = 'calculate';
        $data['kecamatan'] = $this->kecamatan->where('uid', $uid)->first();
        $data['totalDesa'] = $this->desa->where('id_kecamatan', $data['kecamatan']['id'])->countAllResults();


        $data['tahunMulai'] = $this->db->orderBy('tahun', 'ASC')->first();
        $data['tahunMulai'] = $data['tahunMulai']['tahun'] ?? date('Y');

        $i = 0;
        for ($tahun = (int) $data['tahunMulai']; $tahun <= date('Y'); $tahun++) {
            $pendapatan[$tahun] = 0;
            $produksi[$tahun] = 0;
            $year[$i] = $tahun;
            $i++;
        }

        $allDesa = $this->desa
            ->where('id_kecamatan', $data['kecamatan']['id'])
            ->asObject()
            ->findAll();

        foreach ($allDesa as $desa) {
            for ($tahun = $data['tahunMulai']; $tahun <= date('Y'); $tahun++) {
                $penjualan = $this->db
                    ->where([
                        'id_desa'   => $desa->id,
                        'tahun'     => $tahun
                    ])
                    ->first();
                $produksi[$tahun]   += $penjualan['total_produksi'];
                $pendapatan[$tahun] += $penjualan['total_pendapatan'];
            }
        }
        $data['totalProduksi']      = $produksi;
        $data['totalPendapatan']    = $pendapatan;
        $data['tahun']              = $year;

        return view('user/konten/detailKecamatan', $data);
    }
}
