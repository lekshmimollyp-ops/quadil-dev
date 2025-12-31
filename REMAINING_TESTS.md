# QUDIL - Remaining Testing Tasks

**Date:** December 31, 2025  
**Status:** Ready for Testing  
**Login:** http://localhost:8080/login (admin@qudil.com / admin123)

---

## ✅ **Completed Testing (December 30, 2025)**

### 1. System Settings ✅
- ✅ City Masters (CRUD operations)
- ✅ Service Areas (with geocoding)
- ✅ System Masters (Countries & States)

### 2. Consignee Governance ✅
- ✅ Merchant Registry (flat list)
- ✅ Organizational Tree (hierarchical view)
- ✅ Parent-child relationships

### 3. Dispatch Governance - Agent Roster ✅
- ✅ Agent approval workflow
- ✅ Agent-merchant associations
- ✅ Soft delete for agents
- ✅ Display associated merchants

---

## 🔄 **REMAINING TESTS - Today's Focus**

---

## 📦 **SECTION 1: Dispatch Governance (Remaining)**

### Test 1: Live Orders Module
**Navigation:** Dispatch Governance → Live Orders  
**Route:** http://localhost:8080/orders

#### Test Cases:

**TC-ORD-01: View Live Orders**
1. Click on "Live Orders" in the sidebar
2. Verify the orders list displays
3. Check for status badges (Quoted, Booked, Out for Delivery, Delivered)
4. Verify order details are visible (Order ID, Customer, Status, Agent)

**Expected Result:**
- Orders table loads successfully
- Status badges are color-coded
- All columns display correctly

---

**TC-ORD-02: View Order Details**
1. Click on any Order ID in the list
2. Verify order detail page opens
3. Check for:
   - Order timeline/lifecycle
   - Pickup and delivery addresses
   - Assigned agent details
   - Current status
   - Tracking information (if available)

**Expected Result:**
- Order details page loads
- All information is displayed correctly
- Timeline shows order progression

---

**TC-ORD-03: Manual Dispatch Assignment**
1. Find an unassigned order (status: "Quoted" or "Pending")
2. Click "DISPATCH" or "Assign Agent" button
3. Select an available agent from the dropdown
4. Confirm assignment

**Expected Result:**
- Agent dropdown shows online/available agents
- Assignment succeeds
- Order status updates to "Assigned"
- Agent name appears in the order

---

### Test 2: MIS Analytics (Under Dispatch Governance)
**Navigation:** Dispatch Governance → MIS Analytics  
**Route:** http://localhost:8080/analytics

#### Test Cases:

**TC-MIS-01: Platform KPIs Dashboard**
1. Navigate to MIS Analytics
2. Verify dashboard displays:
   - Total Orders (today/week/month)
   - Active Merchants count
   - Active Agents count
   - Gross Platform Revenue
   - Order completion rate
   - Average delivery time

**Expected Result:**
- All KPI cards display with numbers
- Data is accurate and real-time
- Charts/graphs render correctly

---

**TC-MIS-02: Merchant Performance Analytics**
1. In MIS Analytics, look for merchant-specific stats
2. Filter by merchant (if available)
3. Check for:
   - Orders per merchant
   - Revenue per merchant
   - Top performing merchants

**Expected Result:**
- Merchant stats display correctly
- Filtering works (if implemented)
- Data matches actual orders

---

## 💰 **SECTION 2: Accounts & MIS (Complete Testing)**

### Test 3: Credit Control (Wallet Management)
**Navigation:** Accounts & MIS → Credit Control  
**Route:** http://localhost:8080/accounts/wallets

#### Test Cases:

**TC-FIN-01: View Merchant Wallets**
1. Navigate to Credit Control
2. Verify wallet list displays all merchants
3. Check columns:
   - Merchant Name
   - Current Balance
   - Credit Limit
   - Total Buying Power (Balance + Credit Limit)
   - Status

**Expected Result:**
- All merchants with wallets are listed
- Balance and credit limit display correctly
- Buying power calculation is accurate

---

**TC-FIN-02: Top-up Merchant Wallet**
1. Locate a merchant in the wallet list
2. Click "MANAGE WALLET" or "Top-up" button
3. Enter amount: `1000`
4. Add note: "Test top-up"
5. Click "Add Credits" or "Submit"

**Expected Result:**
- Modal opens with top-up form
- Amount is validated (positive number)
- Balance updates immediately after submission
- New entry appears in Usage Ledgers

---

**TC-FIN-03: Set/Update Credit Limit**
1. In the wallet management modal
2. Find "Credit Limit" field
3. Enter new limit: `5000`
4. Save changes

**Expected Result:**
- Credit limit updates successfully
- Total Buying Power recalculates (Balance + 5000)
- Changes reflect immediately

---

**TC-FIN-04: Low Balance Alert Verification**
1. Find a merchant with low balance (< credit limit threshold)
2. Check for visual indicators:
   - Warning badge
   - Color-coded status (amber/red)
   - Alert icon

**Expected Result:**
- Low balance merchants are highlighted
- Visual warnings are clear
- Status reflects wallet health

---

