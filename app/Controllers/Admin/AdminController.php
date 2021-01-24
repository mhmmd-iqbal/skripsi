<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ModelDesa;
use App\Models\ModelKecamatan;
use App\Models\ModelPenjualan;
use App\Models\ModelUser;
use CodeIgniter\API\ResponseTrait;

class AdminController extends BaseController
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
        return view('admin/konten/dashboard', $data);
    }

    // function get()
    // {
    //     $list = $this->db->get_datatables();
    //     $data = array();
    //     $no = $this->request->getPost('start');
    //     foreach ($list as $field) {
    //         $aktifasi_btn = $field->status == '1' ? '<button style="margin-right: 5px;" class="btn btn-sm btn-danger  non-aktif" value="' . $field->id . '"><i class="fa fa-times"></i> Non Aktifkan</button>' : '<button style="margin-right: 5px;" class="btn btn-sm btn-info  aktif" value="' . $field->id . '"><i class="fa fa-power-off"></i> Aktifkan</button>';
    //         $no++;
    //         $row = array();
    //         $row[] = $no;
    //         $row[] = $field->username;
    //         $row[] = $field->email;
    //         $row[] = $field->created_at;
    //         $row[] = $field->status == '1' ? '<div class="badge badge-blue">Aktif</div>' : '<div class="badge badge-red">Tidak Aktif</div>';
    //         $row[] = '<button style="margin-right: 5px;" class="btn btn-sm btn-success  update" value="' . $field->id . '"><i class="fa fa-pencil"></i> Edit</button>' . $aktifasi_btn;
    //         $data[] = $row;
    //     }

    //     $output = array(
    //         "start" => $this->request->getPost('start'),
    //         "draw" => $this->request->getPost('draw'),
    //         "recordsTotal" => $this->db->count_all(),
    //         "recordsFiltered" => $this->db->count_filtered(),
    //         "data" => $data,
    //     );
    //     return $this->respond($output, 200);
    // }

}
