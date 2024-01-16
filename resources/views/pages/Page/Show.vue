<template>
    <Head :title="page.title" />

    <div>
        <div class="g-titlebar">
            <h1>{{ page.title }}</h1>
            <Link v-bind:href="'/pages/'+page.id+'/edit/'" class="right" v-if="canEdit">
                <font-awesome-icon icon="pen-to-square" />
                Редактировать
            </Link>
        </div>
        <div class="g-content" v-html="page.content"></div>
    </div>
</template>

<script>
import { ElContainer, ElHeader, ElAside, ElMain, ElTree } from "element-plus";

export default {
    name: "PageShow",
    components: {
        ElContainer, ElHeader, ElAside, ElMain, ElTree
    },
    props: {
        page: Object,
        canEdit: Boolean
    }
};
</script>

<style lang="scss">
</style>
