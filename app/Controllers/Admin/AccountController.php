<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ModelUser;
use CodeIgniter\API\ResponseTrait;

class AccountController extends BaseController
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
        $data['judul'] = 'MASTER DATA | ACCOUNT';
        $data['username'] = $this->session->username;
        $data['active'] = 'account';

        $data['user'] = (object) $this->db
            ->where([
                'level' => $this->session->username,
                'email' => $this->session->email
            ])
            ->first();
        return view('admin/konten/account', $data);
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

        session()->setFlashdata('success', 'Berhasil Mengubah Data');
        return redirect()->to('/admin/account');
    }
}
