<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\ModelUser;
use CodeIgniter\API\ResponseTrait;

class AksiProfile extends BaseController
{
    use ResponseTrait;
    function __construct()
    {
        $this->session = \Config\Services::session();
        if ($this->session->username == null) {
            $res = [
                'text'   => 'Session is null',
            ];
            return $this->respond($res, 200);
        }
    }
    function getDataUser()
    {
        $username = $this->session->username;
        $db = new ModelUser();
        $data = $db->getUser($username)->first();
        return $this->respond($data, 200);
    }

    function update()
    {
        if ($this->request->getFile('logo')->getName() == '') {
            $rules = [
                'name_usaha' => 'required',
                'phone_usaha' => 'max_length[15]',
                'name' => 'required',
                'phone' => 'max_length[15]',
                'kabupaten' => 'max_length[255]',
            ];

            if (!$this->validate($rules)) {
                $validation =  \Config\Services::validation();
                $res = [
                    'text'   => $validation->getErrors(),
                    'status' => FALSE
                ];
                return $this->respond($res, 400);
            }

            $data = [
                'name' => $this->request->getVar('name', FILTER_SANITIZE_STRING),
                'phone' => $this->request->getVar('phone', FILTER_SANITIZE_NUMBER_INT),
                'whatsapp' => $this->request->getVar('whatsapp') == TRUE ? TRUE : FALSE,
                'name_usaha' => $this->request->getVar('name_usaha', FILTER_SANITIZE_STRING),
                'phone_usaha' => $this->request->getVar('phone_usaha', FILTER_SANITIZE_NUMBER_INT),
                'whatsapp_usaha' => $this->request->getVar('whatsapp_usaha') == '1' ? '1' : '0',
                'kabupaten' => $this->request->getVar('kabupaten', FILTER_SANITIZE_STRING),
                'address' => $this->request->getVar('address', FILTER_SANITIZE_STRING),
            ];
        } else {
            $rules = [
                'name_usaha' => 'required',
                'phone_usaha' => 'max_length[15]',
                'name' => 'required',
                'phone' => 'max_length[15]',
                'kabupaten' => 'max_length[255]',
                'logo' => 'uploaded[logo]|max_size[logo,500]|is_image[logo]|mime_in[logo,image/jpg,image/jpeg,image/png]',
            ];

            if (!$this->validate($rules)) {
                $validation =  \Config\Services::validation();
                $res = [
                    'text'   => $validation->getErrors(),
                    'status' => FALSE
                ];
                return $this->respond($res, 400);
            }

            $file = $this->request->getFile('logo');
            $newName = $file->getRandomName();
            $file->move($this->base_file . '/logo', $newName);

            $data = [
                'name' => $this->request->getVar('name', FILTER_SANITIZE_STRING),
                'phone' => $this->request->getVar('phone', FILTER_SANITIZE_NUMBER_INT),
                'whatsapp' => $this->request->getVar('whatsapp') == TRUE ? TRUE : FALSE,
                'name_usaha' => $this->request->getVar('name_usaha', FILTER_SANITIZE_STRING),
                'phone_usaha' => $this->request->getVar('phone_usaha', FILTER_SANITIZE_NUMBER_INT),
                'whatsapp_usaha' => $this->request->getVar('whatsapp_usaha') == '1' ? '1' : '0',
                'kabupaten' => $this->request->getVar('kabupaten', FILTER_SANITIZE_STRING),
                'address' => $this->request->getVar('address', FILTER_SANITIZE_STRING),
                'logo' => $newName,
            ];
        }


        $username = $this->session->username;

        $dataModel = new ModelUser();
        $simpan = $dataModel
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

    function updateLanjutan()
    {
        if ($this->request->getFile('cover')->getName() != '') {
            $rules = [
                'cover' => 'uploaded[cover]|max_size[cover,700]|is_image[cover]|mime_in[cover,image/jpg,image/jpeg,image/png]',
            ];

            if (!$this->validate($rules)) {
                $validation =  \Config\Services::validation();
                $res = [
                    'text'   => $validation->getErrors(),
                    'status' => FALSE
                ];
                return $this->fail($res, 400);
            }

            $file = $this->request->getFile('cover');
            $newName = $file->getRandomName();
            $file->move($this->base_file . '/cover', $newName);

            $data['cover'] =  $newName;
        }
        $data['description'] = $this->request->getVar('description', FILTER_SANITIZE_STRING);


        $username = $this->session->username;

        $dataModel = new ModelUser();
        $simpan = $dataModel
            ->where('username', $username)
            ->set($data)
            ->update();

        if ($simpan == TRUE) {
            $res = [
                'text'  => 'Data Telah Diperbarui',
                'status' => TRUE,
            ];
            return $this->respondCreated($res);
        }
        return $this->fail('Error update data', 400);
    }
}
