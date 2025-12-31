# Manual Dispatch Testing Guide

**Status:** ✅ Ready for Testing  
**Date:** December 31, 2025

---

## ✅ **Prerequisites - All Services Running**

| Service | Port | Status | Required For |
|---------|------|--------|--------------|
| Order Service | 8004 | ✅ Running | Order data |
| Dispatch Service | 8006 | ✅ Running | Driver assignment |
| Agent Service | 8008 | ✅ Running | Driver/agent data |
| API Gateway | 8000 | ✅ Running | Request routing |
| Admin Web | 8080 | ✅ Running | UI interface |

---

## 📋 **Test Data Available**

### **Orders Ready for Dispatch:**
- **3 Pending Orders** - Status: "pending", waiting for agent assignment

### **Agents Available:**
From previous testing, you have 5 agents:
- Agent 1003 - Active/Online
- Agent 1004 - Active/Offline  
- Agent 1005 - Active/Away

**Note:** Only **online** agents will appear in the dispatch dropdown.

---

## 🧪 **Manual Dispatch Test Steps**

### **Step 1: Navigate to Live Orders**
1. Login to http://localhost:8080/login
2. Click **"Live Orders"** in the sidebar (under Dispatch Governance)
3. You should see all 16 orders

### **Step 2: Filter Pending Orders**
1. Look for orders with status badge **"Pending"** (amber/yellow color)
2. You should see **3 pending orders**
3. These are ready for manual dispatch

### **Step 3: Open Order Details**
1. Click on any **Pending** order's ID
2. Order detail page will open
3. You should see:
   - Order information
   - Pickup and delivery addresses
   - Parcel details
   - **"Manual Dispatch"** section

### **Step 4: Assign Driver**
1. In the "Manual Dispatch" section, you'll see:
   - Dropdown list of **online drivers**
   - "ASSIGN DRIVER" button

2. Select a driver from the dropdown
3. Click **"ASSIGN DRIVER"** button

### **Step 5: Verify Assignment**
1. Page will refresh
2. Order status should change from **"Pending"** to **"Assigned"**
3. Driver name should appear in order details
4. Success message: "Driver assigned successfully"

---

## ⚠️ **Potential Issues & Solutions**

### **Issue 1: No Drivers in Dropdown**
**Cause:** No agents are marked as "online"

**Solution:** 
1. Go to **Agent Roster** (Dispatch Governance → Agent Roster)
2. Check agent statuses
3. Ensure at least one agent has status "Online" (green badge)
4. If all are offline, you may need to update agent status in the database

**Quick Fix:**
```bash
cd c:/Users/Lekshmi M/Documents/velosit/retalinepro/quadil/agent-service
php artisan tinker --execute="App\Models\Agent::where('id', 1003)->update(['status' => 'online']);"
```

---

### **Issue 2: "Failed to assign driver" Error**
**Cause:** Dispatch service not responding or missing assignment logic

**Solution:**
1. Check dispatch-service is running on port 8006
2. Check API Gateway logs
3. Verify dispatch service has assignment endpoint

---

### **Issue 3: Order Status Not Updating**
**Cause:** Assignment succeeded but order status not updated

**Solution:**
1. Refresh the page
2. Check order-service logs
3. Verify order status in database:
```bash
cd c:/Users/Lekshmi M/Documents/velosit/retalinepro/quadil/order-service
php artisan tinker --execute="App\Models\Order::where('status', 'assigned')->get(['id', 'status']);"
```

---

## ✅ **Expected Behavior**

### **Before Assignment:**
- Order status: **Pending**
- No driver assigned
- "Manual Dispatch" section shows available drivers

### **After Assignment:**
- Order status: **Assigned**
- Driver name/ID visible
- Assignment timestamp recorded
- Driver can see order in their queue

---

## 🔄 **Testing Multiple Assignments**

You have **3 pending orders**, so you can:

1. **Test 1:** Assign first pending order to Agent 1003
2. **Test 2:** Assign second pending order to Agent 1004
3. **Test 3:** Assign third pending order to Agent 1005

This tests:
- Multiple assignments
- Different agents
- Assignment distribution

---

## 📊 **Verification Checklist**

After manual dispatch testing, verify:

- [ ] Pending orders list shows correctly
- [ ] Order detail page loads
- [ ] Driver dropdown populates with online agents
- [ ] Assignment button works
- [ ] Order status updates to "Assigned"
- [ ] Driver information appears in order
- [ ] Success message displays
- [ ] Can assign multiple orders
- [ ] Assigned orders move out of pending list

---

## 🎯 **Success Criteria**

Manual dispatch testing is successful when:

1. ✅ Can view all pending orders
2. ✅ Can open order details
3. ✅ Can see available online drivers
4. ✅ Can select a driver
5. ✅ Can assign driver to order
6. ✅ Order status updates correctly
7. ✅ Assignment is persisted
8. ✅ Can repeat for multiple orders

---

## 📝 **Test Case: TC-ORD-03**

**Test Case ID:** TC-ORD-03  
**Test Name:** Manual Dispatch Assignment  
**Precondition:** At least 1 pending order and 1 online agent

**Steps:**
1. Navigate to Live Orders
2. Click on a pending order
3. Select an online driver from dropdown
4. Click "Assign Driver"

**Expected Result:**
- Assignment succeeds
- Order status → "Assigned"
- Driver name appears
- Success notification shown

**Status:** Ready for Testing ✅

---

**All Set! You can now test manual dispatch functionality!** 🚀
