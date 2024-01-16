<template>
    <div class="b-menu">
        <div class="b-menu-item"
             v-for="link in links"
             :key="link.label"
             :class="[link.className, link.active ? 'active' : '']">
            <Link class="b-menu-label" v-if="link.url" :href="link.url">{{ link.label }}</Link>
            <div class="b-menu-label" v-else>{{ link.label }}</div>
            <div class="b-menu-children" v-if="link.children">
                <BMenu :links="link.children" />
            </div>
        </div>


    </div>
</template>

<script>
export default {
    name: "BMenu",
    props: {
        // В формате [{label: '', url: '', active: '', stat: ''}]
        links: Object,
    }
};
</script>

<style lang="scss">
.b-menu {
    &-item {

    }
    &-label {
        display: block;
    }
}
</style>
