import { createApp, h } from 'vue'
import { createInertiaApp, Link, Head } from '@inertiajs/vue3'
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { toLocalTime } from "./helpers.js";
import "./font-awesome.js"

import '../css/app.scss';
import Admin from "../views/layouts/Admin.vue";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";

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

        app.config.globalProperties.$filters = {
            /**
             * Форматировать число
             * @param number
             * @param decimals
             * @returns {*}
             */
            formatNumber(number, decimals) {
                return new Intl.NumberFormat('ru-RU', {
                    minimumFractionDigits: parseInt(decimals ?? 0),
                    maximumFractionDigits: parseInt(decimals ?? 0)
                }).format(number);
            },
            /**
             * Получить правильное склонение существительного в зависимости от числа
             * @param n
             * @param forms Напр: ['день', 'дня', 'дней']
             * @returns {*}
             */
            ruPluralForm(n, forms){
                n = parseInt(n);
                return (n%10 == 1 && n%100 != 11 ? forms[0] : (n%10 >= 2 && n%10 <= 4 && (n%100<10 || n%100>=20) ? forms[1] : forms[2]))
            },
            /**
             * Перевести GMT-время в местное время пользователя
             * @param gmtTime Дата GMT в формате 2023-09-29 16:30:38
             * @param separator Разделитель между датой и временем
             * @returns {string}
             */
            toLocalTime(gmtTime, separator){
                return toLocalTime(gmtTime, separator);
            }
        };

        app.mount(el);
    },
    title: title => title + (title ? ' — ' : '') + 'TopSbor.com',
    progress: {
        color: '#fff'
    }
})