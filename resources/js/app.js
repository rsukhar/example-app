import { createApp, h } from 'vue';
import { createInertiaApp, Link, Head } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import * as filters from './filters.js';
import 'startup-ui/dist/index.css';

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import './font-awesome.js';

import Admin from '../views/layouts/Admin.vue';
import '../css/app.scss';

const appName = 'ExampleApp';
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

/**
 * Обходной фикс бага startup-ui 0.12: скрытые <input type="radio"> внутри SRadioGroup
 * не имеют общего name, и при выборе новой опции у соседних радиокнопок не снимается
 * нативный checked — визуально «нажатыми» остаются сразу несколько кнопок (значение
 * модели при этом корректно). Принудительно снимаем отметку с остальных радиокнопок
 * той же группы. Слушатель на document регистрируется один раз и переживает SPA-переходы.
 */
document.addEventListener('change', (event) => {
    const radio = event.target;

    if ( ! radio.matches?.('.s-radiogroup-container input[type="radio"]')) {
        return;
    }

    radio.closest('.s-radiogroup-container')
        .querySelectorAll('input[type="radio"]')
        .forEach((sibling) => {
            if (sibling !== radio) {
                sibling.checked = false;
            }
        });
});