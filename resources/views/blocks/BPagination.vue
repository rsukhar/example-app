<template>
    <div class="b-pagination" :class="{ 'b-pagination-right' : links.length <= 3 }">
        <div class="b-pagination-links" v-if="links.length > 3">
            <template v-for="(link, index) in links" :key="index">
                <Link v-if="link.url && !link.active" :class="{ active: link.active }" v-html="link.label"
                      :href="link.url ? link.url.replace(/[\?\&]page\=1$/, '') : ''"
                      :preserve-scroll="preserveScroll" />
                <span v-else :class="{ active: link.active }" v-html="link.label" />
            </template>
        </div>
        <div class="b-pagination-options">
            <div class="b-options-pagination-perPage" v-if="perPageOptions">
                <Select v-model="currentPerPage"
                        @change="handleSelectedChange"
                        :options="formatPageOptions"
                        option-value="value"
                        placeholder="По .."
                        option-label="label"
                />
            </div>
            <div class="b-options-pagination-shown-counter" v-if="from && to && total">
                Показаны: <span class="b-pagination-options-shown-counter-range">{{ from }} - {{ to }}</span> из
                {{ total }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { router } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const props = defineProps({
    url: {
        type: String,
        default() {
            return location.pathname;
        },
    },
    links: Object,
    total: Number,
    preserveScroll: {
        type: Boolean,
        default() {
            return true;
        }
    },
    perPageOptions: Array,
    perPage: Number,
    from: Number,
    to: Number,
});

const currentPerPage = ref(props.perPage);

const formatPageOptions = computed(() => {
    const array = [];
    for (var key in props.perPageOptions) {
        array.push({label: 'По ' + props.perPageOptions[key], value: props.perPageOptions[key]})
    }
    return array;
});

/**
 * Обновление страницы после выбора пагинации
 */
function handleSelectedChange() {
    // Преобразуем GET-параметры в объект
    const search = location.search.substring(1);
    const query = search ? JSON.parse('{"' + decodeURI(search).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g, '":"') + '"}') : {};
    // удаляем параметр page, чтобы при переключении пагинации страница сбрасывалась на первую
    delete query['page'];

    if (props.perPageOptions && currentPerPage.value !== props.perPageOptions[0]) {
        query.perpage = currentPerPage.value;
    } else {
        delete query['perpage'];
    }

    router.get(props.url, query, {
        preserveScroll: props.preserveScroll
    });
}
</script>

<style lang="scss">
/* Постраничная навигация */
.b-pagination {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: space-between;
    margin-bottom: var(--base-margin) !important;
    font-weight: 400;

    &-right {
        justify-content: end;
    }

    &-links {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;

        & > span,
        & > a {
            box-sizing: border-box;
            display: flex;
            padding: 0 5px;
            min-width: 32px;
            min-height: 32px;
            justify-content: center;
            border-radius: var(--border-radius);
            align-items: center;

            &.active {
                background-color: var(--primary-lightest);
                color: var(--primary);
                border: 1px solid var(--primary);
                font-weight: 700;
            }

            & > svg {
                margin: 0;
            }
        }

        & > a {
            &:hover {
                background-color: var(--bg);
            }
        }

        &-total {
            margin-left: auto;
        }
    }

    &-options {
        display: flex;
        gap: 21px;
        align-items: center;

        &-pagination-perPage {
            color: var(--black);
        }

        &-shown-counter-range {
            font-weight: 700;
        }

    }

    .el-select {
        width: 100px;
    }
}
</style>
