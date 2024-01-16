<template>
    <Head title="Страницы" />

    <div class="g-titlebar">
        <h1>Страницы</h1>
    </div>

    <div class="g-tree">
        <div class="g-tree-header">
            <div class="g-tree-row">
                <div class="g-tree-cell for_title">Заголовок</div>
                <div class="g-tree-cell align_left" style="width: 150px;">URL</div>
                <div class="g-tree-cell align_center" style="width: 130px">Огр.доступа</div>
                <div class="g-tree-cell align_center" style="width: 100px">Статус</div>
                <div class="g-tree-cell align_right" style="width: 100px"></div>
            </div>
        </div>
        <el-tree :data="pages" node-key="id" default-expand-all draggable @node-drop="movePage">
            <template #default="{ data }">
                <div class="g-tree-row" v-bind:data-id="data.id">
                    <div class="g-tree-cell for_title">
                        {{ data.title }}
                    </div>
                    <div class="g-tree-cell align_left" style="width: 150px">
                        <a :href="`${data.url}`" target="_blank" v-if="data.url" @click="(e) => e.stopPropagation()">
                            {{ data.url }}
                        </a>
                        <span v-else>—</span>
                    </div>
                    <div class="g-tree-cell align_center" style="width: 130px">
                        <template v-if="data.access === 'all'">—</template>
                        <template v-else>{{ data.accessName }}</template>
                    </div>
                    <div class="g-tree-cell align_center" style="width: 100px">
                        <div class="g-status grey" v-if="!data.isPublished">Черновик</div>
                    </div>
                    <div class="g-tree-cell align_right" style="width: 100px; margin-left: auto"
                         @click="(e) => e.stopPropagation()">
                        <div class="g-actionicon" @click="deletePage(data)" v-if="!data.children">
                            <font-awesome-icon icon="trash" />
                        </div>
                        <Link :href="`/pages/${data.id}/edit`" class="g-actionicon">
                            <font-awesome-icon icon="pen-to-square" />
                        </Link>
                    </div>
                </div>
            </template>
        </el-tree>
    </div>

    <Link :href="`/pages/create`" @click="(e) => e.stopPropagation()">
        <div class="g-button">Создать страницу</div>
    </Link>
</template>

<script>
import { ElTree, ElSelect, ElOption, ElMessageBox, ElNotification } from "element-plus";
import { router } from "@inertiajs/vue3";

export default {
    name: "PageIndex",
    components: {
        ElTree, ElSelect, ElOption
    },
    props: {
        pages: Array,
    },
    methods: {
        deletePage(page) {
            ElMessageBox.confirm(
                `Вы действительно хотите удалить статью «${page.title}»?`,
                'Подтверждение удаления',
                {
                    confirmButtonText: 'Да',
                    cancelButtonText: 'Нет',
                    confirmButtonClass: 'g-button',
                    cancelButtonClass: 'g-button outlined'
                }
            ).then(() => {
                router.delete(`/pages/${page.id}`);
            }).catch((error) => {
                ElNotification({
                    title: 'Ошибка',
                    message: error.message,
                    type: 'error',
                });
            });
        },
        movePage(draggingNode, dropNode, dropType, ev) {
            router.post('/pages/' + draggingNode.data.id + '/move', {
                target_id: dropNode.data.id,
                relation: dropType
            });
        },
    }
};
</script>

<style lang="scss">
</style>
