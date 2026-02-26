# InventoryV2 Operational Process

This file defines the standard daily operating flow for users and admins.

## 1) Access the system
- Start Apache and MySQL from XAMPP.
- Open `http://localhost/inventoryv2/public/`.
- Sign in with your assigned account.

## 2) Run core transactions
- Procurement: create and process PR/PO/PO Request records.
- Receiving: convert approved PO requests and post receiving.
- Inventory Issuance: create, approve, and release issuance records.

## 3) Review stock and reports
- Check `Inventory Quantities` for current balances.
- Use report pages for stock balance, movements, issuances, low stock, and fast moving.

## 4) Export CSV (required reporting step)
- Open the target page.
- Apply filters (status/date/keyword/module) as needed.
- Click `Export CSV` in the page action area.
- Save the downloaded file using your team naming convention.

## 5) End-of-day checks
- Verify critical queues are clear (pending approvals, unposted receivings, pending issuances).
- Confirm required exports were generated and shared.
