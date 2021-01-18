<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\User\ModelSeller;

class HomeController extends BaseController
{
    function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $db = new ModelSeller();
        $user = $db->get_data_with_user($this->session->username);
        $data   = [
            'judul'     => 'USER | Dashboard',
            'username'  => $this->session->username,
            'active'    => 'dashboard',
            'user'     => $user
        ];
        return view('konten-user/dashboard', $data);
    }
}
