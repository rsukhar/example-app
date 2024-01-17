import { createApp, h } from 'vue';
import { createInertiaApp, Link, Head } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { toLocalTime, formatNumber, ruPluralForm } from './helpers.js';

import PrimeVue from 'primevue/config';
import PrimeVueConfirmationService from 'primevue/confirmationservice';
import PrimeVueToastService from 'primevue/toastservice';

import AdminLayout from '../views/layouts/Admin.vue';
import '../css/app.scss';
import 'primevue/resources/themes/lara-light-blue/theme.css';
import 'primeicons/primeicons.css';

const appName = 'ExampleApp';

createInertiaApp({
    resolve: async name => {
        const page = await resolvePageComponent(`../views/pages/${name}.vue`, import.meta.glob("../views/pages/**/*.vue"));
        page.default.layout ??= AdminLayout;
        return page;
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(PrimeVue)
            .use(PrimeVueConfirmationService)
            .use(PrimeVueToastService)
            .component('Link', Link)
            .component('Head', Head)

        app.provide('filters', {
            formatNumber(number, decimals) {
                return formatNumber(number, decimals);
            },
            ruPluralForm(n, forms) {
                return ruPluralForm(n, forms);
            },
            toLocalTime(gmtTime, separator) {
                return toLocalTime(gmtTime, separator);
            }
        });

        app.mount(el);
    },
    title: title => title + (title ? ' — ' : '') + appName,
    progress: {
        color: '#fff'
    }
})