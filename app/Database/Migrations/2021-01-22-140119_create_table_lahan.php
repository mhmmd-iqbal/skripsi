<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableLahan extends Migration
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
			'id_desa' => [
				'type'	=> 'INT',
				'constraint' => 5,
				'unsigned'	=> TRUE
			],
			'tahun'       => [
				'type'           => 'INT',
				'constrain'      => 4,
			],
			'tbm'       => [
				'type'           => 'INT',
				'constrain'      => 11,
			],
			'tm'       => [
				'type'           => 'INT',
				'constrain'      => 11,
			],
			'ttm'       => [
				'type'           => 'INT',
				'constrain'      => 11,
			],
			'jumlah'    => [
				'type'           => 'INT',
				'constrain'      => 11,
			],
			'produksi'    => [
				'type'           => 'INT',
				'constrain'      => 11,
			],
			'produktivitas'    => [
				'type'           => 'INT',
				'constrain'      => 11,
			],
			'jml_petani'    => [
				'type'           => 'INT',
				'constrain'      => 11,
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
		$this->forge->addForeignKey('id_desa', 'tb_desa', 'id');
		$this->forge->createTable('tb_lahan');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		//
		$this->forge->dropTable('tb_lahan');
	}
}
