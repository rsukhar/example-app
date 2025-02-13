import { createApp, h } from 'vue';
import { createInertiaApp, Link, Head } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import './font-awesome.js';

import Admin from '../views/layouts/Admin.vue';
import '../css/app.scss';

import PrimeVue from 'primevue/config';
import ConfirmationService from 'primevue/confirmationservice';
import Aura from '@primevue/themes/aura';


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
            .use(PrimeVue, {
                theme: {
                    preset: Aura,
                    options: {
                        prefix: 'p',
                        darkModeSelector: '.dark-mode', // класс на темный режим темы
                        cssLayer: false
                    }
                },
                locale: {
                    firstDayOfWeek: 1,
                    dayNames: ['Воскресение', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
                    dayNamesMin: ['Вск', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
                    monthNamesShort: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
                    monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
                    dateFormat: 'dd.mm.yy'
                }
            })
            .use(ConfirmationService)
            .component('Link', Link)
            .component('Head', Head)
            .component('font-awesome-icon', FontAwesomeIcon);
        app.mount(el);
    },
    title: title => title + (title ? ' — ' : '') + appName,
    progress: {
        color: appProgressBarColor
    }
});