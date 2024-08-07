<template>
    <Head title="Пользователи" />

    <div class="g-titlebar">
        <h1>Пользователи</h1>
        <Link href="/users/create/" class="g-button outlined">Создать пользователя</Link>
    </div>

    <b-filter
        v-model="filter"
        :fields="{
            role: {
                type: 'radio',
                options: {
                    '': 'Все',
                    ...userRoles
                }
            },
            q: {
                type: 'text',
                placeholder: 'Поиск по имени пользователя',
                style: 'width: 350px; max-width: 350px;'
            }
        }"
    />

    <el-table :data="users.data" table-layout="auto" :class="{ loading: filter.loading }">
        <el-table-column label="Пользователь">
            <template #default="{ row }">
                <Link :href="`/users/${row.username}/`">{{ row.username }}</Link>
                <div class="g-status red" v-if="row.is_blocked" style="margin-left: 1rem">Заблокирован</div>
            </template>
        </el-table-column>
        <el-table-column label="Email" prop="email" />
        <el-table-column label="Имя" prop="first_name" />
        <el-table-column label="Регистрация" align="center">
            <template #default="{ row }">
                {{ $filters.toLocalTime(row.created_at).split(' ')[0] }}
            </template>
        </el-table-column>
        <el-table-column align="right">
            <template #default="{ row }">
                <a @click="deleteUser(row.username)" title="Удалить" class="g-actionicon" v-if="row.role !== 'admin'">
                    <font-awesome-icon icon="trash" />
                </a>
                <Link :href="`/users/${row.username}/edit/`" class="g-actionicon">
                    <font-awesome-icon icon="pen-to-square" />
                </Link>
            </template>
        </el-table-column>
    </el-table>

    <b-pagination :links="users.links" :total="users.total" />
</template>

<script setup>
import { inject, reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import BFilter from "../../blocks/BFilter.vue";
import BPagination from "../../blocks/BPagination.vue";
import { ElMessageBox, ElTable, ElTableColumn } from "element-plus";

const props = defineProps({
    users: Object,
    userRoles: Object,
    initialFilter: {
        type: Object,
        default() {
            return {};
        },
    },
});

const $filters = inject('$filters');

const filter = reactive({
    ...props.initialFilter
});

function deleteUser(username) {
    ElMessageBox.confirm(
        `Вы действительно хотите удалить пользователя «${username}»?`,
        'Подтверждение удаления', {
            confirmButtonText: 'Да',
            cancelButtonText: 'Нет',
            confirmButtonClass: 'g-button',
            cancelButtonClass: 'g-button outlined'
        }
    ).then(() => {
        router.delete(`/users/${username}`);
    });
}
</script>

<style scoped>
</style>