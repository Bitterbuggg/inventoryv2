<?php

namespace App\Services\Procurement;

class ProcurementListPresenter
{
    /**
     * @var array<string, string>
     */
    private const PURCHASE_REQUEST_STATUS_OPTIONS = [
        'draft'           => 'Draft',
        'submitted'       => 'Submitted',
        'approved'        => 'Approved',
        'rejected'        => 'Rejected',
        'cancelled'       => 'Cancelled',
        'converted_to_po' => 'Converted to PO',
    ];

    /**
     * @var array<string, string>
     */
    private const PURCHASE_ORDER_STATUS_OPTIONS = [
        'draft'              => 'Draft',
        'issued'             => 'Issued',
        'partially_received' => 'Partially Received',
        'fully_received'     => 'Fully Received',
        'cancelled'          => 'Cancelled',
    ];

    /**
     * @var array<string, string>
     */
    private const PO_REQUEST_STATUS_OPTIONS = [
        'pending'                => 'Pending',
        'approved'               => 'Approved',
        'rejected'               => 'Rejected',
        'converted_to_receiving' => 'Converted to Receiving',
    ];

    /**
     * @var array<string, string>
     */
    private const PURCHASE_ORDER_BADGE_CLASSES = [
        'draft'              => 'status-draft',
        'issued'             => 'status-issued',
        'partially_received' => 'status-partial',
        'fully_received'     => 'status-full',
        'cancelled'          => 'status-cancelled',
    ];

    public function __construct(
        private readonly PurchaseRequestService $purchaseRequests,
        private readonly ApprovalService $approvals,
        private readonly PurchaseOrderService $purchaseOrders,
        private readonly PoRequestService $poRequests,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function purchaseRequestStatusOptions(): array
    {
        return self::PURCHASE_REQUEST_STATUS_OPTIONS;
    }

    /**
     * @return array<string, string>
     */
    public function purchaseOrderStatusOptions(): array
    {
        return self::PURCHASE_ORDER_STATUS_OPTIONS;
    }

    /**
     * @return array<string, string>
     */
    public function poRequestStatusOptions(): array
    {
        return self::PO_REQUEST_STATUS_OPTIONS;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listPurchaseRequests(?string $status = null): array
    {
        return array_map(
            fn (array $request): array => $this->presentPurchaseRequest($request),
            $this->purchaseRequests->list($status),
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listPendingApprovals(): array
    {
        return array_map(function (array $approval): array {
            $referenceType = (string) ($approval['reference_type'] ?? '');
            $referenceId = (int) ($approval['reference_id'] ?? 0);

            if ($referenceType !== 'purchase_request' || $referenceId <= 0) {
                return $approval;
            }

            $purchaseRequest = $this->purchaseRequests->findWithItems($referenceId);
            if ($purchaseRequest === null) {
                return $approval;
            }

            $approval['purchase_request'] = [
                'pr_number' => $purchaseRequest['pr_number'] ?? null,
                'request_date' => $purchaseRequest['request_date'] ?? null,
                'requested_by' => $purchaseRequest['requested_by'] ?? null,
                'remarks' => $purchaseRequest['remarks'] ?? null,
                'items' => $purchaseRequest['items'] ?? [],
            ];

            return $approval;
        }, $this->approvals->listPending());
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listPurchaseOrders(?string $status = null): array
    {
        return array_map(
            fn (array $order): array => $this->presentPurchaseOrder($order),
            $this->purchaseOrders->listForIndex($status),
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listPoRequests(?string $status = null): array
    {
        return array_map(function (array $poRequest): array {
            $purchaseOrderId = (int) ($poRequest['purchase_order_id'] ?? 0);

            if ($purchaseOrderId > 0) {
                $purchaseOrder = $this->purchaseOrders->findWithItems($purchaseOrderId);

                if ($purchaseOrder !== null) {
                    $poRequest['purchase_order'] = [
                        'po_number'     => $purchaseOrder['po_number'] ?? null,
                        'supplier_name' => $purchaseOrder['supplier_name'] ?? null,
                        'order_date'    => $purchaseOrder['order_date'] ?? null,
                        'total_amount'  => $purchaseOrder['total_amount'] ?? 0,
                        'items'         => $purchaseOrder['items'] ?? [],
                    ];
                }
            }

            $status = (string) ($poRequest['status'] ?? '');
            $poRequest['status_label'] = $this->statusLabel($status, self::PO_REQUEST_STATUS_OPTIONS);
            $poRequest['uses_special_status_badge'] = $status === 'converted_to_receiving';
            $poRequest['action_by_label'] = $this->nullableDisplayValue(
                $poRequest['approved_by'] ?? $poRequest['rejected_by'] ?? null,
            );

            return $poRequest;
        }, $this->poRequests->list($status));
    }

    /**
     * @param array<string, mixed> $request
     *
     * @return array<string, mixed>
     */
    private function presentPurchaseRequest(array $request): array
    {
        $status = (string) ($request['status'] ?? '');
        $request['status_label'] = $this->statusLabel($status, self::PURCHASE_REQUEST_STATUS_OPTIONS);
        $request['uses_special_status_badge'] = $status === 'converted_to_po';

        return $request;
    }

    /**
     * @param array<string, mixed> $order
     *
     * @return array<string, mixed>
     */
    private function presentPurchaseOrder(array $order): array
    {
        $status = (string) ($order['status'] ?? '');
        $order['status_label'] = $this->statusLabel($status, self::PURCHASE_ORDER_STATUS_OPTIONS);
        $order['status_badge_class'] = self::PURCHASE_ORDER_BADGE_CLASSES[$status] ?? 'status-draft';

        $poRequestStatus = (string) ($order['po_request_status'] ?? '');
        $order['po_request_badge_class'] = $poRequestStatus === 'approved'
            ? 'action-badge-success'
            : 'action-badge-warning';
        $order['po_request_badge_label'] = $poRequestStatus === ''
            ? 'PO REQ: PENDING'
            : 'PO REQ: ' . strtoupper($this->statusLabel($poRequestStatus, self::PO_REQUEST_STATUS_OPTIONS));

        return $order;
    }

    /**
     * @param array<string, string> $labels
     */
    private function statusLabel(string $status, array $labels): string
    {
        if ($status === '') {
            return 'Unknown';
        }

        return $labels[$status] ?? ucwords(str_replace('_', ' ', $status));
    }

    private function nullableDisplayValue(mixed $value): string
    {
        $text = trim((string) $value);

        return $text === '' ? '-' : $text;
    }
}
