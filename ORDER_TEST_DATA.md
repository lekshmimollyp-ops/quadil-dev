# Order Test Data - Summary

**Created:** December 31, 2025  
**Status:** ✅ Successfully Seeded  
**Total Orders:** 16 orders

---

## 📊 **Order Distribution by Status**

| Status | Count | Description | Use Case |
|--------|-------|-------------|----------|
| **Draft** | 2 | Just created, not yet confirmed | Test order creation flow |
| **Pending** | 3 | Confirmed, waiting for agent assignment | Test dispatch assignment |
| **Assigned** | 3 | Agent assigned, not yet picked up | Test agent acceptance |
| **Picked Up** | 2 | Agent has picked up, en route | Test live tracking |
| **Delivered** | 4 | Successfully completed | Test completion flow |
| **Cancelled** | 2 | Cancelled by customer/system | Test cancellation handling |

---

## 🗺️ **Sample Data Details**

### **Pickup Locations (Bangalore)**
1. MG Road, Bangalore (Rajesh Kumar)
2. Koramangala 5th Block (Priya Sharma)
3. Indiranagar 100 Feet Road (Amit Patel)

### **Delivery Locations (Bangalore)**
1. Whitefield Main Road (Sneha Reddy)
2. Electronic City Phase 1 (Vikram Singh)
3. JP Nagar 7th Phase (Lakshmi Iyer)
4. HSR Layout Sector 1 (Arjun Nair)

### **Parcel Types**
- Documents (2.5 kg)
- Electronics (5.0 kg)
- Food (1.0 kg)
- Clothing (3.5 kg)
- Groceries (10.0 kg)

### **Price Range**
- Minimum: ₹150.00
- Maximum: ₹420.00
- Average: ~₹230.00

---

## 🔗 **Data Integrity**

### **Tenant IDs (from tenant-service)**
- `11111111-1111-1111-1111-111111111111` - Global Retail Corp
- `22222222-2222-2222-2222-222222222222` - Fast Food Chain
- `33333333-3333-3333-3333-333333333333` - Local Corner Store

### **User IDs (Merchants/Admins)**
- User ID: 1, 2, 3, 4

### **Timestamps**
- **Draft orders:** 1-2 hours ago
- **Pending orders:** 15-45 minutes ago
- **Assigned orders:** 5 minutes - 3 hours ago
- **Picked up orders:** 15-25 minutes ago
- **Delivered orders:** 6 hours - 2 days ago
- **Cancelled orders:** 5 hours - 3 days ago

---

## ✅ **Testing Scenarios Covered**

### **1. Order Listing**
- View all orders across different statuses
- Filter by status
- Sort by date/time
- Search functionality

### **2. Order Details**
- View complete order information
- See pickup and delivery addresses
- Check parcel details
- View order timeline

### **3. Status Transitions**
```
Draft → Pending → Assigned → Picked Up → Delivered
                    ↓
                Cancelled
```

### **4. Manual Dispatch**
- 3 pending orders available for assignment
- Can test agent selection
- Can test assignment confirmation

### **5. Order Tracking**
- 2 orders in "picked_up" status for live tracking
- Real coordinates for map display
- Contact information available

### **6. Historical Data**
- 4 delivered orders for reporting
- 2 cancelled orders for analytics
- Various timestamps for trend analysis

---

## 🧪 **Test Cases Supported**

### **TC-ORD-01: View Live Orders**
✅ All 16 orders will display in the list  
✅ Status badges will show different colors  
✅ All columns populated with data

### **TC-ORD-02: View Order Details**
✅ Click any order to see full details  
✅ Pickup/delivery addresses with coordinates  
✅ Parcel information complete  
✅ Contact details available

### **TC-ORD-03: Manual Dispatch Assignment**
✅ 3 pending orders ready for assignment  
✅ Can select from available agents  
✅ Status will update to "assigned"

---

## 📝 **Sample Order Details**

### **Example: Pending Order #1**
```json
{
  "status": "pending",
  "pickup": {
    "address": "MG Road, Bangalore, Karnataka 560001",
    "contact": "Rajesh Kumar (+91 98765 43210)",
    "coordinates": [12.9716, 77.5946]
  },
  "delivery": {
    "address": "JP Nagar 7th Phase, Bangalore, Karnataka 560078",
    "contact": "Lakshmi Iyer (+91 98765 43222)",
    "coordinates": [12.8996, 77.5858]
  },
  "parcel": {
    "type": "Food",
    "weight": "1.0 kg",
    "description": "Fresh bakery items",
    "instructions": "Deliver within 2 hours"
  },
  "amount": "₹180.00"
}
```

---

## 🔄 **Re-seeding Instructions**

If you need to reset the data:

```bash
cd c:/Users/Lekshmi M/Documents/velosit/retalinepro/quadil/order-service

# Clear existing orders
php artisan tinker --execute="App\Models\Order::truncate();"

# Re-run seeder
php artisan db:seed --class=OrderDataSeeder
```

---

## ✅ **Verification**

To verify the data:

```bash
# Count total orders
php artisan tinker --execute="echo App\Models\Order::count();"

# Count by status
php artisan tinker --execute="echo App\Models\Order::where('status', 'pending')->count();"
```

---

## 🎯 **Ready for Testing!**

All order test data is now available. You can:

1. Navigate to **Live Orders** → http://localhost:8080/orders
2. View all 16 orders in different statuses
3. Click on any order to see details
4. Test manual dispatch on pending orders
5. Track picked-up orders
6. Review delivered and cancelled orders

**Data Integrity:** ✅ All foreign keys valid  
**Realistic Data:** ✅ Real Bangalore addresses  
**Complete Coverage:** ✅ All 6 statuses represented  
**Testing Ready:** ✅ Supports all test cases

---

**Happy Testing! 🚀**
