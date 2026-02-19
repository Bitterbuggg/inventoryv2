# InventoryV2 Pharmacy System - User Workflow Flowchart

## Overview
This document provides workflow flowcharts for all user roles in the InventoryV2 Pharmacy System. It shows how users interact with procurement, receiving, inventory quantity posting, and issuance modules from login to final transaction completion.

## User Roles
1. **Admin** - Full access across authentication, procurement, receiving, inventory, issuance, and reporting
2. **Employee** - Creates purchase requests, tracks statuses, and submits issuance requests based on permissions
3. **IT Dev/Staff** - Technical support and controlled operational actions based on assigned permissions

---

## Complete System Workflow

```mermaid
graph TD
    Start([User Opens System]) --> Login{Login or Signup}
    Login -->|Signup| Register[Create Account]
    Login -->|Login| Auth[Authenticate Credentials]
    Register --> CreatePending[Create User Record]
    CreatePending --> AssignRole[Assign Default Role]
    AssignRole --> Auth
    Auth --> StatusCheck{Account Active?}
    StatusCheck -->|No| Denied([Access Denied])
    StatusCheck -->|Yes| RoleCheck{Resolve Role}

    RoleCheck -->|Admin| AdminDash[Admin Dashboard]
    RoleCheck -->|Employee| EmployeeDash[Employee Dashboard]
    RoleCheck -->|IT Dev/Staff| ITDash[IT Dashboard]

    AdminDash --> ProcurementFlow[Procurement Flow]
    AdminDash --> ReceivingFlow[Receiving and Inventory Posting]
    AdminDash --> IssuanceFlow[Issuance Approval and Release]
    AdminDash --> ReportsFlow[Reports and Audit]

    EmployeeDash --> EmployeePR[Create Purchase Request]
    EmployeeDash --> EmployeeTrack[Track Request Status]
    EmployeeDash --> EmployeeIssue[Submit Issuance Request]

    ITDash --> ITSupport[Support and Technical Operations]
    ITDash --> ITMonitoring[System Monitoring and Audit]

    ProcurementFlow --> Logout([Logout])
    ReceivingFlow --> Logout
    IssuanceFlow --> Logout
    ReportsFlow --> Logout
    EmployeePR --> Logout
    EmployeeTrack --> Logout
    EmployeeIssue --> Logout
    ITSupport --> Logout
    ITMonitoring --> Logout
```

---

## Admin User Workflow

```mermaid
graph TD
    ALogin([Admin Login]) --> ADash[Admin Dashboard]

    ADash --> ManageUsers[Manage Users and Roles]
    ADash --> ApprovePR[Review Purchase Requests]
    ADash --> GeneratePO[Generate Purchase Orders]
    ADash --> ApprovePOR[Approve PO Requests]
    ADash --> PostReceiving[Post Receiving to Inventory]
    ADash --> ReleaseIssuance[Approve and Release Issuance]
    ADash --> ViewReports[View Reports and Audits]

    ManageUsers --> UserActions{Action}
    UserActions --> CreateUser[Create User]
    UserActions --> AssignRole[Assign or Update Role]
    UserActions --> DisableUser[Disable or Suspend User]
    CreateUser --> UserDone([User Operation Complete])
    AssignRole --> UserDone
    DisableUser --> UserDone

    ApprovePR --> PRDecision{Approve PR?}
    PRDecision -->|Approve| PRApproved[PR Approved]
    PRDecision -->|Reject| PRRejected[PR Rejected]
    PRApproved --> GeneratePO
    PRRejected --> ADEnd([Procurement Cycle Ends])

    GeneratePO --> POIssue{Issue PO?}
    POIssue -->|Yes| POIssued[PO Issued]
    POIssue -->|No| ADEnd
    POIssued --> ApprovePOR

    ApprovePOR --> PORDecision{Approve PO Request?}
    PORDecision -->|Approve| PORApproved[PO Request Approved]
    PORDecision -->|Reject| PORRejected[PO Request Rejected]
    PORApproved --> PostReceiving
    PORRejected --> ADEnd

    PostReceiving --> ReceiveValidate{Quantities Valid?}
    ReceiveValidate -->|No| FixReceive[Revise Receiving Draft]
    ReceiveValidate -->|Yes| ReceivePosted[Receiving Posted]
    FixReceive --> PostReceiving

    ReceivePosted --> ReleaseIssuance
    ReleaseIssuance --> IssueDecision{Release Approved Issuance?}
    IssueDecision -->|Yes| IssueReleased[Stock Released]
    IssueDecision -->|No| ADEnd

    IssueReleased --> ViewReports
    ViewReports --> ADEnd
```

