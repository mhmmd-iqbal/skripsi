<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ModelUser;
use CodeIgniter\API\ResponseTrait;

class UserController extends BaseController
{
    use ResponseTrait;
    protected $session;
    function __construct()
    {
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->db = new ModelUser();
    }

    public function index()
    {
        $data['judul'] = 'MASTER DATA | USER';
        $data['username'] = $this->session->username;
        $data['active'] = 'masterdata';
        return view('admin/konten/user', $data);
    }

    public function create()
    {
        $username = $this->request->getVar('username', FILTER_SANITIZE_STRING);
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password', FILTER_SANITIZE_STRING);
        $level = $this->request->getVar('level', FILTER_SANITIZE_STRING);

        $data = [
            'username'  => $username,
            'email'     => $email,
            'password'  => password_hash($password, PASSWORD_DEFAULT),
            'status'    => FALSE,
            'level'     => $level,
        ];
        $validate = $this->validation->run($data, 'user');
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

    public function update($id)
    {

        if ($this->request->getVar('status') !== null) {
            $data = [
                'status' => $this->request->getVar('status')
            ];
        } else {
            $cek = $this->db->where('id', $id)->first();
            $username = $this->request->getVar('username', FILTER_SANITIZE_STRING);
            $email = $this->request->getVar('email');
            $password = $this->request->getVar('password', FILTER_SANITIZE_STRING);
            $level = $this->request->getVar('level', FILTER_SANITIZE_STRING);

            $data = [
                'username'  => $username ?? $cek['username'],
                'email'     => $email ?? $cek['email'],
                'password'  => $password !== '' ? password_hash($password, PASSWORD_DEFAULT) : $cek['password'],
                'level'     => $level ?? $cek['level'],
            ];
        }

        $this->db->where('id', $id)
            ->set($data)
            ->update();
        $res = [
            'err' => FALSE
        ];
        return $this->respond($res, 200);
    }

    function delete($username)
    {
        $delete = $this->db->delete([
            'username' => $username
        ]);

        if ($delete) {
            return $this->respond([
                'err'   => FALSE
            ], 200);
        }
        return $this->respond([
            'err'   => TRUE
        ], 200);
    }

    function get()
    {
        $list = $this->db->get_datatables();
        $data = array();
        $no = $this->request->getPost('start');
        foreach ($list as $field) {
            $aksi = '<div class="aksi">' .
                '<button class="btn btn-success btn-sm ubah" 
                    data-uid="' . $field->id . '" 
                    data-username= "' . $field->username . '"
                    data-email= "' . $field->email . '"
                    data-level= "' . $field->level . '"
                ><i class="fa fa-edit"></i> </button>' .
                '<button class="btn btn-danger btn-sm hapus" data-uid="' . $field->id . '"><i class="fa fa-times"></i> </button>' .
                ($field->status == FALSE ? '<button class="btn btn-info btn-sm aktif" data-uid="' . $field->id . '"><i class="fa fa-check"></i> </button>' : '<button class="btn btn-info btn-sm nonaktif" data-uid="' . $field->id . '"><i class="fa fa-power-off"></i> </button>') .
                '</div>';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->username;
            $row[] = $field->email;
            $row[] = $field->status == '1' ? '<div class="badge badge-info">Aktif</div>' : '<div class="badge badge-danger">Tidak AKtif</div>';
            $row[] = $field->level;
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

    public function status($id)
    {
    }
}
