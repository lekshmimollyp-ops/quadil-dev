# QUDIL Admin Application - Comprehensive Test Cases

This document provides detailed test cases to verify the full functionality and spec compliance of the QUDIL Admin Module.

---

## 🌎 1. System Settings & Geolocation

### TC-GEO-01: Country and State Master Management
**Objective:** Verify that global administrative boundaries can be managed.
1.  Navigate to **System Masters**.
2.  Click **ADD COUNTRY**, enter name (e.g., "India") and ISO code (e.g., "IN"), then save.
3.  Switch to **States / Provinces** tab.
4.  Click **ADD STATE**, select "India" from dropdown, enter "Karnataka", and save.
**Expected Result:** Both records are saved successfully and appear in their respective lists.

### TC-GEO-02: Hierarchical City Creation
**Objective:** Verify that cities are correctly linked to masters.
1.  Navigate to **City Masters**.
2.  Click **ADD CITY**.
3.  Select "India" as Country and "Karnataka" as State.
4.  Enter "Bangalore" as City Name and save.
**Expected Result:** City appears in the list with "KA" (or Karnataka) and "IN" displayed in location columns.

### TC-GEO-03: Service Area Definition
**Objective:** Verify radius-based service zone creation.
1.  Navigate to **Service Areas**.
2.  Click **NEW AREA**.
3.  Select "Bangalore" as City, enter "City Center" as Zone Name.
4.  Use the search helper to find a location or manually enter Lat/Lng.
5.  Set Radius to 15km and click **PUBLISH ZONE**.
**Expected Result:** Zone card appears with status "Active" and shows the coordinates/radius correctly.

---

## 🏢 2. Consignee Governance (Merchants)

### TC-MER-01: Merchant Onboarding
**Objective:** Verify creation of different tenant types.
1.  Navigate to **Merchant Registry**.
2.  Click **ADD MERCHANT**.
3.  Enter business details and select Type: **Corporate**.
4.  Save the merchant.
**Expected Result:** Merchant appears in the registry with the correct "Corporate" badge.

### TC-MER-02: Organizational Tree View
**Objective:** Verify multi-tier hierarchy display.
1.  Navigate to **Organizational Tree**.
2.  Locate a Corporate entity.
3.  Verify that child "Store Chains" or "Single Stores" are nested correctly under it.
**Expected Result:** Visual tree correctly reflects the parent-child relationships between entities.

---

## 🚚 3. Agent Governance

### TC-AGE-01: Agent Approval Workflow
**Objective:** Verify onboarding and status transitions.
1.  Navigate to **Agent Roster**.
2.  Identify an agent in "Pending" status.
3.  Review profile and click **APPROVE**.
**Expected Result:** Agent status changes to "Active" and they are now visible in dispatch lists.

### TC-AGE-02: Vehicle Registration
**Objective:** Verify agent-vehicle association.
1.  Open an Agent's profile.
2.  Navigate to **Vehicle Management** section.
3.  Add a new vehicle (Type: Bike, Plate: KA-01-AB-1234).
**Expected Result:** Vehicle is successfully registered and linked to the agent's profile.

---

## 💰 4. Financial & Wallet Controls

### TC-FIN-01: Merchant Wallet Top-up
**Objective:** Verify credit addition and ledger tracking.
1.  Navigate to **Credit Control**.
2.  Identify a merchant and click **MANAGE WALLET**.
3.  Add 1,000 credits to the balance.
**Expected Result:** Wallet balance updates immediately, and a new "Credit" entry appears in the merchant's ledger.

### TC-FIN-02: Credit Limit Management
**Objective:** Verify buying power calculations.
1.  In the same **Manage Wallet** modal, set a Credit Limit of 500.
**Expected Result:** The merchant's "Total Buying Power" shows as (Balance + 500).

---

## 📦 5. Order Management

### TC-ORD-01: Live Order Monitoring
**Objective:** Verify order list displays all orders with correct statuses.
**Status:** ✅ PASSED (December 31, 2025)
1.  Navigate to **Live Orders** (Dispatch Governance → Live Orders).
2.  Verify orders list displays with all status types.
3.  Check status badges: Draft, Pending, Assigned, Picked Up, Delivered, Cancelled.
4.  Verify order information: Order ID, Customer, Route, Amount.
**Expected Result:** All 16 test orders display correctly with color-coded status badges.
**Actual Result:** ✅ All orders displayed correctly. Status badges properly color-coded.

