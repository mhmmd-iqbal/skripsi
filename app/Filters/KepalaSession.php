<?php

namespace App\Filters;

use App\Models\ModelUser;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class KepalaSession implements FilterInterface
{
    protected $session;
    function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = new ModelUser();
    }
    public function before(RequestInterface $request, $arguments = null)
    {
        // Do something here
        $user = $this->db
            ->where([
                'level' => $this->session->username,
                'email' => $this->session->email
            ])
            ->first();

        // if (($this->session->level != 'kepala') || (!$user['status'])) {
        // return redirect()->to('/');
        // }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
