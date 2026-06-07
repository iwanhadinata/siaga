<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Route default mengarah ke halaman login Shield
$routes->get('/', '\CodeIgniter\Shield\Controllers\LoginController::loginView');

// KUMPULKAN SEMUA RUTE TERPROTEKSI DI DALAM GRUP INI
$routes->group('', ['filter' => 'session'], static function ($routes) {

    $routes->get('dashboard', 'DashboardController::index');

    // Group khusus untuk modul Jemaat
    $routes->group('jemaat', static function ($routes) {
        $routes->get('/', 'JemaatController::index', ['as' => 'jemaat/index']);
        $routes->get('ajaxList', 'JemaatController::ajaxList', ['as' => 'jemaat/ajaxList']);
        $routes->get('autocomplete', 'JemaatController::autocomplete', ['as' => 'jemaat/autocomplete']);
        $routes->get('create', 'JemaatController::create', ['as' => 'jemaat/create']);
        $routes->post('store', 'JemaatController::store', ['as' => 'jemaat/store']);
        $routes->get('search', 'JemaatController::search', ['as' => 'jemaat/search']);
        $routes->get('detail/(:num)', 'JemaatController::detail/$1', ['as' => 'jemaat/detail']);

        // TAMBAHKAN RUTE INI UNTUK MENANGANI ERROR 404
        // TODO untuk ke depannya:
        // $routes->get('edit/(:num)', 'JemaatController::edit/$1', ['as' => 'jemaat/edit']);
        // $routes->post('update/(:num)', 'JemaatController::update/$1', ['as' => 'jemaat/update']);
        // $routes->post('delete/(:num)', 'JemaatController::delete/$1', ['as' => 'jemaat/delete']);
    });

    // Group khusus untuk modul Pelayanan
    $routes->group('pelayanan', static function ($routes) {
        $routes->get('/', 'PelayananController::index');
        $routes->get('create', 'PelayananController::create');
        $routes->post('store', 'PelayananController::store');
        $routes->get('edit/(:num)', 'PelayananController::edit/$1');
        $routes->post('update/(:num)', 'PelayananController::update/$1');
        $routes->post('delete/(:num)', 'PelayananController::delete/$1');
    });
});

// Mengaktifkan route bawaan Shield (login, logout, register, dll)
service('auth')->routes($routes);
