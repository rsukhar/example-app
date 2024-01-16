import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import timezone from "dayjs/plugin/timezone";

dayjs.extend(utc);
dayjs.extend(timezone);

/**
 * @param gmtTime Дата GMT в формате 2023-09-29 16:30:38
 * @param separator Разделитель
 */
function toLocalTime(gmtTime, separator) {
    // Указываем вторым аргументом точный формат, т.к. может быть разная локаль, и будет Invalid date при парсинге
    const moment = dayjs(gmtTime + ' +00:00', 'YYYY-MM-DD HH:mm:ss Z')
        .tz(dayjs.tz.guess());
    return moment.format('DD.MM.YYYY ') + (separator ? separator + ' ': '') + moment.format('HH:mm');
}

export { toLocalTime };