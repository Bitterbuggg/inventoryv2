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
    $routes->post('users/(:num)/role', 'Admin\UserController::role/$1', ['filter' => 'role:admin']);
    $routes->post('users/(:num)/permissions/module', 'Admin\UserController::modulePermission/$1', ['filter' => 'role:admin']);
    $routes->post('users/(:num)/delete', 'Admin\UserController::delete/$1', ['filter' => 'role:admin']);
    $routes->get('products', 'Admin\ProductController::index', ['filter' => 'role:admin']);
    $routes->post('products', 'Admin\ProductController::store', ['filter' => 'role:admin']);
    $routes->post('products/(:num)', 'Admin\ProductController::update/$1', ['filter' => 'role:admin']);
    $routes->get('suppliers', 'Admin\SupplierController::index', ['filter' => 'role:admin']);
    $routes->post('suppliers', 'Admin\SupplierController::store', ['filter' => 'role:admin']);
    $routes->post('suppliers/(:num)', 'Admin\SupplierController::update/$1', ['filter' => 'role:admin']);
});

$routes->group('procurement', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('purchase-requests', 'Procurement\PurchaseRequestController::index', ['filter' => 'ability:procurement.view,procurement.pr.create,procurement.pr.approve,procurement.po.create,procurement.por.manage']);
    $routes->get('purchase-requests/create', 'Procurement\PurchaseRequestController::create', ['filter' => 'ability:procurement.pr.create']);
    $routes->get('purchase-requests/(:num)', 'Procurement\PurchaseRequestController::show/$1', ['filter' => 'ability:procurement.view,procurement.pr.create,procurement.pr.approve,procurement.po.create,procurement.por.manage']);
    $routes->post('purchase-requests', 'Procurement\PurchaseRequestController::store', ['filter' => 'ability:procurement.pr.create']);
    $routes->get('purchase-requests/(:num)/edit', 'Procurement\PurchaseRequestController::edit/$1', ['filter' => 'ability:procurement.pr.create']);
    $routes->get('purchase-requests/(:num)/items.csv', 'Procurement\PurchaseRequestController::itemsCsv/$1', ['filter' => 'ability:procurement.view,procurement.pr.create,procurement.pr.approve,procurement.po.create,procurement.por.manage']);
    $routes->post('purchase-requests/(:num)/update', 'Procurement\PurchaseRequestController::update/$1', ['filter' => 'ability:procurement.pr.create']);
    $routes->post('purchase-requests/(:num)/submit', 'Procurement\PurchaseRequestController::submit/$1', ['filter' => 'ability:procurement.pr.create']);
    $routes->post('purchase-requests/(:num)/cancel', 'Procurement\PurchaseRequestController::cancel/$1', ['filter' => 'ability:procurement.pr.create']);

    $routes->get('approvals/pending', 'Procurement\PurchaseApprovalController::pending', ['filter' => 'ability:procurement.pr.approve']);
    $routes->post('approvals/(:num)/approve', 'Procurement\PurchaseApprovalController::approve/$1', ['filter' => 'ability:procurement.pr.approve']);
    $routes->post('approvals/(:num)/reject', 'Procurement\PurchaseApprovalController::reject/$1', ['filter' => 'ability:procurement.pr.approve']);

    $routes->get('purchase-orders', 'Procurement\PurchaseOrderController::index', ['filter' => 'ability:procurement.po.create,procurement.por.manage']);
    $routes->post('purchase-orders/from-pr/(:num)', 'Procurement\PurchaseOrderController::createFromPr/$1', ['filter' => 'ability:procurement.po.create']);
    $routes->post('purchase-orders/(:num)/issue', 'Procurement\PurchaseOrderController::issue/$1', ['filter' => 'ability:procurement.po.create']);

    $routes->get('po-requests', 'Procurement\PoRequestController::index', ['filter' => 'ability:procurement.por.manage']);
    $routes->post('po-requests/from-po/(:num)', 'Procurement\PoRequestController::createFromPo/$1', ['filter' => 'ability:procurement.por.manage']);
    $routes->post('po-requests/(:num)/approve', 'Procurement\PoRequestController::approve/$1', ['filter' => 'ability:procurement.por.manage']);
    $routes->post('po-requests/(:num)/reject', 'Procurement\PoRequestController::reject/$1', ['filter' => 'ability:procurement.por.manage']);
});

