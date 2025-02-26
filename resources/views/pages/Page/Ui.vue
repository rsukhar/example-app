<template>
    <h1 class="mb-5">Тайтлбар</h1>
    <div class="g-titlebar">
        <h1>Заголовок 1</h1>
        <Link href="#" class="g-button outlined">Кнопка Outlined</Link>
        <Link href="#" class="right">
            <font-awesome-icon icon="rotate-left" />
            Назад
        </Link>
    </div>

    <h1 class="mt-5 mb-5">Типографика</h1>
    <div class="g-html">
        <h2>Заголовок 2</h2>
        <h3>Заголовок 3</h3>
        <h4>Заголовок 4</h4>
        <p>Параграф</p>
        <blockquote>Блок с цитатой</blockquote>
        <ul>
            <li>Элемент 1</li>
            <li>Элемент 2</li>
            <li>Элемент 3</li>
        </ul>
        <ol>
            <li>Элемент 1</li>
            <li>Элемент 2</li>
            <li>Элемент 3</li>
        </ol>
        <div class="g-alert warning">
            <font-awesome-icon icon="fa-triangle-exclamation" />
            <p>Warning</p>
        </div>
        <div class="g-alert note">
            <font-awesome-icon icon="fa-triangle-exclamation" />
            <p>Note</p>
        </div>
    </div>

    <h1 class="mt-5 mb-5">Кнопки</h1>
    <div class="g-hwrapper">
        <button class="g-button">Кнопка</button>
        <button class="g-button outlined">Кнопка Outlined</button>
        <button class="g-button disabled">Кнопка Disabled</button>
        <button class="g-button loading">Кнопка Loading</button>
        <button class="g-button fullwidth">Кнопка на всю ширину контейнера</button>
    </div>

    <h1 class="mt-5 mb-5">Статусы</h1>
    <div class="g-hwrapper mt-5">
        <div class="g-status green">
            Green статус
        </div>
        <div class="g-status yellow">
            Yellow статус
        </div>
        <div class="g-status red">
            Red статус
        </div>
    </div>

    <h1 class="mt-5 mb-5">Формы</h1>
    <div class="g-hwrapper mt-5">
        <form class="b-form">
            <b-formrow title="Фио">
                <InputText></InputText>
            </b-formrow>
            <b-formrow title="Место работы">
                <InputText></InputText>
            </b-formrow>
        </form>
    </div>
    <div class="g-hwrapper mt-5">
        <form class="b-form label_top">
            <b-formrow title="Фио">
                <InputText></InputText>
            </b-formrow>
            <b-formrow title="Место работы">
                <InputText></InputText>
            </b-formrow>
        </form>
    </div>

    <h1 class="mt-5 mb-5">Таблицы</h1>
    <h3>Hover эффект</h3>
    <div class="g-tablewrapper">
        <table class="g-table hoverable">
            <thead>
            <tr>
                <td>Фио</td>
                <td>Место работы</td>
                <td>Стаж</td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Иванов Иван Иванович</td>
                <td>Хлебозавод №1</td>
                <td>10 лет</td>
            </tr>
            <tr>
                <td>Иванов Иван Иванович</td>
                <td>Хлебозавод №1</td>
                <td>10 лет</td>
            </tr>
            <tr>
                <td>Иванов Иван Иванович</td>
                <td>Хлебозавод №1</td>
                <td>10 лет</td>
            </tr>
            </tbody>
        </table>
    </div>

    <h3>Границы</h3>
    <div class="g-tablewrapper">
        <table class="g-table bordered">
            <thead>
            <tr>
                <td>Фио</td>
                <td>Место работы</td>
                <td>Стаж</td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Иванов Иван Иванович</td>
                <td>Хлебозавод №1</td>
                <td>10 лет</td>
            </tr>
            <tr>
                <td>Иванов Иван Иванович</td>
                <td>Хлебозавод №1</td>
                <td>10 лет</td>
            </tr>
            <tr>
                <td>Иванов Иван Иванович</td>
                <td>Хлебозавод №1</td>
                <td>10 лет</td>
            </tr>
            </tbody>
        </table>
    </div>

    <h3>Выравнивание данных</h3>
    <div class="g-tablewrapper">
        <table class="g-table bordered">
            <thead>
            <tr>
                <td>Фио</td>
                <td class="center">Место работы</td>
                <td class="right">Стаж</td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Иванов Иван Иванович</td>
                <td class="center">Хлебозавод №1</td>
                <td class="right">10 лет</td>
            </tr>
            <tr>
                <td>Иванов Иван Иванович</td>
                <td class="center">Хлебозавод №1</td>
                <td class="right">10 лет</td>
            </tr>
            <tr>
                <td>Иванов Иван Иванович</td>
                <td class="center">Хлебозавод №1</td>
                <td class="right">10 лет</td>
            </tr>
            </tbody>
        </table>
    </div>

    <h1 class="mt-5 mb-5">Alert'ы</h1>
    <div class="g-alert note"><p>Note</p></div>
    <div class="g-alert attention"><p>Attention</p></div>
    <div class="g-alert success"><p>Success</p></div>
    <div class="g-alert error"><p>Error</p></div>


    <h1 class="mt-5 mb-5">Дерево</h1>
    <TreeTable :value="nodes" tableStyle="min-width: 50rem">
        <Column field="name" header="Группа объектов" expander style="width: 34%"></Column>
        <Column field="value" header="Выполнено заданий" style="width: 33%"></Column>
    </TreeTable>

    <h1 class="mt-5 mb-5">Копируемый текст</h1>
    <b-copytext class="mb-6">Скопируй меня!</b-copytext>
    <b-copytext class="mb-6" layout="inline">Скопируй меня!</b-copytext>

    <h1 class="mt-5 mb-5">Островок дешборда</h1>
    <div class="b-dashboard">
        <div class="b-dashboard-item">
            <div class="b-dashboard-item-title">Заголовок</div>
            <div>Контент</div>
        </div>
    </div>

</template>

<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import BFormrow from "../../blocks/BFormrow.vue";
import { ref } from "vue";
import BCopytext from "../../blocks/BCopytext.vue";

const nodes = ref([
    {
        key: 0,
        data: {
            name: 'Роботы',
        },
        children: [
            {
                key: '0-0',
                data: {
                    name: 'А 0-0',
                    value: '10'
                }
            },
            {
                key: '1-0',
                data: {
                    name: 'А 1-0',
                    value: '22'
                }
            },
            {
                key: '2-0',
                data: {
                    name: 'А 2-0',
                    value: '12'
                }
            }
        ]
    }
]);
</script>


<style scoped lang="scss">
pre {
    background: var(--bg);
    padding: var(--base-padding);
    border-radius: var(--border-radius);
}

.flex-direction-column {
    flex-direction: column;
}
</style>