### Test 4: Usage Ledgers
**Navigation:** Accounts & MIS → Usage Ledgers  
**Route:** http://localhost:8080/accounts/ledgers

#### Test Cases:

**TC-LED-01: View All Ledger Entries**
1. Navigate to Usage Ledgers
2. Verify transaction list displays
3. Check columns:
   - Date/Time
   - Merchant Name
   - Transaction Type (Credit/Debit)
   - Amount
   - Balance After
   - Description/Note

**Expected Result:**
- All transactions are listed chronologically
- Credits show as positive (green)
- Debits show as negative (red)
- Running balance is accurate

---

**TC-LED-02: Filter by Merchant**
1. Look for merchant filter/dropdown
2. Select a specific merchant
3. Verify ledger shows only that merchant's transactions

**Expected Result:**
- Filtering works correctly
- Only selected merchant's entries display
- Balance calculations are merchant-specific

---

**TC-LED-03: View Merchant-Specific Ledger**
1. Click on a merchant name in the ledger
2. Or navigate to merchant detail page
3. Verify detailed ledger for that merchant

**Expected Result:**
- Merchant-specific ledger opens
- Shows complete transaction history
- Opening and closing balances are correct

---

### Test 5: COD Summary Reports
**Navigation:** Accounts & MIS → COD Summary  
**Route:** http://localhost:8080/accounts/reports/cod-summary

#### Test Cases:

**TC-COD-01: View COD Summary**
1. Navigate to COD Summary
2. Verify report displays:
   - Agent Name
   - Total COD Collected
   - Number of COD Orders
   - Pending Remittance
   - Last Settlement Date

**Expected Result:**
- All agents with COD collections are listed
- Amounts are accurate
- Pending amounts are highlighted

---

**TC-COD-02: Verify COD Calculations**
1. Select an agent from the COD summary
2. Cross-check with their delivered orders
3. Verify:
   - Sum of COD amounts matches total collected
   - Pending remittance = Total - Settled

**Expected Result:**
- Calculations are accurate
- No discrepancies in amounts
- Data matches order records

---

**TC-COD-03: Settlement Tracking**
1. Look for settlement status indicators
2. Check for:
   - Settled vs Pending amounts
   - Settlement dates
   - Settlement history (if available)

**Expected Result:**
- Settlement status is clear
- Dates are accurate
- History is maintained

---

## 📊 **Testing Checklist Summary**

### Dispatch Governance - Remaining
- [ ] Live Orders - View list
- [ ] Live Orders - View details
- [ ] Live Orders - Manual dispatch
- [ ] MIS Analytics - Platform KPIs
- [ ] MIS Analytics - Merchant stats

### Accounts & MIS - Complete
- [ ] Credit Control - View wallets
- [ ] Credit Control - Top-up wallet
- [ ] Credit Control - Set credit limit
- [ ] Credit Control - Low balance alerts
- [ ] Usage Ledgers - View all entries
- [ ] Usage Ledgers - Filter by merchant
- [ ] Usage Ledgers - Merchant-specific view
- [ ] COD Summary - View report
- [ ] COD Summary - Verify calculations
- [ ] COD Summary - Settlement tracking

---

## 🚀 **Testing Order (Recommended)**

Follow this sequence for efficient testing:

1. **Start with Live Orders** (15-20 minutes)
   - View orders list
   - Check order details
   - Test manual dispatch

2. **MIS Analytics** (10-15 minutes)
   - Review platform KPIs
   - Check merchant analytics

3. **Credit Control** (20-25 minutes)
   - View all wallets
   - Test top-up functionality
   - Set credit limits
   - Verify low balance alerts

4. **Usage Ledgers** (15-20 minutes)
   - Review transaction history
   - Test filtering
   - Verify calculations

5. **COD Summary** (15-20 minutes)
   - Review COD collections
   - Verify calculations
   - Check settlement tracking

**Total Estimated Time:** 75-100 minutes (1.5-2 hours)

---

## 📝 **Test Data Requirements**

### For Order Testing:
- Need at least 5-10 sample orders in different states
- Need 3-5 active agents (online status)
- Need pickup and delivery addresses

### For Financial Testing:
- Need merchants with varying wallet balances
- Need transaction history (credits and debits)
- Need some COD orders with agent assignments

### If Test Data is Missing:
You may need to:
1. Create sample orders via API or seeder
2. Assign agents to orders
3. Generate wallet transactions
4. Create COD order records

---

## ✅ **Success Criteria**

Testing is complete when:

1. ✅ All 15 test cases pass without errors
2. ✅ All CRUD operations work smoothly
3. ✅ Calculations are accurate (wallets, ledgers, COD)
4. ✅ UI displays data correctly
5. ✅ No console errors or warnings
6. ✅ Loading states work properly
7. ✅ Error handling is graceful

---

## 🐛 **Issue Reporting Template**

If you encounter issues, document:

```
**Test Case:** TC-XXX-XX
**Expected:** [What should happen]
**Actual:** [What actually happened]
**Steps to Reproduce:**
1. Step 1
2. Step 2
3. Step 3

**Error Message:** [If any]
**Screenshot:** [If applicable]
```

---

**Ready to Begin Testing!** 🚀

Start with Live Orders and work through each section systematically.
