import { defineConfig } from 'vitest/config';
import vue from '@vitejs/plugin-vue';
import { resolve } from 'path';

export default defineConfig({
    plugins: [vue()],
    test: {
        environment: 'jsdom',
        globals: true,
    },
    resolve: {
        alias: {
            // Wayfinder generates @/routes/* at runtime — use stubs for tests
            '@/routes': resolve(__dirname, 'resources/js/tests/__stubs__/routes'),
            '@': resolve(__dirname, 'resources/js'),
        },
    },
});
