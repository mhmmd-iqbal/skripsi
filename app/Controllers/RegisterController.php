<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Admin\ModelSeller;
use App\Models\Admin\ModelUser;

class RegisterController extends BaseController
{
    function __construct()
    {
        $this->session = \Config\Services::session();
        $this->validation =  \Config\Services::validation();
        helper('text');
    }
    public function index()
    {
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'email' => [
                    'label'  => 'Email',
                    'rules'  => 'required|valid_email|is_unique[user.email]',
                    'errors' => [
                        'required' => 'Email wajib diisi',
                        'valid_email' => 'Format Email salah',
                        'is_unique' => 'Email ini sudah pernah terdaftar'
                    ]
                ],
                'name' => [
                    'label'  => 'Name',
                    'rules'  => 'required|max_length[255]|min_length[3]|alpha_space',
                    'errors' => [
                        'required' => 'Nama wajib diisi',
                        'alpha_space' => 'Nama tidak boleh mengandung angka',
                        'max_length' => 'Nama terlalu panjang',
                        'min_length' => 'Nama terlalu pendek',
                    ]
                ],
                'password' => [
                    'label'  => 'Password',
                    'rules'  => 'required|min_length[5]|max_length[255]',
                    'errors' => [
                        'required' => 'Password wajib diisi',
                        'min_length' => 'Password terlalu pendek',
                        'max_length' => 'Password terlalu panjang',
                    ]
                ],
                'password_confirm' => [
                    'label'  => 'Password Confirm',
                    'rules'  => 'required|matches[password]',
                    'errors' => [
                        'required' => 'Retype Password wajib diisi',
                        'matches' => 'Retype Password tidak sesuai',
                    ]
                ],

            ];
            if (!$this->validate($rules)) {
                return redirect()->back()->withInput();
            }
            $name = $this->request->getVar('name', FILTER_SANITIZE_STRING);
            $email = $this->request->getVar('email', FILTER_SANITIZE_EMAIL);
            $password = password_hash($this->request->getVar('password'), PASSWORD_DEFAULT);
            $status    = $this->request->getVar('status');
            $verified_account = 0;
            $sign_as = $this->request->getVar('sign_as');

            // Generate Username Terlebih dahulu
            $username = strtoupper(substr($name, 0, 3) . random_string('numeric', 5));

            // Simpan ke User
            $user = new ModelUser();
            $save_user = $user->save([
                'username'  => $username,
                'email' => $email,
                'password'  => $password,
                'level' => $sign_as,
            ]);

            // Simpan ke Seller atau Customer. Tergantung 'sign_as'
            if ($sign_as == 'seller') {
                $save_data = $seller = new ModelSeller();
                $seller->save([
                    'username' => $username,
                    'name'  => $name,
                    'verified_account' => $verified_account
                ]);
            } else if ($sign_as == 'customer') {
                echo "Belum Buat";
            }

            if ($save_user && $save_data) {
                return redirect()->to('/login')->with('success', 'Akun anda telah dibuat. Cek Email untuk verifikasi akun');
            }
            return dd(['err' => 'gagal simpan']);
        } else {
            $data['error'] = $this->validation;
            return view('user-register', $data);
        }
    }
}
