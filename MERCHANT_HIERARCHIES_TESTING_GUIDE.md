# Merchant Hierarchies - Testing Guide

This document provides a comprehensive testing guide for the **Merchant Hierarchies** module (Consignee Governance).

---

## ✅ Test Status: READY FOR VERIFICATION

The following features are ready to be tested:
- 🏢 Merchant Registry (Flat List View)
- 🌳 Organizational Tree (Hierarchical View)
- 🔗 Parent-Child Relationships
- ✏️ CRUD Operations for all tenant types

---

## 📊 Pre-seeded Test Data

The following test data has been loaded into your database:

### Hierarchical Structure:
```
Global Retail Corp (Corporate)
└── Fast Food Chain (Chain)

Local Corner Store (Single) - Independent
```

---

## 📋 Test Scenarios

### 1. Merchant Registry - Flat List View

#### Test Case: View All Merchants
**Steps:**
1. Navigate to **Merchant Registry** from the sidebar
2. Observe the merchant list

**Expected Result:**
- Table displays all merchants in a flat list
- Shows: Name, Type, Domain, Status, Join Date
- You should see:
  - "Global Retail Corp" (Type: corporate)
  - "Fast Food Chain" (Type: chain)
  - "Local Corner Store" (Type: single)

#### Test Case: Add a New Merchant
**Steps:**
1. Click **ADD MERCHANT** button
2. Fill in the form:
   - **Name**: `Tech Startup Inc`
   - **Type**: Select `Single`
   - **Domain**: `tech-startup.com`
3. Save the merchant

**Expected Result:**
- New merchant appears in the registry
- Status shows as "Active"
- Type badge displays correctly

#### Test Case: Edit an Existing Merchant
**Steps:**
1. Locate "Local Corner Store" in the list
2. Click the **Edit icon** (pencil)
3. Change the name to `Premium Corner Store`
4. Save changes

**Expected Result:**
- Name updates in the registry
- Other details remain unchanged
- No duplicate entries created

---

### 2. Organizational Tree - Hierarchical View

#### Test Case: View Hierarchical Structure
**Steps:**
1. Navigate to **Organizational Tree** from the sidebar
2. Observe the tree structure

**Expected Result:**
- Tree displays hierarchical relationships
- "Global Retail Corp" appears as a top-level node
- Visual indicators show it has children (chevron icon)
- Color coding:
  - Corporate = Indigo
  - Chain = Amber
  - Single = Emerald

#### Test Case: Expand/Collapse Tree Nodes
**Steps:**
1. Click on "Global Retail Corp" to expand
2. Observe the child nodes
3. Click again to collapse

**Expected Result:**
- Clicking expands to show "Fast Food Chain" nested underneath
- Indentation and border lines show the parent-child relationship
- Clicking again collapses the tree

#### Test Case: Navigate to Merchant Details
**Steps:**
1. In the Organizational Tree, hover over "Fast Food Chain"
2. Click the **External Link icon** that appears

**Expected Result:**
- Navigates to the merchant edit page
- Form is pre-filled with the merchant's current data
- Parent relationship is preserved

---

### 3. Parent-Child Relationships

#### Test Case: Create a Child Entity
**Steps:**
1. Go to **Merchant Registry**
2. Click **ADD MERCHANT**
3. Fill in:
   - **Name**: `Downtown Branch`
   - **Type**: `Single`
   - **Parent**: Select `Fast Food Chain`
   - **Domain**: `downtown-branch.local`
4. Save

**Expected Result:**
- New merchant is created
- In the Organizational Tree, "Downtown Branch" appears nested under "Fast Food Chain"
- Tree structure now shows:
  ```
  Global Retail Corp
  └── Fast Food Chain
      └── Downtown Branch
  ```

#### Test Case: Verify Multi-Level Nesting
**Steps:**
1. Navigate to **Organizational Tree**
2. Expand "Global Retail Corp"
3. Expand "Fast Food Chain"

**Expected Result:**
- Three levels of nesting are visible
- Visual hierarchy is clear with proper indentation
- Each level has appropriate color coding

---

### 4. Switch Between Views

#### Test Case: Toggle Between List and Tree
**Steps:**
1. Start in **Merchant Registry** (flat list)
2. Click **Organizational Tree** from the sidebar
3. Click **Flat List View** button in the tree page

**Expected Result:**
- Seamless navigation between views
- Data consistency across both views
- Same merchants appear in both

---

## 🔍 Verification Checklist

After completing the above tests, verify:

- [ ] All merchants display correctly in the flat list
- [ ] Organizational tree shows proper parent-child relationships
- [ ] Tree nodes can be expanded and collapsed
- [ ] Color coding differentiates tenant types
- [ ] Edit links work from both views
- [ ] New merchants can be added with parent selection
- [ ] Multi-level nesting (3+ levels) works correctly
- [ ] Independent merchants (no parent) display at root level
- [ ] Status indicators (Active/Inactive) work correctly

---

## 📊 Expected Final Data Structure

After all tests, your hierarchy should look like:

```
Global Retail Corp (Corporate)
└── Fast Food Chain (Chain)
    └── Downtown Branch (Single)

Premium Corner Store (Single) - Independent
Tech Startup Inc (Single) - Independent
```

---

## 🎯 Next Steps

Once Merchant Hierarchies are verified:
1. Test **Agent Governance** (Approval workflows)
2. Test **Financial Controls** (Wallet management)
3. Test **Order Management** (Live stream)

---

**Test Completed By:** [Your Name]  
**Date:** 2025-12-30  
**Status:** ⏳ PENDING VERIFICATION