### TC-ORD-02: Order Details View
**Objective:** Verify order detail page shows complete information.
**Status:** ✅ PASSED (December 31, 2025)
1.  Click on any Order ID from the Live Orders list.
2.  Verify order detail page loads.
3.  Check Route Information section:
   - Pickup location with address and coordinates
   - Delivery destination with address and coordinates
4.  Check Shipment Metadata section:
   - Weight, Type, Description, Instructions
5.  Verify order header shows: Order ID, Status, Customer ID, Total Bill, Created Date.
**Expected Result:** All order details display correctly with proper formatting.
**Actual Result:** ✅ Order details page loads successfully. All information displayed correctly.

### TC-ORD-03: Manual Dispatch Assignment
**Objective:** Verify manual driver assignment to pending orders.
**Status:** ✅ PASSED (December 31, 2025)
**Prerequisites:** 
- At least 1 pending order
- At least 1 online driver (created 3 online drivers in dispatch-service)
1.  Navigate to a **Pending** order detail page.
2.  Verify "Manual Dispatch" section appears on the right.
3.  Check that online drivers list displays (3 drivers: User IDs 1003, 1004, 1005).
4.  Click on a driver to assign.
5.  Confirm assignment in dialog.
6.  Verify order status changes from "Pending" to "Assigned".
**Expected Result:** 
- Online drivers display in dropdown
- Assignment succeeds
- Order status updates to "Assigned"
- Success message displays
**Actual Result:** ✅ All steps passed. Manual dispatch working correctly.
**Notes:** 
- Required dispatch-service running on port 8006
- Required driver records in dispatch-service database
- Re-assignment currently allowed but not recommended (see ORDER_ASSIGNMENT_GUIDE.md)

### TC-ORD-04: Live Order Tracking
**Objective:** Verify real-time tracking for picked-up orders.
**Status:** ⏸️ NOT IMPLEMENTED
**Reason:** Tracking UI components and tracking-service integration not yet built.
**Current State:**
- Order details page shows static pickup/delivery coordinates
- No live map visualization
- No real-time driver location updates
- No ETA calculations
**Requirements for Implementation:**
- Tracking-service (port 8007) - not currently running
- WebSocket/MQTT for real-time updates
- Map integration (Mapbox/Google Maps)
- Driver location update mechanism
**Recommendation:** Implement in future sprint.

---

## 💳 5. Accounts & Credit Control

### TC-FIN-01: Credit Control List
**Objective:** Verify listed wallets.
1. Navigate to **Credit Control**.
2. Check list of merchants.
**Status:** ✅ PASSED
  - Verified 6 wallets (High/Low/Zero/Inactive)
  - Status badges (Operational/Critical) working

### TC-FIN-02: Wallet Top-Up
**Objective:** Verify ledger credit.
1. Click Top-Up on a merchant.
2. Add amount.
**Status:** ✅ PASSED
  - Balance updates immediately
  - Transaction recorded in Usage Ledger

### TC-LED-01: Merchant Ledger
**Objective:** Verify transaction history.
1. Click **Audit History**.
**Status:** ✅ PASSED
  - Transaction list displays accurately
  - Credits (green) and Debits (red) correct

---

## 📊 6. Reports & MIS

### TC-MIS-01: COD Summary Verification
**Objective:** Verify cash collection reporting.
1.  Navigate to **COD Summary**.
2.  Verify the list of agents with pending cash collections.
**Expected Result:** Total amount collected matches the individual order values for that agent.
**Status:** ✅ PASSED (December 31, 2025)
  - Verified with simulated data (John Doe, Jane Smith)
  - Report layout and aggregation logic confirmed

### TC-MIS-02: Platform Analytics
**Objective:** Verify KPI accuracy.
1.  Navigate to **MIS Analytics**.
2.  Verify "Gross Platform Revenue" and "Active Merchants" counts.
**Expected Result:** Data reflects real-time totals from the respective services.
**Status:** ✅ PASSED
  - Platform Gross Revenue: $205,010.00 (Verified)
  - Active Merchants: 8 (3 Real + 5 Synthetic)
  - Charts and trends visualization confirmed

