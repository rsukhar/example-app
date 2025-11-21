<template>
    <Head title="Пользователи" />

    <div class="g-titlebar">
        <h1>Пользователи</h1>
        <Link href="/users/create/" class="g-button outlined">Создать пользователя</Link>
    </div>

    <SFilterGroup>
        <SFilter name="role">
            <SRadioGroup :options="userRoles" placeholder="Все" buttons/>
        </SFilter>
        <SFilter name="q" :debounce="500">
            <SInput type="search" placeholder="Поиск по имени пользователя" style="width: 350px"/>
        </SFilter>
    </SFilterGroup>

    <STable :data="users.data" :class="{ loading: filter.loading }">
        <template #headers>
            <td class="name">Пользователь</td>
            <td class="center">Email</td>
            <td class="center">Имя</td>
            <td class="center">Регистрация</td>
            <td class="right"></td>
        </template>
        <template #row="{ row }">
            <td>
                <Link :href="`/users/${row.username}/`">{{ row.username }}</Link>
                <div class="g-status red" v-if="row.is_blocked" style="margin-left: 1rem">Заблокирован</div>
            </td>
            <td class="center">{{ row.email }}</td>
            <td class="center">{{ row.first_name }}</td>
            <td class="center">{{ $filters.toLocalTime(row.created_at).split(' ')[0] }}</td>
            <td class="right">
                <a @click="deleteUser(row.username)" title="Удалить" class="g-actionicon" v-if="row.role !== 'admin'">
                    <FontAwesomeIcon icon="trash" />
                </a>
                <Link :href="`/users/${row.username}/edit/`" class="g-actionicon">
                    <FontAwesomeIcon icon="pen-to-square" />
                </Link>
            </td>
        </template>
    </STable>

    <SPagination :links="users.links" :total="users.total" />
</template>

<script setup>
import { inject, reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import { STable, SInput, SPagination, SFilterGroup, SFilter, SRadioGroup, SConfirm } from "startup-ui";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

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
    SConfirm.open(
        `Вы действительно хотите удалить пользователя «${username}»?`, {
            title: 'Подтверждение удаления',
            onAccept: () => router.delete(`/users/${username}`)
        }
    )
}
</script>

<style scoped>
</style>