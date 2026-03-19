<?php

namespace Config;

use App\Repositories\Contracts\Analytics\AnalyticsRepositoryInterface;
use App\Repositories\Contracts\Auth\UserRepositoryInterface;
use App\Repositories\Contracts\Inventory\InventoryStockRepositoryInterface as IssuanceInventoryStockRepositoryInterface;
use App\Repositories\Contracts\Inventory\IssuanceItemAllocationRepositoryInterface;
use App\Repositories\Contracts\Inventory\IssuanceItemRepositoryInterface;
use App\Repositories\Contracts\Inventory\IssuanceRepositoryInterface;
use App\Repositories\Contracts\Inventory\ReportingRepositoryInterface;
use App\Repositories\Contracts\Inventory\StockMovementRepositoryInterface as IssuanceStockMovementRepositoryInterface;
use App\Repositories\Contracts\Procurement\ApprovalRepositoryInterface;
use App\Repositories\Contracts\Procurement\PoRequestRepositoryInterface;
use App\Repositories\Contracts\Procurement\PurchaseOrderRepositoryInterface;
use App\Repositories\Contracts\Procurement\PurchaseRequestRepositoryInterface;
use App\Repositories\Contracts\Receiving\InventoryStockRepositoryInterface as ReceivingInventoryStockRepositoryInterface;
use App\Repositories\Contracts\Receiving\ReceivingItemRepositoryInterface;
use App\Repositories\Contracts\Receiving\ReceivingRepositoryInterface;
use App\Repositories\Contracts\Receiving\StockMovementRepositoryInterface as ReceivingStockMovementRepositoryInterface;
use App\Repositories\Contracts\Shared\AuditLogRepositoryInterface;
use App\Repositories\EloquentLike\Analytics\AnalyticsRepository;
use App\Repositories\EloquentLike\Auth\UserRepository;
use App\Repositories\EloquentLike\Inventory\InventoryStockRepository as IssuanceInventoryStockRepository;
use App\Repositories\EloquentLike\Inventory\IssuanceItemAllocationRepository;
use App\Repositories\EloquentLike\Inventory\IssuanceItemRepository;
use App\Repositories\EloquentLike\Inventory\IssuanceRepository;
use App\Repositories\EloquentLike\Inventory\ReportingRepository;
use App\Repositories\EloquentLike\Inventory\StockMovementRepository as IssuanceStockMovementRepository;
use App\Repositories\EloquentLike\Procurement\ApprovalRepository;
use App\Repositories\EloquentLike\Procurement\PoRequestRepository;
use App\Repositories\EloquentLike\Procurement\PurchaseOrderRepository;
use App\Repositories\EloquentLike\Procurement\PurchaseRequestRepository;
use App\Repositories\EloquentLike\Receiving\InventoryStockRepository as ReceivingInventoryStockRepository;
use App\Repositories\EloquentLike\Receiving\ReceivingItemRepository;
use App\Repositories\EloquentLike\Receiving\ReceivingRepository;
use App\Repositories\EloquentLike\Receiving\StockMovementRepository as ReceivingStockMovementRepository;
use App\Repositories\EloquentLike\Shared\AuditLogRepository;
use App\Services\Analytics\AnalyticsService;
use App\Services\Auth\AuthenticationService;
use App\Services\Inventory\InventoryAvailabilityService;
use App\Services\Inventory\IssuanceApprovalService;
use App\Services\Inventory\IssuanceReleaseService;
use App\Services\Inventory\IssuanceService;
use App\Services\Inventory\ReportingService;
use App\Services\Procurement\ApprovalService;
use App\Services\Procurement\PoRequestService;
use App\Services\Procurement\PurchaseOrderService;
use App\Services\Procurement\PurchaseRequestService;
use App\Services\Receiving\InventoryPostingService;
use App\Services\Receiving\InventoryQuantityService;
use App\Services\Receiving\ReceivingService;
use App\Services\Receiving\ReceivingValidationService;
use App\Services\Receiving\StockMovementService;
use App\Services\Shared\AuditService;

class RepositoryServices
{
    private static ?UserRepositoryInterface $userRepository = null;
    private static ?AnalyticsRepositoryInterface $analyticsRepository = null;

    private static ?PurchaseRequestRepositoryInterface $purchaseRequestRepository = null;
    private static ?ApprovalRepositoryInterface $approvalRepository = null;
    private static ?PurchaseOrderRepositoryInterface $purchaseOrderRepository = null;
    private static ?PoRequestRepositoryInterface $poRequestRepository = null;

    private static ?ReceivingRepositoryInterface $receivingRepository = null;
    private static ?ReceivingItemRepositoryInterface $receivingItemRepository = null;
    private static ?ReceivingInventoryStockRepositoryInterface $receivingInventoryStockRepository = null;
    private static ?ReceivingStockMovementRepositoryInterface $receivingStockMovementRepository = null;

