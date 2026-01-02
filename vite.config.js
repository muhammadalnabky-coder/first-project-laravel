import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        port: 5173, // اختر أي رقم تريد
        strictPort: true, // إذا كان المنفذ مشغول، يظهر خطأ بدل اختيار منفذ آخر
    },
});
