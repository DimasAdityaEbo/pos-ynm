<?php

namespace Config;

$routes= Services::routes();


if(file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('AuthControllera');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

$routes->get('/', 'AuthController::index'); 
$routes->get('login', 'AuthController::index');
$routes->post('login/process', 'AuthController::process');
$routes->get('logout', 'AuthController::logout');

$routes->group('admin', function($routes) {
    $routes->get('dashboard', 'AdminController::index');

    // kategori
    $routes->get('categories', 'CategoryController::index');
    $routes->post('categories/store', 'CategoryController::store');
    $routes->post('categories/update/(:num)', 'CategoryController::update/$1');
    $routes->get('categories/delete/(:num)', 'CategoryController::delete/$1');

    // menu
    $routes->get('menus', 'MenuController::index');
    $routes->post('menus/store', 'MenuController::store');
    $routes->post('menus/update/(:num)', 'MenuController::update/$1');
    $routes->get('menus/delete/(:num)', 'MenuController::delete/$1');

    // user
    $routes->get('users', 'UserController::index');
    $routes->post('users/store', 'UserController::store');
    $routes->post('users/update/(:num)', 'UserController::update/$1');
    $routes->get('users/delete/(:num)', 'UserController::delete/$1');

    // reservasi
    $routes->get('reservations', 'ReservationController::index');
    $routes->post('reservations/store', 'ReservationController::store');
    $routes->post('reservations/update/(:num)', 'ReservationController::update/$1');
    $routes->post('reservations/update_status/(:num)', 'ReservationController::updateStatus/$1');
    $routes->get('reservations/delete/(:num)', 'ReservationController::delete/$1');

    // diskon
    $routes->get('discounts', 'DiscountController::index');
    $routes->post('discounts/store', 'DiscountController::store');
    $routes->post('discounts/update/(:num)', 'DiscountController::update/$1');
    $routes->get('discounts/delete/(:num)', 'DiscountController::delete/$1');

    // laporan
    $routes->get('reports', 'ReportController::index');
});

$routes->group('pos', function($routes) {
    $routes->get('/', 'PosController::index');
    $routes->post('checkout', 'PosController::checkout');

    // reservasi
    $routes->get('reservations', 'PosController::getReservations');
    $routes->post('reservations/store', 'PosController::storeReservation');

    // riwayat transaksi
    $routes->get('transactions', 'PosController::getTransactions');
});

$routes->group('owner', function($routes) {
    $routes->get('dashboard', 'OwnerController::index');

    // reservasi
    $routes->get('reservations', 'OwnerController::reservations');

    // laporan
    $routes->get('reports/sales', 'OwnerController::salesReport');
});

if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

?>