<?php

namespace App\Controllers\Admin;

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

    public function index()
    {
        $data['judul'] = 'MASTER DATA | KECAMATAN';
        $data['username'] = $this->session->username;
        $data['active'] = 'masterdata';
        return view('admin/konten/kecamatan', $data);
    }

    public function show($id)
    {
        $data['judul'] = 'MASTER DATA | KECAMATAN';
        $data['username'] = $this->session->username;
        $data['active'] = 'masterdata';
        $data['kecamatan'] = $this->db->where('uid', $id)->first();
        $data['totalDesa'] = $this->dbDesa->where('id_kecamatan', $data['kecamatan']['id'])->countAllResults();


        $data['tahunMulai'] = $this->dbPenjualan->orderBy('tahun', 'ASC')->first();
        $data['tahunMulai'] = $data['tahunMulai']['tahun'] ?? date('Y');

        $i = 0;
        for ($tahun = (int) $data['tahunMulai']; $tahun <= date('Y'); $tahun++) {
            $pendapatan[$tahun] = 0;
            $produksi[$tahun] = 0;
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
                $produksi[$tahun]   += $penjualan['total_produksi'];
                $pendapatan[$tahun] += $penjualan['total_pendapatan'];
            }
        }
        $data['totalProduksi']      = $produksi;
        $data['totalPendapatan']    = $pendapatan;
        $data['tahun']              = $year;

        return view('admin/konten/detailKecamatan', $data);
    }

    public function create()
    {
        $kecamatan = $this->request->getVar('kecamatan', FILTER_SANITIZE_STRING);
        $data = [
            'uid'   => Uuid::uuid4(),
            'kecamatan' => $kecamatan
        ];
        $validate = $this->validation->run($data, 'kecamatan');
        if ($validate) {
            $this->db->save($data);
            $res = [
                'err' => FALSE
            ];
        } else {
            $res = [
                'err' => TRUE,
                'msg' => $this->validation->getErrors()
            ];
        }
        return $this->respond($res, 200);
    }

    public function update($uid)
    {
        $row = $this->db->where('uid', $uid)->first();
        $kecamatan = $this->request->getVar('kecamatan', FILTER_SANITIZE_STRING);
        $data = [
            'kecamatan' => $kecamatan
        ];
        $validate = $this->validation->run($data, 'kecamatan');
        if ($validate) {
            $this->db->where('uid', $uid)
                ->set($data)
                ->update();
            $res = [
                'err' => FALSE
            ];
            return $this->respond($res, 200);
        } else {
            $res = [
                'err' => TRUE,
                'msg' => $this->validation->getErrors()
            ];
        }

        return $this->respond($res, 200);
    }

    function get()
    {
        $list = $this->db->get_datatables();
        $data = array();
        $no = $this->request->getPost('start');
        foreach ($list as $field) {
            $aksi = '<div class="aksi">' .
                '<a href="kecamatan/' . $field->uid . '" class="btn btn-info btn-sm" ><i class="fa fa-eye"></i> </a>' .
                '<button class="btn btn-success btn-sm ubah" data-uid="' . $field->uid . '" ><i class="fa fa-edit"></i> </button>' .
                '<button class="btn btn-danger btn-sm hapus" data-uid="' . $field->uid . '"><i class="fa fa-times"></i> </button>' .
                '</div>';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->kecamatan;
            $row[] = date('d M Y h:i:s', strtotime($field->created_at));
            $row[] = $aksi;
            $data[] = $row;
        }

        $output = array(
            "start" => $this->request->getPost('start'),
            "draw" => $this->request->getPost('draw'),
            "recordsTotal" => $this->db->count_all(),
            "recordsFiltered" => $this->db->count_filtered(),
            "data" => $data,
        );
        return $this->respond($output, 200);
    }
}
