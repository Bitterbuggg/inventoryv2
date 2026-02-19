# InventoryV2 Pharmacy System - Complete Database Schema

## Overview
This document provides the complete database schema for the InventoryV2 Pharmacy System, including foundation, procurement, receiving, inventory, issuance, and audit tables. The schema follows MySQL conventions, is optimized for CodeIgniter 4 workflows, and enforces strict referential integrity for pharmacy stock operations.

## Schema Conventions
- **Primary Keys**: `id` (auto-incrementing BIGINT UNSIGNED)
- **Foreign Keys**: `{table}_id` format (e.g., `user_id`, `product_id`)
- **Timestamps**: `created_at`, `updated_at` (nullable DATETIME)
- **Soft Deletes**: `deleted_at` for archivable entities
- **Status Enums**: Explicit state enums for workflow transitions
- **Decimal Precision**: Quantity fields use `decimal(12,3)`, monetary fields use `decimal(12,2)`

---

## Foundation Tables (Authentication & Access Control)

### users
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    display_name VARCHAR(255) NOT NULL,
    status ENUM('pending', 'active', 'inactive', 'suspended') DEFAULT 'active',
    last_login_at DATETIME NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    deleted_at DATETIME NULL,

    INDEX idx_users_status (status),
    INDEX idx_users_last_login_at (last_login_at)
);
```

### roles
```sql
CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    code VARCHAR(50) UNIQUE NOT NULL,
    description TEXT NULL,
    is_system BOOLEAN DEFAULT FALSE,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
);
```

### permissions
```sql
CREATE TABLE permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) UNIQUE NOT NULL,
    module VARCHAR(100) NOT NULL,
    description TEXT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,

    INDEX idx_permissions_module (module)
);
```

### user_roles
```sql
CREATE TABLE user_roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    role_id BIGINT UNSIGNED NOT NULL,
    assigned_by BIGINT UNSIGNED NULL,
    assigned_at DATETIME NOT NULL,

    UNIQUE KEY uq_user_roles_user_role (user_id, role_id),
    INDEX idx_user_roles_role_id (role_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_by) REFERENCES users(id) ON DELETE SET NULL
);
```

### role_permissions
```sql
CREATE TABLE role_permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id BIGINT UNSIGNED NOT NULL,
    permission_id BIGINT UNSIGNED NOT NULL,

    UNIQUE KEY uq_role_permissions (role_id, permission_id),
    INDEX idx_role_permissions_permission_id (permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);
