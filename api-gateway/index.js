const express = require('express');
const proxy = require('express-http-proxy');
const cors = require('cors');
const app = express();
const PORT = 8000;

app.use(cors());

// Service Mappings
const services = {
    '/auth': 'http://127.0.0.1:8001',
    '/tenant': 'http://127.0.0.1:8002',
    '/geo': 'http://127.0.0.1:8003',
    '/order': 'http://127.0.0.1:8004',
    '/pricing': 'http://127.0.0.1:8005',
    '/dispatch': 'http://127.0.0.1:8006',
    '/tracking': 'http://127.0.0.1:8007',
    '/agent': 'http://127.0.0.1:8008',
    '/wallet': 'http://127.0.0.1:8010',
    '/pod': 'http://127.0.0.1:8015',
    '/notification': 'http://127.0.0.1:8011',
    '/accounting': 'http://127.0.0.1:8010',
    '/analytics': 'http://127.0.0.1:8013',
    '/webhook': 'http://127.0.0.1:8014',
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
