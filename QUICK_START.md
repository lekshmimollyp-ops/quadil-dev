# QUDIL - Quick Start Cheat Sheet

## 🚀 Start All Services (Copy & Paste)

Open 7 terminals and paste these commands:

### Terminal 1 - Auth Service
```bash
cd c:/Users/Lekshmi M/Documents/velosit/retalinepro/quadil/auth-service && php artisan serve --port=8001
```

### Terminal 2 - Tenant Service
```bash
cd c:/Users/Lekshmi M/Documents/velosit/retalinepro/quadil/tenant-service && php artisan serve --port=8002
```

### Terminal 3 - Geo Service
```bash
cd c:/Users/Lekshmi M/Documents/velosit/retalinepro/quadil/geo-service && php artisan serve --port=8003
```

### Terminal 4 - Agent Service
```bash
cd c:/Users/Lekshmi M/Documents/velosit/retalinepro/quadil/agent-service && php artisan serve --port=8008
```

### Terminal 5 - API Gateway ⚠️ START THIS FIRST
```bash
cd c:/Users/Lekshmi M/Documents/velosit/retalinepro/quadil/api-gateway && node index.js
```

### Terminal 6 - Admin Web (Laravel)
```bash
cd c:/Users/Lekshmi M/Documents/velosit/retalinepro/quadil/admin-web && php artisan serve --port=8080
```

### Terminal 7 - Admin Web (Vite)
```bash
cd c:/Users/Lekshmi M/Documents/velosit/retalinepro/quadil/admin-web && npm run dev
```

---

## 🌐 Access

**Login:** http://localhost:8080/login  
**Email:** admin@qudil.com  
**Password:** admin123

---

## ✅ Quick Check

All running? Check these:
- Auth: http://localhost:8001/api/v1/users/count
- Gateway: http://localhost:8000/health
- Admin: http://localhost:8080/login

---

## 🛑 Stop All

Press `Ctrl+C` in each terminal (7, 6, 5, 4, 3, 2, 1)
