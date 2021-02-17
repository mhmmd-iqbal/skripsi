<?php

namespace App\Controllers\User;

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

        if (
            $this->db
            ->where([
                'username' => $this->session->username,
                'email' => $this->session->email
            ])->countAllResults() === 0
        ) {
            $this->session->destroy();
            return redirect()->to('/');
        }

        $data['user'] = (object) $this->db
            ->where([
                'username' => $this->session->username,
                'email' => $this->session->email
            ])
            ->first();
        if ($data['user'] === null) {
            $this->session->destroy();
            return redirect()->to('/');
        }
        return view('user/konten/account', $data);
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
        if ($password !== '') {
            $this->session->destroy();
            return redirect()->to('/');
        }
        if (
            $this->db
            ->where([
                'username' => $this->session->username,
                'email' => $this->session->email
            ])->countAllResults() === 0
        ) {
            $this->session->destroy();
            return redirect()->to('/');
        }
        session()->setFlashdata('success', 'Berhasil Mengubah Data');
        return redirect()->to('/user/account');
    }
}
