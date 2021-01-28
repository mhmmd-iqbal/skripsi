<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ModelUser;
use CodeIgniter\API\ResponseTrait;

class LoginController extends BaseController
{
    use ResponseTrait;
    function __construct()
    {
        $this->session = \Config\Services::session();
    }
    public function index()
    {
        if ($this->request->getMethod() == 'post') {
            $username = $this->request->getVar('username');
            $email    = $this->request->getVar('email');
            $password = $this->request->getVar('password');

            $user = new ModelUser();
            $cek  = $user->where('username', $username)
                ->where('email', $email)
                ->where('status', TRUE)
                ->first();

            if ($cek == null)
                return redirect()->back()->withInput()->with('error', 'Data Login Tidak Sesuai');

            $pass_verification =  password_verify($password, $cek['password']);
            if ($pass_verification == null) {
                return redirect()->back()->withInput()->with('error', 'Data Login Tidak Sesuai');
            }


            //Create Session Data for This User
            $data_session = [
                'logged'    => TRUE,
                'username'  => $cek['username'],
                'email'     => $cek['email'],
                'level'      => $cek['level'],
            ];

            $this->session->set($data_session);
            switch ($cek['level']) {
                case 'admin':
                    return redirect()->to('/admin');
                    break;
                case 'kepala':
                    return redirect()->to('/user');
                    break;
            }
        } else {
            $data['success'] = $this->session->success;
            $data['error'] = $this->session->error;
            return view('login', $data);
        }
    }

    function test()
    {
        if ($this->request->getMethod() == 'post') {
            $data['method'] = $this->request->getVar('method');
            $data['url'] = $this->request->getVar('url');
            $data['api_key'] = $this->request->getVar('api_key');
            $data['api_secret'] = $this->request->getVar('api_secret');
            $data['client_id'] = $this->request->getVar('client_id');
            $data['client_secret'] = $this->request->getVar('client_secret');
            $data['token'] = $this->request->getVar('token');
            $data['timestamp'] = $this->request->getVar('timestamp');
            // $data['timestamp'] = date("c", strtotime(date('Y-m-d\TH:i:sO')));
            // $data['timestamp'] = date('Y-m-d\TH:i:s.000');
            $data['request_body'] = $this->request->getVar('request_body');


            // Examine HTTP Method to Uppercase 
            $data['method'] = strtoupper($data['method']);

            // sha256 request body
            $data['request_body'] = hash('sha256', $data['request_body'], false);
            $data['request_body'] = strtolower($data['request_body']);

            // Construct to sign
            $data['stringtosign'] = $data['method'] . ":" . $data['url'] . ":" . $data['token'] . ":" . $data['request_body'] . ":" . $data['timestamp'];

            $data['strtosigntest'] = "GET:/banking/v2/corporates/h2hauto009/accounts/0611104625:gp9HjjEj813Y9JGoqwOeOPWbnt4CUpvIJbU1mMU4a11MNDZ7Sg5u9a:e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855:2017-03-17T09:44:18.000+07:00";
            // HMAC sha256
            $data['hmacsha256'] = hash_hmac('sha256', $data['stringtosign'], $data['api_secret']);
            $data['hmacsha256test'] = hash_hmac('sha256', $data['strtosigntest'], "f6068d37-0fd8-456a-bced-61ac35af53da");
            // return view('test');
            return $this->respond($data, 200);
        } else {
            return view('test');
        }
    }

    public function sign_out()
    {
        $this->session->destroy();
        return redirect()->to('/');
    }

    // function password()
    // {
    //     $hash = password_hash("admin", PASSWORD_DEFAULT);
    //     $verify = password_verify('admin', $hash);
    //     dd($verify);
    // }
}
