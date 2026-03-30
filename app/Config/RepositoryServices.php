<?php

namespace Config;

use App\Repositories\Contracts\Catalog\ProductRepositoryInterface;
use App\Repositories\Contracts\Catalog\SupplierRepositoryInterface;
use App\Repositories\Contracts\Analytics\AnalyticsRepositoryInterface;
use App\Repositories\Contracts\Auth\UserRepositoryInterface;
use App\Repositories\Contracts\Inventory\InventoryStockRepositoryInterface;
use App\Repositories\Contracts\Inventory\IssuanceItemAllocationRepositoryInterface;
use App\Repositories\Contracts\Inventory\IssuanceItemRepositoryInterface;
use App\Repositories\Contracts\Inventory\IssuanceRepositoryInterface;
use App\Repositories\Contracts\Inventory\StockMovementRepositoryInterface;
use App\Repositories\Contracts\Procurement\ApprovalRepositoryInterface;
use App\Repositories\Contracts\Procurement\PoRequestRepositoryInterface;
use App\Repositories\Contracts\Procurement\PurchaseOrderRepositoryInterface;
use App\Repositories\Contracts\Procurement\PurchaseRequestRepositoryInterface;
use App\Repositories\Contracts\Receiving\ReceivingItemRepositoryInterface;
use App\Repositories\Contracts\Receiving\ReceivingRepositoryInterface;
use App\Repositories\Contracts\Shared\AuditLogRepositoryInterface;
use App\Repositories\EloquentLike\Catalog\ProductRepository;
use App\Repositories\EloquentLike\Catalog\SupplierRepository;
use App\Repositories\EloquentLike\Analytics\AnalyticsRepository;
use App\Repositories\EloquentLike\Auth\UserRepository;
use App\Repositories\EloquentLike\Inventory\InventoryStockRepository;
use App\Repositories\EloquentLike\Inventory\IssuanceItemAllocationRepository;
use App\Repositories\EloquentLike\Inventory\IssuanceItemRepository;
use App\Repositories\EloquentLike\Inventory\IssuanceRepository;
use App\Repositories\EloquentLike\Inventory\StockMovementRepository;
use App\Repositories\EloquentLike\Procurement\ApprovalRepository;
use App\Repositories\EloquentLike\Procurement\PoRequestRepository;
use App\Repositories\EloquentLike\Procurement\PurchaseOrderRepository;
use App\Repositories\EloquentLike\Procurement\PurchaseRequestRepository;
use App\Repositories\EloquentLike\Receiving\ReceivingItemRepository;
use App\Repositories\EloquentLike\Receiving\ReceivingRepository;
use App\Repositories\EloquentLike\Shared\AuditLogRepository;
use App\Services\Analytics\AnalyticsService;
use App\Services\Analytics\ActivityLogQueryService;
use App\Services\Analytics\AnalyticsExportPresenter;
use App\Services\Admin\UserManagementService;
use App\Services\Auth\AuthenticationService;
use App\Services\Catalog\ProductService;
use App\Services\Catalog\SupplierService;
use App\Services\Inventory\InventoryAvailabilityService;
use App\Services\Inventory\IssuanceApprovalService;
use App\Services\Inventory\IssuanceReleaseService;
use App\Services\Inventory\IssuanceService;
use App\Services\Inventory\ReportingService;
use App\Services\Inventory\Reports\FastMovingReportReadModel;
use App\Services\Inventory\Reports\IssuanceReportReadModel;
use App\Services\Inventory\Reports\LowStockReportReadModel;
use App\Services\Inventory\Reports\ReportingExportPresenter;
use App\Services\Inventory\Reports\StockBalanceReportReadModel;
use App\Services\Inventory\Reports\StockMovementReportReadModel;
use App\Services\Procurement\ApprovalService;
use App\Services\Procurement\PoRequestService;
use App\Services\Procurement\ProcurementExportPresenter;
use App\Services\Procurement\ProcurementListPresenter;
use App\Services\Procurement\PurchaseOrderService;
use App\Services\Procurement\PurchaseRequestService;
use App\Services\Receiving\InventoryPostingService;
use App\Services\Receiving\InventoryQuantityService;
use App\Services\Receiving\ReceivingService;
use App\Services\Receiving\ReceivingValidationService;
use App\Services\Receiving\ReceivingWorkflowContextService;
use App\Services\Receiving\StockMovementService;
use App\Services\Shared\ApprovalWorkflowService;
use App\Services\Shared\AuditService;

