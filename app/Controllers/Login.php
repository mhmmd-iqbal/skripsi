<?php

namespace App\Controllers;

use App\Models\ModelUser;
use CodeIgniter\API\ResponseTrait;

class Login extends BaseController
{
    use ResponseTrait;
    protected $validation;
    protected $session;
    function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
    }
    public function index()
    {
        return redirect()->to('/');
    }

    public function signOut()
    {
        $this->session->destroy();
        return redirect()->to('/');
    }

    public function redirect()
    {
        $level = $this->session->level;

        switch ($level) {
            case '1':
                return redirect()->to('/admin');
                break;
            case '2':
                return redirect()->to('/user');
                break;
            default:
                return redirect()->to('/');
                break;
        }
    }

    function validasi()
    {
        $db = new ModelUser();
        if (!$this->validate([
            'username' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Tidak Boleh Kosong'
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Tidak Boleh Kosong'
                ]
            ],
        ])) {
            // return redirect()->to('/login')->with('validation', $this->validation);
            $res = [
                'error' => TRUE,
                'message' => $this->validation
            ];
            return $this->setResponseFormat('json')->respond($res);
        }
        $username = $this->request->getVar('username', FILTER_SANITIZE_STRING);
        $password = $this->request->getVar('password', FILTER_SANITIZE_STRING);
        $data = $db->checkUser($username)->first();

        if ($data == null) {
            $res = [
                'error' => TRUE,
                'message' => "Password atau username tidak sesuai"
            ];
            return $this->setResponseFormat('json')->respond($res);
        }

        $cekPass = password_verify($password, $data['pass']);;
        if ($cekPass == FALSE) {
            $res = [
                'error' => TRUE,
                'message' => "Password atau username tidak sesuai"
            ];
            return $this->setResponseFormat('json')->respond($res);
        }
        $sessionData = [
            'username'  => $data['username'],
            'status'    => $data['status'],
            'logged_in' => TRUE,
            'level'     => 2
        ];
        $this->session->set($sessionData);
        $res = [
            'error' => FALSE,
            'message' => "Login Berhasil"
        ];
        return $this->setResponseFormat('json')->respond($res);
    }

    // function cek()
    // {
    //     if ($this->request->getMethod() == 'post') {
    //         $username = $this->request->getVar('username');
    //         $email = $this->request->getVar('email');
    //         $pass = $this->request->getVar('pass');

    //         $dataModel = new UserModel;
    //         $data = $dataModel->checkUser($username, $email)->first();

    //         if ($data == null) {
    //             $this->session->setFlashdata('error', 'Password, Email dan Username Tidak Sesuai');
    //             return redirect()->to('/login');
    //         }
    //         $cekPass = $data['pass'];
    //         $res =  password_verify($pass, $cekPass);

    //         if ($res == FALSE) {
    //             $this->session->setFlashdata('error', 'Password, Email dan Username Tidak Sesuai');
    //             return redirect()->to('/login');
    //         }
    //         $sessionData = [
    //             'username'  => $data['username'],
    //             'level'     => $data['id_level'],
    //             'email'     => $data['email'],
    //         ];
    //         $this->session->set($sessionData);

    //         switch ($data['id_level']) {
    //             case "1":
    //                 return redirect()->to('/Admin/dashboard');
    //                 break;
    //             default:
    //                 $this->session->setFlashdata('error', 'Sabar ya, Programmer lagi nge Develop hak akses kamu');
    //                 return redirect()->to('/login');
    //         }
    //     }
    // }
}
