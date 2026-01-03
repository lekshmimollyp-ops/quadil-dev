const express = require('express');
const proxy = require('express-http-proxy');
const cors = require('cors');
const app = express();
const PORT = 8000;

app.use(cors());

// Service Mappings
const services = {
    '/auth': process.env.AUTH_SERVICE_URL || 'http://127.0.0.1:8001',
    '/tenant': process.env.TENANT_SERVICE_URL || 'http://127.0.0.1:8002',
    '/geo': process.env.GEO_SERVICE_URL || 'http://127.0.0.1:8003',
    '/order': process.env.ORDER_SERVICE_URL || 'http://127.0.0.1:8004',
    '/pricing': process.env.PRICING_SERVICE_URL || 'http://127.0.0.1:8005',
    '/dispatch': process.env.DISPATCH_SERVICE_URL || 'http://127.0.0.1:8006',
    '/tracking': process.env.TRACKING_SERVICE_URL || 'http://127.0.0.1:8007',
    '/agent': process.env.AGENT_SERVICE_URL || 'http://127.0.0.1:8008',
    '/wallet': process.env.WALLET_SERVICE_URL || 'http://127.0.0.1:8010',
    '/pod': process.env.POD_SERVICE_URL || 'http://127.0.0.1:8015',
    '/notification': process.env.NOTIFICATION_SERVICE_URL || 'http://127.0.0.1:8011',
    '/accounting': process.env.ACCOUNTING_SERVICE_URL || 'http://127.0.0.1:8010',
    '/analytics': process.env.ANALYTICS_SERVICE_URL || 'http://127.0.0.1:8013',
    '/webhook': process.env.WEBHOOK_SERVICE_URL || 'http://127.0.0.1:8014',
};

// Apply proxies
Object.entries(services).forEach(([path, target]) => {
    app.use(path, proxy(target, {
        proxyReqPathResolver: (req) => {
            // Keep the /api/v1 portion but strip the service prefix
            return req.url; 
        }
    }));
});

app.get('/health', (req, res) => {
    res.json({ status: 'Gateway is running', active_services: Object.keys(services).length });
});

app.listen(PORT, () => {
    console.log(`🚀 QUDIL Central Gateway running on http://localhost:${PORT}`);
    console.log('Routes:');
    Object.keys(services).forEach(s => console.log(`  ${s} -> ${services[s]}`));
});
