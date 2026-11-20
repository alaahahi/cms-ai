import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import fs from 'fs';
import path from 'path';
import os from 'os';

const herdCertDir = path.join(os.homedir(), '.config', 'herd', 'config', 'valet', 'Certificates');
const herdKey = path.join(herdCertDir, 'cms-ai.test.key');
const herdCrt = path.join(herdCertDir, 'cms-ai.test.crt');
const hasHerdCerts = fs.existsSync(herdKey) && fs.existsSync(herdCrt);

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: {
        host: '127.0.0.1',
        port: 5173,
        strictPort: true,
        https: hasHerdCerts
            ? {
                key: fs.readFileSync(herdKey),
                cert: fs.readFileSync(herdCrt),
            }
            : false,
        hmr: {
            host: '127.0.0.1',
            protocol: hasHerdCerts ? 'wss' : 'ws',
        },
    },
    optimizeDeps: {
        include: [
            '@fawmi/vue-google-maps',
            'fast-deep-equal',
        ],
    },
});
