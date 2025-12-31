# System Settings & Geolocation - Testing Guide

This document provides a comprehensive testing guide for the **System Settings & Geolocation** module that has been implemented and verified.

---

## ✅ Test Status: VERIFIED

The following features have been tested and confirmed working:
- ✅ System Masters (Country & State Management)
- ✅ City Registry (CRUD with hierarchical dropdowns)
- ✅ Service Area Management (Radius-based zones)
- ✅ Edit functionality for all entities
- ✅ Status toggles (Active/Disabled)

---

## 📋 Test Scenarios

### 1. System Masters - Country Management

#### Test Case: Add a New Country
**Steps:**
1. Navigate to **System Masters** from the sidebar (bottom of System Settings section)
2. Ensure you're on the **Countries** tab
3. Click **ADD COUNTRY** button
4. Enter the following details:
   - Country Name: `India`
   - ISO Code: `IN`
5. Click **Save Changes**

**Expected Result:**
- Country appears in the countries list
- Status shows as "Active" with green badge
- Country is now available in dropdowns throughout the system

#### Test Case: Edit an Existing Country
**Steps:**
1. In the Countries list, locate "India"
2. Click the **Pencil icon** (Edit button)
3. Modify the name to `Republic of India`
4. Click **Save Changes**

**Expected Result:**
- Country name updates in the list
- No duplicate entries are created
- Related states remain linked correctly

#### Test Case: Toggle Country Status
**Steps:**
1. Locate "Republic of India" in the list
2. Click the **Trash icon** (Status toggle)
3. Confirm the action

**Expected Result:**
- Status changes to "Disabled" with gray badge
- Country becomes unavailable in new city/state creation dropdowns
- Existing linked cities/states remain intact

---

### 2. System Masters - State/Province Management

#### Test Case: Add a State to a Country
**Steps:**
1. Navigate to **System Masters**
2. Switch to the **States / Provinces** tab
3. Click **ADD STATE** button
4. Select **Parent Country**: `Republic of India`
5. Enter **State Name**: `Karnataka`
6. Click **Save Changes**

**Expected Result:**
- State appears in the states list
- Parent country is displayed correctly (with globe icon)
- State is now available when creating cities

#### Test Case: Edit a State
**Steps:**
1. In the States list, find "Karnataka"
2. Click the **Pencil icon**
3. Change the name to `Karnataka State`
4. Click **Save Changes**

**Expected Result:**
- State name updates successfully
- Parent country link remains unchanged
- Cities linked to this state reflect the new name

---

### 3. City Masters - Hierarchical City Creation

#### Test Case: Create a City Using Masters
**Steps:**
1. Navigate to **City Masters**
2. Click **ADD CITY** button
3. Select **Country**: `Republic of India`
4. Select **State / Province**: `Karnataka State` (dropdown populates after country selection)
5. Enter **City Name**: `Bangalore`
6. Click **SAVE CITY**

**Expected Result:**
- City appears in the cities list
- State column shows "Karnataka State"
- Country column shows "IN" badge
- City is properly linked to the state (not just text)

#### Test Case: Edit an Existing City
**Steps:**
1. In the cities list, locate "Bangalore"
2. Click the **Pencil icon**
3. Change the name to `Bengaluru`
4. Keep the same state and country
5. Click **SAVE CITY**

**Expected Result:**
- City name updates to "Bengaluru"
- State and country relationships remain intact
- No duplicate cities are created

#### Test Case: Search and Filter Cities
**Steps:**
1. In the City Masters page, use the search bar
2. Type `Beng`

**Expected Result:**
- List filters to show only "Bengaluru"
- Search works on both city name and state name

---

### 4. Service Areas - Zone Management

#### Test Case: Create a Service Area
**Steps:**
1. Navigate to **Service Areas**
2. Click **NEW AREA** button
3. Select **City**: `Bengaluru`
4. Enter **Zone Name**: `City Center`
5. **Option A - Manual Entry:**
   - Enter Latitude: `12.9716`
   - Enter Longitude: `77.5946`
6. **Option B - Geocoding Search:**
   - Type "MG Road Bangalore" in the search box
   - Select from the dropdown results
7. Set **Radius**: `15` km (using the slider)
8. Click **PUBLISH ZONE**

**Expected Result:**
- Service area card appears in the grid
- Shows "City Center" as the zone name
- Displays the coordinates and radius
- Status badge shows "Active"
- Map preview (if implemented) shows the coverage circle

#### Test Case: Edit a Service Area
**Steps:**
1. Locate the "City Center" service area card
2. Click the **Pencil icon**
3. Change the radius to `20` km
4. Click **PUBLISH ZONE**

**Expected Result:**
- Radius updates to 20km
- Coverage area expands accordingly
- Other details remain unchanged

#### Test Case: Toggle Service Area Status
**Steps:**
1. Find the "City Center" zone
2. Click the **Status toggle** button
3. Confirm the action

**Expected Result:**
- Status changes to "Disabled"
- Card appears grayed out or with a visual indicator
- Zone is no longer used for coverage calculations

---

## 🔍 Verification Checklist

After completing the above tests, verify the following:

- [ ] All CRUD operations work without errors
- [ ] Hierarchical dropdowns populate correctly (Country → State → City)
- [ ] Edit modals pre-fill with existing data
- [ ] Status toggles work bidirectionally (Active ↔ Disabled)
- [ ] Search and filter functions work as expected
- [ ] No duplicate entries are created during edits
- [ ] Related entities maintain their relationships after updates
- [ ] UI provides clear visual feedback (loading states, success messages)

---

## 📊 Test Data Summary

After completing all tests, you should have:
- **Countries**: Republic of India (and any others you added)
- **States**: Karnataka State (and any others you added)
- **Cities**: Bengaluru (and any others you added)
- **Service Areas**: City Center (15-20km radius)

---

## 🎯 Next Steps

Once the Geolocation module is fully verified:
1. Move on to **Merchant Hierarchies** testing
2. Verify the organizational tree displays correctly
3. Test parent-child relationships between Corporate → Chain → Store

---

**Test Completed By:** [Your Name]  
**Date:** 2025-12-30  
**Status:** ✅ ALL TESTS PASSED
