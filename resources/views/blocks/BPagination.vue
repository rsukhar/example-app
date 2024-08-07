<template>
    <div class="b-pagination" v-if="links.length > 3">
        <Component
            v-for="(link, index) in links"
            :key="index"
            :class="{ active: link.active }"
            :is="link.url && !link.active ? 'Link' : 'span'"
            :href="link.url ? link.url.replace(/[\?\&]page\=1$/, '') : ''"
            v-html="link.label"
        />

        <div class="b-pagination-total" v-if="total">Всего: {{ total }}</div>
    </div>
</template>

<script setup>
const props = defineProps({
    links: Object,
    total: Number,
});
</script>

<style lang="scss">
/* Постраничная навигация */
.b-pagination {
    display: flex;
    line-height: 2.5rem;
    margin-bottom: 2rem;

    & > span,
    & > a {
        padding: 0.2rem 1.2rem;
        text-align: center;

        &.active {
            background-color: var(--primary);
            color: var(--white);
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
</style>
