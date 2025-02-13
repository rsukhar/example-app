import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'
import Components from 'unplugin-vue-components/vite';
import { PrimeVueResolver } from '@primevue/auto-import-resolver';

export default defineConfig({
    css: {
        preprocessorOptions: {
            scss: {
                api: 'modern-compiler',
                additionalData: `@use "/resources/css/mixins" as *;`
            }
        },
    },
    plugins: [
        vue({
            // Вот это нужно, чтобы абсолютные пути к картинкам работали
            template: {
                transformAssetUrls: {
                    includeAbsolute: false,
                },
            },
        }),
        laravel({
            input: ['resources/js/app.js'],
        }),
        Components({
            resolvers: [
                PrimeVueResolver()
            ]
        }),
    ],
});