---

## Employee Workflow

```mermaid
graph TD
    ELogin([Employee Login]) --> EDash[Employee Dashboard]

    EDash --> CreatePR[Create Purchase Request]
    EDash --> TrackPR[Track PR and PO Status]
    EDash --> SubmitIssuance[Create Issuance Request]
    EDash --> ViewHistory[View Request and Issuance History]

    CreatePR --> AddItems[Add Requested Products]
    AddItems --> SubmitPR[Submit Purchase Request]
    SubmitPR --> PRStatus{PR Status}
    PRStatus -->|Pending| WaitPR[Wait for Approval]
    PRStatus -->|Approved| PRDone([PR Approved])
    PRStatus -->|Rejected| PRRevise[Revise and Resubmit]
    WaitPR --> PRStatus
    PRRevise --> CreatePR

    TrackPR --> CheckTimeline[Check Workflow Timeline]
    CheckTimeline --> TrackDone([Tracking Complete])

    SubmitIssuance --> AddIssueItems[Add Items and Quantity]
    AddIssueItems --> SubmitIssue[Submit Issuance Request]
    SubmitIssue --> IssueStatus{Issuance Status}
    IssueStatus -->|Pending| WaitIssue[Wait for Approval]
    IssueStatus -->|Approved| ReleasedIssue[Issued by Admin]
    IssueStatus -->|Rejected| IssueRevise[Revise Request]
    WaitIssue --> IssueStatus
    IssueRevise --> SubmitIssuance

    ViewHistory --> HistoryDone([History Viewed])
```

---

## IT Dev/Staff Workflow

```mermaid
graph TD
    ILogin([IT Dev/Staff Login]) --> IDash[IT Dashboard]

    IDash --> TechSupport[Support User and Access Issues]
    IDash --> MonitorLogs[Monitor Logs and Audit Trails]
    IDash --> MaintainConfig[Maintain App Configuration]
    IDash --> DataOps[Assist Data Integrity Operations]

    TechSupport --> SupportAction{Action}
    SupportAction --> ResetAccess[Assist Access Recovery]
    SupportAction --> RoleAssist[Apply Role Updates if Permitted]
    ResetAccess --> ITDone([Support Task Complete])
    RoleAssist --> ITDone

    MonitorLogs --> ReviewAudit[Review Audit Logs]
    ReviewAudit --> DetectIssue{Issue Detected?}
    DetectIssue -->|Yes| RaiseIncident[Create Incident Ticket]
    DetectIssue -->|No| ITDone
    RaiseIncident --> ITDone

    MaintainConfig --> ConfigAction{Change Type}
    ConfigAction --> CacheOps[Cache and Performance Tuning]
    ConfigAction --> BackupOps[Backup and Restore Checks]
    CacheOps --> ITDone
    BackupOps --> ITDone

    DataOps --> DataCheck[Check Integrity for Workflow Tables]
    DataCheck --> FixPlan[Prepare Safe Data Fix Plan]
    FixPlan --> ITDone
```

---

## Purchase Request to Issuance End-to-End Workflow

```mermaid
graph TD
    PRStart([Create Purchase Request]) --> PRSubmit[Submit PR]
    PRSubmit --> PRApprove{PR Approved?}
    PRApprove -->|No| PREndReject([PR Rejected])
    PRApprove -->|Yes| CreatePO[Generate Purchase Order]

    CreatePO --> IssuePO[Issue Purchase Order]
    IssuePO --> CreatePOR[Create PO Request]
    CreatePOR --> PORApprove{PO Request Approved?}
    PORApprove -->|No| POREndReject([PO Request Rejected])
    PORApprove -->|Yes| ConvertReceive[Convert to Receiving]

    ConvertReceive --> EncodeReceive[Encode Receiving Lines]
    EncodeReceive --> ValidateReceive{Receiving Valid?}
    ValidateReceive -->|No| ReviseReceive[Revise Receiving Draft]
    ValidateReceive -->|Yes| PostReceive[Post Receiving]
    ReviseReceive --> EncodeReceive

    PostReceive --> UpdateStock[Update Inventory Quantities]
    UpdateStock --> CreateIssue[Create Issuance Request]
    CreateIssue --> ApproveIssue{Issuance Approved?}
    ApproveIssue -->|No| IssueReject([Issuance Rejected])
    ApproveIssue -->|Yes| ReleaseIssue[Release Stock]
    ReleaseIssue --> End([Workflow Complete])
```

