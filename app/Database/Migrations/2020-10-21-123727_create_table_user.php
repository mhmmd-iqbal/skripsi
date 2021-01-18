<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableUser extends Migration
{
	public function up()
	{
			$this->forge->addField([
					'id'          => [
						'type'           => 'INT',
						'constraint'     => 5,
						'unsigned'       => true,
						'auto_increment' => true,
					],
					'username'       => [
							'type'           => 'VARCHAR',
							'constraint'     => '100',
					],
					'email' => [
							'type'           => 'TEXT',
					],
					'password' => [
							'type'           => 'VARCHAR',
							'constraint'	 => 255
					],
					'status' => [
							'type'           => 'INT',
							'constraint'	 => 1
					],
					'level' => [
							'type'           => 'varchar',
							'constraint'	 => 10
					],
					'created_at' => [
							'type'           => 'DATETIME',
					],
					'deleted_at' => [
							'type'           => 'DATETIME',
							'null'           => true,
					],
					'updated_at' => [
							'type'           => 'TIMESTAMP',
					],
			]);
			$this->forge->addKey('id', true);
			$this->forge->createTable('tb_user');
	}

	public function down()
	{
			$this->forge->dropTable('tb_user');
	}
}
