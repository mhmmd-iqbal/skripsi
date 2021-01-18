<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableDesa extends Migration
{
	public function up()
	{
		//
		$this->forge->addField([
			'id'          => [
				'type'           => 'INT',
				'constraint'     => 5,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'uid' => [
				'type' => 'VARCHAR',
				'constraint' => 255,
			],
			'id_kecamatan' => [
				'type'	=> 'INT',
				'constraint' => 5,
				'unsigned'	=> TRUE
			],
			'desa'       => [
				'type'           => 'TEXT',
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
		$this->forge->addForeignKey('id_kecamatan', 'tb_kecamatan', 'id');
		$this->forge->createTable('tb_desa');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		//
		$this->forge->dropTable('tb_desa');
	}
}