class RepositoryServices
{
    private static ?UserRepositoryInterface $userRepository = null;
    private static ?AnalyticsRepositoryInterface $analyticsRepository = null;
    private static ?ProductRepositoryInterface $productRepository = null;
    private static ?SupplierRepositoryInterface $supplierRepository = null;

    private static ?PurchaseRequestRepositoryInterface $purchaseRequestRepository = null;
    private static ?ApprovalRepositoryInterface $approvalRepository = null;
    private static ?PurchaseOrderRepositoryInterface $purchaseOrderRepository = null;
    private static ?PoRequestRepositoryInterface $poRequestRepository = null;

    private static ?ReceivingRepositoryInterface $receivingRepository = null;
    private static ?ReceivingItemRepositoryInterface $receivingItemRepository = null;
    private static ?InventoryStockRepositoryInterface $inventoryStockRepository = null;
    private static ?StockMovementRepositoryInterface $stockMovementRepository = null;

    private static ?IssuanceRepositoryInterface $issuanceRepository = null;
    private static ?IssuanceItemAllocationRepositoryInterface $issuanceItemAllocationRepository = null;
    private static ?IssuanceItemRepositoryInterface $issuanceItemRepository = null;

    private static ?AuditLogRepositoryInterface $auditLogRepository = null;

    private static ?AuthenticationService $authenticationService = null;
    private static ?AnalyticsService $analyticsService = null;
    private static ?ActivityLogQueryService $activityLogQueryService = null;
    private static ?AnalyticsExportPresenter $analyticsExportPresenter = null;
    private static ?UserManagementService $userManagementService = null;
    private static ?ApprovalWorkflowService $approvalWorkflowService = null;
    private static ?ProductService $productService = null;
    private static ?SupplierService $supplierService = null;

    private static ?PurchaseRequestService $purchaseRequestService = null;
    private static ?ApprovalService $approvalService = null;
    private static ?PurchaseOrderService $purchaseOrderService = null;
    private static ?PoRequestService $poRequestService = null;
    private static ?ProcurementListPresenter $procurementListPresenter = null;
    private static ?ProcurementExportPresenter $procurementExportPresenter = null;

    private static ?StockMovementService $stockMovementService = null;
    private static ?ReceivingValidationService $receivingValidationService = null;
    private static ?InventoryPostingService $inventoryPostingService = null;
    private static ?ReceivingWorkflowContextService $receivingWorkflowContextService = null;
    private static ?ReceivingService $receivingService = null;
    private static ?InventoryQuantityService $inventoryQuantityService = null;

    private static ?InventoryAvailabilityService $inventoryAvailabilityService = null;
    private static ?StockBalanceReportReadModel $stockBalanceReportReadModel = null;
    private static ?StockMovementReportReadModel $stockMovementReportReadModel = null;
    private static ?IssuanceReportReadModel $issuanceReportReadModel = null;
    private static ?LowStockReportReadModel $lowStockReportReadModel = null;
    private static ?FastMovingReportReadModel $fastMovingReportReadModel = null;
    private static ?ReportingExportPresenter $reportingExportPresenter = null;
    private static ?IssuanceService $issuanceService = null;
    private static ?IssuanceApprovalService $issuanceApprovalService = null;
    private static ?IssuanceReleaseService $issuanceReleaseService = null;
    private static ?ReportingService $reportingService = null;
    private static ?AuditService $auditService = null;

    public static function userRepository(): UserRepositoryInterface
    {
        if (self::$userRepository === null) {
            self::$userRepository = new UserRepository();
        }

        return self::$userRepository;
    }

    public static function analyticsRepository(): AnalyticsRepositoryInterface
    {
        if (self::$analyticsRepository === null) {
            self::$analyticsRepository = new AnalyticsRepository();
        }

        return self::$analyticsRepository;
    }

    public static function productRepository(): ProductRepositoryInterface
    {
        if (self::$productRepository === null) {
            self::$productRepository = new ProductRepository();
        }

        return self::$productRepository;
    }