---

## Receiving Conversion and Inventory Posting Workflow

```mermaid
graph TD
    RStart([Approved PO Request]) --> RCreate[Create Receiving Draft]
    RCreate --> RLines[Add Receiving Items]
    RLines --> RCheckQty{Accepted + Rejected = Received?}
    RCheckQty -->|No| RError[Show Quantity Error]
    RCheckQty -->|Yes| RCheckLimit{Within PO Remaining Qty?}
    RCheckLimit -->|No| ROver[Flag Over-Receipt]
    RCheckLimit -->|Yes| RPost[Post Receiving]
    RError --> RLines
    ROver --> RLines

    RPost --> StockFind[Find or Create Inventory Stock Key]
    StockFind --> StockAdd[Add Accepted Quantity]
    StockAdd --> RecomputeAvail[Recompute Available Qty]
    RecomputeAvail --> MoveIn[Create Inbound Stock Movement]
    MoveIn --> RDone([Receiving Posted Successfully])
```

---

## Issuance Approval and Release Workflow

```mermaid
graph TD
    IStart([Issuance Draft]) --> ISubmit[Submit Issuance]
    ISubmit --> IApprove{Approved by Authorized User?}
    IApprove -->|No| IReject([Issuance Rejected])
    IApprove -->|Yes| IAlloc[Allocate Stock by Batch]
    IAlloc --> ICheck{Sufficient Available Qty?}
    ICheck -->|No| IInsuff[Insufficient Stock Error]
    ICheck -->|Yes| IRelease[Release Issuance]
    IInsuff --> IAlloc

    IRelease --> Deduct[Deduct from Inventory Stock]
    Deduct --> MoveOut[Create Outbound Stock Movement]
    MoveOut --> IFinal([Issuance Released])
```

---

## System Access Control Matrix

| Module | Admin | Employee | IT Dev/Staff |
|--------|-------|----------|--------------|
| Authentication & User Management | Full Access | Own Account | Limited Admin (if granted) |
| Procurement (PR/Approval/PO/POR) | Full Access | Create/Track PR | Operational Support (if granted) |
| Receiving Conversion | Full Access | No Direct Posting | Operational Support (if granted) |
| Inventory Quantity Management | Full Access | View Limited | Operational Support |
| Issuance Approval/Release | Full Access | Submit Requests | Operational Support (if granted) |
| Reports & Audit Logs | Full Access | Limited Own/Department Views | Technical and Audit Views |

---

## Key Workflow Notes

### Authentication and Authorization
- All routes require authenticated sessions except login/signup
- Role filters and permission checks enforce operation boundaries
- Unauthorized access attempts are logged to audit records

### Data Flow
- Procurement creates transaction intent (`PR -> PO -> PO Request`)
- Receiving converts approved requests into stock entries
- Inventory tables hold current balances and movement history
- Issuance consumes stock and writes outbound movements

### Approval Controls
- PR approval is required before PO generation
- PO request approval is required before receiving conversion
- Issuance approval is required before stock release
- Invalid state transitions are blocked by service-layer rules

### Notifications and Tracking
- Users track statuses via dashboard and module timelines
- Approval/rejection actions create audit entries
- Operational exceptions are visible in logs and report views

---

**Document Version:** 1.0  
**Last Updated:** 2026-02-19  
**Related Documents:**
- [Architectural Plan](Architectural Plan.md)
- [Architecture Reference](Architecture.md)
- [Complete Database Schema](PHARMACY_DATABASE_SCHEMA.md)
- [Auth/RBAC Module](AUTH_RBAC_MODULE_ARCHITECTURE.md)
- [Procurement Module](PROCUREMENT_MODULE_ARCHITECTURE.md)
- [Receiving + Inventory Module](RECEIVING_INVENTORY_MODULE_ARCHITECTURE.md)
- [Issuance + Reporting Module](ISSUANCE_REPORTING_MODULE_ARCHITECTURE.md)