```

---

## Master Data Tables (Pharmacy Catalog)

### suppliers
```sql
CREATE TABLE suppliers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    supplier_code VARCHAR(50) UNIQUE NOT NULL,
    supplier_name VARCHAR(255) NOT NULL,
    contact_person VARCHAR(255) NULL,
    contact_number VARCHAR(50) NULL,
    email VARCHAR(255) NULL,
    address TEXT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    deleted_at DATETIME NULL,

    INDEX idx_suppliers_status (status),
    INDEX idx_suppliers_name (supplier_name)
);
```

### product_categories
```sql
CREATE TABLE product_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_code VARCHAR(50) UNIQUE NOT NULL,
    category_name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,

    INDEX idx_product_categories_active (is_active)
);
```

### units
```sql
CREATE TABLE units (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    unit_code VARCHAR(30) UNIQUE NOT NULL,
    unit_name VARCHAR(100) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
);
```

### products
```sql
CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_code VARCHAR(80) UNIQUE NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    generic_name VARCHAR(255) NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    base_unit_id BIGINT UNSIGNED NOT NULL,
    reorder_level DECIMAL(12,3) DEFAULT 0,
    max_stock_level DECIMAL(12,3) NULL,
    is_controlled_substance BOOLEAN DEFAULT FALSE,
    requires_batch_tracking BOOLEAN DEFAULT TRUE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    deleted_at DATETIME NULL,

    INDEX idx_products_category_id (category_id),
    INDEX idx_products_status (status),
    INDEX idx_products_name (product_name),
    FOREIGN KEY (category_id) REFERENCES product_categories(id),
    FOREIGN KEY (base_unit_id) REFERENCES units(id)
);
```

---

## Procurement Workflow Tables

### purchase_requests
```sql
CREATE TABLE purchase_requests (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pr_number VARCHAR(50) UNIQUE NOT NULL,
    requested_by BIGINT UNSIGNED NOT NULL,
    request_date DATE NOT NULL,
    needed_date DATE NULL,
    department VARCHAR(120) NULL,
    remarks TEXT NULL,
    status ENUM('draft', 'submitted', 'approved', 'rejected', 'cancelled', 'converted_to_po') DEFAULT 'draft',
    submitted_at DATETIME NULL,
    approved_by BIGINT UNSIGNED NULL,
    approved_at DATETIME NULL,
    rejected_by BIGINT UNSIGNED NULL,
    rejected_at DATETIME NULL,
    rejection_reason TEXT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,

    INDEX idx_purchase_requests_status (status),
    INDEX idx_purchase_requests_request_date (request_date),
    INDEX idx_purchase_requests_requested_by (requested_by),
    FOREIGN KEY (requested_by) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (rejected_by) REFERENCES users(id) ON DELETE SET NULL
);
```

### purchase_request_items
```sql
CREATE TABLE purchase_request_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    purchase_request_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    unit_id BIGINT UNSIGNED NOT NULL,
    requested_qty DECIMAL(12,3) NOT NULL,
    approved_qty DECIMAL(12,3) NULL,
    estimated_unit_cost DECIMAL(12,2) NULL,
    notes TEXT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,

    INDEX idx_pr_items_purchase_request_id (purchase_request_id),
    INDEX idx_pr_items_product_id (product_id),
    FOREIGN KEY (purchase_request_id) REFERENCES purchase_requests(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (unit_id) REFERENCES units(id)
);
```

### approvals
```sql
CREATE TABLE approvals (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reference_type ENUM('purchase_request', 'po_request', 'issuance') NOT NULL,
    reference_id BIGINT UNSIGNED NOT NULL,
    approval_level INT UNSIGNED NOT NULL DEFAULT 1,
    approver_id BIGINT UNSIGNED NOT NULL,
    decision ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    decision_at DATETIME NULL,
    comments TEXT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,

    INDEX idx_approvals_reference (reference_type, reference_id),
    INDEX idx_approvals_approver_id (approver_id),
    INDEX idx_approvals_decision (decision),
    FOREIGN KEY (approver_id) REFERENCES users(id)
);
```

### purchase_orders
```sql
CREATE TABLE purchase_orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    po_number VARCHAR(50) UNIQUE NOT NULL,
    purchase_request_id BIGINT UNSIGNED NOT NULL,
    supplier_id BIGINT UNSIGNED NOT NULL,
    order_date DATE NOT NULL,
    expected_delivery_date DATE NULL,
    currency VARCHAR(10) DEFAULT 'PHP',
    subtotal_amount DECIMAL(12,2) DEFAULT 0,
    discount_amount DECIMAL(12,2) DEFAULT 0,
    tax_amount DECIMAL(12,2) DEFAULT 0,
    total_amount DECIMAL(12,2) DEFAULT 0,
    status ENUM('draft', 'issued', 'partially_received', 'fully_received', 'cancelled') DEFAULT 'draft',
    issued_by BIGINT UNSIGNED NULL,
    issued_at DATETIME NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,

    INDEX idx_purchase_orders_status (status),
    INDEX idx_purchase_orders_order_date (order_date),
    INDEX idx_purchase_orders_supplier_id (supplier_id),
    FOREIGN KEY (purchase_request_id) REFERENCES purchase_requests(id),
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id),
    FOREIGN KEY (issued_by) REFERENCES users(id) ON DELETE SET NULL
);
```

### purchase_order_items
```sql
CREATE TABLE purchase_order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    purchase_order_id BIGINT UNSIGNED NOT NULL,
    purchase_request_item_id BIGINT UNSIGNED NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    unit_id BIGINT UNSIGNED NOT NULL,
    ordered_qty DECIMAL(12,3) NOT NULL,
    received_qty DECIMAL(12,3) DEFAULT 0,
    unit_cost DECIMAL(12,2) NOT NULL,
    line_total DECIMAL(12,2) NOT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,

    INDEX idx_po_items_purchase_order_id (purchase_order_id),
    INDEX idx_po_items_product_id (product_id),
    FOREIGN KEY (purchase_order_id) REFERENCES purchase_orders(id) ON DELETE CASCADE,
    FOREIGN KEY (purchase_request_item_id) REFERENCES purchase_request_items(id) ON DELETE SET NULL,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (unit_id) REFERENCES units(id)
);
```

### po_requests
```sql
CREATE TABLE po_requests (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    po_request_number VARCHAR(50) UNIQUE NOT NULL,
    purchase_order_id BIGINT UNSIGNED NOT NULL,
    requested_by BIGINT UNSIGNED NOT NULL,
    request_date DATE NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'converted_to_receiving', 'closed') DEFAULT 'pending',
    approved_by BIGINT UNSIGNED NULL,
    approved_at DATETIME NULL,
    rejected_by BIGINT UNSIGNED NULL,
    rejected_at DATETIME NULL,
    rejection_reason TEXT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,

    INDEX idx_po_requests_status (status),
    INDEX idx_po_requests_request_date (request_date),
    FOREIGN KEY (purchase_order_id) REFERENCES purchase_orders(id),
    FOREIGN KEY (requested_by) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (rejected_by) REFERENCES users(id) ON DELETE SET NULL
);
```

---

## Receiving & Inventory Tables

### receivings
```sql
CREATE TABLE receivings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    receiving_number VARCHAR(50) UNIQUE NOT NULL,
    po_request_id BIGINT UNSIGNED NOT NULL,
    supplier_id BIGINT UNSIGNED NOT NULL,
    received_date DATE NOT NULL,
    delivery_reference VARCHAR(100) NULL,
    received_by BIGINT UNSIGNED NOT NULL,
    verified_by BIGINT UNSIGNED NULL,
    status ENUM('draft', 'posted', 'voided') DEFAULT 'draft',
    remarks TEXT NULL,
    posted_at DATETIME NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,

    INDEX idx_receivings_status (status),
    INDEX idx_receivings_received_date (received_date),
    FOREIGN KEY (po_request_id) REFERENCES po_requests(id),
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id),
    FOREIGN KEY (received_by) REFERENCES users(id),
    FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE SET NULL
);
```

### receiving_items
```sql
CREATE TABLE receiving_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    receiving_id BIGINT UNSIGNED NOT NULL,
    purchase_order_item_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    unit_id BIGINT UNSIGNED NOT NULL,
    received_qty DECIMAL(12,3) NOT NULL,
    accepted_qty DECIMAL(12,3) NOT NULL,
    rejected_qty DECIMAL(12,3) DEFAULT 0,
    batch_no VARCHAR(100) NULL,
    lot_no VARCHAR(100) NULL,
    expiry_date DATE NULL,
    unit_cost DECIMAL(12,2) NOT NULL,
    line_total DECIMAL(12,2) NOT NULL,
    remarks TEXT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,

    INDEX idx_receiving_items_receiving_id (receiving_id),
    INDEX idx_receiving_items_product_id (product_id),
    INDEX idx_receiving_items_expiry_date (expiry_date),
    FOREIGN KEY (receiving_id) REFERENCES receivings(id) ON DELETE CASCADE,
    FOREIGN KEY (purchase_order_item_id) REFERENCES purchase_order_items(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (unit_id) REFERENCES units(id)
);
```

### inventory_stocks
```sql
CREATE TABLE inventory_stocks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    batch_no VARCHAR(100) NULL,
    lot_no VARCHAR(100) NULL,
    expiry_date DATE NULL,
    unit_id BIGINT UNSIGNED NOT NULL,
    on_hand_qty DECIMAL(12,3) NOT NULL DEFAULT 0,
    reserved_qty DECIMAL(12,3) NOT NULL DEFAULT 0,
    available_qty DECIMAL(12,3) NOT NULL DEFAULT 0,
    average_unit_cost DECIMAL(12,2) NOT NULL DEFAULT 0,
    location_code VARCHAR(50) NULL,
    last_movement_at DATETIME NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,

    UNIQUE KEY uq_inventory_stocks_key (product_id, batch_no, lot_no, expiry_date, unit_id),
    INDEX idx_inventory_stocks_product_id (product_id),
    INDEX idx_inventory_stocks_expiry_date (expiry_date),
    INDEX idx_inventory_stocks_available_qty (available_qty),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (unit_id) REFERENCES units(id)
);
```

### stock_movements
```sql
CREATE TABLE stock_movements (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    movement_number VARCHAR(60) UNIQUE NOT NULL,
    movement_type ENUM('receiving', 'issuance', 'adjustment_in', 'adjustment_out', 'return') NOT NULL,
    reference_type ENUM('receiving', 'issuance', 'manual_adjustment') NOT NULL,
    reference_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    inventory_stock_id BIGINT UNSIGNED NULL,
    unit_id BIGINT UNSIGNED NOT NULL,
    qty_in DECIMAL(12,3) DEFAULT 0,
    qty_out DECIMAL(12,3) DEFAULT 0,
    balance_after DECIMAL(12,3) NOT NULL,
    unit_cost DECIMAL(12,2) NULL,
    performed_by BIGINT UNSIGNED NOT NULL,
    performed_at DATETIME NOT NULL,
    remarks TEXT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,

    INDEX idx_stock_movements_reference (reference_type, reference_id),
    INDEX idx_stock_movements_product_id (product_id),
    INDEX idx_stock_movements_performed_at (performed_at),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (inventory_stock_id) REFERENCES inventory_stocks(id) ON DELETE SET NULL,
    FOREIGN KEY (unit_id) REFERENCES units(id),
    FOREIGN KEY (performed_by) REFERENCES users(id)
);
```

---

## Issuance & Audit Tables

### issuances
```sql
CREATE TABLE issuances (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    issuance_number VARCHAR(50) UNIQUE NOT NULL,
    requestor_id BIGINT UNSIGNED NOT NULL,
    department VARCHAR(120) NULL,
    issue_date DATE NOT NULL,
    purpose TEXT NULL,
    status ENUM('draft', 'submitted', 'approved', 'released', 'cancelled') DEFAULT 'draft',
    approved_by BIGINT UNSIGNED NULL,
    approved_at DATETIME NULL,
    released_by BIGINT UNSIGNED NULL,
    released_at DATETIME NULL,
    remarks TEXT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,

    INDEX idx_issuances_status (status),
    INDEX idx_issuances_issue_date (issue_date),
    INDEX idx_issuances_requestor_id (requestor_id),
    FOREIGN KEY (requestor_id) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (released_by) REFERENCES users(id) ON DELETE SET NULL
);
```

### issuance_items
```sql
CREATE TABLE issuance_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    issuance_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    inventory_stock_id BIGINT UNSIGNED NULL,
    unit_id BIGINT UNSIGNED NOT NULL,
    requested_qty DECIMAL(12,3) NOT NULL,
    issued_qty DECIMAL(12,3) DEFAULT 0,
    unit_cost DECIMAL(12,2) NULL,
    line_total DECIMAL(12,2) NULL,
    remarks TEXT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,

    INDEX idx_issuance_items_issuance_id (issuance_id),
    INDEX idx_issuance_items_product_id (product_id),
    FOREIGN KEY (issuance_id) REFERENCES issuances(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (inventory_stock_id) REFERENCES inventory_stocks(id) ON DELETE SET NULL,
    FOREIGN KEY (unit_id) REFERENCES units(id)
);
```

### audit_logs
```sql
CREATE TABLE audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    actor_id BIGINT UNSIGNED NULL,
    action VARCHAR(150) NOT NULL,
    module VARCHAR(80) NOT NULL,
    reference_type VARCHAR(80) NULL,
    reference_id BIGINT UNSIGNED NULL,
    old_values JSON NULL,
    new_values JSON NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at DATETIME NOT NULL,

    INDEX idx_audit_logs_actor_id (actor_id),
    INDEX idx_audit_logs_module (module),
    INDEX idx_audit_logs_reference (reference_type, reference_id),
    INDEX idx_audit_logs_created_at (created_at),
    FOREIGN KEY (actor_id) REFERENCES users(id) ON DELETE SET NULL
);
```

---

## Foreign Key Relationships Summary

### Key Relationships
1. **users ↔ roles**: Many-to-many via `user_roles`
2. **roles ↔ permissions**: Many-to-many via `role_permissions`
3. **products → product_categories/units**: Product master linked to category and base unit
4. **purchase_requests → users**: Request created by authenticated user
5. **purchase_orders → purchase_requests/suppliers**: PO generated from approved PR and mapped supplier
6. **po_requests → purchase_orders**: PO request tracks readiness for receiving
7. **receivings → po_requests**: Receiving conversion from approved PO request
8. **inventory_stocks → products**: Stock ledger keyed by product and batch/lot/expiry
9. **issuance_items → inventory_stocks**: Issuance consumes available stock
10. **stock_movements**: Captures all inbound/outbound stock operations with references

### Cross-Module Dependencies
- **Foundation → All Modules**: users/roles/permissions power secure access
- **Procurement → Receiving**: PO requests feed receiving conversion
- **Receiving → Inventory**: Posted receiving updates stock quantities
- **Inventory → Issuance**: Available stock validates issuance operations
- **All Modules → Audit**: Critical transitions recorded in audit logs

---

## Indexing Strategy

### Performance Indexes
- **Foreign Key Indexes**: All FK columns explicitly indexed
- **Status Indexes**: Workflow status columns indexed for task queues
- **Date Indexes**: Operational dates indexed for reports and timelines
- **Unique Constraints**: Transaction numbers and master codes unique
- **Composite Indexes**: Reference and relationship lookups optimized

### Query Optimization
- Use transaction boundaries for PR->PO and receiving->stock posting flows
- Read-heavy dashboards should use summarized query views
- Expiry and low-stock lookups rely on `expiry_date` and `available_qty` indexes
- Report queries should use date range filters with indexed columns

---

## Data Migration Notes

### Seeding Order
1. **Foundation**: users, roles, permissions, role_permissions, user_roles
2. **Masters**: suppliers, product_categories, units, products
3. **Procurement**: purchase_requests and downstream PO/PO request tables
4. **Receiving & Inventory**: receivings, inventory_stocks, stock_movements
5. **Issuance & Audit**: issuances, issuance_items, audit_logs

### Migration Dependencies
- Create foundation tables before any operational module tables
- Create catalog tables before procurement item tables
- Create procurement tables before receiving and issuance tables
- Create stock tables before stock movement and issuance detail tables
- Seed baseline roles and permissions before enabling protected routes

---

**Schema Version**: 1.0  
**Last Updated**: 2026-02-19  
**Total Tables**: 21 core tables  
**Estimated Storage**: ~8-12GB for multi-year batch-level stock movement history (depends on transaction volume)
