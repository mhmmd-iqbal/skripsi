<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Development implements FilterInterface
{
    protected $session;
    function __construct()
    {
        $this->session = \Config\Services::session();
    }
    public function before(RequestInterface $request, $arguments = null)
    {
        // Do something here
        // return view('konten-main/development');
        return redirect()->to('/');
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
