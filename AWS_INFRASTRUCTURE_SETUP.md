# AWS Infrastructure Setup Guide 🏗️

This document covers the **One-Time Infrastructure Setup** required before you can run the application.
Use this guide if you are setting up a **Brand New Server** from scratch.

---

## 🏗️ 1. Create Server (GUI Steps)

### Step 1: Create AWS Account
1.  Go to [aws.amazon.com](https://aws.amazon.com/).
2.  Click **"Create an AWS Account"**.
3.  Sign In to the Console as Root User.

### Step 2: Launch Virtual Server (EC2)
1.  Go to **EC2 Dashboard** -> **Launch Instance**.
2.  **Name:** `Quadil-Production-Server`.
3.  **OS Images:** Select **Ubuntu Server 22.04 LTS**.
4.  **Instance Type:** Select **`t3.large`** (2 vCPU, 8GB RAM).
    *   *Warning: Do not use `t2.micro`, it is too small for 14 services.*
5.  **Key Pair:** Create new key pair `quadil-key.pem` and **Download it**.

### Step 3: Configure Firewall (Security Group)
1.  In "Network settings", ensure "Create security group" is checked.
2.  Add Rules:
    *   **SSH (22):** My IP (or Anywhere).
    *   **HTTP (80):** Anywhere.
    *   **Custom TCP (8080):** Anywhere (For Admin Web).
    *   **Custom TCP (8000):** Anywhere (For API Gateway).

### Step 4: Launch
Click **Launch Instance**.

---

## 💻 2. Install Software (Terminal)

Connect to your new server:
```bash
ssh -i "quadil-key.pem" ubuntu@<Public-IP>
```

Run these commands to prepare the environment:

### Step 1: Install Docker & Compose
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Docker
sudo apt install -y docker.io docker-compose

# Add user to docker group (avoids using sudo)
sudo usermod -aG docker $USER
newgrp docker

# Verify installation
docker --version
docker-compose --version
```

### Step 2: Deploy Code
```bash
# Clone Repository (Use HTTPS token or SSH key logic)
git clone https://github.com/lekshmimollyp-ops/quadil-dev.git quadil
cd quadil
```

### Step 3: First Startup
Now refer to the **`AWS_STARTUP_GUIDE.md`** for the specific command to start the stack (`docker-compose up -d`).

---

## 🛑 Cost Warning
*   **t3.large** runs at ~$0.08/hour (~$60/month).
*   If you are testing, remember to **Stop** the instance when not in use to save money.

---
*Generated: 2025-01-03 | QUDIL Infrastructure Team*
