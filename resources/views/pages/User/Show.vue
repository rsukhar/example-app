<template>
    <Head :title="user.username" />

    <div class="b-dashboard">

        <div class="b-dashboard-item">
            <div class="b-dashboard-item-title">
                <div>Личные данные</div>
                <Link :href="`/users/${user.username}/edit/`">Редактировать</Link>
            </div>

            <div class="g-stat">
                <div>Логин</div>
                <div>{{ user.username }}</div>
            </div>

            <div class="g-stat" v-if="user.first_name">
                <div>Имя</div>
                <div>{{ user.first_name }}</div>
            </div>

            <div class="g-stat" v-if="user.email">
                <div>Email</div>
                <div>{{ user.email }}</div>
            </div>

            <div class="g-stat" v-if="canViewAll">
                <div>Роль</div>
                <div>{{ userRoles[user.role] ?? user.role }}</div>
            </div>

            <div class="g-stat" v-if="user.is_blocked">
                <div>Статус</div>
                <div>
                    <div class="g-status red">Заблокирован</div>
                </div>
            </div>

            <div class="g-stat">
                <div>Дата регистрации</div>
                <div>{{ toLocalTime(user.created_at).split(' ')[0] }}</div>
            </div>
        </div>

    </div>
</template>

<script setup>

import { toLocalTime } from "../../../js/helpers.js";

const props = defineProps({
    user: Object,
    userRoles: Object
});

</script>

<style lang="scss">
</style>
