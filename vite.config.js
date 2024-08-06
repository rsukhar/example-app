import { defineConfig, splitVendorChunkPlugin } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'
import ElementPlus from 'unplugin-element-plus/vite'

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
        ElementPlus({
            // options
        }),
        laravel({
            input: ['resources/js/app.js'],
        }),
    ],
});
