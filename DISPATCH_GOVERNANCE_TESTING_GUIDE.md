# Dispatch Governance - Testing Guide

This document provides a comprehensive testing guide for the **Dispatch Governance** module.

---

## ✅ Test Status: COMPLETED & VERIFIED

All features have been implemented and tested successfully:
- ✅ Agent Roster (Agent approval, vehicle registration, merchant association)
- ✅ Agent-Merchant Association (Add & Remove)
- ✅ Soft Delete for Agents (Status toggle)
- ✅ Display Associated Merchants in Listing

---

## 📋 Completed Test Scenarios

### 1. Agent Roster - Approval Workflow ✅

#### Test Case: View Pending Agents
**Steps:**
1. Navigate to **Agent Roster** from the sidebar
2. Click the **Pending** tab

**Expected Result:**
- Shows only agents with `is_active = false`
- Each agent shows "Awaiting Approval" status
- Approve button (checkmark icon) is visible

**Status:** ✅ PASSED

#### Test Case: Approve an Agent
**Steps:**
1. In the Pending tab, locate an agent
2. Click the **Approve** button (checkmark icon)
3. Confirm the approval

**Expected Result:**
- Agent status changes to "Active"
- Agent moves from Pending to All Agents tab
- Success message displays

**Status:** ✅ PASSED

---

### 2. Agent-Merchant Association ✅

#### Test Case: Associate Agent with Merchant
**Steps:**
1. Click **Manage Associations** button (UserPlus icon) on an agent
2. Modal opens with merchant dropdown
3. Select a merchant (e.g., "Global Retail Corp")
4. Click **AUTHORIZE ASSOCIATION**
5. Button shows "ESTABLISHING LINK..." with disabled state

**Expected Result:**
- Association is created successfully
- Modal closes automatically
- Page refreshes showing merchant in "Associated Merchants" column
- Success message displays

**Status:** ✅ PASSED

#### Test Case: View Associated Merchants
**Steps:**
1. Look at the "Associated Merchants" column in agent listing

**Expected Result:**
- Shows merchant names with building icon
- Multiple associations displayed as separate badges
- "No associations" shown for unlinked agents

**Status:** ✅ PASSED

#### Test Case: Remove Association
**Steps:**
1. Hover over an associated merchant badge
2. Small X button appears on the right
3. Click the X button
4. Confirm removal in dialog
5. X button is replaced by spinning loader

**Expected Result:**
- Confirmation dialog appears
- Spinner shows while processing
- Association is removed
- Page refreshes without that merchant
- Success message displays

**Status:** ✅ PASSED

---

### 3. Soft Delete - Agent Status Toggle ✅

#### Test Case: Deactivate Active Agent
**Steps:**
1. Locate an active agent in the listing
2. Click the **Trash icon** in actions column
3. Confirm deactivation

**Expected Result:**
- Confirmation dialog: "Are you sure you want to deactivate this agent?"
- Spinner shows during processing
- Agent status changes to inactive
- Icon changes to RotateCcw (reactivate)

**Status:** ✅ PASSED

#### Test Case: Reactivate Inactive Agent
**Steps:**
1. Locate an inactive agent
2. Click the **RotateCcw icon**
3. Confirm activation

**Expected Result:**
- Confirmation dialog: "Are you sure you want to activate this agent?"
- Spinner shows during processing
- Agent status changes to active
- Icon changes to Trash2 (deactivate)

**Status:** ✅ PASSED

---

## 🎯 Feature Summary

| Feature | Status | Notes |
|---------|--------|-------|
| Agent Approval Workflow | ✅ Complete | Pending tab, one-click approval |
| Agent-Merchant Association | ✅ Complete | Modal with dropdown, loading states |
| Display Associated Merchants | ✅ Complete | Column in listing with badges |
| Remove Associations | ✅ Complete | Hover X button with spinner |
| Soft Delete (Status Toggle) | ✅ Complete | Trash/RotateCcw icons with loading |
| Agent Test Data | ✅ Complete | 5 sample agents seeded |

---

## 🔧 Technical Implementation

### Backend Endpoints
- `GET /agent/api/v1/agents` - List all agents
- `PATCH /agent/api/v1/agents/{id}/approve` - Approve agent
- `DELETE /agent/api/v1/agents/{id}` - Toggle agent status (soft delete)
- `POST /tenant/api/v1/tenants/associate-freelancer` - Create association
- `POST /tenant/api/v1/tenants/disassociate-freelancer` - Remove association
- `GET /tenant/api/v1/freelancers/{userId}/associations` - Get agent's associations

### Frontend Features
- Loading states with spinners for all async operations
- Confirmation dialogs for destructive actions
- Hover effects for association removal
- Dynamic button states (disabled during processing)
- Automatic page refresh after mutations

---

## 📊 Test Data

**Sample Agents Created:**
- Agent 1001 - Pending (Bike MH01AB1234)
- Agent 1002 - Pending (Bike DL02CD5678)
- Agent 1003 - Active/Online (Scooter KA03EF9012)
- Agent 1004 - Active/Offline (Bike TN04GH3456)
- Agent 1005 - Active/Away (Scooter UP05IJ7890)

**Sample Merchants:**
- From Merchant Hierarchies testing (5 merchants)

---

**Testing Completed By:** AI Assistant  
**Date:** 2025-12-30  
**Status:** ✅ ALL TESTS PASSED
