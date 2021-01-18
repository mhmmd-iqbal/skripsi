<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\User\ModelSeller;
use CodeIgniter\API\ResponseTrait;


class ProfileController extends BaseController
{

    use ResponseTrait;
    function __construct()
    {
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {

        $data   = [
            'judul'     => 'USER | Profile',
            'username'  => $this->session->username,
            'active'    => 'profile',
        ];
        return view('konten-user/profile', $data);
    }

    public function get()
    {
        $seller  = new ModelSeller();
        $username = $this->session->username;
        $get = $seller->get_data_with_user($username);
        return $this->respond($get, 200);
    }

    public function update()
    {
        $username = $this->session->username;
        $name = $this->request->getVar('name', FILTER_SANITIZE_STRING);
        $phone = $this->request->getVar('phone', FILTER_SANITIZE_NUMBER_INT);
        $address = $this->request->getVar('address', FILTER_SANITIZE_STRING);
        $sex = $this->request->getVar('sex', FILTER_SANITIZE_STRING);
        $company_name = $this->request->getVar('company_name', FILTER_SANITIZE_STRING);
        $company_address = $this->request->getVar('company_address', FILTER_SANITIZE_STRING);
        $company_phone = $this->request->getVar('company_phone', FILTER_SANITIZE_NUMBER_INT);
        $company_desc = $this->request->getVar('company_desc', FILTER_SANITIZE_NUMBER_INT);

        $data = [
            'name' => $name,
            'sex'   => $sex,
            'phone'   => $phone,
            'address'   => $address,
            'company_name'   => $company_name,
            'company_desc'   => $company_desc,
            'company_address'   => $company_address,
            'company_phone'   => $company_phone,
        ];

        if ($this->request->getFile('company_logo')->getName() == '') {
            //     $rules = [
            //         'name' => [
            //             'label'  => 'Name',
            //             'rules'  => 'required|max_length[255]|min_length[3]|alpha_space',
            //             'errors' => [
            //                 'required' => 'Nama wajib diisi',
            //                 'alpha_space' => 'Nama tidak boleh mengandung angka',
            //                 'max_length' => 'Nama terlalu panjang',
            //                 'min_length' => 'Nama terlalu pendek',
            //             ]
            //         ],
            //         'company_name' => 'required',
            //     ];

            //     if (!$this->validate($rules)) {
            //         $validation =  $this->validation;
            //         $res = [
            //             'text'   => $validation->getErrors(),
            //             'status' => FALSE
            //         ];
            //         return $this->respond($res, 400);
            //     }


        } else {
            // $rules = [
            //     'name' => [
            //         'label'  => 'Name',
            //         'rules'  => 'required|max_length[255]|min_length[3]|alpha_space',
            //         'errors' => [
            //             'required' => 'Nama wajib diisi',
            //             'alpha_space' => 'Nama tidak boleh mengandung angka',
            //             'max_length' => 'Nama terlalu panjang',
            //             'min_length' => 'Nama terlalu pendek',
            //         ]
            //     ],
            //     'company_name' => 'required',
            //     'company_logo' => 'uploaded[logo]|max_size[logo,500]|is_image[logo]|mime_in[logo,image/jpg,image/jpeg,image/png]',
            // ];

            // if (!$this->validate($rules)) {
            //     $validation =  $this->validation;
            //     $res = [
            //         'text'   => $validation->getErrors(),
            //         'status' => FALSE
            //     ];
            //     return $this->respond($res, 400);
            // }

            $file = $this->request->getFile('company_logo');
            $newName = $file->getRandomName();
            $file->move($this->base_file . '/logo', $newName);

            $data['company_logo'] = $newName;
        }


        $username = $this->session->username;

        $seller = new ModelSeller();
        $simpan = $seller
            ->where('username', $username)
            ->set($data)
            ->update();

        if ($simpan == TRUE) {
            $res = [
                'text'  => 'Data Telah Diperbarui',
                'status' => TRUE,
            ];
        }
        return $this->respondCreated($res);
    }
}
