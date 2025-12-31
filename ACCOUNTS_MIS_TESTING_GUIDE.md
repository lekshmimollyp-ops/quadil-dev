# Accounts & MIS Testing Guide

**Date:** December 31, 2025  
**Status:** ✅ Test Data Ready  
**Login:** http://localhost:8080/login (admin@qudil.com / admin123)

---

## ✅ **Test Data Created Successfully!**

### **Wallets (6 total):**
- ✅ High balance: ₹50,000 (Buying Power: ₹75,000)
- ✅ Medium balance: ₹15,000 (Buying Power: ₹25,000)
- ✅ Low balance: ₹2,500 (Buying Power: ₹7,500) - for alert testing
- ✅ Very low: ₹500 (Buying Power: ₹5,500) - critical alert
- ✅ Zero balance: ₹0 (Buying Power: ₹10,000) - needs top-up
- ✅ Inactive: ₹1,000 (Suspended)

### **Transactions (13 total):**
- ✅ Wallet 1: 5 transactions (3 credits, 2 debits)
- ✅ Wallet 2: 4 transactions (2 credits, 2 debits)
- ✅ Wallet 3: 4 transactions (1 credit, 3 debits)

---

## 🧪 **TEST 1: Credit Control (Wallet Management)**

### **Step 1: Navigate to Credit Control**
1. Login to http://localhost:8080/login
2. Click **"Credit Control"** in sidebar (Accounts & MIS section)

### **TC-FIN-01: View Merchant Wallets**
**What to Check:**
- [ ] Wallet list displays (should show 6 wallets)
- [ ] Columns visible: Merchant/Tenant ID, Balance, Credit Limit, Buying Power, Status
- [ ] Buying Power = Balance + Credit Limit
- [ ] Low balance wallets highlighted (₹2,500 and ₹500 wallets)
- [ ] Zero balance wallet shows warning
- [ ] Inactive wallet marked as suspended

**Expected:**
- All 6 wallets display correctly
- Calculations are accurate
- Visual indicators for low balance

---

### **TC-FIN-02: Top-up Merchant Wallet**
**Steps:**
1. Find the **Zero balance wallet** (₹0)
2. Click **"MANAGE WALLET"** or **"Top-up"** button
3. Enter amount: `1000`
4. Add note: `Test top-up - December 31`
5. Click **"Add Credits"** or **"Submit"**

**What to Check:**
- [ ] Modal/form opens
- [ ] Amount field accepts positive numbers
- [ ] Note field accepts text
- [ ] Submit button works
- [ ] Balance updates from ₹0 to ₹1,000
- [ ] Buying Power updates to ₹11,000 (₹1,000 + ₹10,000 credit limit)
- [ ] Success message displays

**Expected:**
- Top-up succeeds
- Balance updates immediately
- New transaction appears in ledgers

---

### **TC-FIN-03: Set/Update Credit Limit**
**Steps:**
1. Select any wallet (e.g., Medium balance wallet)
2. Open wallet management
3. Find **"Credit Limit"** field
4. Change from current value to: `5000`
5. Save changes

**What to Check:**
- [ ] Credit limit field is editable
- [ ] New value saves successfully
- [ ] Buying Power recalculates (Balance + ₹5,000)
- [ ] Changes reflect immediately in wallet list

**Expected:**
- Credit limit updates
- Buying power recalculates correctly

---

### **TC-FIN-04: Low Balance Alert Verification**
**What to Check:**
- [ ] Low balance wallet (₹2,500) has warning indicator
- [ ] Very low wallet (₹500) has critical alert
- [ ] Color coding: amber for low, red for critical
- [ ] Alert icons visible
- [ ] Status badges show wallet health

**Expected:**
- Visual warnings are clear
- Different severity levels distinguishable

---

## 🧪 **TEST 2: Usage Ledgers**

### **Step 2: Navigate to Usage Ledgers**
1. Click **"Usage Ledgers"** in sidebar (Accounts & MIS section)
2. URL: http://localhost:8080/accounts/ledgers

