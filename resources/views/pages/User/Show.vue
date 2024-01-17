<template>
    <Head :title="user.username" />

    <div class="b-dashboard">
        <div class="b-dashboard-item">
            <div class="b-dashboard-item-title">
                <div>Личные данные</div>
                <Link :href="`/users/${user.username}/edit/`">Редактировать</Link>
            </div>

            <div class="g-stat">
                <div>Дата регистрации</div>
                <div>{{ $filters.toLocalTime(user.created_at) }}</div>
            </div>

            <div class="g-stat">
                <div>Логин</div>
                <div>{{ user.username }}</div>
            </div>

            <div class="g-stat">
                <div>Email</div>
                <div>{{ user.email ?? '-' }}</div>
            </div>

            <div class="g-stat">
                <div>Имя</div>
                <div>{{ user.name ?? '-' }}</div>
            </div>

            <div class="g-stat" v-if="user.birthday">
                <div>День рождения</div>
                <div>{{ user.birthday ?? '-' }}</div>
            </div>

            <div class="g-stat">
                <div>Роль</div>
                <div>{{ user.role_name }}</div>
            </div>

            <div class="g-stat" v-if="user.is_blocked">
                <div>Статус</div>
                <div>
                    <div class="g-status red">Заблокирован</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { inject } from "vue";

const $filters = inject('filters');

const props = defineProps({
    user: Object
});
</script>

<style lang="scss">
.b-dashboard-item.for_notifications {
    .b-dashboard-item-title .status {
        font-size: 1rem;
        font-weight: normal;
        margin-left: auto;
        color: var(--green);
    }

    .el-switch {
        height: auto;

        &__label {
            height: auto;

            & span {
                line-height: 1.5;
            }
        }

        & + .el-switch {
            margin-top: 1.5rem;
        }
    }
}
</style>