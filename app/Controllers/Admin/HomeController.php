<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class HomeController extends BaseController
{
    protected $session;
    function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $data   = [
            'judul'     => 'ADMIN | Dashboard',
            'username'  => $this->session->username,
            'active'    => 'dashboard'
        ];
        return view('konten-admin/dashboard', $data);
    }
}
