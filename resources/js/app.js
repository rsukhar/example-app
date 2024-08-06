import { createApp, h } from 'vue';
import { createInertiaApp, Link, Head } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import * as filters from './filters.js';

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import './font-awesome.js';

import Admin from '../views/layouts/Admin.vue';
import '../css/app.scss';

const appName = 'Monstro.ru';
const appProgressBarColor = '#fff';

createInertiaApp({
    resolve: async name => {
        const page = await resolvePageComponent(`../views/pages/${name}.vue`, import.meta.glob("../views/pages/**/*.vue"));
        page.default.layout ??= Admin;
        return page;
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .component('Link', Link)
            .component('Head', Head)
            .component('font-awesome-icon', FontAwesomeIcon);

        // Глобальные фильтры, которые будут полезны в любых Vue-файлах
        app.provide('$filters', filters);

        app.mount(el);
    },
    title: title => title + (title ? ' — ' : '') + appName,
    progress: {
        color: appProgressBarColor
    }
});