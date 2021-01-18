<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ModelKecamatan;
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
    }

    public function index()
    {
        $data['judul'] = 'MASTER DATA | KECAMATAN';
        $data['username'] = $this->session->username;
        $data['active'] = 'masterdata';
        return view('admin/konten/kecamatan', $data);
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
