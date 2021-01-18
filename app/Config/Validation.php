<?php

namespace Config;

class Validation
{
	//--------------------------------------------------------------------
	// Setup
	//--------------------------------------------------------------------

	/**
	 * Stores the classes that contain the
	 * rules that are available.
	 *
	 * @var array
	 */
	public $ruleSets = [
		\CodeIgniter\Validation\Rules::class,
		\CodeIgniter\Validation\FormatRules::class,
		\CodeIgniter\Validation\FileRules::class,
		\CodeIgniter\Validation\CreditCardRules::class,
	];

	/**
	 * Specifies the views that are used to display the
	 * errors.
	 *
	 * @var array
	 */
	public $templates = [
		'list'   => 'CodeIgniter\Validation\Views\list',
		'single' => 'CodeIgniter\Validation\Views\single',
	];

	public $kecamatan = [
		'kecamatan' => 'required'
	];

	public $desa = [
		'id_kecamatan' => 'required',
		'desa' => 'required',
	];

	public $penjualan = [
		'id_desa' => 'required',
		'tahun' => 'required',
		'total_produksi' => 'required',
		'harga' => 'required',
		'total_pendapatan' => 'required',
		'created_by' => 'required'
	];

	public $user = [
		'username' => 'required',
		'email' => 'required',
		'password' => 'required',
		'level' => 'required',
	];

	//--------------------------------------------------------------------
	// Rules
	//--------------------------------------------------------------------
}