    public static function supplierRepository(): SupplierRepositoryInterface
    {
        if (self::$supplierRepository === null) {
            self::$supplierRepository = new SupplierRepository();
        }

        return self::$supplierRepository;
    }

    public static function purchaseRequestRepository(): PurchaseRequestRepositoryInterface
    {
        if (self::$purchaseRequestRepository === null) {
            self::$purchaseRequestRepository = new PurchaseRequestRepository();
        }

        return self::$purchaseRequestRepository;
    }

    public static function approvalRepository(): ApprovalRepositoryInterface
    {
        if (self::$approvalRepository === null) {
            self::$approvalRepository = new ApprovalRepository();
        }

        return self::$approvalRepository;
    }

    public static function purchaseOrderRepository(): PurchaseOrderRepositoryInterface
    {
        if (self::$purchaseOrderRepository === null) {
            self::$purchaseOrderRepository = new PurchaseOrderRepository();
        }

        return self::$purchaseOrderRepository;
    }

    public static function poRequestRepository(): PoRequestRepositoryInterface
    {
        if (self::$poRequestRepository === null) {
            self::$poRequestRepository = new PoRequestRepository();
        }

        return self::$poRequestRepository;
    }

    public static function receivingRepository(): ReceivingRepositoryInterface
    {
        if (self::$receivingRepository === null) {
            self::$receivingRepository = new ReceivingRepository();
        }

        return self::$receivingRepository;
    }

    public static function receivingItemRepository(): ReceivingItemRepositoryInterface
    {
        if (self::$receivingItemRepository === null) {
            self::$receivingItemRepository = new ReceivingItemRepository();
        }

        return self::$receivingItemRepository;
    }

    public static function inventoryStockRepository(): InventoryStockRepositoryInterface
    {
        if (self::$inventoryStockRepository === null) {
            self::$inventoryStockRepository = new InventoryStockRepository();
        }

        return self::$inventoryStockRepository;
    }

    public static function stockMovementRepository(): StockMovementRepositoryInterface
    {
        if (self::$stockMovementRepository === null) {
            self::$stockMovementRepository = new StockMovementRepository();
        }

        return self::$stockMovementRepository;
    }

    public static function issuanceRepository(): IssuanceRepositoryInterface
    {
        if (self::$issuanceRepository === null) {
            self::$issuanceRepository = new IssuanceRepository();
        }

        return self::$issuanceRepository;
    }

    public static function issuanceItemRepository(): IssuanceItemRepositoryInterface
    {
        if (self::$issuanceItemRepository === null) {
            self::$issuanceItemRepository = new IssuanceItemRepository();
        }

        return self::$issuanceItemRepository;
    }

    public static function issuanceItemAllocationRepository(): IssuanceItemAllocationRepositoryInterface
    {
        if (self::$issuanceItemAllocationRepository === null) {
            self::$issuanceItemAllocationRepository = new IssuanceItemAllocationRepository();
        }

        return self::$issuanceItemAllocationRepository;
    }

    public static function stockBalanceReportReadModel(): StockBalanceReportReadModel
    {
        if (self::$stockBalanceReportReadModel === null) {
            self::$stockBalanceReportReadModel = new StockBalanceReportReadModel(db_connect());
        }

        return self::$stockBalanceReportReadModel;
    }

    public static function stockMovementReportReadModel(): StockMovementReportReadModel
    {
        if (self::$stockMovementReportReadModel === null) {
            self::$stockMovementReportReadModel = new StockMovementReportReadModel(db_connect());
        }

        return self::$stockMovementReportReadModel;
    }

    public static function issuanceReportReadModel(): IssuanceReportReadModel
    {
        if (self::$issuanceReportReadModel === null) {
            self::$issuanceReportReadModel = new IssuanceReportReadModel(db_connect());
        }

        return self::$issuanceReportReadModel;
    }

    public static function lowStockReportReadModel(): LowStockReportReadModel
    {
        if (self::$lowStockReportReadModel === null) {
            self::$lowStockReportReadModel = new LowStockReportReadModel(db_connect());
        }

        return self::$lowStockReportReadModel;
    }

    public static function fastMovingReportReadModel(): FastMovingReportReadModel
    {
        if (self::$fastMovingReportReadModel === null) {
            self::$fastMovingReportReadModel = new FastMovingReportReadModel(db_connect());
        }

        return self::$fastMovingReportReadModel;
    }

