<template>
    <Head :title="user.username" />

    <div class="surface-section">
        <div class="flex flex-row">
            <div class="font-medium text-3xl text-900 mb-3">Личные данные</div>
            <div class="text-500 mb-5 ml-5">
                <Link :href="`/users/${user.username}/edit/`">
                    <Button label="Редактировать" />
                </Link>
            </div>
        </div>

        <ul class="list-none p-0 m-0">
            <li class="flex align-items-center py-3 px-2 border-top-1 surface-border flex-wrap">
                <div class="text-500 w-6 md:w-2 font-medium">Дата регистрации</div>
                <div class="text-900 w-full md:w-8 md:flex-order-0 flex-order-1">
                    {{ $filters.toLocalTime(user.created_at) }}
                </div>
            </li>
            <li class="flex align-items-center py-3 px-2 border-top-1 surface-border flex-wrap">
                <div class="text-500 w-6 md:w-2 font-medium">Логин</div>
                <div class="text-900 w-full md:w-8 md:flex-order-0 flex-order-1">{{ user.username }}</div>
            </li>
            <li class="flex align-items-center py-3 px-2 border-top-1 surface-border flex-wrap">
                <div class="text-500 w-6 md:w-2 font-medium">Электронная почта</div>
                <div class="text-900 w-full md:w-8 md:flex-order-0 flex-order-1">{{ user.email ?? '-' }}</div>
            </li>
            <li class="flex align-items-center py-3 px-2 border-top-1 surface-border flex-wrap">
                <div class="text-500 w-6 md:w-2 font-medium">Имя</div>
                <div class="text-900 w-full md:w-8 md:flex-order-0 flex-order-1">{{ user.name ?? '-' }}</div>
            </li>
            <li class="flex align-items-center py-3 px-2 border-top-1 surface-border flex-wrap">
                <div class="text-500 w-6 md:w-2 font-medium">День рождения</div>
                <div class="text-900 w-full md:w-8 md:flex-order-0 flex-order-1">{{ user.birthday ?? '-' }}</div>
            </li>
            <li class="flex align-items-center py-3 px-2 border-top-1 surface-border flex-wrap">
                <div class="text-500 w-6 md:w-2 font-medium">Роль</div>
                <div class="text-900 w-full md:w-8 md:flex-order-0 flex-order-1">{{ user.role_name }}</div>
            </li>
            <li class="flex align-items-center py-3 px-2 border-top-1 surface-border flex-wrap" v-if="user.is_blocked">
                <div class="text-500 w-6 md:w-2 font-medium">Статус</div>
                <div class="text-900 w-full md:w-8 md:flex-order-0 flex-order-1">
                    <div class="g-status red">Заблокирован</div>
                </div>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { inject } from "vue";
import Button from 'primevue/button';

const $filters = inject('filters');

const props = defineProps({
    user: Object
});
</script>

<style lang="scss">
</style>