### **TC-LED-01: View All Ledger Entries**
**What to Check:**
- [ ] Transaction list displays (should show 13+ transactions)
- [ ] Columns visible: Date/Time, Merchant/Wallet, Type, Amount, Description
- [ ] Credits show as positive (green) with "+" sign
- [ ] Debits show as negative (red) with "-" sign
- [ ] Transactions sorted by date (newest first)
- [ ] Reference IDs visible (TOP-xxx, ORD-xxx)

**Expected:**
- All 13 transactions display
- Credits and debits clearly differentiated
- Data is accurate

---

### **TC-LED-02: Filter by Merchant**
**Steps:**
1. Look for merchant/wallet filter dropdown
2. Select a specific wallet (e.g., High balance wallet)
3. Verify only that wallet's transactions show

**What to Check:**
- [ ] Filter dropdown exists
- [ ] Filtering works correctly
- [ ] Only selected wallet's transactions display
- [ ] Transaction count updates

**Expected:**
- Filtering works
- Correct transactions shown

---

### **TC-LED-03: Verify Transaction Details**
**What to Check:**
- [ ] Click on a transaction to view details (if supported)
- [ ] Reference ID links to order (if applicable)
- [ ] Description is clear and informative
- [ ] Amounts match wallet balance changes

**Expected:**
- Transaction details are complete
- References are valid

---

## 🧪 **TEST 3: COD Summary**

### **Step 3: Navigate to COD Summary**
1. Click **"COD Summary"** in sidebar (Accounts & MIS section)
2. URL: http://localhost:8080/accounts/reports/cod-summary

### **TC-COD-01: View COD Summary**
**What to Check:**
- [ ] COD report displays
- [ ] Agent list shows (if COD orders exist)
- [ ] Columns: Agent Name/ID, Total Collected, Order Count, Pending Remittance
- [ ] Settlement dates visible

**Note:** COD data depends on delivered orders with COD payment method. May be empty if no COD orders exist.

**Expected:**
- Report loads successfully
- Data displays if available
- Empty state message if no COD orders

---

### **TC-COD-02: Verify COD Calculations**
**If COD data exists:**
- [ ] Total collected = sum of COD order amounts
- [ ] Pending remittance = Total - Settled
- [ ] Order count matches delivered COD orders

**Expected:**
- Calculations are accurate
- No discrepancies

---

### **TC-COD-03: Settlement Tracking**
**What to Check:**
- [ ] Settlement status indicators (Settled/Pending)
- [ ] Settlement dates displayed
- [ ] Settlement history (if available)

**Expected:**
- Settlement status is clear
- Dates are accurate

---

## ✅ **Testing Checklist Summary**

### **Credit Control:**
- [ ] TC-FIN-01: View wallets ✅
- [ ] TC-FIN-02: Top-up wallet ✅
- [ ] TC-FIN-03: Update credit limit ✅
- [ ] TC-FIN-04: Low balance alerts ✅

### **Usage Ledgers:**
- [ ] TC-LED-01: View all entries ✅
- [ ] TC-LED-02: Filter by merchant ✅
- [ ] TC-LED-03: Verify details ✅

### **COD Summary:**
- [ ] TC-COD-01: View report ✅
- [ ] TC-COD-02: Verify calculations ✅
- [ ] TC-COD-03: Settlement tracking ✅

---

## 📝 **After Testing**

Document results in `ADMIN_TEST_CASES.md`:
- Mark each test case as ✅ PASSED or ❌ FAILED
- Note any issues encountered
- Add actual results vs expected results
- Take screenshots if needed

---

## 🎯 **Success Criteria**

Testing is complete when:
1. ✅ All 6 wallets display correctly
2. ✅ Wallet top-up works
3. ✅ Credit limit updates work
4. ✅ Low balance alerts visible
5. ✅ All 13 transactions display in ledgers
6. ✅ Ledger filtering works
7. ✅ COD report displays (or shows empty state)
8. ✅ No console errors
9. ✅ All test cases documented

---

**Ready to test! Start with Credit Control and work through each section.** 🚀