    public static function reportingExportPresenter(): ReportingExportPresenter
    {
        if (self::$reportingExportPresenter === null) {
            self::$reportingExportPresenter = new ReportingExportPresenter();
        }

        return self::$reportingExportPresenter;
    }

    public static function auditLogRepository(): AuditLogRepositoryInterface
    {
        if (self::$auditLogRepository === null) {
            self::$auditLogRepository = new AuditLogRepository();
        }

        return self::$auditLogRepository;
    }

    public static function authenticationService(): AuthenticationService
    {
        if (self::$authenticationService === null) {
            self::$authenticationService = new AuthenticationService(self::userRepository());
        }

        return self::$authenticationService;
    }

    public static function analyticsService(): AnalyticsService
    {
        if (self::$analyticsService === null) {
            self::$analyticsService = new AnalyticsService(self::analyticsRepository());
        }

        return self::$analyticsService;
    }

    public static function activityLogQueryService(): ActivityLogQueryService
    {
        if (self::$activityLogQueryService === null) {
            self::$activityLogQueryService = new ActivityLogQueryService(self::analyticsService());
        }

        return self::$activityLogQueryService;
    }

    public static function analyticsExportPresenter(): AnalyticsExportPresenter
    {
        if (self::$analyticsExportPresenter === null) {
            self::$analyticsExportPresenter = new AnalyticsExportPresenter();
        }

        return self::$analyticsExportPresenter;
    }

    public static function userManagementService(): UserManagementService
    {
        if (self::$userManagementService === null) {
            self::$userManagementService = new UserManagementService(
                self::userRepository(),
                self::authenticationService(),
            );
        }

        return self::$userManagementService;
    }

    public static function approvalWorkflowService(): ApprovalWorkflowService
    {
        if (self::$approvalWorkflowService === null) {
            self::$approvalWorkflowService = new ApprovalWorkflowService(self::approvalRepository());
        }

        return self::$approvalWorkflowService;
    }

    public static function productService(): ProductService
    {
        if (self::$productService === null) {
            self::$productService = new ProductService(self::productRepository());
        }

        return self::$productService;
    }

    public static function supplierService(): SupplierService
    {
        if (self::$supplierService === null) {
            self::$supplierService = new SupplierService(self::supplierRepository());
        }

        return self::$supplierService;
    }

    public static function purchaseRequestService(): PurchaseRequestService
    {
        if (self::$purchaseRequestService === null) {
            self::$purchaseRequestService = new PurchaseRequestService(
                self::purchaseRequestRepository(),
                self::approvalWorkflowService(),
                self::productService(),
            );
        }

        return self::$purchaseRequestService;
    }

    public static function approvalService(): ApprovalService
    {
        if (self::$approvalService === null) {
            self::$approvalService = new ApprovalService(
                self::approvalWorkflowService(),
                self::purchaseRequestRepository(),
            );
        }

        return self::$approvalService;
    }

    public static function purchaseOrderService(): PurchaseOrderService
    {
        if (self::$purchaseOrderService === null) {
            self::$purchaseOrderService = new PurchaseOrderService(
                self::purchaseOrderRepository(),
                self::purchaseRequestRepository(),
                self::poRequestRepository(),
                self::supplierService(),
            );
        }

        return self::$purchaseOrderService;
    }

    public static function poRequestService(): PoRequestService
    {
        if (self::$poRequestService === null) {
            self::$poRequestService = new PoRequestService(
                self::poRequestRepository(),
                self::purchaseOrderRepository(),
            );
        }

        return self::$poRequestService;
    }

    public static function procurementListPresenter(): ProcurementListPresenter
    {
        if (self::$procurementListPresenter === null) {
            self::$procurementListPresenter = new ProcurementListPresenter(
                self::purchaseRequestService(),
                self::approvalService(),
                self::purchaseOrderService(),
                self::poRequestService(),
            );
        }

        return self::$procurementListPresenter;
    }

    public static function procurementExportPresenter(): ProcurementExportPresenter
    {
        if (self::$procurementExportPresenter === null) {
            self::$procurementExportPresenter = new ProcurementExportPresenter();
        }

        return self::$procurementExportPresenter;
    }

