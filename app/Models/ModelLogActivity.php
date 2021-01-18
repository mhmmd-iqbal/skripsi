<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelLogActivity extends Model
{
    protected $table      = 'log_activity';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'username',
        'last_login',
    ];
}
