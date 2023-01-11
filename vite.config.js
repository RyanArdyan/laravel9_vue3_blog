import { defineConfig } from 'vite';
// panggil kode didalam package.json
import laravel from 'laravel-vite-plugin';
// di dapatkan dari npmjs.com
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        // setelah vue di import maka panggil vuenya
        vue(),
        laravel({
            // panggil resources/js/app.js
            input: 'resources/js/app.js',
            refresh: true,
        }),
    ],
});