    public static function stockMovementService(): StockMovementService
    {
        if (self::$stockMovementService === null) {
            self::$stockMovementService = new StockMovementService(self::stockMovementRepository());
        }

        return self::$stockMovementService;
    }

    public static function receivingValidationService(): ReceivingValidationService
    {
        if (self::$receivingValidationService === null) {
            self::$receivingValidationService = new ReceivingValidationService();
        }

        return self::$receivingValidationService;
    }

    public static function inventoryPostingService(): InventoryPostingService
    {
        if (self::$inventoryPostingService === null) {
            self::$inventoryPostingService = new InventoryPostingService(
                self::inventoryStockRepository(),
                self::purchaseOrderRepository(),
                self::stockMovementService(),
            );
        }

        return self::$inventoryPostingService;
    }

    public static function receivingWorkflowContextService(): ReceivingWorkflowContextService
    {
        if (self::$receivingWorkflowContextService === null) {
            self::$receivingWorkflowContextService = new ReceivingWorkflowContextService(
                self::receivingRepository(),
                self::receivingItemRepository(),
                self::poRequestRepository(),
                self::purchaseOrderRepository(),
            );
        }

        return self::$receivingWorkflowContextService;
    }

    public static function receivingService(): ReceivingService
    {
        if (self::$receivingService === null) {
            self::$receivingService = new ReceivingService(
                self::receivingRepository(),
                self::receivingItemRepository(),
                self::poRequestRepository(),
                self::purchaseOrderRepository(),
                self::receivingWorkflowContextService(),
                self::receivingValidationService(),
                self::inventoryPostingService(),
                db_connect(),
                self::auditService(),
            );
        }

        return self::$receivingService;
    }

    public static function inventoryQuantityService(): InventoryQuantityService
    {
        if (self::$inventoryQuantityService === null) {
            self::$inventoryQuantityService = new InventoryQuantityService(
                self::inventoryStockRepository(),
                self::stockMovementRepository(),
                self::stockMovementService(),
                \Config\Database::connect()
            );
        }

        return self::$inventoryQuantityService;
    }

    public static function inventoryAvailabilityService(): InventoryAvailabilityService
    {
        if (self::$inventoryAvailabilityService === null) {
            self::$inventoryAvailabilityService = new InventoryAvailabilityService(
                self::inventoryStockRepository(),
            );
        }

        return self::$inventoryAvailabilityService;
    }

    public static function issuanceService(): IssuanceService
    {
        if (self::$issuanceService === null) {
            self::$issuanceService = new IssuanceService(
                self::issuanceRepository(),
                self::issuanceItemRepository(),
                self::issuanceItemAllocationRepository(),
                self::auditService(),
                self::approvalWorkflowService(),
                self::productService(),
            );
        }

        return self::$issuanceService;
    }

    public static function issuanceApprovalService(): IssuanceApprovalService
    {
        if (self::$issuanceApprovalService === null) {
            self::$issuanceApprovalService = new IssuanceApprovalService(
                self::issuanceRepository(),
                self::approvalWorkflowService(),
                self::auditService(),
            );
        }

        return self::$issuanceApprovalService;
    }

    public static function issuanceReleaseService(): IssuanceReleaseService
    {
        if (self::$issuanceReleaseService === null) {
            self::$issuanceReleaseService = new IssuanceReleaseService(
                self::issuanceRepository(),
                self::issuanceItemRepository(),
                self::issuanceItemAllocationRepository(),
                self::inventoryStockRepository(),
                self::stockMovementService(),
                self::inventoryAvailabilityService(),
                self::auditService(),
                db_connect(),
            );
        }

        return self::$issuanceReleaseService;
    }

    public static function reportingService(): ReportingService
    {
        if (self::$reportingService === null) {
            self::$reportingService = new ReportingService(
                self::stockBalanceReportReadModel(),
                self::stockMovementReportReadModel(),
                self::issuanceReportReadModel(),
                self::lowStockReportReadModel(),
                self::fastMovingReportReadModel(),
            );
        }

        return self::$reportingService;
    }

    public static function auditService(): AuditService
    {
        if (self::$auditService === null) {
            self::$auditService = new AuditService(self::auditLogRepository());
        }

        return self::$auditService;
    }
}
