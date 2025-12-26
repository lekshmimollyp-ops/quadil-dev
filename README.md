# QUDIL - On-Demand Logistics Platform

QUDIL is a large-scale, multi-tenant, on-demand logistics and delivery platform designed with a microservice architecture.

## 📖 Documentation
*   [System Architecture & Microservices](QUDIL_System_Architecture.md)

## 🏗 System Modules

The system is divided into the following domain-driven microservices:

| Service Name | Directory | Description |
| :--- | :--- | :--- |
| **Identity Service** | `/auth-service` | centralized auth, JWT, RBAC |
| **Tenant Service** | `/tenant-service` | multi-tenant config, white-labeling |
| **Pricing Service** | `/pricing-service` | dynamic pricing engine, rate cards |
| **Order Service** | `/order-service` | order lifecycle management |
| **Dispatch Service** | `/dispatch-service` | driver matching & allocation algorithms |
| **Geo Service** | `/geo-service` | live tracking, geofencing, location stream |
| **Partner Service** | `/partner-service` | driver onboarding, duty management |
| **POD Service** | `/pod-service` | proof of delivery artifacts (images/signatures) |
| **Billing Service** | `/billing-service` | wallets, invoicing, payments |
| **Notify Service** | `/notify-service` | SMS/Push/Email notifications |
| **Analytics Service** | `/analytics-service` | reporting and data pipelines |

## 🚀 Getting Started

*(Instructions for local development setup will be added here)*
