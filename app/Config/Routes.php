<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->group('/', function ($routes) {
	$routes->get('', 'LoginController::index');
	$routes->get('/test', 'LoginController::test');
	$routes->post('/test', 'LoginController::test');
	$routes->post('/login', 'LoginController::index');
	$routes->get('/logout', 'LoginController::sign_out');
});
$routes->group('user', ['filter' => 'kepala_auth', 'namespace' => 'App\Controllers\User'], function ($routes) {
	$routes->get('', 'UserController::index');
	$routes->resource('penjualan', [
		'controller' => 'PenjualanController'
	]);


	$routes->get('grafik/penjualan/(:any)', 'PenjualanController::grafik/$1');
	$routes->post('get/penjualan', 'PenjualanController::get');
	$routes->get('export/penjualan', 'PenjualanController::export');
	$routes->post('import/penjualan', 'PenjualanController::import');
	$routes->get('pdf/penjualan', 'PenjualanController::exportPdf');

	$routes->resource('lahan', [
		'controller' => 'LahanController'
	]);
	$routes->post('get/lahan', 'LahanController::get');
	$routes->post('import/lahan', 'LahanController::import');
	$routes->get('export/lahan', 'LahanController::export');
	$routes->get('pdf/lahan', 'LahanController::exportPdf');

	$routes->get('calculate', 'CalculateController::index');
	$routes->post('calculate', 'CalculateController::index');
	$routes->get('pdf/calculate', 'CalculateController::exportPdf');

	$routes->get('laporan', 'CalculateController::laporan');

	$routes->resource('account', [
		'controller' => 'AccountController'
	]);

	$routes->resource('master/kecamatan', [
		'controller' => 'KecamatanController',
		'only'		 => ['show']
	]);
});
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
	$routes->post('get/penjualan', 'PenjualanController::get');
	$routes->get('export/penjualan', 'PenjualanController::export');
	$routes->post('import/penjualan', 'PenjualanController::import');
	$routes->get('pdf/penjualan', 'PenjualanController::exportPdf');

	$routes->post('get/lahan', 'LahanController::get');
	$routes->post('import/lahan', 'LahanController::import');
	$routes->get('export/lahan', 'LahanController::export');
	$routes->get('pdf/lahan', 'LahanController::exportPdf');

	$routes->get('calculate', 'CalculateController::index');
	$routes->post('calculate', 'CalculateController::index');
	$routes->get('pdf/calculate', 'CalculateController::exportPdf');

	$routes->get('laporan', 'CalculateController::laporan');

	$routes->resource('master/user', [
		'controller' => 'UserController',
		'only'		 => ['update']
	]);
});

$routes->group('admin', ['filter' => 'admin_auth', 'namespace' => 'App\Controllers\Admin'], function ($routes) {
	$routes->get('', 'AdminController::index');

	$routes->resource('master/user', [
		'controller' => 'UserController',
		'only'		 => ['index', 'create', 'delete', 'update']
	]);

	$routes->post('master/get/user', 'UserController::get');
	$routes->post('master/user/(:any)', 'UserController::update/$1');

	$routes->resource('master/kecamatan', [
		'controller' => 'KecamatanController',
		'only'		 => ['index', 'show', 'create', 'delete', 'update']
	]);
	$routes->post('master/get/kecamatan', 'KecamatanController::get');

	$routes->resource('master/desa', [
		'controller' => 'DesaController',
		'only'		 => ['index', 'create', 'delete', 'update']
	]);
	$routes->post('master/get/desa', 'DesaController::get');
	$routes->get('master/get/desa/(:any)', 'DesaController::get/$1');
	$routes->get('export/desa', 'DesaController::export');

	$routes->resource('penjualan', [
		'controller' => 'PenjualanController'
	]);


	$routes->get('grafik/penjualan/(:any)', 'PenjualanController::grafik/$1');

	$routes->resource('lahan', [
		'controller' => 'LahanController'
	]);


	$routes->resource('account', [
		'controller' => 'AccountController'
	]);
});

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
