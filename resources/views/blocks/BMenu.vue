<template>
    <div class="b-menu">
        <div class="b-menu-item"
             v-for="link in links"
             :key="link.label"
             :class="[link.className, link.active ? 'active' : '']"
        >
            <Link class="b-menu-label" v-if="link.url" :href="link.url">{{ link.label }}</Link>
            <div class="b-menu-label" v-else>{{ link.label }}</div>

            <div class="b-menu-children" v-if="link.children">
                <BMenu :links="link.children" />
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    // В формате [{label: '', url: '', active: '', stat: ''}]
    links: Object,
});
</script>

<style lang="scss">
.b-menu {
    &-label {
        display: block;
    }
}

.b-sidebar {
    .b-menu {
        &-item {
            padding-left: 15px;

            &.active {
                border-radius: 5px;
                background-color: var(--gray);

                .b-menu-label {
                    color: var(--black);
                }
            }
        }
    }
}
</style>
