# Architecture Overview
This document serves as a critical, living template designed to equip agents with a rapid and comprehensive understanding of the codebase's architecture, enabling efficient navigation and effective contribution from day one. Update this document as the codebase evolves.

## 1. Project Structure
This section provides a high-level overview of the project's directory and file structure, categorised by architectural layer or major functional area. It is essential for quickly navigating the codebase, locating relevant files, and understanding the overall organization and separation of concerns.

[Project Root]/
├── app/                            # Main CodeIgniter 4 application code
│   ├── Config/                     # App, routes, filters, and validation
│   ├── Controllers/                # HTTP controllers (lean; delegates to services)
│   │   ├── Admin/                  # Admin routes and dashboard controllers
│   │   ├── Auth/                   # Login, signup, logout
│   │   └── Inventory/              # PR, approval, PO, receiving, issuance controllers
│   ├── Database/
│   │   ├── Migrations/             # Database schema definitions
│   │   └── Seeds/                  # Seeders for roles, users, and sample data
│   ├── Entities/                   # Domain entities (optional but recommended in CI4)
│   ├── Filters/                    # Auth and role-based route guards
│   ├── Helpers/                    # Shared helper functions
│   ├── Models/                     # Direct data models for core tables
│   ├── Repositories/               # Data access abstraction layer
│   │   ├── Contracts/              # Repository interfaces
│   │   └── EloquentLike/           # Concrete repository implementations
│   ├── Services/                   # Business logic layer
│   │   ├── Auth/                   # Authentication and user registration logic
│   │   ├── Procurement/            # PR, approval, PO workflow logic
│   │   ├── Receiving/              # Receiving conversion and stock intake logic
│   │   └── Inventory/              # Stock movement and issuance logic
│   ├── Validation/                 # Custom validation rules
│   └── Views/                      # HTML/CSS views (server-rendered)
│       ├── admin/                  # Dashboard and admin pages
│       ├── auth/                   # Login/signup pages
│       ├── inventory/              # Inventory and workflow pages
│       └── layouts/                # Shared layout templates
├── public/                         # Web root (index.php, static assets)
│   └── assets/                     # CSS, JS, images
├── tests/                          # PHPUnit + CodeIgniter test suites
│   ├── unit/                       # Unit tests for services/repositories
│   ├── integration/                # Integration tests for controllers + DB flow
│   └── _support/                   # Shared test utilities/fixtures
├── writable/                       # Cache, logs, sessions, uploads
├── .env                            # Environment variables
├── composer.json                   # PHP dependencies
├── phpunit.xml.dist                # Test configuration
├── spark                           # CodeIgniter CLI entrypoint
├── README.md                       # Project overview and setup
└── Architecture.md                 # This document

## 2. High-Level System Diagram
Provide a simple block diagram (e.g., a C4 Model Level 1: System Context diagram, or a basic component diagram) or a clear text-based description of the major components and their interactions. Focus on how data flows, services communicate, and key architectural boundaries.

[Admin / Employee / IT Dev/Staff] <--> [CodeIgniter 4 Web App (Views + Controllers)]
                                            |
                                            v
                                  [Service Layer (Business Rules)]
                                            |
                                            v
                                [Repository Layer (Data Access)]
                                            |
                                            v
                                    [MySQL Database]

Core transaction flow:
[Purchase Request] -> [Approval] -> [Purchase Order] -> [PO Request] -> [Receiving Conversion] -> [Inventory Quantity Entry] -> [Issuance]

## 3. Core Components
(List and briefly describe the main components of the system. For each, include its primary responsibility and key technologies used.)

### 3.1. Frontend

Name: Server-Rendered Web App

Description: The main user interface for pharmacy users to log in, create purchase requests, approve requests, generate POs, receive stock, update inventory quantities, and issue items. Built as server-rendered pages for fast initial delivery and simple deployment on XAMPP.

Technologies: PHP 8.x (CodeIgniter 4 Views), HTML5, CSS3, minimal JavaScript

Deployment: Apache via XAMPP (local development and staging)

### 3.2. Backend Services

(Repeat for each significant backend service. Add more as needed.)

#### 3.2.1. Authentication and Access Control Service

Name: Auth & RBAC Service

Description: Handles signup, login, logout, session lifecycle, password hashing, and role-based permissions for `admin`, `employee`, and `IT dev/staff`.

Technologies: PHP 8.x, CodeIgniter 4 (Filters, Sessions, Validation, Password Hashing)

Deployment: Runs inside the CodeIgniter 4 monolith on Apache/XAMPP

#### 3.2.2. Procurement Workflow Service

Name: Purchase Lifecycle Service

