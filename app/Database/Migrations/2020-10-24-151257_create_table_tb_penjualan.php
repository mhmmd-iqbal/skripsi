<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableTbPenjualan extends Migration
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
			'total_produksi'       => [
				'type'           => 'INT',
				'constrain'      => 11,
			],
			'harga'       => [
				'type'           => 'FLOAT',
				'constrain'      => 11,
			],
			'total_pendapatan'       => [
				'type'           => 'FLOAT',
				'constrain'      => 11,
			],
			'created_by' => [
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
		$this->forge->addForeignKey('id_desa', 'tb_desa', 'id');
		$this->forge->createTable('tb_penjualan');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('tb_penjualan');
		//
	}
}
