<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\User\ModelPaket;
use App\Models\User\ModelReviewPaket;
use CodeIgniter\API\ResponseTrait;

class AksiPaket extends BaseController
{
    use ResponseTrait;
    function __construct()
    {
        $this->db = new ModelPaket();
        $this->session = \Config\Services::session();
        if ($this->session->username == null) {
            $res = [
                'text'   => 'Session is null',
            ];
            return $this->respond($res, 200);
        }
    }

    function get()
    {
        $username = $this->session->username;
        $list = $this->db->get_datatables($username);
        $data = array();
        $no = $this->request->getPost('start');
        foreach ($list as $field) {
            // Count Total Ulasan Per Data
            $dbReviewer = new ModelReviewPaket();
            $total_ulasan = $dbReviewer->count($field->id);

            // Show Data
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->nama;
            $row[] = $field->kategori;
            $row[] = $field->harga;
            $row[] = $field->status == '1' ? '<div class="badge badge-blue">Aktif</div>' : '<div class="badge badge-red">Tidak Aktif</div>';
            $row[] = "10 Tranksasi";
            $row[] = $total_ulasan . " Ulasan";
            $row[] = '<button style="margin-right: 5px;" class="btn btn-sm btn-info  detail" value="' . $field->id . '"><i class="fa fa-search"></i> </button>' . '<button style="margin-right: 5px;" class="btn btn-sm btn-success  update" value="' . $field->id . '"><i class="fa fa-pencil"></i> </button>' . '<button style="margin-right: 5px;" class="btn btn-sm btn-danger  delete" value="' . $field->id . '"><i class="fa fa-trash-o"></i> </button>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $this->request->getPost('draw'),
            "recordsTotal" => $this->db->count_all($username),
            "recordsFiltered" => $this->db->count_filtered($username),
            "data" => $data,
        );
        return $this->respond($output, 200);
    }
    function tambah()
    {
        helper('text');
        if ($this->request->getFile('gambar')->getName() == '') {
            $rules = [
                'nama' => 'required|max_length[255]',
                'harga' => 'required',
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
                'username' => $this->session->username,
                'nama' => $this->request->getVar('nama', FILTER_SANITIZE_STRING),
                'kategori' => $this->request->getVar('kategori', FILTER_SANITIZE_STRING),
                'harga' => $this->request->getVar('harga', FILTER_SANITIZE_NUMBER_INT),
                'keterangan' => $this->request->getVar('keterangan', FILTER_SANITIZE_STRING),
                'status'    => TRUE,
                'slug'    => random_string('alnum', 15)
            ];
        } else {
            $rules = [
                'nama' => 'required|max_length[255]',
                'harga' => 'required',
                'gambar' => 'uploaded[gambar]|max_size[gambar,500]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]',
            ];

            if (!$this->validate($rules)) {
                $validation =  \Config\Services::validation();
                $res = [
                    'text'   => $validation->getErrors(),
                    'status' => FALSE
                ];
                return $this->respond($res, 400);
            }

            $file = $this->request->getFile('gambar');
            $newName = $file->getRandomName();
            $file->move($this->base_file . '/paket', $newName);

            $data = [
                'username' => $this->session->username,
                'nama' => $this->request->getVar('nama', FILTER_SANITIZE_STRING),
                'kategori' => $this->request->getVar('kategori', FILTER_SANITIZE_STRING),
                'harga' => $this->request->getVar('harga', FILTER_SANITIZE_NUMBER_INT),
                'keterangan' => $this->request->getVar('keterangan', FILTER_SANITIZE_STRING),
                'gambar' => $newName,
                'status'    => TRUE,
                'slug'    => random_string('alnum', 15)
            ];
        }

        $simpan = $this->db->save($data);

        if ($simpan == TRUE) {
            $res = [
                'text'  => 'Data Telah Disimpan',
                'status' => TRUE,
            ];
        }
        return $this->respondCreated($res);
    }

    function detail($id)
    {
        $username = $this->session->username;
        $data = $this->db->getPaket($id, $username)->first();
        $dbReviewer = new ModelReviewPaket();
        $data['totalUlasan'] = $dbReviewer->count($id);
        return $this->respond($data, 200);
    }

    function totalPaket()
    {
        $username = $this->session->username;
        $data = $this->db->count_all($username);
        return $this->respond($data, 200);
    }
}
