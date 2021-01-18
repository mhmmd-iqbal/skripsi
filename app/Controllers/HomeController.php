<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Admin\ModelKategori;
use App\Models\User\ModelProduct;
use App\Models\ModelBanner;

class HomeController extends BaseController
{
    public function index()
    {
        return view('login', ['error' => null]);
    }
}