Description: Orchestrates purchase request creation, approval workflow, purchase order generation, and PO request transitions while enforcing status rules and audit consistency.

Technologies: PHP 8.x, CodeIgniter 4 Services + Repositories, MySQL transactions

Deployment: Runs inside the CodeIgniter 4 monolith on Apache/XAMPP

#### 3.2.3. Receiving and Inventory Movement Service

Name: Stock Intake & Issuance Service

Description: Converts approved PO requests to receiving records, updates inventory quantities, logs stock movements, and processes issuance with validation against available stock.

Technologies: PHP 8.x, CodeIgniter 4 Services + Repositories, MySQL indexing

Deployment: Runs inside the CodeIgniter 4 monolith on Apache/XAMPP

## 4. Data Stores

(List and describe the databases and other persistent storage solutions used.)

### 4.1. Relational Data Store

Name: Primary Pharmacy Inventory Database

Type: MySQL (InnoDB)

Purpose: Stores transactional and master data for users, roles, suppliers, products, purchase lifecycle, stock balances, and issuance history.

Key Schemas/Collections: `users`, `roles`, `permissions`, `user_roles`, `suppliers`, `products`, `product_categories`, `units`, `purchase_requests`, `purchase_request_items`, `approvals`, `purchase_orders`, `purchase_order_items`, `po_requests`, `receivings`, `receiving_items`, `inventory_stocks`, `stock_movements`, `issuances`, `issuance_items`, `audit_logs`

### 4.2. Cache and Session Store

Name: Application Cache and Session Storage

Type: CodeIgniter File Cache + File Session Driver (phase 1), optional Redis (future)

Purpose: Stores authenticated sessions, CSRF/session state, and cacheable lookup results to reduce repetitive database reads.

## 5. External Integrations / APIs

(List any third-party services or external APIs the system interacts with.)

Service Name 1: None (phase 1)

Purpose: Initial architecture is intentionally self-contained for local development reliability.

Integration Method: N/A

Service Name 2: None

Purpose: External integrations are deferred until core inventory workflow is stable.

Integration Method: N/A

## 6. Deployment & Infrastructure

Cloud Provider: Local on-premise via XAMPP (development baseline)

Key Services Used: Apache HTTP Server, PHP 8.x, MySQL, Composer, CodeIgniter CLI (`spark`)

CI/CD Pipeline: GitHub Actions (recommended baseline: lint, unit tests, integration tests on pull requests)

Monitoring & Logging: CodeIgniter logs (`writable/logs`), Apache logs, MySQL slow query logs, audit trail tables

## 7. Security Considerations

(Highlight any critical security aspects, authentication mechanisms, or data encryption practices.)

Authentication: Session-based authentication with secure password hashing (`password_hash`), login validation, and session regeneration on login

Authorization: Route and controller guards with RBAC for `admin`, `employee`, and `IT dev/staff`

Data Encryption: TLS in transit outside local environments; password hashes at rest; secure handling of `.env` secrets

Key Security Tools/Practices: CSRF protection, strict input validation, output escaping in views, prepared queries/query builder, secure session cookies, audit logging for critical workflow changes

## 8. Development & Testing Environment

Local Setup Instructions: Install XAMPP + Composer, configure `.env`, run migrations/seeds via `php spark migrate` and `php spark db:seed`, then start Apache/MySQL and access via localhost

Testing Frameworks: PHPUnit + CodeIgniter 4 testing utilities (unit + integration)

Code Quality Tools: PHP_CodeSniffer or PHP-CS-Fixer, PHPStan (recommended), CI pipeline test gates

## 9. Future Considerations / Roadmap

(Briefly note any known architectural debts, planned major changes, or significant future features that might impact the architecture.)

[Introduce Redis for cache/session scaling and lower database load under concurrent usage.]

[Add FEFO/expiry-aware allocation, lot/batch tracking, and barcode support for pharmacy operations.]

[Implement asynchronous notifications (queue worker) for workflow events and retry handling.]

[Add first-party on-prem analytics (events + aggregated metrics) for workflow usage visibility without external trackers.]

## 10. Project Identification

Project Name: InventoryV2 Pharmacy Inventory System

Repository URL: Local repository (`c:\Users\asphy\Desktop\nigga\inventoryv2`)

Primary Contact/Team: InventoryV2 Engineering Team

Date of Last Update: 2026-02-19

## 11. Glossary / Acronyms

Define any project-specific terms or acronyms.)

PR: Purchase Request

PO: Purchase Order

RBAC: Role-Based Access Control

FEFO: First-Expired, First-Out inventory allocation method

GRN: Goods Received Note (receiving conversion record)

