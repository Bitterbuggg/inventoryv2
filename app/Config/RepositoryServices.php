<?php

namespace Config;

use App\Repositories\Contracts\Auth\UserRepositoryInterface;
use App\Repositories\Contracts\Procurement\ApprovalRepositoryInterface;
use App\Repositories\Contracts\Procurement\PoRequestRepositoryInterface;
use App\Repositories\Contracts\Procurement\PurchaseOrderRepositoryInterface;
use App\Repositories\Contracts\Procurement\PurchaseRequestRepositoryInterface;
use App\Repositories\Contracts\Receiving\InventoryStockRepositoryInterface;
use App\Repositories\Contracts\Receiving\ReceivingItemRepositoryInterface;
use App\Repositories\Contracts\Receiving\ReceivingRepositoryInterface;
use App\Repositories\Contracts\Receiving\StockMovementRepositoryInterface;
use App\Repositories\EloquentLike\Auth\UserRepository;
use App\Repositories\EloquentLike\Procurement\ApprovalRepository;
use App\Repositories\EloquentLike\Procurement\PoRequestRepository;
use App\Repositories\EloquentLike\Procurement\PurchaseOrderRepository;
use App\Repositories\EloquentLike\Procurement\PurchaseRequestRepository;
use App\Repositories\EloquentLike\Receiving\InventoryStockRepository;
use App\Repositories\EloquentLike\Receiving\ReceivingItemRepository;
use App\Repositories\EloquentLike\Receiving\ReceivingRepository;
use App\Repositories\EloquentLike\Receiving\StockMovementRepository;
use App\Services\Auth\AuthenticationService;
use App\Services\Auth\AuthorizationService;
use App\Services\Procurement\ApprovalService;
use App\Services\Procurement\PoRequestService;
use App\Services\Procurement\PurchaseOrderService;
use App\Services\Procurement\PurchaseRequestService;
use App\Services\Receiving\InventoryPostingService;
use App\Services\Receiving\InventoryQuantityService;
use App\Services\Receiving\ReceivingService;
use App\Services\Receiving\ReceivingValidationService;
use App\Services\Receiving\StockMovementService;

class RepositoryServices
{
    private static ?UserRepositoryInterface $userRepository = null;

    private static ?PurchaseRequestRepositoryInterface $purchaseRequestRepository = null;
    private static ?ApprovalRepositoryInterface $approvalRepository = null;
    private static ?PurchaseOrderRepositoryInterface $purchaseOrderRepository = null;
    private static ?PoRequestRepositoryInterface $poRequestRepository = null;

    private static ?ReceivingRepositoryInterface $receivingRepository = null;
    private static ?ReceivingItemRepositoryInterface $receivingItemRepository = null;
    private static ?InventoryStockRepositoryInterface $inventoryStockRepository = null;
    private static ?StockMovementRepositoryInterface $stockMovementRepository = null;

    private static ?AuthenticationService $authenticationService = null;
    private static ?AuthorizationService $authorizationService = null;

    private static ?PurchaseRequestService $purchaseRequestService = null;
    private static ?ApprovalService $approvalService = null;
    private static ?PurchaseOrderService $purchaseOrderService = null;
    private static ?PoRequestService $poRequestService = null;

    private static ?StockMovementService $stockMovementService = null;
    private static ?ReceivingValidationService $receivingValidationService = null;
    private static ?InventoryPostingService $inventoryPostingService = null;
    private static ?ReceivingService $receivingService = null;
    private static ?InventoryQuantityService $inventoryQuantityService = null;

    public static function userRepository(): UserRepositoryInterface
    {
        if (self::$userRepository === null) {
            self::$userRepository = new UserRepository();
        }

        return self::$userRepository;
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

    public static function authenticationService(): AuthenticationService
    {
        if (self::$authenticationService === null) {
            self::$authenticationService = new AuthenticationService(self::userRepository());
        }

        return self::$authenticationService;
    }

    public static function authorizationService(): AuthorizationService
    {
        if (self::$authorizationService === null) {
            self::$authorizationService = new AuthorizationService(self::userRepository());
        }

        return self::$authorizationService;
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
            );
        }

        return self::$inventoryQuantityService;
    }
}
