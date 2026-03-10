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
    $routes->get('users/create', 'Admin\UserController::create', ['filter' => 'role:admin']);
    $routes->post('users', 'Admin\UserController::store', ['filter' => 'role:admin']);
    $routes->get('users/(:num)/edit', 'Admin\UserController::edit/$1', ['filter' => 'role:admin']);
    $routes->post('users/(:num)', 'Admin\UserController::update/$1', ['filter' => 'role:admin']);
    $routes->post('users/(:num)/delete', 'Admin\UserController::delete/$1', ['filter' => 'role:admin']);
});

$routes->group('procurement', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('purchase-requests', 'Procurement\PurchaseRequestController::index', ['filter' => 'role:admin,employee,it_staff']);
    $routes->get('purchase-requests/create', 'Procurement\PurchaseRequestController::create', ['filter' => 'role:admin,employee,it_staff']);
    $routes->post('purchase-requests', 'Procurement\PurchaseRequestController::store', ['filter' => 'role:admin,employee,it_staff']);
    $routes->get('purchase-requests/(:num)/edit', 'Procurement\PurchaseRequestController::edit/$1', ['filter' => 'role:admin,employee,it_staff']);
    $routes->get('purchase-requests/(:num)/items.csv', 'Procurement\PurchaseRequestController::itemsCsv/$1', ['filter' => 'role:admin,employee,it_staff']);
    $routes->post('purchase-requests/(:num)/update', 'Procurement\PurchaseRequestController::update/$1', ['filter' => 'role:admin,employee,it_staff']);
    $routes->post('purchase-requests/(:num)/submit', 'Procurement\PurchaseRequestController::submit/$1', ['filter' => 'role:admin,employee,it_staff']);
    $routes->post('purchase-requests/(:num)/cancel', 'Procurement\PurchaseRequestController::cancel/$1', ['filter' => 'role:admin,employee,it_staff']);

    $routes->get('approvals/pending', 'Procurement\PurchaseApprovalController::pending', ['filter' => 'role:admin']);
    $routes->post('approvals/(:num)/approve', 'Procurement\PurchaseApprovalController::approve/$1', ['filter' => 'role:admin']);
    $routes->post('approvals/(:num)/reject', 'Procurement\PurchaseApprovalController::reject/$1', ['filter' => 'role:admin']);

    $routes->get('purchase-orders', 'Procurement\PurchaseOrderController::index', ['filter' => 'role:admin']);
    $routes->post('purchase-orders/from-pr/(:num)', 'Procurement\PurchaseOrderController::createFromPr/$1', ['filter' => 'role:admin']);
    $routes->post('purchase-orders/(:num)/issue', 'Procurement\PurchaseOrderController::issue/$1', ['filter' => 'role:admin']);

    $routes->get('po-requests', 'Procurement\PoRequestController::index', ['filter' => 'role:admin']);
    $routes->post('po-requests/from-po/(:num)', 'Procurement\PoRequestController::createFromPo/$1', ['filter' => 'role:admin']);
    $routes->post('po-requests/(:num)/approve', 'Procurement\PoRequestController::approve/$1', ['filter' => 'role:admin']);
    $routes->post('po-requests/(:num)/reject', 'Procurement\PoRequestController::reject/$1', ['filter' => 'role:admin']);
});

$routes->group('receiving', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('/', 'Receiving\ReceivingController::index', ['filter' => 'role:admin']);
    $routes->get('create/from-po-request/(:num)', 'Receiving\ReceivingController::createFromPoRequest/$1', ['filter' => 'role:admin']);
    $routes->post('/', 'Receiving\ReceivingController::store', ['filter' => 'role:admin']);
    $routes->get('(:num)', 'Receiving\ReceivingController::show/$1', ['filter' => 'role:admin']);
    $routes->get('(:num)/items.csv', 'Receiving\ReceivingController::itemsCsv/$1', ['filter' => 'role:admin']);
    $routes->post('(:num)/post', 'Receiving\ReceivingController::post/$1', ['filter' => 'role:admin']);
    $routes->post('(:num)/void', 'Receiving\ReceivingController::void/$1', ['filter' => 'role:admin']);
    $routes->post('(:num)/validate', 'Receiving\ReceivingValidationController::validateDraft/$1', ['filter' => 'role:admin']);
});

$routes->group('inventory', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('quantities', 'Receiving\InventoryQuantityController::index', ['filter' => 'role:admin,employee,it_staff']);
    $routes->get('quantities/(:num)', 'Receiving\InventoryQuantityController::show/$1', ['filter' => 'role:admin,employee,it_staff']);
    $routes->get('quantities/(:num)/movements.csv', 'Receiving\InventoryQuantityController::movementsCsv/$1', ['filter' => 'role:admin,employee,it_staff']);

    $routes->get('issuance', 'Inventory\IssuanceController::index', ['filter' => 'role:admin,employee,it_staff']);
    $routes->get('issuance/create', 'Inventory\IssuanceController::create', ['filter' => 'role:admin,employee,it_staff']);
    $routes->post('issuance', 'Inventory\IssuanceController::store', ['filter' => 'role:admin,employee,it_staff']);
    $routes->get('issuance/(:num)', 'Inventory\IssuanceController::show/$1', ['filter' => 'role:admin,employee,it_staff']);
    $routes->get('issuance/(:num)/items.csv', 'Inventory\IssuanceController::itemsCsv/$1', ['filter' => 'role:admin,employee,it_staff']);
    $routes->post('issuance/(:num)/submit', 'Inventory\IssuanceController::submit/$1', ['filter' => 'role:admin,employee,it_staff']);
    $routes->post('issuance/(:num)/cancel', 'Inventory\IssuanceController::cancel/$1', ['filter' => 'role:admin,employee,it_staff']);

    $routes->post('issuance/(:num)/approve', 'Inventory\IssuanceApprovalController::approve/$1', ['filter' => 'role:admin']);
    $routes->post('issuance/(:num)/reject', 'Inventory\IssuanceApprovalController::reject/$1', ['filter' => 'role:admin']);
    $routes->post('issuance/(:num)/release', 'Inventory\IssuanceController::release/$1', ['filter' => 'role:admin']);
    $routes->get('issuance/(:num)/allocations.csv', 'Inventory\IssuanceController::allocationsCsv/$1', ['filter' => 'role:admin,employee,it_staff']);
});

$routes->group('reports', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('stock-balance', 'Inventory\ReportingController::stockBalance', ['filter' => 'role:admin,it_staff']);
    $routes->get('stock-movements', 'Inventory\ReportingController::stockMovements', ['filter' => 'role:admin,it_staff']);
    $routes->get('issuances', 'Inventory\ReportingController::issuances', ['filter' => 'role:admin,it_staff']);
    $routes->get('low-stock', 'Inventory\ReportingController::lowStock', ['filter' => 'role:admin,it_staff']);
    $routes->get('fast-moving', 'Inventory\ReportingController::fastMoving', ['filter' => 'role:admin,it_staff']);
});

$routes->group('analytics', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('activity-logs', 'Analytics\AnalyticsController::activityLogs', ['filter' => 'role:admin,it_staff']);
    $routes->get('dashboard', 'Analytics\AnalyticsController::dashboard', ['filter' => 'role:admin,it_staff']);
    $routes->get('events', 'Analytics\AnalyticsController::events', ['filter' => 'role:admin,it_staff']);
    $routes->get('metrics', 'Analytics\AnalyticsController::metrics', ['filter' => 'role:admin,it_staff']);
    $routes->post('track', 'Analytics\AnalyticsController::track', ['filter' => 'role:admin,employee,it_staff']);
});
