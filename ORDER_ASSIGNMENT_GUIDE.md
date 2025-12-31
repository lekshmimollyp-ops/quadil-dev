# Order Assignment & Re-assignment - Technical Guide

**Date:** December 31, 2025  
**Status:** Current Implementation Analysis

---

## 📊 **Order Status Flow**

### **Complete Lifecycle:**
```
draft → pending → assigned → picked_up → delivered
                     ↓
                 cancelled
```

### **Status Definitions:**

| Status | Description | Can Assign Driver? |
|--------|-------------|-------------------|
| **draft** | Order created but not confirmed | ❌ No |
| **pending** | Confirmed, waiting for driver | ✅ Yes (Primary) |
| **assigned** | Driver assigned, not picked up | ⚠️ Currently Yes (Re-assignment) |
| **picked_up** | Driver has parcel, en route | ❌ No (too late) |
| **delivered** | Successfully completed | ❌ No (completed) |
| **cancelled** | Order cancelled | ❌ No (cancelled) |

---

## 🔄 **Current Re-assignment Behavior**

### **What Happens Now:**

When you assign a driver to an **already assigned** order:

1. ✅ **Assignment succeeds** - No error thrown
2. ⚠️ **New assignment created** in dispatch-service
3. ⚠️ **Old assignment remains** in database
4. ⚠️ **Previous driver status** still shows "busy"
5. ⚠️ **No notification** to previous driver about cancellation
6. ⚠️ **Potential confusion** about who is responsible

### **Database State After Re-assignment:**

```sql
-- assignments table in dispatch-service
| id | order_id | driver_id | status | created_at |
|----|----------|-----------|--------|------------|
| 1  | order-123| driver-A  | assigned | 10:00 AM |
| 2  | order-123| driver-B  | assigned | 10:15 AM | ← New
```

**Problem:** Two active assignments for same order!

---

## ✅ **Recommended Best Practices**

### **Option 1: Prevent Re-assignment (Safest)**

**Implementation:**
- Check order status before allowing assignment
- Only allow assignment if status = `pending`
- Show error message if order is already `assigned`

**Benefits:**
- ✅ Prevents confusion
- ✅ Clear responsibility
- ✅ Simple logic
- ✅ No orphaned assignments

**UI Change:**
```javascript
// In Show.vue - assignDriver function
const assignDriver = (driverId) => {
    // Check if order is already assigned
    if (props.order.status === 'assigned') {
        alert('This order is already assigned to a driver. Please unassign first.');
        return;
    }
    
    if (confirm('Are you sure you want to assign this driver?')) {
        form.driver_id = driverId;
        form.post(route('orders.assign', props.order.id));
    }
};
```

---

### **Option 2: Explicit Unassign → Reassign Flow**

**Implementation:**
- Add "Unassign Driver" button for assigned orders
- Unassign sets order status back to `pending`
- Marks old assignment as `cancelled`
- Updates previous driver status to `online`
- Then allow new assignment

**Workflow:**
```
1. Admin clicks "Unassign Driver"
2. Order status: assigned → pending
3. Old assignment: status → cancelled
4. Previous driver: busy → online
5. Admin can now assign new driver
```

**Benefits:**
- ✅ Clear audit trail
- ✅ Proper cleanup
- ✅ Driver availability updated
- ✅ Explicit admin action required

---

### **Option 3: Allow Re-assignment with Safeguards**

**Implementation:**
- Allow re-assignment but with confirmations
- Cancel old assignment automatically
- Update previous driver status
- Send notifications to both drivers
- Log re-assignment reason

**Workflow:**
```
1. Admin selects new driver for assigned order
2. System shows warning:
   "Order is currently assigned to Driver A. 
    Reassign to Driver B?"
3. Admin confirms
4. System:
   - Cancels old assignment
   - Updates driver A → online
   - Creates new assignment
   - Updates driver B → busy
   - Sends notifications
   - Logs reason
```

**Benefits:**
- ✅ Flexibility for emergencies
- ✅ Proper cleanup
- ✅ Audit trail
- ✅ Driver notifications

---

## 🎯 **Recommended Implementation**

For your testing and production use, I recommend **Option 1** (Prevent Re-assignment) because:

1. **Simplest** - Least code changes
2. **Safest** - No data inconsistencies
3. **Clearest** - Explicit workflow
4. **Testable** - Easy to verify

