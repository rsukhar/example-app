<template>
    <Head :title="user.username" />

    <SDashboard>
        <SDashboardItem title="Личные данные">
            <template #extra>
                <Link :href="`/users/${user.username}/edit/`">Редактировать</Link>
            </template>
            <SStat title="Логин">
                <div>{{ user.username }}</div>
            </SStat>

            <SStat title="Имя" v-if="user.first_name">
                <div>{{ user.first_name }}</div>
            </SStat>

            <SStat title="Email" v-if="user.email">
                <div>{{ user.email }}</div>
            </SStat>

            <SStat title="Роль" v-if="canViewAll">
                <div>{{ userRoles[user.role] ?? user.role }}</div>
            </SStat>

            <SStat title="Статус" v-if="user.is_blocked">
                <div class="g-status red">Заблокирован</div>
            </SStat>

            <SStat title="Дата регистрации">
                <div>{{ $filters.toLocalTime(user.created_at).split(' ')[0] }}</div>
            </SStat>
        </SDashboardItem>
    </SDashboard>
</template>

<script setup>
import { inject } from 'vue';
import { SDashboard, SDashboardItem, SStat } from "startup-ui";

const props = defineProps({
    user: Object,
    userRoles: Object,
    canViewAll: Boolean
});

const $filters = inject('$filters');

</script>

<style lang="scss">
</style>
