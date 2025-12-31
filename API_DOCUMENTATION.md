# QUDIL - COMPREHENSIVE API DOCUMENTATION 🚀📱

This document provides Postman-style request and response formats for **all 14 microservices** in the QUDIL ecosystem.

**🌐 Unified Gateway URL (via Ngrok):** `https://preprostatic-sulfureous-randolph.ngrok-free.dev`
> [!IMPORTANT]
> All services are now accessible via this single URL. Each service corresponds to a specific path prefix.

---

## 📡 Gateway Routing Table

| Service | Gateway Prefix | Target Local Port |
| :--- | :--- | :--- |
| **Auth** | `/auth` | `8001` |
| **Tenant** | `/tenant` | `8002` |
| **Geo** | `/geo` | `8003` |
| **Order** | `/order` | `8004` |
| **Pricing** | `/pricing` | `8005` |
| **Dispatch** | `/dispatch` | `8006` |
| **Wallet** | `/wallet` | `8007` |
| **Agent** | `/agent` | `8008` |
| **Tracking** | `/tracking` | `8009` |
| **POD** | `/pod` | `8010` |
| **Notification** | `/notification` | `8011` |
| **Accounting** | `/accounting` | `8012` |
| **Analytics** | `/analytics` | `8013` |
| **Webhook** | `/webhook` | `8014` |

---

## 🔑 1. Identity Service
Access via: `{{GATEWAY_URL}}/auth/api/v1/...`

### [POST] Login
`POST /auth/api/v1/login`
**Request:**
```json
{ "email": "admin@qudil.com", "password": "password" }
```
**Response (200):**
```json
{ "token": "7|abc...", "user": { "id": 1, "name": "Admin", "role": "admin" } }
```

---

## 🏢 2. Tenant Service
Access via: `{{GATEWAY_URL}}/tenant/api/v1/...`

### [POST] Register Merchant
`POST /tenant/api/v1/tenants`
**Request:**
```json
{ "name": "Pizza Hut", "type": "Chain", "email": "contact@pizzahut.com" }
```
**Response (201):**
```json
{ "id": "592c7d15...", "name": "Pizza Hut", "slug": "pizza-hut" }
```

---

## 🗺️ 3. Geography Service
Access via: `{{GATEWAY_URL}}/geo/api/v1/...`

### [POST] Check Coverage
`POST /geo/api/v1/check-coverage`
**Request:**
```json
{ "city_id": 1, "latitude": 12.97, "longitude": 77.59 }
```
**Response (200):**
```json
{ "covered": true, "area": "Indiranagar" }
```

---

## 📦 4. Order Service
Access via: `{{GATEWAY_URL}}/order/api/v1/...`

### [POST] Create Order
`POST /order/api/v1/orders`
**Request:**
```json
{
    "tenant_id": "592c7d15...",
    "pickup_address": "Indiranagar",
    "delivery_address": "Koramangala",
    "parcel_details": { "weight": "1kg" }
}
```
**Response (201):**
```json
{ "order_id": "f47ac10b...", "status": "pending" }
```

---

## 💰 5. Pricing Service
Access via: `{{GATEWAY_URL}}/pricing/api/v1/...`

### [POST] Calculate Fare
`POST /pricing/api/v1/calculate`
**Request:**
```json
{ "distance_km": 5.2, "weight_kg": 1.5, "tenant_id": "592c7d15..." }
```
**Response (200):**
```json
{ "base_fare": 40.00, "distance_fare": 26.00, "weight_fare": 15.00, "total_fare": 81.00 }
```

---

## 🚛 6. Dispatch Service
Access via: `{{GATEWAY_URL}}/dispatch/api/v1/...`

### [POST] Assign Driver
`POST /dispatch/api/v1/assign`
**Request:**
```json
{ "order_id": "f47ac10b...", "driver_id": "552f0366..." }
```
**Response (200):**
```json
{ "message": "Driver assigned", "assignment_id": 12 }
```

---

## 💳 7. Wallet Service
Access via: `{{GATEWAY_URL}}/wallet/api/v1/...`

### [GET] Balance
`GET /wallet/api/v1/balance/592c7d15...`
**Response (200):**
```json
{ "advance_balance": "450.00", "credit_limit": "1000.00" }
```

---

## 👤 8. Agent Service
Access via: `{{GATEWAY_URL}}/agent/api/v1/...`

### [POST] Add Vehicle
`POST /agent/api/v1/agents/552f0366.../vehicles`
**Request:**
```json
{ "vehicle_type": "Bike", "plate_number": "KA-01-EF-1234" }
```
**Response (201):**
```json
{ "message": "Vehicle added and pending verification" }
```

---

## 🛰️ 9. Tracking Service
Access via: `{{GATEWAY_URL}}/tracking/api/v1/...`

### [POST] GPS Ping
`POST /tracking/api/v1/ping`
**Request:**
```json
{ "agent_id": "552f0366...", "latitude": 12.97, "longitude": 77.64 }
```
**Response (200):**
```json
{ "message": "Location updated" }
```

---

## ✍️ 10. POD Service
Access via: `{{GATEWAY_URL}}/pod/api/v1/...`

### [POST] Verify OTP
`POST /pod/api/v1/verify-otp`
**Request:**
```json
{ "order_id": "f47ac10b...", "otp": "4821" }
```
**Response (200):**
```json
{ "message": "Order verified successfully" }
```

---

## 🔔 11. Notification Service
Access via: `{{GATEWAY_URL}}/notification/api/v1/...`

### [POST] Send Alert
`POST /notification/api/v1/send`
**Request:**
```json
{ "user_id": 101, "type": "whatsapp", "content": "Your order is arriving!" }
```
**Response (200):**
```json
{ "status": "sent", "notification_id": 88 }
```

---

## 📊 12. Accounting Service
Access via: `{{GATEWAY_URL}}/accounting/api/v1/...`

### [GET] Summary
`GET /accounting/api/v1/summary`
**Response (200):**
```json
{ "platform_revenue": 15000.50, "platform_expense": 8000.20 }
```

---

## 📈 13. Analytics Service
Access via: `{{GATEWAY_URL}}/analytics/api/v1/...`

### [GET] Platform Stats
`GET /analytics/api/v1/platform`
**Response (200):**
```json
{ "total_tenants": 12, "platform_total_orders": 450 }
```

---

## 🔌 14. Webhook Service
Access via: `{{GATEWAY_URL}}/webhook/api/v1/...`

### [POST] Register Webhook
`POST /webhook/api/v1/webhooks`
**Request:**
```json
{ "tenant_id": "592c7d15...", "url": "https://callback.com", "events": ["order.delivered"] }
```
**Response (201):**
```json
{ "config_id": 1, "secret": "shhh_secret_key" }
```

---
*Generated: 2025-12-29 | QUDIL V1.1 (Gateway Mode)*
