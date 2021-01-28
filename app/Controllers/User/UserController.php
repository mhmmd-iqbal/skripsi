<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\ModelDesa;
use App\Models\ModelKecamatan;
use App\Models\ModelPenjualan;
use App\Models\ModelUser;
use CodeIgniter\API\ResponseTrait;

class UserController extends BaseController
{

    use ResponseTrait;
    protected $session;
    function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = new ModelUser();
    }

    public function index()
    {
        $data['judul'] = 'DASHBOARD';
        $data['username'] = $this->session->username;
        $data['active'] = 'dashboard';

        $allKecamatan = new ModelKecamatan();
        $allDesa = new ModelDesa();
        $allKecamatan = $allKecamatan->findAll();
        foreach ($allKecamatan as $i => $kecamatan) {
            $data['kecamatan'][$i]['kecamatan'] = $kecamatan['kecamatan'];
            $data['kecamatan'][$i]['totalDesa'] = $allDesa->where('id_kecamatan', $kecamatan['id'])->countAllResults();
        }

        $data['countUser'] = $this->db
            ->select('level')
            ->selectCount('username', 'totalUser')
            ->where('status', true)
            ->groupBy('level')
            ->find();

        $penjualan = new ModelPenjualan();
        $data['tahunMulai'] = $penjualan->orderBy('tahun', 'ASC')->first();
        $data['tahunMulai'] = $data['tahunMulai']['tahun'] ?? date('Y');
        return view('user/konten/dashboard', $data);
    }
}
