import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import timezone from "dayjs/plugin/timezone";

dayjs.extend(utc);
dayjs.extend(timezone);

/**
 * Перевести GMT-время в местное время пользователя
 * @param gmtTime Дата GMT в формате 2023-09-29 16:30:38
 * @param separator Разделитель между датой и временем
 * @returns {string}
 */
function toLocalTime(gmtTime, separator = false) {
    // Указываем вторым аргументом точный формат, т.к. может быть разная локаль, и будет Invalid date при парсинге
    const moment = dayjs.utc(gmtTime).tz(dayjs.tz.guess());
    return moment.format('DD.MM.YYYY ') + (separator ? separator + ' ': '') + moment.format('HH:mm');
}

/**
 * Перевести GMT-время в краткий формат даты DD.MM
 * @param gmtTime
 */
function toShortDate(gmtTime){
    const moment = dayjs(gmtTime).tz(dayjs.tz.guess());
    return moment.format('DD.MM');
}

/**
 * Форматировать число
 * @param number
 * @param decimals
 * @returns {*}
 */
function formatNumber(number, decimals) {
    return new Intl.NumberFormat('ru-RU', {
        minimumFractionDigits: parseInt(decimals ?? 0),
        maximumFractionDigits: parseInt(decimals ?? 0)
    }).format(number);
}

/**
 * Получить правильное склонение существительного в зависимости от числа
 * @param n
 * @param forms Напр: ['день', 'дня', 'дней']
 * @returns {*}
 */
function ruPluralForm(n, forms) {
    n = parseInt(n);
    return n + ' ' + (n%10 == 1 && n%100 != 11 ? forms[0] : (n%10 >= 2 && n%10 <= 4 && (n%100<10 || n%100>=20) ? forms[1] : forms[2]));
}

export { toLocalTime, toShortDate, formatNumber, ruPluralForm };