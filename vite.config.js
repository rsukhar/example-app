import { defineConfig, splitVendorChunkPlugin } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    css: {
        preprocessorOptions: {
            scss: {
                additionalData: `@import "resources/css/mixins.scss";`
            }
        },
    },
    plugins: [
        splitVendorChunkPlugin(),
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
    ],
});
