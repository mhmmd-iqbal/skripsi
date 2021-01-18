<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\Admin\ModelKategori;
use App\Models\User\ModelProduct;
use CodeIgniter\API\ResponseTrait;


class ProductController extends BaseController
{

    use ResponseTrait;
    function __construct()
    {
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->product = new ModelProduct();
    }

    public function index()
    {

        $data   = [
            'judul'     => 'USER | Product',
            'username'  => $this->session->username,
            'active'    => 'product',
        ];
        return view('konten-user/product', $data);
    }

    public function new()
    {
        $category = new ModelKategori();
        $data   = [
            'judul'     => 'USER | Product',
            'username'  => $this->session->username,
            'active'    => 'product',
            'category'  => $category->get()->getResultObject()
        ];
        return view('konten-user/add_product', $data);
    }

    public function get()
    {
        $username = $this->session->username;
        $list = $this->product->get_datatables($username);
        $data = array();
        $no = $this->request->getPost('start');
        foreach ($list as $field) {

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->category_name;
            $row[] = $field->product_name;
            $row[] = "Rp. " . number_format($field->product_price, 0, ',', '.');
            $row[] = number_format($field->product_disc, 0, ',', '.') . "%";
            $row[] = "Rp. " . number_format($field->product_price - ($field->product_price * $field->product_disc / 100), 0, ',', '.');
            $row[] = '<a href="/sys/seller/' . $field->id . '" class="btn btn-info detail btn-sm" style="margin-right: 5px;"><i class="fa fa-search"></i> </a>' . '<button  style="margin-right: 5px;" class="btn btn-sm btn-danger" value="' . $field->id . '"><i class="fa fa-trash-o"></i> </button>';
            $data[] = $row;
        }

        $output = array(
            "start" => $this->request->getPost('start'),
            "draw" => $this->request->getPost('draw'),
            "recordsTotal" => $this->product->count_all($username),
            "recordsFiltered" => $this->product->count_filtered($username),
            "data" => $data,
        );
        return $this->respond($output, 200);
    }

    public function add()
    {
        $username = $this->session->username;
        $product_name = $this->request->getVar('product_name', FILTER_SANITIZE_STRING);
        $id_category = $this->request->getVar('id_category', FILTER_SANITIZE_STRING);
        $product_quantity = $this->request->getVar('product_quantity', FILTER_SANITIZE_NUMBER_INT);
        $product_price = $this->request->getVar('product_price', FILTER_SANITIZE_NUMBER_INT);
        $product_disc = $this->request->getVar('product_disc', FILTER_SANITIZE_NUMBER_INT);
        $product_desc = $this->request->getVar('product_desc', FILTER_SANITIZE_STRING);

        $file = $this->request->getFile('product_image');
        $newName = $file->getRandomName();
        $file->move($this->base_file . '/product', $newName);

        $data = [
            'username'  => $username,
            'id_category' => $id_category,
            'product_name'  => $product_name,
            'product_quantity' => $product_quantity,
            'product_price' => $product_price,
            'product_disc' => $product_disc,
            'product_desc' => $product_desc,
            'product_image' => $newName
        ];
        $product = new ModelProduct();
        $simpan = $product->save($data);

        if ($simpan == TRUE) {
            $res = [
                'text'  => 'Data Telah Diperbarui',
                'status' => TRUE,
            ];
        }
        return $this->respondCreated($res);
    }
}
