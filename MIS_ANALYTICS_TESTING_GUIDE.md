# MIS Analytics Testing Guide

## 📊 Overview
This guide covers the testing of the **MIS Analytics** module (`/analytics`), which acts as the central intelligence dashboard for the Application Admin.

---

## 🧪 Test Scenarios

### **TC-ANA-01: Platform Overview**
**Objective:** Verify that the main dashboard loads populated data.
1.  Navigate to **MIS Analytics** from the sidebar.
2.  Check the **Platform Gross Revenue** card.
    *   **Expected:** Should show a value > $10,000 (aggregated from real + synthetic data).
    *   **Trend:** Should show a percentage change (e.g., +12.5%).
3.  Check **Operating Outflow** and **Net Profit**.
    *   **Expected:** Values should be calculated and formatted as currency.

### **TC-ANA-02: Revenue & Performance Charts**
**Objective:** Verify chart visualization (if implemented) or key metrics.
1.  Look successfully at the "Revenue Trends" or "Order Volume" sections.
2.  **Expected:** Data should not be empty. You seeded ~8 tenants total, so metrics should reflect this volume.

### **TC-ANA-03: Active Merchant List**
**Objective:** Verify individual merchant performance stats.
1.  Scroll down to "Merchant Performance".
2.  **Expected:** You should see stats for **Dhanya Supermarket**, **Downtown Branch**, etc.
    *   **Order Count:** Should be between 50-200 (as per seeder).
    *   **Revenue:** Should be between $5,000 - $25,000.

---

## ✅ Success Criteria
- [ ] No "0" values for Platform Revenue.
- [ ] No "Loading..." errors that persist.
- [ ] Merchant names from `tenant-service` should ideally match (Note: Analytics Service stores `tenant_id`, frontend might need to resolve names or showing ID is acceptable for this MVP phase).
