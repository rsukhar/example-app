import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'
import path from 'node:path';

export default defineConfig({
    css: {
        preprocessorOptions: {
            scss: {
                additionalData: `@import "resources/css/mixins.scss";`,
                // Современный компилятор Sass (Vite 6+) резолвит импорты по loadPaths,
                // а не от корня проекта — указываем корень явно
                loadPaths: [path.resolve(import.meta.dirname)],
            }
        },
    },
    build: {
        // Vite 8 по умолчанию минифицирует CSS через lightningcss, который падает
        // на современном селекторе :nth-child(... of ...) из CSS TinyMCE (внутри startup-ui).
        // esbuild-минификатор обрабатывает его корректно.
        cssMinify: 'esbuild',
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
    ],
});