If re-assignment is needed:
- Admin must first "unassign" the driver
- Order returns to `pending` status
- Then assign new driver

---

## 🛠️ **Implementation Guide**

### **Step 1: Update Frontend (Show.vue)**

Add status check in `assignDriver` function:

```javascript
const assignDriver = (driverId) => {
    // Prevent re-assignment
    if (props.order.status !== 'pending') {
        alert(`Cannot assign driver. Order status is "${props.order.status}". Only pending orders can be assigned.`);
        return;
    }
    
    if (confirm('Are you sure you want to assign this driver?')) {
        form.driver_id = driverId;
        form.post(route('orders.assign', props.order.id));
    }
};
```

### **Step 2: Update Backend (OrderController.php)**

Add validation in `assign` method:

```php
public function assign(Request $request, string $id)
{
    $token = session('remote_token');
    
    // First, get current order status
    $orderRes = Http::withToken($token)->timeout(5)
        ->get("{$this->orderBaseUrl}/orders/{$id}");
    
    if (!$orderRes->successful()) {
        return back()->withErrors(['message' => 'Failed to fetch order details.']);
    }
    
    $order = $orderRes->json();
    
    // Check if order is pending
    if ($order['status'] !== 'pending') {
        return back()->withErrors([
            'message' => "Cannot assign driver. Order status is '{$order['status']}'. Only pending orders can be assigned."
        ]);
    }
    
    // Proceed with assignment
    $response = Http::withToken($token)->timeout(5)
        ->post("{$this->dispatchBaseUrl}/assign", [
            'order_id' => $id,
            'driver_id' => $request->driver_id,
        ]);

    if ($response->successful()) {
        return Redirect::route('orders.show', $id)
            ->with('success', 'Driver assigned successfully.');
    }

    return back()->withErrors(['message' => 'Failed to assign driver.']);
}
```

### **Step 3: Update Dispatch Service**

Add validation in `DispatchController.php`:

```php
public function assignOrder(Request $request)
{
    $request->validate([
        'order_id' => 'required|uuid',
        'driver_id' => 'required|uuid',
    ]);
    
    // Check if order already has an active assignment
    $existingAssignment = Assignment::where('order_id', $request->order_id)
        ->where('status', 'assigned')
        ->first();
    
    if ($existingAssignment) {
        return response()->json([
            'message' => 'Order already has an active assignment',
            'existing_driver_id' => $existingAssignment->driver_id
        ], 409); // Conflict
    }
    
    // Create new assignment
    $assignment = Assignment::create([
        'order_id' => $request->order_id,
        'driver_id' => $request->driver_id,
        'status' => 'assigned',
    ]);

    // Update driver status to busy
    Driver::where('id', $request->driver_id)
        ->update(['status' => 'busy']);

    return response()->json([
        'message' => 'Driver assigned to order',
        'assignment' => $assignment
    ]);
}
```

---

## 📋 **Testing Scenarios**

### **Test 1: Normal Assignment (Should Work)**
1. Order status: `pending`
2. Select driver
3. Click assign
4. **Expected:** Assignment succeeds, status → `assigned`

### **Test 2: Re-assignment Attempt (Should Fail)**
1. Order status: `assigned`
2. Try to select different driver
3. Click assign
4. **Expected:** Error message, no assignment

### **Test 3: Assignment After Pickup (Should Fail)**
1. Order status: `picked_up`
2. Try to assign driver
3. **Expected:** Error message, no assignment

---

## 💡 **Current Testing Recommendation**

For your **current testing session**:

1. **Test normal assignment** on pending orders ✅
2. **Avoid re-assignment** for now
3. **Document behavior** if re-assignment is attempted
4. **Decide on policy:**
   - Should re-assignment be allowed?
   - If yes, which safeguards are needed?

---

## 🎯 **Summary**

**Question 1:** What should be the status after assignment?
- **Answer:** `pending` → `assigned`

**Question 2:** Should re-assignment be allowed?
- **Current:** Yes (no validation)
- **Recommended:** No (add validation)
- **Alternative:** Yes, but with proper cleanup and safeguards

**Next Steps:**
1. Test current behavior with pending orders
2. Decide on re-assignment policy
3. Implement chosen option
4. Add proper error messages
5. Update documentation

---

**For Now:** Test with pending orders only. Avoid re-assigning already assigned orders until we implement proper safeguards.
