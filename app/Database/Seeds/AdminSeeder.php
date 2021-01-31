<?php

namespace App\Database\Seeds;


class AdminSeeder extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 1; $i++) {
            $data = [
                'username'      => 'admin',
                'email'         => 'admin@admin.com',
                'password'      => password_hash("admin", PASSWORD_DEFAULT),
                'status'        => TRUE,
                'level'         => 'admin',
                'created_at'    => date('Y-m-d h:i:s'),
                'updated_at'    => date('Y-m-d h:i:s'),

            ];

            $this->db->table('tb_user')->insert($data);
        }
    }
}
