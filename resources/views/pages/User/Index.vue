<template>
    <Head title="Пользователи" />
    <div class="g-titlebar">
        <h1>Пользователи</h1>
        <Link href="/users/create/" class="g-button outlined">Создать пользователя</Link>
    </div>
    <b-filter v-model="filter" :fields="{
        role: {type: 'radio', options: {'': 'Все', ...roleTitles}},
        q: {type: 'text', placeholder: 'Поиск по имени пользователя или телефону'}
    }" />
    <el-table :data="users.data" table-layout="auto" :class="{ loading: filter.loading }">
        <el-table-column label="Пользователь">
            <template #default="{ row }">
                <Link :href="`/users/${row.username}/`">{{ row.username }}</Link>
                <div class="g-status red" v-if="row.is_blocked" style="margin-left: 1rem">Заблокирован</div>
            </template>
        </el-table-column>
        <el-table-column label="Баланс">
            <template #default="{ row }">
                <Link :href="`/users/${row.username}/transactions`">{{ $filters.formatNumber(row.balance) }}</Link>
            </template>
        </el-table-column>
        <el-table-column label="Тариф">
            <template #default="{ row }">
                {{ row.plan }}
            </template>
        </el-table-column>
        <el-table-column label="Переходов" prop="click_count" />
        <el-table-column label="Проекты">
            <template #default="{ row }">
                <Link :href="`/projects/?user=${row.username}`">{{ row.projects_count }}</Link>
            </template>
        </el-table-column>
        <el-table-column label="Зарегистрирован">
            <template #default="{ row }">
                {{ $filters.toLocalTime(row.created_at, 'в') }}
            </template>
        </el-table-column>
        <el-table-column align="right">
            <template #default="{ row }">
                <a
                    @click="deleteUser(row.username)"
                    title="Удалить"
                    class="g-actionicon"
                    v-if="row.role !== 'admin'"
                >
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

<script>
import { router } from "@inertiajs/vue3";
import BFilter from "../../blocks/BFilter.vue";
import { ElMessageBox, ElTable, ElTableColumn } from "element-plus";
import BPagination from "../../blocks/BPagniation.vue";

export default {
    components: { BPagination, ElTableColumn, ElTable, BFilter },
    props: {
        users: Object,
        roleTitles: Object,
        initialFilter: {
            type: Object,
            default() {
                return {};
            },
        },
    },
    data() {
        return {
            filter: { ...this.initialFilter },
        };
    },
    methods: {
        deleteUser(username) {
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
            }).catch(() => {
            });
        },
    },
};
</script>