$routes->group('receiving', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('/', 'Receiving\ReceivingController::index', ['filter' => 'ability:receiving.view,receiving.convert']);
    $routes->get('create/from-po-request/(:num)', 'Receiving\ReceivingController::createFromPoRequest/$1', ['filter' => 'ability:receiving.convert']);
    $routes->post('/', 'Receiving\ReceivingController::store', ['filter' => 'ability:receiving.convert']);
    $routes->get('(:num)', 'Receiving\ReceivingController::show/$1', ['filter' => 'ability:receiving.view,receiving.convert']);
    $routes->get('(:num)/items.csv', 'Receiving\ReceivingController::itemsCsv/$1', ['filter' => 'ability:receiving.view,receiving.convert']);
    $routes->post('(:num)/post', 'Receiving\ReceivingController::post/$1', ['filter' => 'ability:receiving.convert']);
    $routes->post('(:num)/void', 'Receiving\ReceivingController::void/$1', ['filter' => 'ability:receiving.convert']);
    $routes->post('(:num)/validate', 'Receiving\ReceivingController::validateDraft/$1', ['filter' => 'ability:receiving.convert']);
});

$routes->group('inventory', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('quantities', 'Receiving\InventoryQuantityController::index', ['filter' => 'ability:inventory.view,inventory.quantity.update']);
    $routes->get('quantities/(:num)', 'Receiving\InventoryQuantityController::show/$1', ['filter' => 'ability:inventory.view,inventory.quantity.update']);
    $routes->post('quantities/(:num)/adjust-out', 'Receiving\InventoryQuantityController::adjustOut/$1', ['filter' => 'ability:inventory.quantity.update']);
    $routes->get('quantities/(:num)/movements.csv', 'Receiving\InventoryQuantityController::movementsCsv/$1', ['filter' => 'ability:inventory.view,inventory.quantity.update']);

    $routes->get('issuance', 'Inventory\IssuanceController::index', ['filter' => 'ability:inventory.view,inventory.issuance.create,inventory.issuance.approve']);
    $routes->get('issuance/create', 'Inventory\IssuanceController::create', ['filter' => 'ability:inventory.issuance.create']);
    $routes->post('issuance', 'Inventory\IssuanceController::store', ['filter' => 'ability:inventory.issuance.create']);
    $routes->get('issuance/(:num)', 'Inventory\IssuanceController::show/$1', ['filter' => 'ability:inventory.view,inventory.issuance.create,inventory.issuance.approve']);
    $routes->get('issuance/(:num)/items.csv', 'Inventory\IssuanceController::itemsCsv/$1', ['filter' => 'ability:inventory.view,inventory.issuance.create,inventory.issuance.approve']);
    $routes->post('issuance/(:num)/submit', 'Inventory\IssuanceController::submit/$1', ['filter' => 'ability:inventory.issuance.create']);
    $routes->post('issuance/(:num)/cancel', 'Inventory\IssuanceController::cancel/$1', ['filter' => 'ability:inventory.issuance.create']);

    $routes->post('issuance/(:num)/approve', 'Inventory\IssuanceApprovalController::approve/$1', ['filter' => 'ability:inventory.issuance.approve']);
    $routes->post('issuance/(:num)/reject', 'Inventory\IssuanceApprovalController::reject/$1', ['filter' => 'ability:inventory.issuance.approve']);
    $routes->post('issuance/(:num)/release', 'Inventory\IssuanceController::release/$1', ['filter' => 'ability:inventory.issuance.approve']);
    $routes->get('issuance/(:num)/allocations.csv', 'Inventory\IssuanceController::allocationsCsv/$1', ['filter' => 'ability:inventory.view,inventory.issuance.create,inventory.issuance.approve']);
});

$routes->group('reports', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('stock-balance', 'Inventory\ReportingController::stockBalance', ['filter' => 'ability:reports.view']);
    $routes->get('stock-movements', 'Inventory\ReportingController::stockMovements', ['filter' => 'ability:reports.view']);
    $routes->get('issuances', 'Inventory\ReportingController::issuances', ['filter' => 'ability:reports.view']);
    $routes->get('low-stock', 'Inventory\ReportingController::lowStock', ['filter' => 'ability:reports.view']);
    $routes->get('fast-moving', 'Inventory\ReportingController::fastMoving', ['filter' => 'ability:reports.view']);
});

$routes->group('analytics', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('activity-logs', 'Analytics\AnalyticsController::activityLogs', ['filter' => 'ability:audit.view']);
    $routes->get('dashboard', 'Analytics\AnalyticsController::dashboard', ['filter' => 'ability:audit.view']);
    $routes->get('events', 'Analytics\AnalyticsController::events', ['filter' => 'ability:audit.view']);
    $routes->get('metrics', 'Analytics\AnalyticsController::metrics', ['filter' => 'ability:audit.view']);
    $routes->get('system-architecture', 'Analytics\AnalyticsController::systemArchitecture', ['filter' => 'ability:audit.view']);
});
