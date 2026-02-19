<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('login', 'Auth\LoginController::index');
$routes->post('login', 'Auth\LoginController::store');
$routes->get('signup', 'Auth\SignupController::index');
$routes->post('signup', 'Auth\SignupController::store');
$routes->post('logout', 'Auth\LogoutController::destroy', ['filter' => 'auth']);

$routes->group('admin', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('dashboard', 'Admin\DashboardController::index', ['filter' => 'role:admin']);
    $routes->get('users', 'Admin\UserController::index', ['filter' => 'role:admin']);
    $routes->post('users/(:num)/role', 'Admin\UserController::assignRole/$1', ['filter' => 'role:admin']);
});
