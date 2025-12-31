# QUDIL Admin Web Application - Startup Guide

This document provides step-by-step instructions to start the QUDIL Admin Web Application and all required microservices.

---

## 📋 Prerequisites

Before starting the application, ensure you have:
- ✅ PHP 8.3+ installed
- ✅ Node.js installed
- ✅ PostgreSQL database running
- ✅ Composer dependencies installed in all services
- ✅ NPM dependencies installed in admin-web and api-gateway

---

## 🚀 Quick Start (7 Commands)

Open **7 separate terminal windows** and run the following commands in order:

### Terminal 1: Auth Service (Port 8001)
```bash
cd c:/Users/Lekshmi M/Documents/velosit/retalinepro/quadil/auth-service
php artisan serve --port=8001
```

### Terminal 2: Tenant Service (Port 8002)
```bash
cd c:/Users/Lekshmi M/Documents/velosit/retalinepro/quadil/tenant-service
php artisan serve --port=8002
```

### Terminal 3: Geo Service (Port 8003)
```bash
cd c:/Users/Lekshmi M/Documents/velosit/retalinepro/quadil/geo-service
php artisan serve --port=8003
```

### Terminal 4: Agent Service (Port 8008)
```bash
cd c:/Users/Lekshmi M/Documents/velosit/retalinepro/quadil/agent-service
php artisan serve --port=8008
```

### Terminal 5: API Gateway (Port 8000) ⚠️ **IMPORTANT - Start this BEFORE admin-web**
```bash
cd c:/Users/Lekshmi M/Documents/velosit/retalinepro/quadil/api-gateway
node index.js
```

### Terminal 6: Admin Web - Laravel Server (Port 8080)
```bash
cd c:/Users/Lekshmi M/Documents/velosit/retalinepro/quadil/admin-web
php artisan serve --port=8080
```

### Terminal 7: Admin Web - Vite Dev Server (Port 5173)
```bash
cd c:/Users/Lekshmi M/Documents/velosit/retalinepro/quadil/admin-web
npm run dev
```

---

## ✅ Verification Checklist

After starting all services, verify they are running:

| Service | Port | Check URL | Expected Response |
|---------|------|-----------|-------------------|
| Auth Service | 8001 | http://localhost:8001/api/v1/users/count | JSON with count |
| Tenant Service | 8002 | http://localhost:8002/api/v1/tenants | JSON array |
| Geo Service | 8003 | http://localhost:8003/api/v1/cities | JSON array |
| Agent Service | 8008 | http://localhost:8008/api/v1/agents | JSON array |
| API Gateway | 8000 | http://localhost:8000/health | Gateway status JSON |
| Admin Web | 8080 | http://localhost:8080/login | QUDIL Login Page |
| Vite | 5173 | Terminal shows "ready" | - |

---

## 🌐 Access the Application

### **Login URL:**
**http://localhost:8080/login**

### **Login Credentials:**
- **Email:** `admin@qudil.com`
- **Password:** `admin123`

### **Dashboard URL (after login):**
**http://localhost:8080/dashboard**

---

## 🔧 Troubleshooting

### Issue: "Maximum execution time exceeded" on login

**Cause:** API Gateway is not running or admin-web started before the gateway.

**Solution:**
1. Stop admin-web (Terminal 6)
2. Ensure API Gateway is running on port 8000 (Terminal 5)
3. Restart admin-web on port 8080

---

### Issue: Seeing default Laravel page instead of QUDIL interface

**Cause:** Vite dev server is not running.

**Solution:**
1. Start Vite dev server (Terminal 7)
2. Refresh browser at http://localhost:8080/login

---

### Issue: "Connection refused" errors

**Cause:** One or more microservices are not running.

**Solution:**
1. Check all 7 terminals are active
2. Verify each service shows "Server running on [http://...]"
3. Restart any failed services

---

## 📊 Service Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                        Browser                               │
│                 http://localhost:8080                        │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│                    Admin Web (Port 8080)                     │
│              Laravel + Inertia.js + Vue.js                   │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│                  API Gateway (Port 8000)                     │
│                   Node.js Proxy Router                       │
└─────────┬──────────┬──────────┬──────────┬──────────────────┘
          │          │          │          │
          ▼          ▼          ▼          ▼
    ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐
    │  Auth   │ │ Tenant  │ │   Geo   │ │  Agent  │
    │  :8001  │ │  :8002  │ │  :8003  │ │  :8008  │
    └─────────┘ └─────────┘ └─────────┘ └─────────┘
```

---

## 🛑 Shutdown Procedure

To stop all services:

1. Press `Ctrl+C` in each terminal window
2. Confirm termination if prompted
3. Close all terminal windows

**Recommended Order:**
1. Stop Vite (Terminal 7)
2. Stop Admin Web (Terminal 6)
3. Stop API Gateway (Terminal 5)
4. Stop all microservices (Terminals 1-4)

---

## 📝 Notes

- **Port 8000** is reserved for the API Gateway (not admin-web)
- **Port 8080** is the admin web application
- **Port 5173** is the Vite dev server for hot-reloading
- All microservices must be running for full functionality
- The API Gateway routes requests from admin-web to microservices

---

## 🔄 Optional: Additional Services

For complete functionality, you may also need to start:

### Order Service (Port 8004)
```bash
cd c:/Users/Lekshmi M/Documents/velosit/retalinepro/quadil/order-service
php artisan serve --port=8004
```

### Pricing Service (Port 8005)
```bash
cd c:/Users/Lekshmi M/Documents/velosit/retalinepro/quadil/pricing-service
php artisan serve --port=8005
```

### Wallet Service (Port 8010)
```bash
cd c:/Users/Lekshmi M/Documents/velosit/retalinepro/quadil/wallet-service
php artisan serve --port=8010
```

### Analytics Service (Port 8013)
```bash
cd c:/Users/Lekshmi M/Documents/velosit/retalinepro/quadil/analytics-service
php artisan serve --port=8013
```

---

## ✅ Success Indicators

You'll know everything is working when:

1. ✅ All 7 terminals show "Server running" or "ready"
2. ✅ http://localhost:8080/login shows the QUDIL login page
3. ✅ You can log in with admin@qudil.com / admin123
4. ✅ Dashboard loads without errors
5. ✅ Navigation menu is visible and functional

---

**Last Updated:** December 31, 2025  
**Version:** 1.0  
**Status:** Production Ready ✅
