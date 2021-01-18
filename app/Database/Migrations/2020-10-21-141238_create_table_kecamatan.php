<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableKecamatan extends Migration
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
			'kecamatan'       => [
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
		$this->forge->createTable('tb_kecamatan');
	}
	
	//--------------------------------------------------------------------
	
	public function down()
	{
		//
		$this->forge->dropTable('tb_kecamatan');
	}
}
