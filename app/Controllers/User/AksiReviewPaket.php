<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\User\ModelReviewPaket;
use CodeIgniter\API\ResponseTrait;

class AksiReviewPaket extends BaseController
{
    use ResponseTrait;
    function __construct()
    {
        $this->db = new ModelReviewPaket();
        $this->session = \Config\Services::session();
        if ($this->session->username == null) {
            $res = [
                'text'   => 'Session is null',
            ];
            return $this->respond($res, 200);
        }
    }
    function totalReview()
    {
        $username = $this->session->username;
        $data = $this->db->count_all($username);
        return $this->respond($data, 200);
    }
    function getBintangCount()
    {
        $username = $this->session->username;
        $data = [
            1 => $this->db->getBintangCount($username, 1),
            2 => $this->db->getBintangCount($username, 2),
            3 => $this->db->getBintangCount($username, 3),
            4 => $this->db->getBintangCount($username, 4),
            5 => $this->db->getBintangCount($username, 5),
        ];

        return $this->respond($data, 200);
    }
}
