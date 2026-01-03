# AWS Cloud Startup Guide ☁️

This document is specific to managing the **AWS EC2 Production Server**.
Unlike the local version (which uses `php artisan serve`), the AWS version creates a self-contained environment using **Docker**.

---

## 🔑 1. Connect to Server
To manage the server, you must SSH into the AWS instance.
```bash
ssh -i "your-key-pair.pem" ubuntu@<AWS_PUBLIC_IP>
```
*(Replace `<AWS_PUBLIC_IP>` with the current IP address from your AWS Console)*

---

## 🚀 2. Start the System
If the server was stopped or rebooted, start the application stack:

### Step 1: Navigate to Project
```bash
cd quadil
```

### Step 2: Start Containers (Background Mode)
```bash
docker-compose -f docker-compose.prod.yml up -d
```
*(This starts all 14 microservices, the Gateway, and the Admin Web)*

### Step 3: Verify Status
```bash
docker-compose -f docker-compose.prod.yml ps
```
*All services should show status `Up`.*

---

## 🛑 3. Stop the System
To stop all services and free up CPU/Memory (stops billing for compute):
```bash
docker-compose -f docker-compose.prod.yml down
```

To **Stop + Remove Volumes** (WARNING: Deletes DB Data):
```bash
docker-compose -f docker-compose.prod.yml down -v
```

---

## 🔄 4. Update Code (Deploy Changes)
When you push new code to GitHub, follow this sequence to update the live server:

### Step 1: Pull Latest Code
```bash
git pull origin master
```

### Step 2: Rebuild Changed Containers
You don't need to rebuild everything. For example, if you only changed `admin-web`:
```bash
# 1. Stop and Remove the specific container (Fixes "Zombie" issues)
docker-compose -f docker-compose.prod.yml rm -sf admin-web

# 2. Rebuild and Start it
docker-compose -f docker-compose.prod.yml up -d --build admin-web
```

### Step 3: Restore Environment (Admin Web Only)
The `admin-web` container often needs its Key regenerated after a rebuild:
```bash
docker-compose -f docker-compose.prod.yml exec admin-web sh -c "cp .env.example .env && php artisan key:generate && php artisan optimize:clear"
```

---

## 📋 5. Manage Data (Reset/Seed)

### Refresh Database (Delete all data, Re-seed)
This will wipe all 14 databases and re-populate them with fresh test data.
```bash
# Run migration fresh + seed on specific services
docker-compose -f docker-compose.prod.yml exec auth-service php artisan migrate:fresh --seed
docker-compose -f docker-compose.prod.yml exec tenant-service php artisan migrate:fresh --seed
docker-compose -f docker-compose.prod.yml exec order-service php artisan migrate:fresh --seed
# ... repeat for other services
```

### Create MORE Orders (API Simulation)
To generate more test orders without wiping data:
```bash
docker-compose -f docker-compose.prod.yml exec order-service php artisan db:seed --class=OrderDataSeeder
```

---

## 🔍 6. View Logs (Debugging)
If something isn't working, check the logs of the specific service.

**Admin Web Logs:**
```bash
docker-compose -f docker-compose.prod.yml logs -f admin-web
```

**Order Service Logs:**
```bash
docker-compose -f docker-compose.prod.yml logs -f order-service
```
*(Press `Ctrl+C` to exit logs)*

---

## ⚠️ Important Troubleshooting

### "Zombie Container" Error
If you see `KeyError: 'ContainerConfig'` or build failures:
```bash
docker-compose -f docker-compose.prod.yml rm -sf <service_name>
```

### "500 Server Error" on Admin Panel
Usually means `APP_KEY` is missing. Run the **Restore Environment** command in Section 4.

---
*Generated for QUDIL AWS Deployment Team*