    private static ?IssuanceRepositoryInterface $issuanceRepository = null;
    private static ?IssuanceItemAllocationRepositoryInterface $issuanceItemAllocationRepository = null;
    private static ?IssuanceItemRepositoryInterface $issuanceItemRepository = null;
    private static ?IssuanceInventoryStockRepositoryInterface $issuanceInventoryStockRepository = null;
    private static ?IssuanceStockMovementRepositoryInterface $issuanceStockMovementRepository = null;
    private static ?ReportingRepositoryInterface $reportingRepository = null;

    private static ?AuditLogRepositoryInterface $auditLogRepository = null;

    private static ?AuthenticationService $authenticationService = null;
    private static ?AnalyticsService $analyticsService = null;

    private static ?PurchaseRequestService $purchaseRequestService = null;
    private static ?ApprovalService $approvalService = null;
    private static ?PurchaseOrderService $purchaseOrderService = null;
    private static ?PoRequestService $poRequestService = null;

    private static ?StockMovementService $stockMovementService = null;
    private static ?ReceivingValidationService $receivingValidationService = null;
    private static ?InventoryPostingService $inventoryPostingService = null;
    private static ?ReceivingService $receivingService = null;
    private static ?InventoryQuantityService $inventoryQuantityService = null;

    private static ?InventoryAvailabilityService $inventoryAvailabilityService = null;
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

    public static function receivingInventoryStockRepository(): ReceivingInventoryStockRepositoryInterface
    {
        if (self::$receivingInventoryStockRepository === null) {
            self::$receivingInventoryStockRepository = new ReceivingInventoryStockRepository();
        }

        return self::$receivingInventoryStockRepository;
    }

    public static function receivingStockMovementRepository(): ReceivingStockMovementRepositoryInterface
    {
        if (self::$receivingStockMovementRepository === null) {
            self::$receivingStockMovementRepository = new ReceivingStockMovementRepository();
        }

        return self::$receivingStockMovementRepository;
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

    public static function issuanceInventoryStockRepository(): IssuanceInventoryStockRepositoryInterface
    {
        if (self::$issuanceInventoryStockRepository === null) {
            self::$issuanceInventoryStockRepository = new IssuanceInventoryStockRepository();
        }

        return self::$issuanceInventoryStockRepository;
    }

    public static function issuanceStockMovementRepository(): IssuanceStockMovementRepositoryInterface
    {
        if (self::$issuanceStockMovementRepository === null) {
            self::$issuanceStockMovementRepository = new IssuanceStockMovementRepository();
        }

        return self::$issuanceStockMovementRepository;
    }

    public static function reportingRepository(): ReportingRepositoryInterface
    {
        if (self::$reportingRepository === null) {
            self::$reportingRepository = new ReportingRepository(db_connect());
        }

        return self::$reportingRepository;
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

    public static function purchaseRequestService(): PurchaseRequestService
    {
        if (self::$purchaseRequestService === null) {
            self::$purchaseRequestService = new PurchaseRequestService(
                self::purchaseRequestRepository(),
                self::approvalRepository(),
            );
        }

        return self::$purchaseRequestService;
    }

    public static function approvalService(): ApprovalService
    {
        if (self::$approvalService === null) {
            self::$approvalService = new ApprovalService(
                self::approvalRepository(),
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

    public static function stockMovementService(): StockMovementService
    {
        if (self::$stockMovementService === null) {
            self::$stockMovementService = new StockMovementService(self::receivingStockMovementRepository());
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
                self::receivingInventoryStockRepository(),
                self::purchaseOrderRepository(),
                self::stockMovementService(),
            );
        }

        return self::$inventoryPostingService;
    }

    public static function receivingService(): ReceivingService
    {
        if (self::$receivingService === null) {
            self::$receivingService = new ReceivingService(
                self::receivingRepository(),
                self::receivingItemRepository(),
                self::poRequestRepository(),
                self::purchaseOrderRepository(),
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
                self::receivingInventoryStockRepository(),
                self::receivingStockMovementRepository(),
                \Config\Database::connect()
            );
        }

        return self::$inventoryQuantityService;
    }

    public static function inventoryAvailabilityService(): InventoryAvailabilityService
    {
        if (self::$inventoryAvailabilityService === null) {
            self::$inventoryAvailabilityService = new InventoryAvailabilityService(
                self::issuanceInventoryStockRepository(),
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
                self::approvalRepository(),
                self::auditService(),
            );
        }

        return self::$issuanceService;
    }

    public static function issuanceApprovalService(): IssuanceApprovalService
    {
        if (self::$issuanceApprovalService === null) {
            self::$issuanceApprovalService = new IssuanceApprovalService(
                self::issuanceRepository(),
                self::approvalRepository(),
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
                self::issuanceInventoryStockRepository(),
                self::issuanceStockMovementRepository(),
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
            self::$reportingService = new ReportingService(self::reportingRepository());
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
